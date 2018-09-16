<?php

namespace App\DataFixtures;

use App\Entity\Person;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class PersonFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = new Factory();
        $faker = $faker->create();

        $person = new Person();
        $person->setName($faker->name);
        $manager->persist($person);

        $manager->flush();
    }
}
