<?php

namespace App\Controller;

use App\Entity\Categories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/{slug}', name: 'liste')]
    public function list(Categories $categories): Response
    {
        // get all Products
        
        $products = $categories->getProducts();

        return $this->render('category/list.html.twig',compact('categories', 'products'));
    }
}
