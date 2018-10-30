<?php

namespace AppBundle\EventListener;
 
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
 
class LocaleListener implements EventSubscriberInterface
{
    /*
        private $defaultLocale;
    
        public function __construct($defaultLocale = 'en')
        {
            $this->defaultLocale = $defaultLocale;
        }
    
        public function onKernelRequest(GetResponseEvent $event)
        {
            $request = $event->getRequest();
            if (!$request->hasPreviousSession()) {
                return;
            }
    
            // Comprueba si le llega el parametro _locale de la ruta por la URL
            if ($locale = $request->attributes->get('_locale')) {
                $request->getSession()->set('_locale', $locale);
            } else {
                // Si no le llega utiliza el defaultLocale en este caso inglés
                $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
            }
        }
    
        public static function getSubscribedEvents()
        {
            return array( 
                KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
            );
        }
    */

    private $defaultLocale;

    public function __construct($defaultLocale = 'en')
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasPreviousSession()) {
            return;
        }

        if($locale = $request->attributes->get("_locale"))
        {
            $request->getSession()->set("_locale",$locale);
        }
        else
        {
            $request->setLocale($request->getSession()->get("_locale",  $this->defaultLocale));
        }
    } 

    public static function getSubscribedEvents()
    {
        return array( 
            KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
        );
    }


}
