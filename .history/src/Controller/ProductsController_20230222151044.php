<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products', name: 'app_products')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'products_')]
    public function index(): Response
    {
        return $this->render('products/index.html.twig', [
            'controller_name' => 'ProductsController',
        ]);
    }

    #[Route('/slug', name: 'products_')]
    public function details(): Response
    {
        return $this->render('products/details.html.twig');
    }
}
