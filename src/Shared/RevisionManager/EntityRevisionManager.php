<?php


namespace App\Shared\RevisionManager;

use App\Shared\RevisionManager\Filters\RevisionLoggableEntityInterface;
use DateTime;
use DateTimeZone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Connections\PrimaryReadReplicaConnection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\LockMode;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\QuoteStrategy;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\PessimisticLockException;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\TransactionRequiredException;
use Doctrine\ORM\UnexpectedResultException;
use RuntimeException;
use Throwable;

/**
 * Revision management helper
 *
 * @package App\Doctrine
 */
class EntityRevisionManager
{
    /**
     * Check revision for entity (version lock).
     * Assumes that softDelete filter is disabled!
     *
     * @throws NonUniqueResultException
     * @throws PessimisticLockException
     * @throws TransactionRequiredException
     */
    public function lockEntityRevision(
        EntityManagerInterface $em,
        RevisionLoggableEntityInterface $object,
        int $revFrom,
        ?int $revTo,
        ClassMetadata $classMetadata
    ): void {
        $qb     = $em->createQueryBuilder();
        $sAlias = 'o';
        $where  = [
            $qb->expr()->eq($sAlias.'.revFrom', ':revFrom'),
            $revTo !== null ? $qb->expr()->eq($sAlias.'.revTo', ':revTo') : $qb->expr()->isNull($sAlias.'.revTo'),
        ];
        $params = new ArrayCollection(
            [ new Parameter('revFrom', $revFrom, $classMetadata->getTypeOfField('revFrom')), ]
        );

        if ($revTo !== null) {
            $params->add(new Parameter('revTo', $revTo, $classMetadata->getTypeOfField('revTo')));
        }

        foreach ($classMetadata->getIdentifierValues($object) as $key => $v) {
            $where[] = $qb->expr()->eq($sAlias.'.'.$key, ":$key");
            $params->add(new Parameter($key, $v, $classMetadata->getTypeOfField($key)));
        }

        $entityRev = $qb
            ->select($sAlias.'.revFrom')->from(get_class($object), $sAlias)
            ->where($qb->expr()->andX(...$where))
            ->getQuery()->setParameters($params)->setLockMode(LockMode::PESSIMISTIC_WRITE)->getOneOrNullResult()
        ;

        if (!$entityRev) {
            throw new PessimisticLockException('Entity revision mismatch');
        }
    }

    /**
     * Insert entity into log
     *
     * @throws Exception
     */
    public function insertEntityLog(
        EntityManagerInterface $em,
        RevisionLoggableEntityInterface $object,
        int $oldRevisionFrom,
        int $revisionTo
    ): void {
        $classMetadata = $em->getClassMetadata(get_class($object));
        $quoteStrategy = $em->getConfiguration()->getQuoteStrategy();
        $connection = $em->getConnection();
        $table = ltrim(
            $classMetadata->getSchemaName().'.'.$classMetadata->getTableName().'_archive',
            '.'
        );

        $table = isset($classMetadata->table['quoted'])
            ? $connection->getDatabasePlatform()->quoteIdentifier($table)
            : $table
        ;

        $dataToInsert = $this->getEntityRawData(
            $object,
            $classMetadata,
            $quoteStrategy,
            $connection
        );

        $changeSet = $em->getUnitOfWork()->getEntityChangeSet($object);
        foreach ($changeSet as $field => $change) {
            $before = $change[0];
            $after  = $change[1];

            if (is_object($before) && $em->contains($before)) {
                $before = &$change[0];
                $before = $em->getClassMetadata(get_class($before))->getIdentifierValues($before);
            }
            if (is_object($after) && $em->contains($after)) {
                $after = &$change[1];
                $after = $em->getClassMetadata(get_class($after))->getIdentifierValues($after);
            }

            $parsedChangeSet[$field] = $change;
        }

        $types = [];
        foreach ($dataToInsert as $field => $value) {
            if (is_bool($value)) {
                $types[$field] = ParameterType::BOOLEAN;
            }
        }

        $dataToInsert['rev_from'] = $oldRevisionFrom;
        $dataToInsert['rev_to']   = $revisionTo;
        $dataToInsert['rev_meta'] = json_encode(['change_set' => $parsedChangeSet ?? []]);

        $connection->insert($table, $dataToInsert, $types);
    }

    /**
     * Update revision
     *
     * @throws TransactionRequiredException
     * @throws Exception
     * @throws UnexpectedResultException
     */
    public function updateSchemaRevision(EntityManagerInterface $em, string $author, string $commitMessage = ''): array
    {
        if (!$em->getConnection()->isTransactionActive()) {
            throw TransactionRequiredException::transactionRequired();
        }

        $oldRevision =
            $em->getConnection()->executeQuery(
                "select rev from static.revision order by rev desc limit 1 for update"
            )->fetchAllAssociative()[0]['rev'] ?? null
        ;

        if ($oldRevision === null) {
            throw new RuntimeException('Unable to get revision from static.revision');
        }

        $revInsertResult = $em->getConnection()->insert(
            'static.revision',
            [
                'created_at' => (new DateTime())->setTimezone(new DateTimeZone('UTC'))->format('c'),
                'author' => $author,
                'description' => $commitMessage,
            ]
        );

        if (!$revInsertResult) {
            throw new RuntimeException('Unable to insert new static.revision');
        }

        $newRev = $em->getConnection()->lastInsertId();

        if ($newRev <= $oldRevision) {
            throw new UnexpectedResultException('New revision number fetched from static.revision must be greater than the old one');
        }

        return [$oldRevision, $newRev];
    }

    /**
     * Runs delete query and rolls back it
     *
     * @throws Throwable
     */
    public function checkDeletion(EntityManagerInterface $em, RevisionLoggableEntityInterface $object): void
    {
        if ($em->getConnection()->isTransactionActive()) {
            throw new RuntimeException('Unable to make deletion check');
        }

        try {
            $em->beginTransaction();
            $classMetadata = $em->getClassMetadata(get_class($object));
            $quoteStrategy = $em->getConfiguration()->getQuoteStrategy();
            $platform = $em->getConnection()->getDatabasePlatform();
            [$where, $params, $paramsTypes] = $this->getObjectIdentifierWherePart(
                $object,
                $classMetadata,
                $quoteStrategy,
                $platform
            );

            if (empty($where)) {
                throw new RuntimeException('Entity without PK can not be selected');
            }

            $em->getConnection()->executeStatement(
                sprintf(
                    'delete from %s where %s',
                    $quoteStrategy->getTableName($classMetadata, $platform),
                    implode(' and ', $where)
                ),
                $params ?? [],
                $paramsTypes ?? []
            );
        } catch (Throwable $t) {
            throw $t;
        } finally {
            $em->rollback();
        }
    }

    /**
     * Get entity raw data array
     *
     * @throws Exception
     */
    protected function getEntityRawData(
        RevisionLoggableEntityInterface $object,
        ClassMetadata $classMetadata,
        QuoteStrategy $quoteStrategy,
        Connection $connection
    ): array {
        $platform = $connection->getDatabasePlatform();
        $originalTable = $quoteStrategy->getTableName($classMetadata, $platform);
        [$where, $params, $paramsTypes] = $this->getObjectIdentifierWherePart($object, $classMetadata, $quoteStrategy, $platform);

        if (empty($where)) {
            throw new RuntimeException('Entity without PK can not be selected');
        }

        if ($connection instanceof PrimaryReadReplicaConnection) {
            $connection->connect('primary');
        }

        // At this point entity with specified revision should be locked already
        $existingEntityData = $connection->executeQuery(
            sprintf('select * from %s where %s', $originalTable, implode(' and ', $where)),
            $params ?? [],
            $paramsTypes ?? []
        )->fetchAllAssociative();

        if (count($existingEntityData) !== 1) {
            throw new RuntimeException('Expected exactly one row in the result');
        }

        return $existingEntityData[0];
    }

    /**
     * Get object identifier "WHERE" part for raw query
     */
    protected function getObjectIdentifierWherePart(
        RevisionLoggableEntityInterface $object,
        ClassMetadata $classMetadata,
        QuoteStrategy $quoteStrategy,
        AbstractPlatform $platform
    ): array {
        $where = [];
        $params = [];
        $paramsTypes = [];

        foreach ($classMetadata->getIdentifierValues($object) as $fieldName => $value) {
            $where[] = sprintf('%s = ?', $quoteStrategy->getColumnName($fieldName, $classMetadata, $platform));
            $params[] = $value;
            $paramsTypes[] = $classMetadata->getTypeOfField($fieldName);
        }

        return [$where, $params, $paramsTypes];
    }
}