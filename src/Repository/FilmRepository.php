<?php

namespace App\Repository;

use App\Entity\Film;
use App\Entity\Seance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Film>
 *
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

//    public function findFilmAffiche() : array
//    {
//        $date = new \DateTime();
//        $dateString = $date->format("Y-m-d h:i");
//        return $this->createQueryBuilder('f')
//            ->select('f','s.dateProjection')
//            ->from('App:Seance' ,'s')
//            ->innerJoin('f.id = s.film ','fs')
//            ->andWhere("s.dateProjection >= :date")
//            ->setParameter("date",$dateString)
//            ->orderBy('s.dateProjection')
//            ->getQuery()
//            ->getResult()
//            ;
//    }
    public function findFilmAffiche(): array
    {
        $date = new \DateTime();
          return $this->createQueryBuilder('f')
              ->select('f')
              ->from(Seance::class, 's')
              ->join('s.film', 'fs')
              ->where('s.dateProjection >= :date')
              ->setParameter('date', $date)
              ->orderBy('s.dateProjection', 'ASC')
              ->getQuery()
              ->getResult();
//
//->getEntityManager()
//            ->createQuery(
//                'SELECT f
//            FROM App\Entity\Seance as s
//            JOIN s.film as f
//            WHERE s.dateProjection >= CURRENT_TIMESTAMP()'
//            )
//            ->getResult();

//        return $this->createQueryBuilder('f')
//            ->select('f')
//            ->from('App:Seance' ,'s')
//            ->innerJoin('f.id', 's')
//            ->where('s.dateProjection >= :date')
//            ->setParameter('date', $date)
//            ->orderBy('s.dateProjection', 'ASC')
//            ->getQuery()
//            ->getResult();
    }

    //    /**
    //     * @return Film[] Returns an array of Film objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Film
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
