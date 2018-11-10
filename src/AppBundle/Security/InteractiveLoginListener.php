<?php

namespace AppBundle\Security;


use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\DependencyInjection\Container;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;

class InteractiveLoginListener {

    protected $em;
    protected $request;
    private $container;

    public function __construct(EntityManager $em, RequestStack $request, Container $container) {

        $this->em = $em;
        $this->request = $request;
        $this->container = $container;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {


        $srv = $this->container->get('srv_TimeZone');
        $timezone =  $srv->getNameTimeZone();
        date_default_timezone_set($timezone);

        $user = $event->getAuthenticationToken()->getUser();

        
        if ($user instanceof User) {
            if($this->request->getCurrentRequest()->hasSession()) {
                $user->setUsrLastLogin(new \DateTime('now'));
                $this->em->persist($user);
                $this->em->flush();
            }
        }
        
    }
}