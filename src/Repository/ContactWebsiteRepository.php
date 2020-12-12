<?php

namespace App\Repository;

use App\Entity\ContactWebsite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContactWebsite|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactWebsite|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactWebsite[]    findAll()
 * @method ContactWebsite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactWebsiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactWebsite::class);
    }

    // /**
    //  * @return ContactWebsite[] Returns an array of ContactWebsite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContactWebsite
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
