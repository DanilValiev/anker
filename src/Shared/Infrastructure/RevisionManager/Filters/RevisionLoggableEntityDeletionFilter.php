<?php

namespace App\Shared\Infrastructure\RevisionManager\Filters;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

/**
 * The EntityRevisionDeletableFilter adds excludes entities with not-null rev_to column (deleted softly)
 */
class RevisionLoggableEntityDeletionFilter extends SQLFilter
{
    /**
     * @param ClassMetadata $targetEntity
     * @param string $targetTableAlias
     *
     * @return string
     * @throws Exception
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias): string
    {
        if (!is_a($targetEntity->getName(), RevisionLoggableEntityInterface::class, true)) {
            return '';
        }

        return $this->getConnection()->getDatabasePlatform()->getIsNullExpression(
            sprintf('%s.rev_to', $targetTableAlias)
        );
    }
}
