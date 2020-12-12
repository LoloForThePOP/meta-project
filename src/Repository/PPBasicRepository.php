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
    

    public function findByCategories (array $category) {

            return $this->createQueryBuilder('p')
            ->select('p as project, COUNT(c) as nbMatchCat')
            ->join('p.categories', 'c')
            ->orWhere('c.id in (:category)')
            ->setParameter('category', $category)
            ->addOrderBy('COUNT(c)', 'DESC')
            ->addOrderBy('SIZE(p.categories)', 'ASC')
            ->addOrderBy('c.id', 'ASC')
            ->groupBy('p.id')
            ->having('SIZE(p.categories) <= COUNT(c) + 1 ')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;

    }

 

   public function findByPlaces ($placeType, $placeName) {

        $qb = $this->createQueryBuilder('p')
            ->select('p as project')
            ->join('p.geoDomains', 'g');

            if ($placeType=="administrative_area_level_1") {
               $qb->andWhere("g.administrativeAreaLevel1=:placeName");
               $qb->setParameter('placeName', $placeName);
            }

            elseif ($placeType=="administrative_area_level_2") {
               $qb->andWhere("g.administrativeAreaLevel2=:placeName");
               $qb->setParameter('placeName', $placeName);
            }

            elseif ($placeType=="country") {
               $qb->andWhere("g.country=:placeName");
               $qb->setParameter('placeName', $placeName);
            }
            
            return $qb  ->setMaxResults(100)
            ->getQuery()
            ->getResult();

    }

   public function findnNear ($latitude, $longitude, $radius) {

        $qb = $this->createQueryBuilder('p')
            ->select('p, g')
            ->join('p.geoDomains', 'g');

            
            return $qb  ->setMaxResults(100)
            ->getQuery()
            ->getResult();

    }



        
        /* $qb->addSelect("(CASE WHEN g.placeName = :placeName THEN 0
           ELSE 1 END) AS HIDDEN ORD ")
            ->setParameter('placeName', $placeName)
            ->orderBy('ORD', 'DESC'); */

     


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
