<?php

namespace App\DataFixtures;

use App\Entity\products;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker;

class ProductFixtures extends Fixture
{
    private $slugger;

    public function __construct( SluggerInterface $slugger)
    {
       
     $this->slugger = $slugger;
     
    }
 
    public function load(ObjectManager $manager): void
    {
        //$faker = Faker\Factory::create();
        $faker = Faker\Factory::create('fr_FR');

        for ($prod = 1; $prod <= 10; $prod++) {
            $products = new Products();
            $products->setName($faker->text(5));
            $products->setDescription($faker->text());
            $products->setSlug($this->slugger->slug($products->getName())->lower());
            $products->setPrice($faker->numberBetween(900,15000));
            $products->setStock($faker->numberBetween(0, 10));

            // getCategories for products;
            $categoties = $this->getReference('cat-'.rand(1,8));
        
            $products->setCategories($categoties);

            $manager->persist($products);
        
        }

        $manager->flush();

    
    }
}
