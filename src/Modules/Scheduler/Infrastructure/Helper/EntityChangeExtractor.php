<?php

namespace App\Modules\Scheduler\Infrastructure\Helper;

use App\Modules\Scheduler\Domain\Helper\EntityChangeExtractorInterface;
use Doctrine\ORM\EntityManagerInterface;

class EntityChangeExtractor implements EntityChangeExtractorInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) { }

    public function extract(object $entity, string $entityFqcn): array
    {
        $uow = $this->entityManager->getUnitOfWork();
        $uow->computeChangeSets();

        $change = $uow->getEntityChangeSet($entity);
        $change['extra']['entityName'] = $entityFqcn;
        $change['extra']['entityIdentify'] = $entity->getId();

        return $change;
    }
}