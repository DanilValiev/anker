<?php

namespace App\EasyAdmin\Scheduler\Helpers;

use Doctrine\ORM\EntityManagerInterface;

class EntityChangeExtractor
{
    public function __construct(
        private EntityManagerInterface $entityManager
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