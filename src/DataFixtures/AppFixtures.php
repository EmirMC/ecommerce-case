<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setEmail('demo' . $i . '@demo.com');
            $password = $this->hasher->hashPassword($user, 'demo123');
            $user->setPassword($password);
            $user->setRoles(["ROLE_CUSTOMER"]);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
