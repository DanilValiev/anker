<?php

namespace App\Shared\Infrastructure\RevisionManager\EventSubscriber;

use App\Shared\Infrastructure\RevisionManager\EntityRevisionManager;
use App\Shared\Infrastructure\RevisionManager\Filters\RevisionLoggableEntityInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\PessimisticLockException;
use Doctrine\ORM\TransactionRequiredException;
use Doctrine\ORM\UnexpectedResultException;
use Symfony\Component\Security\Core\Security;

/**
 * SoftDeletable listener
 */
class RevisionLoggableEntityListener implements EventSubscriber
{
    protected const CHANGESET_NAME_CHANGED = 'changed';

    protected const CHANGESET_NAME_REMOVED = 'removed';

    protected const CHANGESET_NAME_CREATED = 'created';

    /**
     * Security
     *
     * @var Security
     */
    protected Security $security;

    /**
     * EntityRevisionManager
     *
     * @var EntityRevisionManager
     */
    protected EntityRevisionManager $entityRevisionManager;

    /**
     * Entities scheduled for soft delete
     */
    private ?array $scheduledForSoftDelete = [];

    /**
     * Entities scheduled for update
     */
    private ?array $scheduledForUpdate = [];

    /**
     * Entities scheduled for insert
     */
    private ?array $scheduledForInsert = [];

    /**
     * Old Schema Revision
     */
    private ?int $oldSchemaRevision;

    /**
     * New Schema Revision
     */
    private ?int $newSchemaRevision;

    /**
     * SoftDeletableListener constructor.
     */
    public function __construct(Security $security, EntityRevisionManager $entityRevisionManager)
    {
        $this->security = $security;
        $this->entityRevisionManager = $entityRevisionManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
            Events::preUpdate,
            Events::postPersist,
            Events::postFlush,
        ];
    }

    /**
     * If it's a SoftDeletable object, update the rev_to
     * and skip the removal of the objec
     */
    public function onFlush(OnFlushEventArgs $args): void
    {
        $this->clearState();
        $em  = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        // Since we need transaction for `updateSchemaRevision` (due to pessimistic lock)
        // and can not effectively rollback transaction (in case of errors) if it was started here,
        // just postpone actual changes
        foreach ($uow->getScheduledEntityInsertions() as $object) {
            if ($object instanceof RevisionLoggableEntityInterface) {
                $this->scheduledForInsert[spl_object_hash($object)] = $object;
            }
        }

        foreach ($uow->getScheduledEntityDeletions() as $object) {
            if ($object instanceof RevisionLoggableEntityInterface) {
                $this->scheduledForSoftDelete[spl_object_hash($object)] = $object;
                $em->persist($object);
                $uow->recomputeSingleEntityChangeSet($em->getClassMetadata(get_class($object)), $object);
                $uow->scheduleForUpdate($object);
            }
        }

        foreach ($uow->getScheduledEntityUpdates() as $object) {
            if ($object instanceof RevisionLoggableEntityInterface
                && !isset($this->scheduledForSoftDelete[spl_object_hash($object)])
            ) {
                $this->scheduledForUpdate[spl_object_hash($object)] = $object;
            }
        }
    }

    /**
     * Post flush event handler
     */
    public function postFlush(PostFlushEventArgs $args): void
    {
        $this->clearState();
    }

    /**
     * Pre update
     *
     * @throws NonUniqueResultException
     * @throws PessimisticLockException
     * @throws TransactionRequiredException
     * @throws UnexpectedResultException
     * @throws Exception
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $object = $args->getObject();

        if (!$object instanceof RevisionLoggableEntityInterface) {
            return;
        }

        $em = $args->getEntityManager();

        $classMetadata = $em->getClassMetadata(get_class($object));

        if (isset($this->scheduledForUpdate[spl_object_hash($object)])) {
            [$oldSchemaRevision, $newSchemaRevision] = $this->retrieveSchemaRevision($em);
            $this->entityRevisionManager->lockEntityRevision(
                $em,
                $object,
                $object->getRevFrom(),
                $object->getRevTo(),
                $classMetadata
            );
            $this->entityRevisionManager->insertEntityLog($em, $object, $object->getRevFrom(), $oldSchemaRevision);
            $object->setRevFrom($newSchemaRevision);
            $em->getUnitOfWork()->recomputeSingleEntityChangeSet($classMetadata, $object);
        } elseif (isset($this->scheduledForSoftDelete[spl_object_hash($object)])) {
            [$oldSchemaRevision, $newSchemaRevision] = $this->retrieveSchemaRevision($em);
            $object->setRevTo($oldSchemaRevision);
            $em->getUnitOfWork()->recomputeSingleEntityChangeSet($classMetadata, $object);
        }
    }

    /**
     * Post persist
     *
     * @throws TransactionRequiredException
     * @throws UnexpectedResultException|Exception
     */
    public function postPersist(LifecycleEventArgs $args)
    {
        $object = $args->getObject();

        if (!$object instanceof RevisionLoggableEntityInterface || !isset($this->scheduledForInsert[spl_object_hash($object)])) {
            return;
        }

        $em = $args->getEntityManager();
        [$oldSchemaRevision, $newSchemaRevision] = $this->retrieveSchemaRevision($em);
        // prePersist executed before transaction begin, so schedule extra update in postPersist
        $em->getUnitOfWork()->scheduleExtraUpdate(
            $object,
            ['revFrom' => [$object->getRevFrom(), $newSchemaRevision]]
        );
    }

    /**
     * Retrieve schema revision
     *
     * @throws TransactionRequiredException
     * @throws UnexpectedResultException
     * @throws Exception
     */
    protected function retrieveSchemaRevision(EntityManagerInterface $em): array
    {
        if ($this->oldSchemaRevision === null || $this->newSchemaRevision === null) {
            $commitMessage = $this->getRevisionDescription($em);
            [$this->oldSchemaRevision, $this->newSchemaRevision] = $this->entityRevisionManager->updateSchemaRevision(
                $em,
                $this->security->getUser() ? $this->security->getUser()->getUserIdentifier() : '',
                $commitMessage
            );
        }

        return [$this->oldSchemaRevision, $this->newSchemaRevision];
    }

    /**
     * Clear state
     */
    protected function clearState(): void
    {
        $this->scheduledForSoftDelete = $this->scheduledForUpdate = $this->scheduledForInsert = [];
        $this->oldSchemaRevision      = $this->newSchemaRevision = null;
    }

    /**
     * Get revisionDescription property
     */
    protected function getRevisionDescription(EntityManagerInterface $em): string
    {
        $groupedChangeSet = [];
        $changeSetClosure = function (object $v, $key, $type) use ($em, &$groupedChangeSet) {
            $classMetadata = $em->getClassMetadata(get_class($v));

            $result = [];
            if ($type === self::CHANGESET_NAME_CREATED) {
                if ($classMetadata->hasField('name')
                    && is_string($name = $classMetadata->getFieldValue($v, 'name'))) {
                    $result = ['name' => $name];
                }
            } else {
                $result = $classMetadata->getIdentifierValues($v);
            }

            $groupedChangeSet[$type][$classMetadata->getReflectionClass()->getShortName()][] = $result;
        };

        foreach ([
                     self::CHANGESET_NAME_CREATED => &$this->scheduledForInsert,
                     self::CHANGESET_NAME_CHANGED => &$this->scheduledForUpdate,
                     self::CHANGESET_NAME_REMOVED => &$this->scheduledForSoftDelete,
                 ] as $changeType => &$entities) {
            array_walk(
                $entities,
                $changeSetClosure,
                $changeType
            );
        }

        unset($entities);
        $revisionDescription = json_encode($groupedChangeSet, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return $revisionDescription ?: '';
    }
}
