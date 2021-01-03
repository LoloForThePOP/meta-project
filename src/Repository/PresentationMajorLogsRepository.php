<?php

namespace App\Repository;

use App\Entity\PresentationMajorLogs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PresentationMajorLogs|null find($id, $lockMode = null, $lockVersion = null)
 * @method PresentationMajorLogs|null findOneBy(array $criteria, array $orderBy = null)
 * @method PresentationMajorLogs[]    findAll()
 * @method PresentationMajorLogs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PresentationMajorLogsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PresentationMajorLogs::class);
    }

    // /**
    //  * @return PresentationMajorLogs[] Returns an array of PresentationMajorLogs objects
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
    public function findOneBySomeField($value): ?PresentationMajorLogs
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
