<?php

namespace App\EventSubscriber;


use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Environment;

class maintenanceSubscriber
{
    public function __construct(
        #[Autowire("%maintenance%")] private readonly bool $maintenance,
        private Environment $twig
    )


    {
    }

    #[AsEventListener]
    public function maintenance(RequestEvent $event): void
    {
        if($this->maintenance)
        {
            $maint= $this->twig->render('maintenance/maintenance.html.twig');
            $response = new Response($maint, 503);
            $event->setResponse($response);
        }
    }
}