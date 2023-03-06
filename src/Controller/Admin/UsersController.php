<?php

 namespace App\Controller\Admin;

use App\Repository\UsersRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user', name: 'admin_users_')]
 class UsersController extends AbstractController 
 {
    #[Route('/', name: 'index')]
    public function index(UsersRepository $usersRepository)
    {
     
        $users = $usersRepository->findBy([], ['firstname'=> 'asc']);

        return $this->render('admin/users/index.html.twig', compact('users'));

    }



 }