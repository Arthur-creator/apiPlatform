<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Joke;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class JokeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $categories = $manager->getRepository(Category::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();
        for ($i = 0; $i < 10; $i++) {
            $object = (new Joke())
                ->setText($faker->paragraph)
                ->setAuthor($faker->randomElement($users))
                ->setAnswer($faker->paragraph);
            for ($y = 0; $y < 3; $y++) {
                $object->addCategory($faker->randomElement($categories));
            }


            $manager->persist($object);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class,
        ];
    }
}
