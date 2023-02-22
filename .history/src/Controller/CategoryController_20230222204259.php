<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name: 'app_category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'products_')]
    public function index(): Response
    {
        return $this->render('products/index.html.twig');
    }

    #[Route('/{slug}', name: 'products_')]
    public function list(): Response
    {

        return $this->render('products/details.html.twig');
    }
}
