<?php

namespace App\Repository;

use App\Entity\MessageConsultation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MessageConsultation|null find($id, $lockMode = null, $lockVersion = null)
 * @method MessageConsultation|null findOneBy(array $criteria, array $orderBy = null)
 * @method MessageConsultation[]    findAll()
 * @method MessageConsultation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageConsultationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageConsultation::class);
    }

    // /**
    //  * @return MessageConsultation[] Returns an array of MessageConsultation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MessageConsultation
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
