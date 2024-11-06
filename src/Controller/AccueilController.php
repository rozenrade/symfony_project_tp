<?php 

namespace App\Controller;


use App\Entity\User;
use App\Form\InscriptionFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AccueilController extends AbstractController{

    #[Route('/', name: 'accueil')]
    public function index(UserRepository $repo, Request $req): Response {


        return $this->render('pages/home/index.html.twig');
    }

}

