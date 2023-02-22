<?php

namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class UsersFixtures extends Fixture
{
    private $passwordEncoder;
    private $slugger;

    public function __construct(UserPasswordHasherInterface $passwordEncoder, SluggerInterface $slugger) 
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new Users();
        $admin->setFirstname('Ibrahima kalil ');
        $admin->setLastname('Diarrisso');
        $admin->setAdresse('am Becketal 80');
        $admin->setZipCode('28755');
        $admin->setEmail('Ibrahima@gmail.com');
        $admin->setCity('Bremen');
        $admin->setPassword(

            $this->passwordEncoder->hashPassword($admin, 'Matsinga76')
        );

        $admin->setRoles(['ROLE_ADMIN']);
        
        $manager->persist($admin);

        $manager->flush();
    }
}
