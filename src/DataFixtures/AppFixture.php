<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $names = ['Kevin', 'Antoine', 'Sarah'];
        foreach ($names as $name) {
            $user = new User();
            $user->setUsername($name)
                ->setRoles(['ROLE-USER']);

            $manager->persist($user);
            $manager->flush();
        }
        

    }
}
