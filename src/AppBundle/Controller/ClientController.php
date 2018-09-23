<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use Symfony\Component\Validator\Constraints\DateTimeZone;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

// Include GeoIp2 Classes
//use GeoIp2\Database\Reader;
//use GeoIp2\Exception\AddressNotFoundException;

/**
 * Client controller.
 *
 */
class ClientController extends Controller
{
    private $session;
	
	public function __construct() {
		$this->session = new Session();
	}
    /**
     * Lists all client entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $status = $request->get("sta");
        $userId = $this->getUser()->getUsrId();
        switch($status)
        {
            case "1":
            case "0":
                $clients = $em->getRepository('AppBundle:Client')->findBy( array("cliActive"=>$status, "usr"=> $userId)  );
                //echo "case";
                break;
            default:
                $clients = $em->getRepository('AppBundle:Client')->findBy( array("usr"=> $userId) ); 
                //echo "defaul";
                break;    
        }

        return $this->render('app/client/index.html.twig', array(
            'clients' => $clients,
        ));
    }

    /**
     * Creates a new client entity.
     *
     */
    public function newAction(Request $request)
    {
        $oTimeZone = $this->get('srv_TimeZone');

        //$geo = $oTimeZone->geoIP();
        //echo $geo->location->timeZone;
        //echo "<hr>";
        //echo $oTimeZone->fecha_local("2018-08-05 09:45:12", $geo->location->timeZone);
        

        $client = new Client();
        $form = $this->createForm('AppBundle\Form\ClientType', $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            date_default_timezone_set("UTC");
            $em = $this->getDoctrine()->getManager();

            $userId = $this->getUser()->getUsrId();
            $oUser= $em->getRepository('AppBundle:User')->findOneBy( array( "usrId"=> $userId) );
            
            $client->setCliCreated( new \DateTime() );
            $client->setUsr($oUser);

            $em->persist($client);
            $em->flush();

            $msg = "Client created successfully";
            $this->session->getFlashBag()->add("success", $msg);
            return $this->redirectToRoute('client_edit', array('cliId' => $client->getCliid()));
        }

        return $this->render('app/client/new.html.twig', array(
            'client' => $client,
            'form' => $form->createView(),
        ));
    }

    /*
        function fecha_local( $date, $zone, $format = 'Y-m-d H:i:s' ) {
            $datetime = $date;
            $utc = new \DateTime($datetime, new \DateTimeZone('UTC'));
            $utc->setTimezone(new \DateTimeZone($zone));
            return $utc->format('Y-m-d H:i:s');
        }
    */

    /**
     * Finds and displays a client entity.
     *
     */
    public function showAction(Request $request, Client $client)
    {
        $id = $request->get("id");
        if( $id > 0 )
        {
            $em = $this->getDoctrine()->getManager();
            $userId = $this->getUser()->getUsrId();
            $oClient = $em->getRepository('AppBundle:Client')->findOneBy( array( "usr"=> $userId) );
            if( !$oClient )
            {
                $client = array();
                return $this->render('app/client/show.html.twig', array(
                    'client' => $client,
                    //'delete_form' => $deleteForm->createView(),
                ));
                
            }else{
                $deleteForm = $this->createDeleteForm($client);

                return $this->render('app/client/show.html.twig', array(
                    'client' => $client,
                    'delete_form' => $deleteForm->createView(),
                ));
            }

                
            
        }else{
            throw new NotFoundHttpException("Record not found");
        }
    }

    /**
     * Displays a form to edit an existing client entity.
     *
     */
    public function editAction(Request $request, Client $client)
    {
        date_default_timezone_set("UTC");
        $deleteForm = $this->createDeleteForm($client);
        $editForm = $this->createForm('AppBundle\Form\ClientType', $client);
        $editForm->handleRequest($request);

        $clientId = $client->getCliId();
        if( $clientId > 0)
        {
            $em = $this->getDoctrine()->getManager();
            $userId = $this->getUser()->getUsrId();
            $oClient = $em->getRepository('AppBundle:Client')->findOneBy( array( "cliId"=> $clientId) );
            if( $oClient )
            {
                if ( $userId != $oClient->getUsr()->getUsrId() )
                {
                    throw new AccessDeniedHttpException("Access Denied");
                } 
            }
        }

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('client_edit', array('cliId' => $client->getCliid()));
        }

        return $this->render('app/client/edit.html.twig', array(
            'client' => $client,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a client entity.
     *
     */
    public function deleteAction(Request $request, Client $client)
    {
        $form = $this->createDeleteForm($client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($client);
            $em->flush();
        }

        return $this->redirectToRoute('client_index');
    }

    /**
     * Creates a form to delete a client entity.
     *
     * @param Client $client The client entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Client $client)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('client_delete', array('cliId' => $client->getCliid())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
