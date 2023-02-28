<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\UsersAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, UsersAuthenticator $authenticator, EntityManagerInterface $entityManager, SendMailerService $mail, JWTService $jwt): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            //on creer le Header 
            $header = [
                'typ' => 'JWT',
                'alg' =>  'HS256'
            ];

            // on crée le payload

            $payoad = [
                'user_id' => $user->getId()
            ];
            // on crée le tokem 

            $token = $jwt->generate($header,$payoad, $this->getParameter('app.jwtSecrete') );
            dd($token);

            // do anything else you need here, like send an email
             
            $mail->send(
                "no-replay@gmail.com", 
                $user->getEmail(), 
                "Activation compte email",
                'register',
                compact('user', 'token')
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify', name: 'verifyUserTokem ')]
    public function verifyUserToken($token, UsersRepository $userrepository, JWTService $jwt, EntityManagerInterface $em)
    {
       if( $jwt->isValid($token) && $jwt->isExpire($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))) 
       {
            $payoad = $jwt->getPayload($token);

            // on recupere user 
            $user = $userrepository->find($payoad['user_id']);

            // on verifier si l'utilisateur existe et n'a pas encore activé son compte
            if($user && !$user->getIsVerified())
            {
                $user->setIsverified(true);
                $em->flush($user);
                $this->addFlash('success', 'utilisateur activé');
                return $this->redirectToRoute('app_profile');
            }
        }

       // ici un Probleme se pose dans le token 
       $this->addFlash('danger', 'le token est invalide ou a expiré');

       return $this->redirectToRoute('app_login');
    

    }

    #[Route('/renvoiverif', name: 'resend_')]
    public function resendVerif(JWTService $jwt, SendMailerService $email, UsersRepository $userpository)
    {
        $user = $this->getUser();
       if (!$user) {
           $this->addFlash('danger', 'vous devrait etre connecter pour acceder a cette plateForm');
           return $this->redirectToRoute('app_login');
       }
       if($user->getIsVerified())
       {
           $this->addFlash('warming', 'cet utilisateur est deja activé');
           return  $this->redirectToRoute('app_profile');
       }

        //on creer le Header
        $header = [
            'typ' => 'JWT',
            'alg' => 'HS256'
        ];

        // on crée le payload

        $payoad = [
            'user_id' => $user->getId()
        ];
        // on crée le tokem

        $token = $jwt->generate($header, $payoad, $this->getParameter('app.jwtSecrete'));
        dd($token);

        // do anything else you need here, like send an email

        $email->send(
            'no-replay@gmail.com',
            $user->getEmail(),
            'Activation compte email',
            'register',
            compact('user', 'token')
        );
        $this->addFlash('success', 'email de verification envoyer a votre email');
        return $this->redirectToRoute('app_profile');
    }
}   

 