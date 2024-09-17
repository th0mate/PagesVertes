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

    #[Route('/', name: 'pages_vertes', methods: ['GET', 'POST'])]
    public function pagesRouges(Request $request): Response
    {
        $this->addFlash('success', 'ceci est un test');
        return $this->render('utilisateurs/accueil.html.twig', ['page_actuelle' => 'Accueil']);
    }

    #[Route('/credits', name: 'credits', methods: 'GET')]
    public function afficherCredits(): Response
    {
        return $this->render('credits/credits.html.twig', ['page_actuelle' => 'Credits']);
    }

    #[Route('/inscription', name: 'inscription', methods: ['GET', 'POST'])]
    public function inscription(Request $request, EntityManagerInterface $entityManager): Response
    {

        if($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('feed');
        }

        $form = $this->createForm(UtilisateurType::class, new Utilisateur(), ['method' => 'POST', 'action' => $this->generateUrl('inscription')]);
        $form->handleRequest($request);
        $this->flashMessageHelperInterface->addFormErrorsAsFlash($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur = $form->getData();
            $this->utilisateurManagerInterface->processNewUtilisateur($utilisateur, $form->get('plainPassword')->getData(), $form->get('fichierPhotoProfil')->getData());
            $entityManager->persist($utilisateur);
            $entityManager->flush();
            $this->addFlash('success', 'Inscription réussie : bienvenue sur The Feed !');
            return $this->redirectToRoute('feed');
        }

        return $this->render('utilisateur/inscription.html.twig', ['formInscription' => $form]);
    }

}
