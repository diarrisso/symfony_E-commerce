<?php

 namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user', name: 'admin_users')]
 class UsersController extends AbstractController 
 {
    #[Route('/', name: 'index')]
    public function index() 
    {
     

        return $this->render('admin/users/index.html.twig');

    }



 }