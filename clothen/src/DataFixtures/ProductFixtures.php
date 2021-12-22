<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // PRODUCTS' CATEGORIES
        $category1 = $manager->getRepository(Category::class)->findOneById(1);
        $category2 = $manager->getRepository(Category::class)->findOneById(2);
        $category3 = $manager->getRepository(Category::class)->findOneById(3);
        $category4 = $manager->getRepository(Category::class)->findOneById(4);

        // BONNETS
        $product = new Product();
        $product->setName("Red winter bonnet");
        $product->setDescription("Nulla porttitor accumsan tincidunt. Donec sollicitudin molestie malesuada.");
        $product->setPicture("57a542a637d43cc5f4685f3451dfa3b903abc7cd.jpg");
        $product->setPrice(1000);
        $product->setSlug("red-winter-bonnet");
        $product->setSubtitle("Nulla porttitor accumsan tincidunt");
        $product->setCategory($category1);
        $manager->persist($product);

        $product2 = new Product();
        $product2->setName("Blue winter bonnet");
        $product2->setDescription("Sed porttitor lectus nibh. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus.");
        $product2->setPicture("52bfda97a19c19ace3bd6ea2bd9a638487ad90fd.jpg");
        $product2->setPrice(1500);
        $product2->setSlug("blue-winter-bonnet");
        $product2->setSubtitle("Sed porttitor lectus nibh");
        $product2->setCategory($category1);
        $manager->persist($product2);

        // SCARFS
        $product3 = new Product();
        $product3->setName("Red city scarf");
        $product3->setDescription("Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Quisque velit nisi, pretium ut lacinia in, elementum id enim.");
        $product3->setPicture("e37f85c2c1fa6b6285282ffff7e29547c0a2380e.jpg");
        $product3->setPrice(2000);
        $product3->setSlug("red-city-scarf");
        $product3->setSubtitle("Curabitur non nulla sit amet nisl tempus convallis quis ac lectus");
        $product3->setCategory($category2);
        $manager->persist($product3);

        $product4 = new Product();
        $product4->setName("Brown winter scarf");
        $product4->setDescription("Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a. Vestibulum ac diam sit amet quam vehicula elementum sed sit amet dui.");
        $product4->setPicture("08a7ea0305991cb9f96c213555b2103cc68cb096.jpg");
        $product4->setPrice(2500);
        $product4->setSlug("brown-winter-scarf");
        $product4->setSubtitle("Mauris blandit aliquet elit, eget tincidunt nibh pulvinar a");
        $product4->setCategory($category2);
        $manager->persist($product4);


        // COATS
        $product5 = new Product();
        $product5->setName("Woman fancy coat");
        $product5->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec rutrum congue leo eget malesuada.");
        $product5->setPicture("b085dd553bfeac389631b6b8423000f8ec8f7639.jpg");
        $product5->setPrice(15000);
        $product5->setSlug("woman-fancy-coat");
        $product5->setSubtitle("Lorem ipsum dolor sit amet, consectetur adipiscing elit");
        $product5->setCategory($category3);
        $manager->persist($product5);

        $product6 = new Product();
        $product6->setName("Blue windy coat");
        $product6->setDescription("Cras ultricies ligula sed magna dictum porta. Donec rutrum congue leo eget malesuada.");
        $product6->setPicture("2d78f316a4d993226df09b713e68f961fbc68a22.jpg");
        $product6->setPrice(10000);
        $product6->setSlug("blue-windy-coat");
        $product6->setSubtitle("Cras ultricies ligula sed magna dictum porta");
        $product6->setCategory($category3);
        $manager->persist($product6);

        // T-SHIRT
        $product7 = new Product();
        $product7->setName("Black T-Shirt");
        $product7->setDescription("Cras ultricies ligula sed magna dictum porta. Praesent sapien massa, convallis a pellentesque nec, egestas non nisi.");
        $product7->setPicture("70895302c63a397ab2390a0d7924bf07b6b18d1f.jpg");
        $product7->setPrice(4000);
        $product7->setSlug("black-t-shirt");
        $product7->setSubtitle("Cras ultricies ligula sed magna dictum porta");
        $product7->setCategory($category4);
        $manager->persist($product7);

        $product8 = new Product();
        $product8->setName("White T-Shirt");
        $product8->setDescription("Proin eget tortor risus. Vivamus magna justo, lacinia eget consectetur sed, convallis at tellus.");
        $product8->setPicture("2c4468fc728bf2c3008f85ca079ca6c0857b7f22.jpg");
        $product8->setPrice(3000);
        $product8->setSlug("white-t-shirt");
        $product8->setSubtitle("Proin eget tortor risus");
        $product8->setCategory($category4);
        $manager->persist($product8);


        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class
        ];
    }


}
