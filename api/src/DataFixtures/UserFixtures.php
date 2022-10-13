<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $pwd = '$2y$13$SWUCVjNWUsVVdk4AahN0Iu5Kd.q7V6TtulVWTSBaK2QXsNgmt.DG2';
        $user = (new User())
            ->setEmail('user@user.com')
            ->setRoles(['ROLE_USER'])
            ->setPassword($pwd);
        $manager->persist($user);

        $admin = (new User())
            ->setEmail('admin@admin.com')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($pwd);
        $manager->persist($admin);

        $moderator = (new User())
            ->setEmail('moderator@moderator.com')
            ->setRoles(['ROLE_MODERATOR'])
            ->setPassword($pwd);
        $manager->persist($moderator);
        $manager->flush();
    }
}
