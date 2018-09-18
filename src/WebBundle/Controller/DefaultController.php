<?php

namespace WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Contactus;
use AppBundle\Entity\UserViews;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include GeoIp2 Classes
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;

class DefaultController extends Controller
{

    public function __construct() {
        $this->session = new Session();
    }

    public function indexAction(Request $request)
    {
        

        return $this->render('web/default/index.html.twig', array(
                
            )
        );
    }

    public function geoIP()
    {
        //https://ourcodeworld.com/articles/read/693/how-to-detect-the-city-country-and-locale-from-a-visitor-s-ip-using-the-free-geolite-database-in-symfony-3

        //IS CODE
        //http://kirste.userpage.fu-berlin.de/diverse/doc/ISO_3166.html

        // Declare the path to the GeoLite2-City.mmdb file (database)
        $GeoLiteDatabasePath = $this->get('kernel')->getRootDir() . '/../web/GeoLite2-City/GeoLite2-City.mmdb';

        // Create an instance of the Reader of GeoIp2 and provide as first argument
        // the path to the database file
        $reader = new Reader($GeoLiteDatabasePath);

        try{
            // if you are in the production environment you can retrieve the
            // user's IP with $request->getClientIp()
            // Note that in a development environment 127.0.0.1 will
            // throw the AddressNotFoundException

            //var_dump($reader);
            // In this example, use a fixed IP address in Minnesota

            $ip = $_SERVER['REMOTE_ADDR'];
            $record = $reader->city($ip);

        } catch (AddressNotFoundException $ex) {
            // Couldn't retrieve geo information from the given IP
            //return new Response("It wasn't possible to retrieve information about the providen IP");
            return false;
        }

        //return new JsonResponse($record);
        return $record;
    }
    private function getRealIP()
    {

        if ($_SERVER['SERVER_NAME'])
        {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'] ))
            {
                $sIpAddress = @$_SERVER["HTTP_X_FORWARDED_FOR"];
            }
            elseif (isset($_SERVER["HTTP_CLIENT_IP"] ))
            {
                $sIpAddress = @$_SERVER["HTTP_CLIENT_IP"];
            }
            else
            {
                $sIpAddress = @$_SERVER["REMOTE_ADDR"];
            }
        }
        return $sIpAddress;
    }

    public function landingAction( Request $request ){

        

        return $this->render('web/default/landing.html.twig' ,array( ) );
    }

    public function contactusAction( Request $request ){

        $em     = $this->getDoctrine()->getManager();

        $name = $request->get('name');
        $email = $request->get('email');
        $phone = $request->get('phone');
        $message = $request->get('message');


        if( $request ){

            $contactus = new Contactus();
            $contactus->setConName( $name );
            $contactus->setConEmail( $email );
            $contactus->setConPhone( $phone );
            $contactus->setConComment( $message );
            $contactus->setConCreate( new \DateTime("now") );

            $em->merge($contactus);
            //$em->flush();

            $flush = $em->flush();
            if($flush == null){
                $success = 1;
                $this->session->getFlashBag()->add("success",$success);
            }else{
                $error = 0;
                $this->session->getFlashBag()->add("error",$error);
            }
        }

        return $this->redirectToRoute("web_landing");

    }

    public function sendContactFormToDoctorAction( Request $request /*, \Swift_Mailer $mailer*/ ){
        $name = $request->get("name");
        $email = $request->get("email");
        $msg = $request->get("message");
        $profileId = $request->get("profileId");

        if ($request->isMethod('POST') && is_numeric($profileId) && is_numeric($profileId) > 0 )
        {
            $em = $this->getDoctrine()->getManager();

            $oDetail = $em->getRepository('AppBundle:MedicalDetail')->findOneBy( array("usr"=>$profileId ) );
            if( !$oDetail )
            {
                throw new NotFoundHttpException("Profile not found");
            }

            $fullNameProfile = $oDetail->getMdFirstName()." ".$oDetail->getMdMiddleName()." ".$oDetail->getMdFirstSurname()." ".$oDetail->getMdSecondSurname();

            //Get emails
            $RAW_QUERY  = "SELECT uhsn.usn_link FROM social_network sn
                            INNER JOIN user_has_social_network uhsn ON sn.sn_id = uhsn.sn_id
                            WHERE uhsn.usr_id = $profileId
                            AND sn.sn_key = 'email' AND uhsn.usn_active = 1";

            $statement  = $em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result    	= $statement->fetchAll();
            /*
            if ( count($result) > 0 )
            {
                $to = implode(",",$result[0]); //"emails split it per comma"
            }else{
                $to = $oDetail->getUsr()->getUsrEmail();
            }
            */
            /*
            // the message
            $subject = "Contact Form - $name";
            // Always set content-type when sending HTML email
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";


            $view = $this->renderView( 'web/default/contactEmail.html.twig',
                array(
                    'fullNameProflie'=>$fullNameProfile,
                    'name' => $name,
                    "email"=>$email,
                    "msg"=>$msg
                    )
            );
            $message = $view;
            // More headers
            $headers .= "From: <$email>" . "\r\n";
            //$headers .= 'Cc: myboss@example.com' . "\r\n";
            $headers .= "Reply-To: $email" . "\r\n";

            if( mail($to,$subject,$message,$headers) )
            {
                echo 1;
            }
            else
            {
                echo "Error";
            }
            */
            /*
                $message = \Swift_Message::newInstance()//(new \Swift_Message('Hello Email'))
                ->setFrom($email)
                ->setTo('gialvarezlopez@gmail.com')
                ->setBody(
                    $this->renderView(
                        // app/Resources/views/Emails/registration.html.twig
                        'web/default/contactEmail.html.twig',
                        array('name' => $name)
                    ),
                    'text/html'
                )

                ;
                $this->get('mailer')->send($message);
                //$mailer->send($message);

                // or, you can also fetch the mailer service this way
                // $this->get('mailer')->send($message);

                //return $this->render(...);
            */



            $view = $this->renderView( 'web/default/contactEmail.html.twig',
                array(
                    'fullNameProflie'=>$fullNameProfile,
                    'name' => $name,
                    "email"=>$email,
                    "msg"=>$msg
                    )
            );

            $mail = new PHPMailer();
            //$mail->isSMTP();
            //$mail->Host = 'mail.doctorsbillboard.com';
            //$mail->SMTPAuth = true;
            //$mail->Username = 'info@doctorsbillboard.com';
            //$mail->Password = '';
            //$mail->SMTPSecure = 'tls';
            //$mail->Port = 25;
            //$mail->setFrom('acedmy@leewayweb.com', 'Leeway Academy');
			$mail->setFrom($email, $name);

            if ( count($result) > 0 )
            {
                for( $i=0; $i < count($result); $i++)
                {
                    $mail->addAddress($result[$i]['usn_link']);
                }
            }else{
                $mail->addAddress($oDetail->getUsr()->getUsrEmail());
            }


            //Content
            $mail->isHTML(true);   // Set email format to HTML
            $mail->Subject = 'Contact Form';
            $mail->Body    =  $view;
            $mail->AltBody = '';
            if(!$mail->send()) {
                echo 'Message could not be sent. Mailer Error: '.$mail->ErrorInfo;
            } else {
                echo 1;
            }

        }
        else
        {
            throw new Exception('Error');
        }

        exit();
    }
}
