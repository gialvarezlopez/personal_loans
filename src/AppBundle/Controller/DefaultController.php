<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{

    public function indexAction(Request $request)
    {
        //exit();
        // replace this example code with whatever you need
        return $this->render('app/default/index.html.twig', array());
        /*
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..'),
        ]);
        */
    }

    public function langAction(Request $request)
    {
        $redirect = $request->headers->get('referer');
        //exit();
        return  $this->redirect(
            $request->headers->get('referer')
         );
        //return $this->redirectToRoute("dashboard_index");
    }


    

}
