<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\EditMdpUtilisateurType;
use App\Form\EditUtilisateurType;
use App\Form\FindUserByCodeType;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Service\FlashMessageHelperInterface;
use App\Service\UtilisateurManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\SecurityBundle\Security;

class UtilisateurController extends AbstractController
{

    public function __construct(
        private UtilisateurManagerInterface $utilisateurManagerInterface,
        private EntityManagerInterface      $entityManager,
        private FlashMessageHelperInterface $flashMessageHelperInterface,
        private UtilisateurRepository       $utilisateurRepository

    )
    {
    }


    /**
     * Route pour afficher la page d'accueil
     * @param Request $request
     * @return Response
     */
    #[Route('/', name: 'pages_vertes', options: ["expose" => true], methods: ['GET', 'POST'])]
    public function pagesRouges(Request $request): Response
    {
        //$this->addFlash('success', 'ceci est un test');
        return $this->render('utilisateur/accueil.html.twig', ['page_actuelle' => 'Accueil']);
    }


    /**
     * Route pour afficher les crédits
     * @return Response
     */
    #[Route('/credits', name: 'credits', methods: 'GET')]
    public function afficherCredits(): Response
    {
        return $this->render('credits/credits.html.twig', ['page_actuelle' => 'Credits']);
    }


    /**
     * Route pour s'inscrire
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/inscription', name: 'inscription', methods: ['GET', 'POST'])]
    public function inscription(Request $request, EntityManagerInterface $entityManager): Response
    {

        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('pages_vertes');
        }

        $inscription = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $inscription, [
            'method' => 'POST',
            'action' => $this->generateURL('inscription')
        ]);
        $form->handleRequest($request);
        $this->flashMessageHelperInterface->addFormErrorsAsFlash($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $utilisateur = $form->getData();
            $this->utilisateurManagerInterface->processNewUtilisateur($utilisateur, $form->get('plainPassword')->getData());
            $entityManager->persist($utilisateur);
            $entityManager->flush();
            $this->addFlash('success', 'Profil inscrit avec succès !');
            return $this->redirectToRoute('pages_vertes');
        }

        return $this->render('utilisateur/inscription.html.twig', ['formInscription' => $form, 'page_actuelle' => 'Inscription']);
    }


    /**
     * Affiche la page listant tous les utilisateurs visibles
     * @return Response : page
     */
    #[Route('/utilisateurs', name: 'afficherUtilisateurs', methods: ['GET', 'POST'])]
    public function afficherProfils(Request $request, Security $security): Response
    {
        // TODO : trier les utilisateurs mais par quoi ? Les plus récents ?

        $form = $this->createForm(FindUserByCodeType::class, null, [
            'method' => 'POST',
            'action' => $this->generateURL('afficherUtilisateurs')
        ]);

        $form->handleRequest($request);
        $this->flashMessageHelperInterface->addFormErrorsAsFlash($form);

        if ($form->isSubmitted() && $form->isValid()) {
            $code = $form->get('code')->getData();
            $utilisateur = $this->utilisateurRepository->findOneBy(['code' => $code]);

            //Si le code n'est pas valide, on reste sur la même page et on change le message flash
            if($utilisateur === null)
            {
                $this->addFlash("error", "Cet utilisateur n'existe pas");
            }
            else
            {
                $this->addFlash('success', 'Profil affiché avec succès !');
                return $this->redirectToRoute('afficherProfil', ['code' => $code]);
            }
        }

        $utilisateurConnecte = $this->getUser();
        if($utilisateurConnecte !== null && $security->isGranted('ROLE_ADMIN'))
        {
            $utilisateursVisible = $this->utilisateurRepository->findAll();
        }
        else
        {
            $utilisateursVisible = $this->utilisateurRepository->findBy(['estVisible' => true]);
        }

        return $this->render('utilisateur/listeUtilisateurs.html.twig', ['page_actuelle' => 'Parcourir', "utilisateursVisible" => $utilisateursVisible
                                                                                , 'form' => $form]);
    }


    /**
     * Route pour se connecter
     * @param AuthenticationUtils $authenticationUtils
     * @return Response : page de connexion
     */
    #[Route('/connexion', name: 'connexion', methods: ['GET', 'POST'])]
    public function connexion(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('pages_vertes');
        }

        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('utilisateur/connexion.html.twig', ['page_actuelle' => 'Connexion', 'last_username' => $lastUsername]);
    }


    /**
     * Route pour vérifier si un code utilisateur est déjà utilisé dans la BD, appelée depuis du JS
     * @param Request $request
     * @return Response : 'true' si le code est libre, false sinon
     */
    #[Route('/utilisateur/verifierCode', name: 'verifierCode', options: ["expose" => true], methods: ['POST'])]
    public function verifierCode(Request $request): Response
    {
        $code = $request->get('code');
        $utilisateur = $this->utilisateurRepository->findOneBy(['code' => $code]);
        if ($utilisateur) {
            return new Response('false');
        }
        return new Response('true');
    }

    #[Route('/utilisateurs/{login}', name: 'editerProfil', methods: ['GET', 'POST'])]
    #[ISGranted(attribute: 'USER_EDIT', subject: 'utilisateur')]
    public function editerProfil(Utilisateur $utilisateur, Request $request) : Response
    {
        $form = $this->createForm(EditUtilisateurType::class, $utilisateur, [
            'method' => 'POST',
            'action' => $this->generateURL('editerProfil', ['login' => $utilisateur->getLogin()])
        ]);
        $form->handleRequest($request);
        $this->flashMessageHelperInterface->addFormErrorsAsFlash($form);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($utilisateur);
            $this->entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès !');
            return $this->redirectToRoute('afficherProfil', ['code' => $utilisateur->getCode()]);
        }

        return $this->render('utilisateur/editionProfil.html.twig', ['page_actuelle' => 'Profil', 'form' => $form]);
    }


    #[Route('/utilisateurs/profil/{code}', name: 'afficherProfil', methods: ['GET'])]
    public function afficherProfil(Utilisateur $utilisateur): Response
    {
        $pageActulle = 'Profil';

        if ($this->getUser() !== $utilisateur) {
            $pageActulle = 'Parcourir';
        }

        return $this->render('utilisateur/profil.html.twig', ['page_actuelle' => $pageActulle, 'utilisateur' => $utilisateur]);
    }

    #[Route('/utilisateurs/profil', name: 'redirigerProfilDepuisForm', methods: ['POST'])]
    public function redirigerProfilDepuisForm(Request $request): Response
    {
        $code = $request->get('code');
        var_dump($code);
        $utilisateur = $this->utilisateurRepository->findOneBy(['code' => $code]);
        if ($utilisateur === null) {
            $this->addFlash('danger', 'Erreur : Utilisateur non trouvé !');
            return $this->redirectToRoute('afficherUtilisateurs');
        }
        return $this->redirectToRoute('afficherProfil', ['code' => $utilisateur->getCode()]);
    }

    #[Route('/utilisateurs/{login}', name: 'supprimerUtilisateur', options: ["expose" => true], methods: ['DELETE'])]
    #[ISGranted(attribute: 'USER_DELETE', subject: 'utilisateur')]
    public function supprimerUtilisateur(Utilisateur $utilisateur, TokenStorageInterface $tokenStorage, SessionInterface $session) : Response
    {
        $this->entityManager->remove($utilisateur);
        $this->entityManager->flush();

        if($utilisateur === $this->getUser()) {
            // Déconnexion de l'utilisateur
            $tokenStorage->setToken(null);

            // Invalider la session
            $session->invalidate();
        }

        $this->addFlash("success", "Le compte a bien été supprimé");
        return new JsonResponse(null, Response::HTTP_OK);
    }

    #[Route('/utilisateur/profil/{code}', name: 'infosUtilisateur', options: ["expose" => true], methods: ['GET'])]
    public function infosUtilisateur(?string $code): Response
    {
        $utilisateur = $this->utilisateurRepository->findOneBy(["code" => $code]);
        if($utilisateur == null)
        {
            return new JsonResponse(null, Response::HTTP_NOT_FOUND);
        }
        return new JsonResponse($utilisateur, Response::HTTP_OK);
    }

    #[Route('/utilisateurs/edit/changepasswd/{login}', name: 'changerMotDePasse', methods: ['GET', 'POST'])]
    #[ISGranted(attribute: 'USER_EDIT', subject: 'utilisateur')]
    public function changerMotDePasse(Utilisateur $utilisateur, Request $request): Response
    {
        $form = $this->createForm(EditMdpUtilisateurType::class, $utilisateur, [
            'method' => 'POST',
            'action' => $this->generateURL('changerMotDePasse', ['login' => $utilisateur->getLogin()])
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('oldPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();
            if (password_verify($oldPassword, $utilisateur->getPassword())) {
                $this->utilisateurManagerInterface->chiffrerMotDePasse($utilisateur, $newPassword);
                $this->entityManager->flush();
                $this->addFlash('success', 'Mot de passe mis à jour avec succès !');
                return $this->redirectToRoute('afficherProfil', ['code' => $utilisateur->getCode(), 'page_actuelle' => 'Profil']);
            } else {
                $this->addFlash('warning', 'Ancien mot de passe incorrect.');
            }
        }

        return $this->render('utilisateur/changerMotDePasse.html.twig', [
            'form' => $form,
            'page_actuelle' => 'Profil'
        ]);
    }
}
