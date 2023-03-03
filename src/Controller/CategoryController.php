<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/{slug}', name: 'liste')]
    public function list(Categories $categories, ProductsRepository $productsRepository, Request $request): Response
    {
        // get current Page
         $page = $request->query->getInt('page', 1);
        // get all Products
        //$products = $categories->getProducts();
        $products = $productsRepository->findProductsPaginated($page, $categories->getSlug(),4);


        return $this->render('category/list.html.twig',compact('categories','products'));
    }
}
