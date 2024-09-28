<?php

namespace App\EventSubscriber;

use App\Entity\Utilisateur;
use App\Service\DateService;
use App\Service\DateServiceInterface;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use PHPUnit\Exception;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;
use Symfony\Component\Security\Http\Event\LogoutEvent;

use Doctrine\ORM\Event\PreUpdateEventArgs;

class AuthenticationSubscriber
{

    public function __construct(private RequestStack $requestStack, private EntityManagerInterface $entityManager, private DateServiceInterface $dateService)
    {
    }

    /**
     * @throws \Exception
     */
    #[AsEventListener]
    public function connexionReussie(LoginSuccessEvent $event): void
    {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add('success', 'Connexion Réussie !');

        $user = $event->getAuthenticatedToken()->getUser();
        if($user instanceof Utilisateur)
        {
            $date = $this->dateService->getActualTime();
            $user->setDateDerniereConnexion($date);

            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
    }

    #[AsEventListener]
    public function connexionEchouee(LoginFailureEvent $event): void
    {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add('warning', 'Login ou mot de passe invalide.');
    }

    #[AsEventListener]
    public function deconnexionReussie(LogoutEvent $event):void
    {
        $flashBag = $this->requestStack->getSession()->getFlashBag();
        $flashBag->add('info', 'Vous avez été déconnecté.');
    }

    /**
     * @throws \Exception
     */
    #[AsEventListener]
    public function preUpdate(PreUpdateEventArgs $event):void
    {
        $user = $event->getObject();

        if (!$user instanceof UserInterface) {
            return;
        }
        else if($event->hasChangedField('dateDerniereConnexion') || $event->hasChangedField('dateDerniereEdition'))
        {
            return;
        }
        else
        {
            $date = $this->dateService->getActualTime();
            $user->setDateDerniereEdition($date);
        }

    }
}