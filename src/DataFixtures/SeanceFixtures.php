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
        $faker = Factory::create("fr_FR");
        for ($i=1;$i<11;$i++){
            $repoFilm = $manager->getRepository(Film::class);
            $filmRec= $repoFilm->find($i);


            $salle = $manager->getRepository(Salle::class)->find($i);
            $seance = new Seance();
            $seance->setFilm($filmRec);
            $seance->setSalle($salle);
            $seance->setDateProjection($faker->dateTime);
            $seance->setTarifNormal(8.50);
            $seance->setTarifReduit(5.50);
            $manager->persist($seance);
        }
        $manager->flush();
    }
}
