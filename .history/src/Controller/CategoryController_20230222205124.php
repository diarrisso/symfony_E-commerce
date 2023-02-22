<?php

namespace App\Controller;

use App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'category_')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig');
    }

    #[Route('/{slug}', name: 'liste')]
    public function list(Categories $categories): Response
    {

        return $this->render('products/list.html.twig',compact('categories'));
    }
}
