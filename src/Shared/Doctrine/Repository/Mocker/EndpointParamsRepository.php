<?php

namespace App\Shared\Doctrine\Repository\Mocker;

use App\Shared\Doctrine\Entity\Mocker\EndpointParam;
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

//    /**
//     * @return EndpointParams[] Returns an array of EndpointParams objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EndpointParams
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
