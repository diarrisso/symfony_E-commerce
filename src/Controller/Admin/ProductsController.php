<?php

 namespace App\Controller\Admin;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/products', name: 'admin_products_')]
 class ProductsController extends AbstractController
 {
    #[Route('/', name: 'index')]
    public function index() 
    {
        return $this->render('admin/products/index.html.twig');
    }

    #[Route('/create', name: 'create_')]
    public function create( )
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/products/index.html.twig');
    }

    #[Route('/edit{id}', name: 'edit')]
    public function edit(Products $products)
    {
        // on verifie sin utilisateur peut editer un produits
        $this->denyAccessUnlessGranted('ROLE_PRODUCTS_ADMIN', $products);
        return $this->render('admin/products/index.html.twig');
    }

    #[Route('/delete{id}', name: 'delete')]
    public function delete(Products $products)
    {
        return $this->render('admin/products/index.html.twig');
    }

    #[Route('/update{id}', name: 'update')]
    public function update(Products $products)
    {
        return $this->render('admin/products/index.html.twig');
    }



 }