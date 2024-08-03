<?php

namespace App\Shared\Infrastructure\Doctrine\Repository\Mocker;

use App\Shared\Domain\Entity\Mocker\EndpointParam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EndpointParam>
 *
 * @method EndpointParam|null find($id, $lockMode = null, $lockVersion = null)
 * @method EndpointParam|null findOneBy(array $criteria, array $orderBy = null)
 * @method EndpointParam[]    findAll()
 * @method EndpointParam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EndpointParamsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EndpointParam::class);
    }
}
