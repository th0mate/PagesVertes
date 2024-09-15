<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Form\PublicationType;
use App\Service\FlashMessageHelper;
use App\Service\FlashMessageHelperInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\PublicationRepository;

class UtilisateurController extends AbstractController
{

    #[Route('/', name: 'pages_rouges')]
    public function pagesRouges(Request $request): Response
    {
        $this->addFlash('success', 'ceci est un test');
        return $this->render('utilisateurs/accueil.html.twig');
    }
}
