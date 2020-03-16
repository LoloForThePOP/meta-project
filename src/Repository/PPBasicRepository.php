<?php

namespace App\Repository;

use App\Entity\PPBasic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PPBasic|null find($id, $lockMode = null, $lockVersion = null)
 * @method PPBasic|null findOneBy(array $criteria, array $orderBy = null)
 * @method PPBasic[]    findAll()
 * @method PPBasic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PPBasicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PPBasic::class);
    }

    // /**
    //  * @return PPBasic[] Returns an array of PPBasic objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PPBasic
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
