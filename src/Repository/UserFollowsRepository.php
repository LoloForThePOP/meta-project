<?php

namespace App\Repository;

use App\Entity\UserFollows;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserFollows|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserFollows|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserFollows[]    findAll()
 * @method UserFollows[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserFollowsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserFollows::class);
    }

    // /**
    //  * @return UserFollows[] Returns an array of UserFollows objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserFollows
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
