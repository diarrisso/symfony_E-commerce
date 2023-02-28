<?php

namespace App\Controller;

use App\Form\ResetPasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use App\Repository\UsersRepository;
use App\Service\SendMailerService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @throws ORMException
     */
    #[Route(path: '/reset_pass', name: 'forgotten_pass')]

    public function forgottenPassword(
        Request $request,
        UsersRepository $usersRepository,
        TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $em,
        SendMailerService $mailer
         ): Response
    {
        $resetpasswordForm = $this->createForm(ResetPasswordRequestFormType::class);

        $resetpasswordForm->handleRequest($request);

        if ($resetpasswordForm->isSubmitted() && $resetpasswordForm->isValid())
        {
            // get user by email
            $user = $usersRepository->findOneByEmail($resetpasswordForm->get('email')->getData());

            if ($user)
            {
                // on update user token
                $token = $tokenGenerator->generateToken();
                $user->setReseToken($token);
                $em->persist($user);
                $em->flush();
                // link for resetPass with neu Password
                $url =  $this->generateUrl('resetPass', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                // on crer email
                $context = compact('url', 'user');

                $mailer->send(
                    'replay@gmail.de',
                    $user->getEmail(),
                    'renitialisation pass pasword',
                    'resetPassword',
                    $context
                );

                $this->addFlash('success', 'email a ete envoyer avec succes');
                return $this->redirectToRoute('app_login');
            }

            $this->addFlash('danger', 'un probleme est survenu');
            return $this->redirectToRoute('app_login');
        }
        return $this->render('security/resetPasswordRequest.html.twig', [
            'resetPassword' => $resetpasswordForm->createView()
        ]);

    }

     #[Route(path: '/resetPass/{token}', name: 'resetPass')]
      public  function resetPass(
          $token,
         Request $request,
         UsersRepository $usersRepository,
         EntityManagerInterface $entityManager,
         UserPasswordHasherInterface $userPasswordHasher
     )
    {
        $user = $usersRepository->findOneByReseToken($token);
        
        if ($user) {
            
            $form = $this->createForm(ResetPasswordFormType::class);
            
             $form->handleRequest($request);
             
             if ($form->isSubmitted() && $form->isValid()) 
             {
                 $user->setReseToken('');
                 $user->setPassword(
                     $userPasswordHasher->hashPassword(
                         $user,
                         $form->get('password')->getData()
                     )
                 );

                 $entityManager->persist($user);
                 $entityManager->flush();
                 $this->addFlash('success', 'password a ete modifier');
                 return  $this->redirectToRoute('app_login');
             }


             return  $this->render('security/resetPassword.html.twig', [
                 'passwordForm' => $form->createView()
             ]);
        }

        $this->addFlash('danger', 'token invalid');
        return  $this->redirectToRoute('app_login');

    }
}
