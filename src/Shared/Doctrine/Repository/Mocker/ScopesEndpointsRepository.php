<?php

namespace App\Shared\Doctrine\Repository\Mocker;

use App\Shared\Doctrine\Entity\Mocker\Endpoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Endpoint>
 *
 * @method Endpoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method Endpoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method Endpoint[]    findAll()
 * @method Endpoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScopesEndpointsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Endpoint::class);
    }

//    /**
//     * @return ScopesEndpoints[] Returns an array of ScopesEndpoints objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ScopesEndpoints
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
