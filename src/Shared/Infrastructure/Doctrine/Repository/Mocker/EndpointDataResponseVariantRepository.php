<?php

namespace App\Shared\Infrastructure\Doctrine\Repository\Mocker;

use App\Shared\Domain\Entity\Mocker\Endpoint\Data\EndpointDataResponseVariant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EndpointData>
 *
 * @method EndpointDataResponseVariant|null find($id, $lockMode = null, $lockVersion = null)
 * @method EndpointDataResponseVariant|null findOneBy(array $criteria, array $orderBy = null)
 * @method EndpointDataResponseVariant[]    findAll()
 * @method EndpointDataResponseVariant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EndpointDataResponseVariantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EndpointDataResponseVariant::class);
    }
}