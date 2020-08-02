<?php

namespace App\Repository;

use App\Entity\Teammate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Teammate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Teammate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Teammate[]    findAll()
 * @method Teammate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeammateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Teammate::class);
    }

    // /**
    //  * @return Teammate[] Returns an array of Teammate objects
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
    public function findOneBySomeField($value): ?Teammate
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
