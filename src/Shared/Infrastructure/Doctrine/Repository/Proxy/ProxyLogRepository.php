<?php

namespace App\Shared\Infrastructure\Doctrine\Repository\Proxy;

use App\Shared\Domain\Entity\Proxy\ProxyLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProxyLog>
 *
 * @method ProxyLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProxyLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProxyLog[]    findAll()
 * @method ProxyLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProxyLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProxyLog::class);
    }

    public function create(ProxyLog $processLog): void
    {
        $this->_em->persist($processLog);
        $this->_em->flush();
    }
}
