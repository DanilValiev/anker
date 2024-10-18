<?php

namespace App\Shared\Infrastructure\Doctrine\Repository\Mocker;

use App\Shared\Domain\Entity\Mocker\Endpoint\Data\ResponseVariant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EndpointData>
 *
 * @method ResponseVariant|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResponseVariant|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResponseVariant[]    findAll()
 * @method ResponseVariant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EndpointDataResponseVariantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResponseVariant::class);
    }
}