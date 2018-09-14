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
               /*
                //$newRate = ( $filterPeriod == 0)? ($rate*2) : $rate;
                for($i=0; $i <= $filterPeriod; $i++)
                {
                        $newRate += $rate;
                        //echo "i - ".$i;
                }
               */
               $totalPeriods = $periods+1; //sum the init period

               for($i=0; $i < $periods; $i++)
               {
                    $newRate += $rate;
                    //echo "i - ".$i;
               }
               //ECHO $newRate;
               $data = array("days"=>$days, "totalPeriods"=>$totalPeriods, "quotas"=>ceil($totalPeriods), "rate"=>$newRate);
               return $data;
            }

        }else{
            //echo "Hay tiempo";
        }

    }

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
}
