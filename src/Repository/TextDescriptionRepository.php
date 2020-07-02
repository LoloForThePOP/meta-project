<?php

namespace App\Repository;

use App\Entity\TextDescription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TextDescription|null find($id, $lockMode = null, $lockVersion = null)
 * @method TextDescription|null findOneBy(array $criteria, array $orderBy = null)
 * @method TextDescription[]    findAll()
 * @method TextDescription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextDescriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TextDescription::class);
    }

    // /**
    //  * @return TextDescription[] Returns an array of TextDescription objects
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
    public function findOneBySomeField($value): ?TextDescription
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
