<?php

namespace App\Repository;

use App\Entity\GeoDomain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GeoDomain|null find($id, $lockMode = null, $lockVersion = null)
 * @method GeoDomain|null findOneBy(array $criteria, array $orderBy = null)
 * @method GeoDomain[]    findAll()
 * @method GeoDomain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GeoDomainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GeoDomain::class);
    }

    // /**
    //  * @return GeoDomain[] Returns an array of GeoDomain objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GeoDomain
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
