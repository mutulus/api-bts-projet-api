<?php

namespace App\DataFixtures;

use App\Entity\Salle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SalleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        for($i=0;$i<10;$i++){
            $salle = new Salle();
            $salle->setNom($faker->name." ".$faker->lastName);
            $salle->setNbPlaces(random_int(0,99));
            $manager->persist($salle);
        }
        $manager->flush();
    }
}
