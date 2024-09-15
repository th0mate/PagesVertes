<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreditController extends AbstractController
{
    #[Route('/credits', name: 'credits', methods: 'POST')]
    public function afficherCredits(): Response
    {
        return $this->render('credits/credits.html.twig');
    }
}
