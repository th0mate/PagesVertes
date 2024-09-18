<?php

namespace App\Controller;

use App\Service\FlashMessageHelper;
use App\Service\FlashMessageHelperInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UtilisateurController extends AbstractController
{

    #[Route('/', name: 'pages_vertes', methods: ['GET', 'POST'])]
    public function pagesRouges(Request $request): Response
    {
        //$this->addFlash('success', 'ceci est un test');
        return $this->render('utilisateurs/accueil.html.twig', ['page_actuelle' => 'Accueil']);
    }

    #[Route('/credits', name: 'credits', methods: 'GET')]
    public function afficherCredits(): Response
    {
        return $this->render('credits/credits.html.twig', ['page_actuelle' => 'Credits']);
    }

    #[Route('/utilisateurs', name: 'afficherUtilisateurs', methods: 'GET')]
    public function afficherProfils(): Response
    {
        return $this->render('utilisateurs/listeUtilisateurs.html.twig', ['page_actuelle' => 'Parcourir']);
    }

}
