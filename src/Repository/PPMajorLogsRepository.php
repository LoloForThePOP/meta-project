<?php

namespace App\Repository;

use App\Entity\PPMajorLogs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PPMajorLogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method PPMajorLogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method PPMajorLogs[]    findAll()
 * @method PPMajorLogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PPMajorLogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PPMajorLogs::class);
    }

    // /**
    //  * @return PPMajorLogs[] Returns an array of PPMajorLogs objects
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
    public function findOneBySomeField($value): ?PPMajorLogs
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
