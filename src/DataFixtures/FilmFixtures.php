<?php

namespace App\DataFixtures;

use App\Entity\Film;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FilmFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));
        // Création de 10 films
        for ($i=0;$i<10;$i++){
            $film = new Film();
            $film->setTitre($faker->movie);
            $film->setDureeMin(random_int(90,150));
            $manager->persist($film);
        }
        $manager->flush();
    }
}
