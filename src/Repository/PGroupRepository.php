<?php

namespace App\Repository;

use App\Entity\PGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method PGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method PGroup[]    findAll()
 * @method PGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PGroup::class);
    }

    // /**
    //  * @return PGroup[] Returns an array of PGroup objects
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
    public function findOneBySomeField($value): ?PGroup
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
