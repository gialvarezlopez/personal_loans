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

class ReportsController extends Controller
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
        $opt = $request->get("opt");

        if( isset($from) && $from != "" && isset($to) && $to != "")
        {
            //$fechaInicio = str_replace("/","-")
            $periodDate = Array("startDate"=>$from,"endDate"=>$to);
            //echo "ddd";
        }
        else
        {
            $periodDate =  $this->inicio_fin_semana();

            //echo "else";
        }

        if( isset($opt) && ( $opt > 0 && $opt < 4) )
        {
            $radioOpt = $opt;
        }else{
            $radioOpt = 1; //1 = Next patment, 2 = prev payment, 3 = both
        }
        //$week =  $this->inicio_fin_semana();
        //var_dump($res);

        
        //$loans = $dashboard->getLoans($userId, $count = false,  $category=false, $limit=false);
        return $this->render('app/reports/index.html.twig', array(
            'periodDate' => $periodDate,
            "opt" => $radioOpt 
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
        return Array("startDate"=>$fechaInicio,"endDate"=>$fechaFin);
    }
}
