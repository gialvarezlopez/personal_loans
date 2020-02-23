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

        if( isset($opt) && ( $opt > 0 && $opt < 4) )
        {
            $radioOpt = $opt;
        }else{
            $radioOpt = 1; //1 = Next patment, 2 = prev payment, 3 = both
        }
        //$week =  $this->inicio_fin_semana();
        //var_dump($res);


        /*
            //echo $day_number = date("d");
            $year=date("Y");
            $month=date("m");
            $day=date("d");

            # Obtenemos el numero de la semana
            $semana=date("W",mktime(0,0,0,$month,$day,$year));

            # Obtenemos el día de la semana de la fecha dada
            $diaSemana=date("w",mktime(0,0,0,$month,$day,$year));

            # el 0 equivale al domingo...
            if($diaSemana==0)
                $diaSemana=7;

            # A la fecha recibida, le restamos el dia de la semana y obtendremos el lunes
            $primerDia=date("d-m-Y",mktime(0,0,0,$month,$day-$diaSemana+1,$year));

            # A la fecha recibida, le sumamos el dia de la semana menos siete y obtendremos el domingo
            $ultimoDia=date("d-m-Y",mktime(0,0,0,$month,$day+(7-$diaSemana),$year));

            echo "<br>Semana: ".$semana." - año: ".$year;
            echo "<br>Primer día ".$primerDia;
            echo "<br>Ultimo día ".$ultimoDia;
        */

        /*
        $fecha_inicio = '2017-08-15'; 
        $fecha_fin = '2017-08-31';
        $fecha = '2017-09-22';

        if ($this->check_in_range($fecha_inicio, $fecha_fin, $fecha))
        {

            echo "$fecha está en el rango\n";

        } else {

            echo "$fecha NO está en el rango\n";

        }
        */


        
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
        $status = $request->get("status");

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

        if( isset($opt) && ( $opt > 0 && $opt < 4) )
        {
            $radioOpt = $opt;
        }else{
            $radioOpt = 1; //1 = Next patment, 2 = prev payment, 3 = both
        }
        //$week =  $this->inicio_fin_semana();
        //var_dump($res);

        $extraTitle = "";
        if( $status == 2 )
        {
            $extraTitle = " - ( Prestamos Congelados)";
        }

        
        //$loans = $dashboard->getLoans($userId, $count = false,  $category=false, $limit=false);
        $html = $this->renderView('app/reports/printLoans.html.twig', array(
            'periodDate' => $periodDate,
            "opt" => $radioOpt,
            "status"=>$status
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
        $canvas->page_text(45, 25, $infoPeriod.$extraTitle, $font, 12, array(0,0,0));
        $canvas->page_text($w-100,$h-28,"Página {PAGE_NUM} de {PAGE_COUNT}", $font,8);
        //$canvas->page_text($w-590,$h-28,"El pie de p&aacute;gina del lado izquiero, Guadalajara, Jalisco C.P. XXXXX Tel. XX (XX) XXXX XXXX", $font,6);

        // Output the generated PDF to Browser (inline view)
        $name = $infoPeriod.$extraTitle;
       /*     
        $info = explode(" ",$name);
        
        $data = array();

        for($i=0; $i < count($info); $i++)
        {
            $txt = trim($info[$i]);
            if( $txt != "" )
            {
                array_push($data,$txt);
            }
        }
        

        $name = implode("-",$data);
        */

        $letters = array(",");
        $name = str_replace($letters, "", $name);
        $dompdf->stream($name.".pdf", [
            "Attachment" => false
        ]);

        exit();
    }

    public function inicio_fin_semana()
    {
       
       //exit("sali");

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

        //YYYY/MM/DD
        $formatDate = $this->getUser()->getUsrDateFormat();
        $fechaInicio = date($formatDate, strtotime($fechaInicio));
        $fechaFin = date($formatDate, strtotime($fechaFin));

        return Array("startDate"=>$fechaInicio,"endDate"=>$fechaFin);
    }
}
