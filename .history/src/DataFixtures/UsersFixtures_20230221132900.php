<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UsersFixtures extends Fixture
{
    private $passwordencoder;
    private $slugger;
    
    public function __construct(UserPasswordHasherInterface $passwordencoder, SluggerInterface $slugger) 
    {
        $this->passwordencoder = $passwordencoder;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $Users = new Users();
        // $manager->persist($product);

        $manager->flush();
    }
}
