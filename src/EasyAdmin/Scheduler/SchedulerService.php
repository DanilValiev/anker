<?php

namespace App\EasyAdmin\Scheduler;

use App\EasyAdmin\Scheduler\Helpers\EntityChangeExtractor;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use JMS\JobQueueBundle\Entity\Job;

class SchedulerService
{
    public const QUEUE_NAME = 'admin-board';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private EntityChangeExtractor $changeExtractor
    ) { }

    public function scheduleEntity(object $entity, string $entityFqcn, DateTime $dateTime)
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