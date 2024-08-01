<?php

namespace App\Shared\Doctrine\Repository\Mocker;

use App\Shared\Doctrine\Entity\Mocker\ProcessLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProcessLog>
 *
 * @method ProcessLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProcessLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProcessLog[]    findAll()
 * @method ProcessLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProcessLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProcessLog::class);
    }

    public function create(ProcessLog $processLog): void
    {
        $this->_em->persist($processLog);
        $this->_em->flush();
    }
}
