<?php

namespace App\Modules\Scheduler\Domain;

use App\Modules\Scheduler\Infrastructure\Helper\EntityChangeExtractor;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use JMS\JobQueueBundle\Entity\Job;

class Scheduler
{
    public const QUEUE_NAME = 'anker-board';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EntityChangeExtractor $changeExtractor
    ) { }

    public function scheduleEntity(object $entity, string $entityFqcn, DateTime $dateTime): void
    {
        $changes = $this->changeExtractor->extract($entity, $entityFqcn);
        $changes = json_encode($changes);

        $job = new Job('scheduler:update:entity', ["--changes={$changes}"], true, self::QUEUE_NAME);
        $job->setExecuteAfter($dateTime);
        $job->addRelatedEntity($entity);

        $this->entityManager->clear();
        $this->entityManager->persist($job);
        $this->entityManager->flush();
    }
}