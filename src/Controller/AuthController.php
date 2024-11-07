<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionFormType;
use App\Form\LoginFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AuthController extends AbstractController
{
    #[Route('/auth', name: 'app_auth')]
    public function index(Request $req, UserRepository $repository, UserPasswordHasherInterface $passwordHash): Response
    {
        $user = new User();
        $form = $this->createForm(InscriptionFormType::class, $user);
        
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){

            $existantEmail = $repository->findOneBy(['email' => $user->getEmail()]);

            if($existantEmail){
                return $this->redirectToRoute('app_auth', ['form' => $form, 'error', 'USER_EXIST']);
            }

            $password = $form->get('password')->getData();
            $hashedPassword = $passwordHash->hashPassword($user, $password);
            
            $user->setPassword($hashedPassword);

            $repository->saving($user, true);
            $message = 'Votre compte a été créé avec succès ✅';

            return $this->render('pages/home/index.html.twig', ['success' => $message]);

        }

        return $this->render('pages/auth/index.html.twig', ['form' => $form]);

    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        
        if($this->isGranted('IS_AUTHENTICATED_FULLY')){
            return $this->redirectToRoute('profile');
        }
        
        $user = new User();
        $logInForm = $this->createForm(LoginFormType::class, $user);
        
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastEmail = $authenticationUtils->getLastUsername();
        
        return $this->render('pages/auth/login.html.twig', ['form' => $logInForm,'last_email' => $lastEmail,'error' => $error]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout() {}

}


