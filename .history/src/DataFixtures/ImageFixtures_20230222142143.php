<?php

namespace App\DataFixtures;

use App\Entity\Images;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ImageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       
        $faker = Faker\Factory::create('fr_FR');

        for ($img= 1; $img <= 10; $img++)
         {
            $img = new Images();
            $img->setName($faker->image(null, 640, 480));
            // getPrducts for img;
            $products = $this->getReference('prod_'.rand(1, 8));
        
            $img->setCategories($categoties);

            $manager->persist($img);
        
        }

        $manager->flush();
    }
}
