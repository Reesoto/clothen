<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $encoder;

    /**
     * @param $encoder
     */
    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("john@doe.com");
        $user->setFirstname("John");
        $user->setLastname("Doe");
        $password = $this->encoder->hashPassword($user, "password");
        $user->setPassword($password);
        $manager->persist($user);

        $manager->flush();
    }
}
