<?php

namespace App\Shared\Doctrine\Repository\Mocker;

use App\Shared\Doctrine\Entity\Mocker\ApiScope;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApiScope>
 *
 * @method ApiScope|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiScope|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiScope[]    findAll()
 * @method ApiScope[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiScopesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiScope::class);
    }
}
