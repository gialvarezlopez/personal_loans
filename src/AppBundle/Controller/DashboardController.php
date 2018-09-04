<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

#use AppBundle\Entity\User;
#use AppBundle\Entity\UserResetPassword;
#use AppBundle\Form\UserType;

class DashboardController extends Controller
{

    private $session;

	public function __construct() {
		$this->session = new Session();
	}

    public function indexAction( Request $request)
    {

        return $this->render('app/dashboard/index.html.twig', array(
            //'error' => $error,
        ));
        //return $this->render("AppBundle:user:login.html.twig");
    }
}
