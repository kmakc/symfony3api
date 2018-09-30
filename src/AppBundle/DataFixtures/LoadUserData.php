<?php

namespace AppBundle\DataFixtures;

use AppBundle\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setUsername('john_doe');
        $user1->setApiKey('fikffkRWJFG#(*$');

        $manager->persist($user1);

        $user2 = new User();
        $user2->setUsername('john_doe2');
        $user2->setApiKey('DSFKJDOSF*U#FODAFHEUAF');

        $manager->persist($user2);

        $manager->flush();
    }
}
