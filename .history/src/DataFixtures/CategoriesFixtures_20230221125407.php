<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{


   private $slugger;

   public function __construct( SluggerInterface $slugger)
   {
      
    $this->slugger = $slugger;
    
   }



    public function load(ObjectManager $manager): void
    {
        $categories = new Categories();
        $categories->setName('informatique');
        $categories->setSlug('informatique');
        $manager->persist($categories);
        $manager->flush();
    }

    public function creatCategories($name, Categories $parent = null, ObjectManager $manager )
    {
        $categories = new Categories();
        $categories->setName($name);
        $categories->setSlug($this->slugger->slug($categories->getName())->lower());
        $manager->persist($categories);
        $manager->flush();
    }
}
