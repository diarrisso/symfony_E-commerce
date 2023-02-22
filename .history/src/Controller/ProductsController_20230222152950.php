<?php

namespace App\Controller;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products', name: 'app_products')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'products_')]
    public function index(): Response
    {
        return $this->render('products/index.html.twig');
    }

    #[Route('/{slug}', name: 'products_')]
    public function details(Products $products): Response
    {
        dd($products);
        return $this->render('products/details.html.twig');
    }
}
