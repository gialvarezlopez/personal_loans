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

class Loans
{
	
	protected $em;
    private $container;
    
    // We need to inject this variables later.
    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

    function getClientLoans($clientId)
    {
        if( !empty($clientId) )
        {
            //$em = $this->getDoctrine()->getManager();
            $loans = $this->em->getRepository('AppBundle:Loan')->findBy( array("cli"=>$clientId,"loaCompleted"=>0, "loaActive"=>1)  );
            return $loans;
        }
        
    }

    function getDetailLoanPayments($loanId)
    {
        if( !empty($loanId) )
        {
            //$em = $this->getDoctrine()->getManager();
            $payments = $this->em->getRepository('AppBundle:LoanPayment')->findBy( array("loa"=>$loanId )  );
            return $payments;
        }
        
    }

    function getCountLoanPaymentsDone($loanId)
    {
        if( !empty($loanId) )
        {
            //$em = $this->getDoctrine()->getManager();
            //$payments = $this->em->getRepository('AppBundle:LoanPayment')->findBy( array("loa"=>$loanId )  );
            //return $payments;
            $RAW_QUERY  = "SELECT COUNT(*) as total FROM loan_payment WHERE loa_id = $loanId AND lpa_paid_date !='' ";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return ($result[0]["total"]);
            
        }
        
    }

    //Get total return amount Init requested (no interest rate)
    function getTotalReturnAmountRequested($loanId)
    {
        if( !empty($loanId) )
        {
            $RAW_QUERY  = "SELECT SUM(lpa_current_amount) as total FROM loan_payment WHERE loa_id = $loanId";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return ($result[0]["total"]);   
        }
    }

    //Get return amount paid at the moment (no interest rate)
    function getTotalReturnAmountPaid($loanId)
    {
        if( !empty($loanId) )
        {
            $RAW_QUERY  = "SELECT SUM(lpa_current_amount) as total FROM loan_payment WHERE loa_id = $loanId AND lpa_paid_date !='' ";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return ($result[0]["total"]);   
        }
    }

    function getTotalAmountPaid($loanId)
    {
        if( !empty($loanId) )
        {
            $RAW_QUERY  = "SELECT SUM(lpa_total_amount_paid) as total FROM loan_payment WHERE loa_id = $loanId AND lpa_paid_date !='' ";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return ($result[0]["total"]);   
        }
    }

    //Get last payment done for the client
    function getLastPayment($loanId)
    {
        if( !empty($loanId) )
        {
            $RAW_QUERY  = "SELECT * FROM loan_payment WHERE loa_id = $loanId AND lpa_paid_date !='' ORDER BY lpa_paid_date DESC LIMIT 1 ";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return ($result);   
        }
    }

    //Get all detail about the loan
    function getAllDetailLoan($loanId)
    {
        if( !empty($loanId) )
        {
            $RAW_QUERY  = "SELECT * FROM loan_payment lp
                            LEFT JOIN loan_payment_type lpt ON lp.lpt_id=lpt.lpt_id WHERE lp.loa_id = $loanId ";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;   
        }
    }


    function checkDeadLineToPay($rate, $recurringDays, $maxPayDate, $zone)
    {
        date_default_timezone_set($zone);

        //echo $maxPayDate;
        //echo $rate;

        //echo floor(9.999); // 9
        $today = strtotime(date('Y-m-d')); 
        $expireDay = strtotime( $maxPayDate ); 
        $timeToEnd = ($expireDay - $today); 
        $days =  ($timeToEnd/(60*60*24) ) ;

        // result will be negative numbers
        if( $days < 0 ) 
        {
            $days = abs($days); //convert to positive numbers
            if(isset($recurringDays) && $recurringDays > 0 )
            {
                $periods = ($days/$recurringDays); 
                $filterPeriod = abs(floor($periods));
               
                $newRate = $rate; 

               //$totalPeriods = $periods+1; //sum the init period
               $totalPeriods = $periods; //sum the init period
               $pros=0;     
               for($i=0; $i < $periods; $i++)
               {
                   if( $pros != 0 || $days > 0 )
                   {
                    $newRate += $rate;
                   }
                    //echo "i - ".$i;
                    $pros++;
               }

                $check = $this->saber_dia($maxPayDate, $zone);
               if($check == "sabado" )
               {
                   $totalPeriods++;
                   $newRate += $rate;
               }
               //ECHO $newRate;
               $data = array("days"=>$days, "totalPeriods"=>$totalPeriods, "quotas"=>ceil($totalPeriods), "rate"=>$newRate);
               return $data;
            }

        }else{
            //echo "Hay tiempo";
        }

    }

    function saber_dia($checkDate, $zone) {
        //echo $zone;
        date_default_timezone_set($zone);
        $dias = array('domingo','lunes','martes','miercoles','jueves','viernes','sabado');
        $fecha = $dias[date('N', strtotime($checkDate))];
        return $fecha;
    }
        // ejecutamos la función pasándole la fecha que queremos
    //saber_dia('2015-03-13');

    function checkPaymentsPerLoan($loanId)
    {
        if( isset($loanId) && !empty($loanId) )
        {
            $RAW_QUERY  = "SELECT SUM(lpa_paid_capital) AS paidCapital, 
                            SUM(lpa_paid_rate_interest) AS paidRate, 
                            SUM(lpa_total_amount_paid) AS paidTotal,
                            SUM(lpa_current_amount) AS currentAmount
                            FROM loan_payment where loa_id = $loanId";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
        }
    }

    function checkChangeAmountPerLoan($loanId)
    {
        $total = 0;
        if( isset($loanId) && !empty($loanId) )
        {
            //$RAW_QUERY  = "SELECT lpa_next_amount 
            //                FROM loan_payment where loa_id = $loanId ORDER BY loa_id DESC LIMIT 1 ";

            $RAW_QUERY = "SELECT  COUNT(*) AS total from loan_payment where loa_id = $loanId AND lpa_next_amount > 0 order by lpa_id";              
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            $total =  $result[0]["total"];
        }
        return $total;
    }




    function getPendingAmount($loanId)
    {
        //select * from loan_payment where loa_id = 117 order by lpa_id desc
        $aResult = array();
        if( isset($loanId) && !empty($loanId) )
        {
            $RAW_QUERY  = "SELECT *  FROM loan_payment where loa_id = $loanId";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();

            $total_pending = 0.00;
            $pros = 0;
            $total_items = count($result);

            $amount = 0.00;
            $max = 0;
            foreach($result as $item)
            {
                $current_amount = $item["lpa_current_amount"]."-";
                $plus_amount = ($item["lpa_next_amount"] != "" )?$item["lpa_next_amount"]:0;
                $paid_capital =  $item["lpa_paid_capital"];

                //$next_rate =  $item["lpa_next_rate_interest"];
                

                if( $pros == 0 )
                {
                    $max = ($current_amount + $plus_amount) - $paid_capital;
                }
                else
                {
                    $max = ($max + $plus_amount) - $paid_capital;
                }

                $amount = $max;
                   
                $pros++;
               
            }

            //echo $amount."-";

            $aResult[] = array("pending"=>$amount);
           
        }

        return $aResult;
    }


    function getFilesPerLoan($loanId)
    {
        if( isset($loanId) && !empty($loanId) )
        {
            $RAW_QUERY  = "SELECT * FROM gallery where loa_id = $loanId";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
        }
    }

    function getPendingQuotasNoPaid($loanId, $initDate, $endDate)
    {
        if( $loanId >0 && $initDate !="" && $endDate != "" )
        {
            /*
            $RAW_QUERY = "SELECT lpa_max_payment_date, lpa_current_amount FROM loan_payment WHERE loa_id = $loanId
                            AND lpa_total_amount_paid IN NULL
                            AND lpa_max_payment_date >= '".$initDate."' AND lpa_max_payment_date <= '".$endDate."'
                            ";
            */
            $RAW_QUERY = "SELECT lpa_max_payment_date, lpa_current_amount FROM loan_payment WHERE loa_id = $loanId
            AND lpa_total_amount_paid IS NULL
            AND lpa_max_payment_date <= '".$endDate."'
            ";                

            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;                
        }
    }
}
