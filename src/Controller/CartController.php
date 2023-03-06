<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;



#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{


    #[Route('/', name: 'cart_')]
    public  function index()
    {


        return $this->render('cart/index.html.twig');
    }

    #[Route('/add/{id}', name: 'add')]
    public function add( $id, SessionInterface $session)
    {

        dd($session);
    }

    #[Route('/add/{id}', name: 'add')]
    public function remove( $id, SessionInterface $session)
    {
        dd($session);
    }



}