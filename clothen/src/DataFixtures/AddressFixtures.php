<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $user = $manager->getRepository(User::class)->findOneById(1);

        $address = new Address();
        $address->setName("Home");
        $address->setLastname("Doe");
        $address->setFirstname("John");
        $address->setAddress("10 rue de la Liberté");
        $address->setZipcode("75008");
        $address->setCity("Paris");
        $address->setCountry("FR");
        $address->setPhone("+33601020304");
        $address->setUser($user);

        $manager->persist($address);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
