<?php

namespace App\Shared\Doctrine\Repository\Mocker;

use App\Shared\Doctrine\Entity\Mocker\EndpointData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EndpointData>
 *
 * @method EndpointData|null find($id, $lockMode = null, $lockVersion = null)
 * @method EndpointData|null findOneBy(array $criteria, array $orderBy = null)
 * @method EndpointData[]    findAll()
 * @method EndpointData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EndpointDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EndpointData::class);
    }
}
