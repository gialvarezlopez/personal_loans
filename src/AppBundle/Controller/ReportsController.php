<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

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

    public function printLoansAction( Request $request )
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
        $html = $this->renderView('app/reports/printLoans.html.twig', array(
            'periodDate' => $periodDate,
            "opt" => $radioOpt 
        ));

        //var_dump($periodDate["startDate"]);
        //echo $periodDate["startDate"];
        //$intl = new \IntlDateFormatter($request->getLocale(), \IntlDateFormatter::LONG, \IntlDateFormatter::NONE, null, null, 'd-LLL-y');
        $intl = new \IntlDateFormatter($request->getLocale(), \IntlDateFormatter::FULL, \IntlDateFormatter::NONE, null, null);

        $startDate = $intl->format( new \DateTime($periodDate["startDate"]) );
        $endDate = $intl->format( new \DateTime($periodDate['endDate']) );



        $txtPeriod = $this->get('translator')->trans('reports_period');
        $txtfrom = $this->get('translator')->trans('reports_btn_filter_info_from');
        $txtTo = $this->get('translator')->trans('reports_btn_filter_info_to');

        $infoPeriod = /*$txtPeriod." ".*$txtfrom. " ".*/$startDate." ".$txtTo." ".$endDate;
        
        //exit();


        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'sans-serif');
        
        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        
        /*
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('default/mypdf.html.twig', [
            'title' => "Welcome to our PDF Test"
        ]);
        */
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();


        #Esto es lo que imprime en el PDF el numero de paginas
        $canvas = $dompdf->getCanvas();
        $footer = $canvas->open_object();
        $w = $canvas->get_width();
        $h = $canvas->get_height();

        $font = $dompdf->getFontMetrics()->get_font("helvetica", "bold");    
        //$canvas->page_text(72, 18, $infoPeriod."Header: {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0,0,0));
        $canvas->page_text(45, 25, $infoPeriod, $font, 12, array(0,0,0));
        $canvas->page_text($w-100,$h-28,"Página {PAGE_NUM} de {PAGE_COUNT}", $font,8);
        //$canvas->page_text($w-590,$h-28,"El pie de p&aacute;gina del lado izquiero, Guadalajara, Jalisco C.P. XXXXX Tel. XX (XX) XXXX XXXX", $font,6);

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);

        exit();
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
