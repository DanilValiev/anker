<?php

namespace App\Modules\Scheduler\Infrastructure\Manager;

use App\Modules\Scheduler\Domain\Scheduler;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use JMS\JobQueueBundle\Entity\Job;

class EntityInQueueManager
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry
    ) { }

    public function getFutureChanges(string $relatedId)
    {
        $manager = $this->getManager();
        $rsm = new ResultSetMapping();
        $rsm
            ->addScalarResult('args', 'args')
            ->addScalarResult('executeafter', 'executeafter')
            ->addScalarResult('id', 'jobId')
        ;

        $qb = $manager->createNativeQuery("
            SELECT
                   j.args, j.executeafter, j.id
            FROM
                 public.jms_jobs AS j
            INNER JOIN
                     jms_job_related_entities jjre on j.id = jjre.job_id
            WHERE
                j.state = 'pending'
              AND
                  j.queue = :queue
                AND
                  jjre.related_id LIKE '%{$relatedId}%'
            ORDER BY id DESC
            LIMIT 1
        ", $rsm)
            ->setParameter('queue', Scheduler::QUEUE_NAME)
        ;

        $response = $qb->getArrayResult();

        return (!empty($response)) ? $response[0] : null;
    }

    public function checkAvailabilityInQueue(string $relatedId): bool
    {
        return !empty($this->getFutureChanges($relatedId));
    }

    public function cancelJob(int $jobId): void
    {
        $manager = $this->getManager();
        $job = $manager->createQueryBuilder()
            ->select('j')
            ->from(Job::class, 'j')
            ->where('j.id = :id')
            ->andWhere('j.queue = :queue')
            ->setParameter('id', $jobId)
            ->setParameter('queue', Scheduler::QUEUE_NAME)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        $job->setState(Job::STATE_CANCELED);
        $manager->persist($job);
        $manager->flush();
    }

    public function prepareChanges(string $changes): array
    {
        $changes = str_replace('--changes=', '', $changes);
        $changes = trim($changes, '[]"');
        $changes = str_replace('\\\\', '\\', $changes);
        $changes = str_replace('\"', '"', $changes);
        $changesArray = json_decode($changes, true);
        $this->prepareChangesArray($changesArray);

        return $changesArray;
    }

    private function prepareChangesArray(array &$changes): void
    {
        foreach ($changes as $key => $change) {
            if ($key == 'extra') {
                continue ;
            }

            if ($change[0] == $change[1]) {
                unset($changes[$key]);
            }
        }
    }

    private function getManager(): ObjectManager
    {
        return $this->managerRegistry->getManagerForClass(Job::class);
    }
}