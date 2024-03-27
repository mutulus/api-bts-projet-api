<?php

namespace App\DataFixtures;

use App\Entity\Film;
use App\Entity\Salle;
use App\Entity\Seance;
use App\Repository\FilmRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeanceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $repoFilm = $manager->getRepository(Film::class);
        for ($i=1;$i<11;$i++){
            $date=new \DateTime();
            $date->format("Y-m-d h:i");
            $filmRec= $repoFilm->find($i);
            $randMin = random_int(0,59);
            $randH = random_int(9,22);
            $randJ = random_int(1,28);
            $date->setTime($randH,$randMin);
            $date->setDate($date->format('Y'),$date->format('m'),$randJ);
            if ($i%2==0){
                $interval = new \DateInterval('P1M');
                $date->add($interval);
            }

            $salle = $manager->getRepository(Salle::class)->find($i);
            $seance = new Seance();
            $seance->setDateProjection($date);
            $seance->setTarifNormal(8.50);
            $seance->setTarifReduit(5.50);
            $filmRec->addSeance($seance);
            $salle->addSeance($seance);
            $manager->persist($seance);
        }
        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            FilmFixtures::class,
            SalleFixtures::class,
        ];
    }
}
