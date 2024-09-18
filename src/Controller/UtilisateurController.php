<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Service\FlashMessageHelper;
use App\Service\FlashMessageHelperInterface;
use App\Service\UtilisateurManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UtilisateurController extends AbstractController
{

    public function __construct(
        private UtilisateurManagerInterface $utilisateurManagerInterface,
        private EntityManagerInterface $entityManager,
        private FlashMessageHelperInterface $flashMessageHelperInterface,
        private UtilisateurRepository $utilisateurRepository

    )
    {}

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

    #[Route('/inscription', name: 'inscription', methods: ['GET', 'POST'])]
    public function inscription(Request $request, EntityManagerInterface $entityManager): Response
    {

        if($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('pages_vertes');
        }

        $inscription = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $inscription, [
            'method' => 'POST',
            'action' => $this->generateURL('inscription')
        ]);        $form->handleRequest($request);
        $this->flashMessageHelperInterface->addFormErrorsAsFlash($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur = $form->getData();
            $this->utilisateurManagerInterface->processNewUtilisateur($utilisateur, $form->get('plainPassword')->getData());
            $entityManager->persist($utilisateur);
            $entityManager->flush();
            $this->addFlash('success', 'Inscription réussie : bienvenue sur Pages Vertes !');
            return $this->redirectToRoute('pages_vertes');
        }

        return $this->render('utilisateur/inscription.html.twig', ['formInscription' => $form, 'page_actuelle' => 'Inscription']);
    }

    #[Route('/utilisateurs', name: 'afficherUtilisateurs', methods: 'GET')]
    public function afficherProfils(): Response
    {
        return $this->render('utilisateurs/listeUtilisateurs.html.twig', ['page_actuelle' => 'Parcourir']);
    }

}
