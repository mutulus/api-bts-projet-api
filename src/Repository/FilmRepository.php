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
            ->innerJoin('f.seances', 's')
            ->where('s.dateProjection >= :date')
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }

    public function findFilmDetailId(int $id) : array
    {
        return $this->createQueryBuilder('f')
            ->select('f','s')
            ->innerJoin('f.seances','s')
            ->where('f.id = :id')
            ->setParameter('id',$id)
            ->orderBy('s.dateProjection','ASC')
            ->getQuery()
            ->getArrayResult();
    }

}
