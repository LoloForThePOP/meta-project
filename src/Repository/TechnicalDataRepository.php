<?php

namespace App\Repository;

use App\Entity\TechnicalData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TechnicalData|null find($id, $lockMode = null, $lockVersion = null)
 * @method TechnicalData|null findOneBy(array $criteria, array $orderBy = null)
 * @method TechnicalData[]    findAll()
 * @method TechnicalData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TechnicalDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TechnicalData::class);
    }

    // /**
    //  * @return TechnicalData[] Returns an array of TechnicalData objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TechnicalData
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
