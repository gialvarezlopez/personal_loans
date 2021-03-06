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

        $em = $this->getDoctrine()->getManager();
        $userId = $this->getUser()->getUsrId();

        $from = $request->get("from");
        $to = $request->get("to");

        if( isset($from) && $from != "" && isset($to) && $to != "")
        {
            //$fechaInicio = str_replace("/","-")
            $from = date("Y-m-d", strtotime($from));
            $to = date("Y-m-d", strtotime($to));
            $periodDate = Array("startDate"=>$from,"endDate"=>$to);
            //echo "ddd";
        }
        else
        {
            $periodDate =  $this->inicio_fin_semana();
            //echo "else";
        }
        //$week =  $this->inicio_fin_semana();
        //var_dump($res);
        //var_dump($request->getLocale());
        //echo $this->get('translator')->trans('dashboard_payments');

        //var_dump($request->getSession()->get("_locale"));

        
        //$loans = $dashboard->getLoans($userId, $count = false,  $category=false, $limit=false);
        return $this->render('app/dashboard/index.html.twig', array(
            'periodDate' => $periodDate,
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
            'locale' => $request->getLocale(),

        ));
        //return $this->render("AppBundle:user:login.html.twig");
    }

    public function inicio_fin_semana()
    {
        $srv = $this->get('srv_TimeZone');
        $timezone =  $srv->getNameTimeZone();

        date_default_timezone_set($timezone);

        $fecha = date("Y-m-d");

        $diaInicio="Monday";
        $diaFin="Sunday";
    
        $strFecha = strtotime($fecha);
    
        $fechaInicio = date('Y-m-d',strtotime('last '.$diaInicio,$strFecha));
        $fechaFin = date('Y-m-d',strtotime('next '.$diaFin,$strFecha));
    
        if(date("l",$strFecha)==$diaInicio){
            $fechaInicio= date("Y-m-d",$strFecha);
        }
        if(date("l",$strFecha)==$diaFin){
            $fechaFin= date("Y-m-d",$strFecha);
        }

        $formatDate = $this->getUser()->getUsrDateFormat();
        $fechaInicio = date($formatDate, strtotime($fechaInicio));
        $fechaFin = date($formatDate, strtotime($fechaFin));

        return Array("startDate"=>$fechaInicio,"endDate"=>$fechaFin);
    }
}
