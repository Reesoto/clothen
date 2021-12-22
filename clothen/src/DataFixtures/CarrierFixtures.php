<?php

namespace App\DataFixtures;

use App\Entity\Carrier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CarrierFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $dhl = new Carrier();
        $dhl->setName("DHL");
        $dhl->setDescription("Express & Logistics. We move the world All the way I'm on it! First Choice Excellence. Simply Delivered.");
        $dhl->setPrice("990");
        $manager->persist($dhl);

        $colissimo = new Carrier();
        $colissimo->setName("Colissimo");
        $colissimo->setDescription("Vous simplifier la vie");
        $colissimo->setPrice("590");
        $manager->persist($colissimo);

        $manager->flush();
    }
}
