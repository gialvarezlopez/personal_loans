<?php
namespace Services;
//namespace AppBundle\Services;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of test
 *
 * @author Owner
 */

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\Container;

class PayerTransactions
{
	
	protected $em;
    private $container;
    
    // We need to inject this variables later.
    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

	function checkPayments($user_id)
    {
        if( isset($user_id) && !empty($user_id) )
        {
            $RAW_QUERY  = "SELECT SUM(pay_money_paid) AS total
                            FROM payer where usr_id = $user_id";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
        }
    }

    function countAcquiredMemberships($user_id)
    {
        if( isset($user_id) && !empty($user_id) )
        {
            $RAW_QUERY  = "SELECT count(*) as total
                            FROM payer where usr_id = $user_id";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
        }
    }

    public function getRestingDays( $user_id )
    {
        //$em = $this->getDoctrine()->getManager();
        $userId = $user_id; // $this->getUser()->getUsrId();
        //SELECT DATE_FORMAT(pay_created,'%Y-%m-%d')  as pay_created
		$RAW_QUERY = "SELECT pay_deadline, pay_startdate, pay_created FROM payer p WHERE p.usr_id =:userId AND pay_deadline >= NOW() ORDER BY pay_id ";
		$statement = $this->em->getConnection()->prepare($RAW_QUERY);
		$statement->bindValue("userId", $userId);
        $statement->execute();
        $result = $statement->fetchAll();
        $restingDays = 0;
        for($i =0; $i < count($result); $i++)
        {
            $startdate =  $result[$i]['pay_startdate'];
            $deadline =  $result[$i]['pay_deadline'];
            $res = $this->restingDays($startdate, $deadline);
            $restingDays = $restingDays + $res;
        }

        //return array("licences"=>count($result), "days"=> 1000000 ); // comentar esta linea despues
        return array("licences"=>count($result), "days"=> $restingDays ); // Descomentar esta liena despues del demo
    }

    private function restingDays($startdate, $deadline){
        if( !empty($startdate) && !empty($deadline ) )
        {
            $deadline = $deadline; //$result[0]['pay_deadline'];
            //$created = date('Y-m-d',strtotime($created));

            date_default_timezone_set("UTC");

            //$srv = $this->container->get('srv_TimeZone');
            //$timezone =  $srv->getNameTimeZone();
            //date_default_timezone_set($timezone);

            $current = date('Y-m-d'); 


            if( $startdate > $current )
            {
                $current = $startdate;
            }


            //$s = strtotime($deadline)-strtotime($created); 
            $s = strtotime($deadline)-strtotime($current);  
            $d = intval($s/86400);  
            $diferencia = $d;
            
            return ( $diferencia > 0 )?$diferencia:0;
        }
    }

    public function freePaymentAccount( $userId )
    {
        date_default_timezone_set("UTC");
        
        //$srv = $this->container->get('srv_TimeZone');
        //$timezone =  $srv->getNameTimeZone();
        //date_default_timezone_set($timezone);
        
        $payer = new \AppBundle\Entity\Payer();

        $oPricing = $this->em->getRepository('AppBundle:Pricing')->findOneBy( array("prActive"=> 1, "prKey"=>"free") );
                    
        if( $oPricing )
        {
            $oUser = $this->em->getRepository('AppBundle:User')->findOneBy( array( "usrId"=> $userId) );
            $payer->setUsr($oUser);

            $payer->setPr($oPricing);

            $payer->setPayMoneyPaid($oPricing->getPrPrice());

            $start = date('Y-m-d H:i:s');
            
            $payer->setPayCreated( new \DateTime($start) );

            //Check if the user has available days    
            $hasDays = $this->getRestingDays( $userId );
            if( $hasDays["days"] == 0 )
            {
                $payer->setPayStartdate( new \DateTime($start) );
            }
            else
            {
                //$start = $now;
                $oLastPayment = $this->lastPaymentByUser( $userId );
                if( $oLastPayment )
                {
                    if( $oLastPayment->getPayDeadline() )
                    {
                        $startDate = $oLastPayment->getPayDeadline()->format('Y-m-d');
                        $limitDate = date($startDate);
                        $newStartdate = date("Y-m-d",strtotime($limitDate."+ 1 days")); 
                        $start = $newStartdate;
                    }
                    
                }
                $payer->setPayStartdate( new \DateTime($start) );
            }

            $months = $oPricing->getPrMonths();// $months;
            $newDate = strtotime ( "+".$months." month" , strtotime ( $start ) ) ;
            $deadLine = date ( 'Y-m-d H:i:s' , $newDate );
            
            $payer->setPayDeadLine( new \DateTime($deadLine) );
            
            
            $payer->setPayActive(1);
            $payer->setPayIsPaid(1);

            $this->em->persist($payer);			
            $flush = $this->em->flush();
            if( $flush == null)
            {
                $resdays = $this->getRestingDays( $userId );
                $menberships = $this->countAcquiredMemberships($userId);

                return ( array("days"=>$resdays["days"], "menberships"=>$menberships, "res"=>1) );
                //return 1;
            }
        }
        //return $this->redirectToRoute('payments_info');
    }

    public function setManualPaymentAccount( $arrData )
    {
        date_default_timezone_set("UTC");
        
        //$srv = $this->container->get('srv_TimeZone');
        //$timezone =  $srv->getNameTimeZone();
        //date_default_timezone_set($timezone);
        //exit();
        $payer = new \AppBundle\Entity\Payer();

        //$oPricing = $this->em->getRepository('AppBundle:Pricing')->findOneBy( array("prActive"=> 1, "prKey"=>"free") );
                    
        //if( $oPricing )
        //{
            $oUser = $this->em->getRepository('AppBundle:User')->findOneBy( array( "usrId"=> $arrData["userId"] ) );
            $payer->setUsr($oUser);

            //$payer->setPr($oPricing);

            $payer->setPayMoneyPaid( $arrData["amountPaid"] );
            $payer->setPayGatewayIdTransaction( $arrData["transactionID"] );


            $start = date('Y-m-d H:i:s');
            
            $payer->setPayCreated( new \DateTime($start) );

            if( $arrData["startDate"] != "" )
            {
                $start = $arrData["startDate"];
            }

            //Check if the user has available days    
            $hasDays = $this->getRestingDays( $arrData["userId"] );
            if( $hasDays["days"] == 0 )
            {
                
                $payer->setPayStartdate( new \DateTime($start) );
            }
            else
            {
                //$start = $now;
                $oLastPayment = $this->lastPaymentByUser( $arrData["userId"] );
                if( $oLastPayment )
                {
                    if( $oLastPayment->getPayDeadline() )
                    {
                        $startDate = $oLastPayment->getPayDeadline()->format('Y-m-d');
                        $limitDate = date($startDate);
                        $newStartdate = date("Y-m-d",strtotime($limitDate."+ 1 days")); 
                        $start = $newStartdate;

                        //ECHO "---".$start."--------------";
                    }
                    
                }
                $payer->setPayStartdate( new \DateTime($start) );
            }
            
            $months = $arrData["months"];// $months;
            $newDate = strtotime ( "+".$months." month" , strtotime ( $start ) ) ;
            $deadLine = date ( 'Y-m-d H:i:s' , $newDate );
            
            $payer->setPayDeadLine( new \DateTime($deadLine) );
            //ECHO "------".$deadLine;
            //EXIT();
            $payer->setPayActive(1);
            $payer->setPayIsPaid(1);

            $this->em->persist($payer);			
            $flush = $this->em->flush();
            if( $flush == null)
            {
                $resdays = $this->getRestingDays( $arrData["userId"] );
                $menberships = $this->countAcquiredMemberships($arrData["userId"]);

                return ( array("days"=>$resdays["days"], "menberships"=>$menberships, "res"=>1) );
                //return 1;
            }
        //}
        //return $this->redirectToRoute('payments_info');
    }

    public function lastPaymentByUser( $userId )
    {
        $result = $this->em->getRepository('AppBundle:Payer')->findOneBy( array( "usr"=> $userId), array('payId' => 'DESC'), 1 );
        if( $result )
        {
            return $result;
        }

        return false;
    }

    /*
    function checkMonthFree($user_Id)
    {
        if( isset($user_Id) && !empty($user_Id) )
        {
            $RAW_QUERY  = "SELECT SUM(pay_money_paid) AS total
                            FROM payer where usr_id = $user_Id";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
        }
    }
    */
}