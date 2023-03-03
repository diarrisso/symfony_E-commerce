<?php

 namespace App\Controller\Admin;

use App\Entity\Products;
use App\Form\ProductFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/products', name: 'admin_products_')]
 class ProductsController extends AbstractController
 {
    #[Route('/', name: 'index')]
    public function index() 
    {
        return $this->render('admin/products/index.html.twig');
    }

    #[Route('/create', name: 'create_')]
    public function create( Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // on cree un nouveau Produits
        $products = new Products();

        $ProductsForm = $this->createForm(ProductFormType::class, $products);

        $ProductsForm->handleRequest($request);
        
        
        if ($ProductsForm->isSubmitted() &&  $ProductsForm->isValid()) {
            
            $slug = $slugger->slug($products->getName());
            $products->setSlug($slug);

            // On arrondie le Prix
            $prix =  $products->getPrice() * 100;
            $products->setPrice($prix);

            $entityManager->persist($products);

            $entityManager->flush();

            $this->addFlash('success', 'le produit a ete bien enregistrer dans la Base de donnee');
            return  $this->redirectToRoute('admin_products_index');
        }



       /* return $this->render('admin/products/add.html.twig',[
            'ProductsForm' => $form->createView()
        ]);*/
        return $this->renderForm('admin/products/add.html.twig', compact('ProductsForm'));
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Products $products,  Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger)
    {
        // on verifie sin utilisateur peut editer un produits
        //$this->denyAccessUnlessGranted('ROLE_PRODUCTS_ADMIN', $products);
        $this->denyAccessUnlessGranted('ROLE_USER', $products);
        // On divise le Prix
        $prix =  $products->getPrice() / 100;
        $products->setPrice($prix);

        $ProductsForm = $this->createForm(ProductFormType::class, $products);

        $ProductsForm->handleRequest($request);


        if ($ProductsForm->isSubmitted() &&  $ProductsForm->isValid()) {

            $slug = $slugger->slug($products->getName());
            $products->setSlug($slug);

            // On arrondie le Prix
            $prix =  $products->getPrice() * 100;
            $products->setPrice($prix);

            $entityManager->persist($products);

            $entityManager->flush();

            $this->addFlash('success', 'le produit a ete modifier avec success');
            return  $this->redirectToRoute('admin_products_index');
        }

        return $this->renderForm('admin/products/edit.html.twig', compact('ProductsForm'));
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