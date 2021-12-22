<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{

    public function load(ObjectManager $manager): void
    {
        $category1 = new Category();
        $category2 = new Category();
        $category3 = new Category();
        $category4 = new Category();

        $category1->setName("Hat");
        $manager->persist($category1);

        $category2->setName("Scarf");
        $manager->persist($category2);

        $category3->setName("Coat");
        $manager->persist($category3);

        $category4->setName("T-shirt");
        $manager->persist($category4);

        $manager->flush();
    }
}
