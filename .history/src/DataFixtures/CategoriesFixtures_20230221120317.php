<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = new Categories();
        $categories->setName('informatique');
        $manager->persist($categories);
        $manager->flush();
    }
}
