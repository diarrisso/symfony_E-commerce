<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{


   private $slugger;
   private $counter = 1;

   public function __construct( SluggerInterface $slugger)
   {
      
    $this->slugger = $slugger;
    
   }



    public function load(ObjectManager $manager): void
    {

    
        $parent = $this->creatCategories('ordinateur', null, $manager);
        $this->creatCategories('Usb', $parent, $manager);
        $this->creatCategories('Hdmi', $parent, $manager);
        $this->creatCategories('Batterie', $parent, $manager);

        $parent = $this->creatCategories('Cuisine', null, $manager);
        $this->creatCategories('couscous', $parent, $manager);
        $this->creatCategories('Poisson', $parent, $manager);
        $this->creatCategories('Viande', $parent, $manager);

        $manager->flush();
    }

    public function creatCategories($name, Categories $parent = null, ObjectManager $manager )
    {
        $categories = new Categories();
        $categories->setName($name);
        $categories->setSlug($this->slugger->slug($categories->getName())->lower());
        $categories->setParent($parent);
        $manager->persist($categories);
        $this->addReference('cat_' .$this->counter, $categories);
        $this->counter++;

        return $categories;
    }
}
