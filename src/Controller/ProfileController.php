<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function index(): Response
    {
        if($this->isGranted('IS_AUTHENTICATED_FULLY')){

            return $this->render('pages/profile/index.html.twig');
        }
    }

    #[Route('/profile/edit', name: 'profile.edit')]
    public function edit(UserRepository $repo ,Request $req, SluggerInterface $slugger,
    #[Autowire('%kernel.project_dir%/public/uploads/avatars')] string $avatarDirectory
    ): Response
    {
        $userFromSession = $this->getUser()->getUserIdentifier();
        $userFromDB = $repo->findOneBy(['email' => $userFromSession]);

        $editForm = $this->createForm(EditFormType::class, $userFromDB);
        $editForm->handleRequest($req);
        
        if($editForm->isSubmitted() && $editForm->isValid()){
            
            $avatarFile = $editForm->get('avatar')->getData();
            if($avatarFile){
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();
                
                try {
                    $avatarFile->move($avatarDirectory, $newFilename);
                } catch (FileException $e) {
                    
                }
                
                $userFromDB->setAvatar($newFilename);
            }

            $repo->saving($userFromDB, true);
            
            return $this->redirectToRoute('profile');
        }
        
        return $this->render('pages/profile/edit.html.twig', ['form' => $editForm]);
    }
}
