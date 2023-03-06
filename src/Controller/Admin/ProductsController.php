<?php

 namespace App\Controller\Admin;

use App\Entity\Images;
use App\Entity\Products;
use App\Form\ProductFormType;
use App\Repository\ProductsRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/products', name: 'admin_products_')]
 class ProductsController extends AbstractController
 {
    #[Route('/', name: 'index')]
    public function index(ProductsRepository $productsRepository) 
    {
        $products = $productsRepository->findAll();

        return $this->render('admin/products/index.html.twig', compact( 'products'));
    }

    /**
     * @throws \Exception
     */
    #[Route('/create', name: 'create_')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        PictureService $pictureService
    )
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        // on cree un nouveau Produits
        $products = new Products();

        $ProductsForm = $this->createForm(ProductFormType::class, $products);

        $ProductsForm->handleRequest($request);
        
        
        if ($ProductsForm->isSubmitted() &&  $ProductsForm->isValid()) {

            // on va recuperer les images

            $images = $ProductsForm ->get('images')->getData();

            foreach ( $images as $image)
            {
                // on definir le dossier de destination
                $folder = 'Products';

                // on appelle le fichier
                $fichier = $pictureService->add($image,$folder, 300, 300 );

                // on crer un nouveau image
                $img = new Images();
                $img->setName($fichier);
                $products->addImage($img);

            }


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

            // on va recuperer les images

            $images = $ProductsForm ->get('images')->getData();
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

        return $this->render('admin/products/edit.html.twig',[
            'ProductsForm' => $ProductsForm->createView(),
            'products' => $products
        ]);
    }

    #[Route('/delete{id}', name: 'delete')]
    public function delete(Products $products)
    {
        return $this->render('admin/products/index.html.twig');
    }

    #[Route('/delete/image{id}', name: 'delete_image',methods: ['DELETE'])]
    public function deleteImage(
        Images $images,
        EntityManagerInterface $entityManage,
        PictureService $pictureService,
        Request $request
    )
    {
        // on recupere le contenu de la request
        $data = json_decode($request->getContent(), true);

        if ($this->isCsrfTokenValid('delete' . $images->getId(), $data['_token'] )) {
             // le token csf est valid et on recupere le nom de image
            $nom = $images->getName();
            if ($pictureService->delete($nom, 'products', 300, 300)) {
                
                $entityManage->remove($images);
                $entityManage->flush();

                return new JsonResponse(['success' => 'image a ete bel et bien Supprimmer'], 200);
            }

             // la suppression a echoue
            return new JsonResponse(['error' => 'Erreur supression'], 400);
        }
       return new JsonResponse(['error' => 'Token invalid'], 400);
    }



 }