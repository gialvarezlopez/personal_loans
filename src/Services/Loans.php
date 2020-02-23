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
            $payments = $this->em->getRepository('AppBundle:LoanPayment')->findBy( array("loaId"=>$loanId )  );
            return $payments;
        }
        
    }

    function getLoanById($loanId)
    {
        if( !empty($loanId) )
        {
            //$em = $this->getDoctrine()->getManager();
            $payments = $this->em->getRepository('AppBundle:Loan')->findOneBy( array("loaId"=>$loanId )  );
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

    

    /*    
    function getTotalAmountPaidAdditional($loanId)
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
    */

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
                            LEFT JOIN loan_payment_type lpt ON lp.lpt_id=lpt.lpt_id WHERE lp.loa_id = $loanId AND lp.laa_id IS NULL order by lp.lpa_id ASC";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;   
        }
    }


    function checkDeadLineToPay($rate, $rateByDefault, $recurringDays, $maxPayDate, $zone)
    {
        date_default_timezone_set($zone);

        //echo $maxPayDate;
        $newRate = $rate;

        //echo floor(9.999); // 9
        $today = strtotime(date('Y-m-d')); 
        $expireDay = strtotime( $maxPayDate ); 
        $timeToEnd = ($expireDay - $today); 
        $days =  ($timeToEnd/(60*60*24) ) ;
        
        //echo date("Y-m-d");

        // result will be negative numbers
        if( $days < 0 ) 
        {
            $days = abs($days); //convert to positive numbers
            if(isset($recurringDays) && $recurringDays > 0 )
            {
                $periods = ($days/$recurringDays); 
                $filterPeriod = abs(floor($periods));
               
                $newRate = $rate; 

                //$newRate = 0; 
                //echo date('l');
                //echo $check = $this->saber_dia(date('Y-m-d')/*$maxPayDate*/, $zone);
                /*
                echo $currentDate = date('l');
                //Saturday
                //$check = "";
                if($currentDate != "Saturday" )
                {
                    $totalPeriods = $periods + 1; //sum the init period
                } else{
                    $totalPeriods = $periods; //sum the init period
                    
                }
                */

                $totalPeriods = $periods + 1; //sum the init period
                //echo $totalPeriods;
               //$totalPeriods = $periods; //sum the init period
               //$totalPeriods = $periods+1; //sum the init period
               $pros=0;     
               for($i=0; $i < ceil($totalPeriods); $i++)
               {
                    if( $pros != 0 /*|| $days > 0*/ )
                    {
                        $newRate += $rateByDefault; ////quiiii
                        //echo  $newRate."---";
                    }
                    //echo "i - ".$i;
                    $pros++;
               }

                $check = $this->saber_dia($maxPayDate, $zone);
               if($check == "sabado" )
               {
                   //$totalPeriods++;
                   //$newRate += $rate;
               }
               //ECHO $newRate;
               $data = array("days"=>$days, "totalPeriods"=>$totalPeriods, "quotas"=>ceil($totalPeriods), "rate"=>$newRate, "rateWithoutCurrentPeriod"=>($newRate - $rate)  );
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
        $fecha = @$dias[date('N', strtotime($checkDate))];
        return $fecha;
        
        /*
        $fechats = strtotime($checkDate); //pasamos a timestamp

        //el parametro w en la funcion date indica que queremos el dia de la semana
        //lo devuelve en numero 0 domingo, 1 lunes,....
        switch (date('w', $fechats)){
            case 0: return "domingo"; break;
            case 1: return "lunes"; break;
            case 2: return "martes"; break;
            case 3: return "miercoles"; break;
            case 4: return "jueves"; break;
            case 5: return "viernes"; break;
            case 6: return "sabado"; break;
        }
        */
    }
        // ejecutamos la función pasándole la fecha que queremos
    //saber_dia('2015-03-13');

    function checkPaymentsPerLoan($loanId, $addtionalAmounts=false, $excepPaymentId = false)
    {
        if( isset($loanId) && !empty($loanId) )
        {
            
                if( $excepPaymentId == true )
                {
                    $filter  = " AND lpa_id <> $excepPaymentId ";
                }
                else
                {
                    $filter  = " ";
                }
            

            $RAW_QUERY  = "SELECT SUM(lpa_paid_capital) AS paidCapital, 
                            SUM(lpa_paid_rate_interest) AS paidRate, 
                            SUM(lpa_total_amount_paid) AS paidTotal,
                            SUM(lpa_current_amount) AS currentAmount
                            FROM loan_payment where loa_id = $loanId ".$filter;
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
        }
    }

    function checkPaymentsPerLoanAdditional($loanId)
    {
        if( isset($loanId) && !empty($loanId) )
        {
            $RAW_QUERY  = "SELECT SUM(lpa_paid_capital) AS paidCapital, 
                            SUM(lpa_paid_rate_interest) AS paidRate, 
                            SUM(lpa_total_amount_paid) AS paidTotal,
                            SUM(lpa_current_amount) AS currentAmount
                            FROM loan_payment where laa_id = $loanId";
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

    //Pending is not complete-----------------------------------------------
    public function checkPaymentDoneIncompleted( $loanId )
    {
        $aResult = array("info"=>"","cuotes"=>"", "totalAmount"=>"");
        if( !empty($loanId) )
        {
            //$em = $this->getDoctrine()->getManager();
            $arrPending = array();
            $RAW_QUERY  = "SELECT * FROM loan_payment WHERE loa_id = $loanId AND lpa_paid_date !='' ORDER BY lpa_id ASC ";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();

            if( count($result) > 0 )
            {
                $number = 1;
                $totalPendingAmount = 0;
                $aCuotes = array();
                foreach( $result as $item )
                {
                    //echo $item["lpa_id"];
                    if(  $item["lpa_total_amount_paid"] < $item["lpa_current_amount"] )
                    {
                        $diffence =  $item["lpa_current_amount"] - $item["lpa_total_amount_paid"];
                        $arrPending[] = "#".$number." = $".number_format($diffence, 2, '.', '');
                        $aCuotes[] = $number;
                        $totalPendingAmount = $totalPendingAmount + $diffence;
                    }
                    $number++;
                }

                $info = implode(",", $arrPending);
                $totalAmount = number_format($totalPendingAmount, 2, '.', '');
                $cuotes = implode(",", $aCuotes);

                $aResult = array("info"=>$info, "cuotes"=>$cuotes, "totalAmount"=>$totalAmount);

            }
        }

        return $aResult;
    }


    function getPendingAmount($loanId, $addtionalAmounts=false)
    {
        //select * from loan_payment where loa_id = 117 order by lpa_id desc
        $aResult = array();
        if( isset($loanId) && !empty($loanId) )
        {
            
            //$fieldId = ($addtionalAmounts == true )?"laa_id":"loa_id";
            if( $addtionalAmounts == true )
            {
                $RAW_QUERY  = "SELECT *  FROM loan_payment where laa_id = $loanId";
            }
            else
            {
                $RAW_QUERY  = "SELECT *  FROM loan_payment where loa_id = $loanId AND laa_id IS NULL ";
            }
            //echo $RAW_QUERY;
            //$RAW_QUERY  = "SELECT *  FROM loan_payment where $fieldId = $loanId";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();

            $total_pending = 0.00;
            $pros = 0;
            $total_items = count($result);

            $amount = 0.00;
            $max = 0;

            //$addtionalAmounts = ($addtionalAmounts == true )?$this->getTotalAdditionalAmounts($loanId):0;
            foreach($result as $item)
            {
                //$current_amount = $item["lpa_current_amount"]."-";
                $current_amount = $item["lpa_current_amount"];// + $addtionalAmounts;
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

            if( $total_items == 0 && $addtionalAmounts == true )
            {
                //echo $loanId;
                $res = $this->getAdditionalAmountById($loanId);
                //echo count($res);
                if( count($res) > 0 )
                {
                    $amount = $res[0]["laa_amount"];
                }
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
            $RAW_QUERY = "SELECT lpa_id, lpa_max_payment_date, lpa_current_amount FROM loan_payment WHERE loa_id = $loanId
            AND lpa_total_amount_paid IS NULL
            AND lpa_max_payment_date <= '".$endDate."'
            ";  
            
            //echo $RAW_QUERY;

            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;                
        }
    }

    function check_in_range($fecha_inicio, $fecha_fin, $fecha){

        $fecha_inicio = strtotime($fecha_inicio);
        $fecha_fin = strtotime($fecha_fin);
        $fecha = strtotime($fecha);
   
        if(($fecha >= $fecha_inicio) && ($fecha <= $fecha_fin)) {
   
            return true;
   
        } else {
   
            return false;
   
        }
    }

    function showNumPendingQuotas( $loanId, $initDate, $endDate )
    {
        if( $loanId >0 && $initDate !="" && $endDate != "" )
        {
            $data = $this->getPendingQuotasNoPaid($loanId, $initDate, $endDate);

           //var_dump($data);
           //return true;
            $arrDates = array();
            //echo count($data);
            if( count($data) > 0 )
            {
                for($i = 0; $i < count($data); $i++)
                {
                    if( $data[$i]["lpa_max_payment_date"] != "" )
                    {
                        //$d = $data[$i]["lpa_max_payment_date"];
                        $d = $data[$i]["lpa_id"];
                       //echo $d." | ";
                       //echo $data[$i]["lpa_id"]." >> ";
                        //array_push($arrDates, $data[$i]["lpa_id"] );
                        $arrDates[$d] = $d;
                        //$arrDates[] = $d;
                    }
                }
            }

           //echo implode(",",$arrDates);
            /*
            $RAW_QUERY = "SELECT lpa_max_payment_date, lpa_current_amount FROM loan_payment WHERE loa_id = $loanId
                            AND lpa_total_amount_paid IN NULL
                            AND lpa_max_payment_date >= '".$initDate."' AND lpa_max_payment_date <= '".$endDate."'
                            ";
            */
            $RAW_QUERY = "SELECT lpa_id, lpa_max_payment_date, lpa_current_amount FROM loan_payment WHERE loa_id = $loanId ";               

            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();

            $arrNumQuotas = array();

            $pending = count($result);
            if( $pending > 0 && count($arrDates) > 0)
            {
                $order = 1;
                for($i = 0; $i < $pending; $i++)
                {
                     //   echo $i." - ";
                    $id = @$result[$i]["lpa_id"];
                        
                    if( $id > 0 )
                    {
                        if ( array_key_exists($result[$i]["lpa_id"], $arrDates) )
                        {
                            //$arrNumQuotasecho "Match found";
                            //echo $result[$i]["lpa_id"]." | ";
                            //echo " o ";
                           array_push($arrNumQuotas, $order );
                        }else{

                        }
                    }
                    
                    $order++;
                }
            }
            $arrNumQuotas = ( count($arrNumQuotas) > 0 )? implode(",",$arrNumQuotas):"";
            return array("totalQuotas"=>$pending, "numQuotas"=>$arrNumQuotas, "dates"=>$arrDates);                
        }
    }

    function getAdditionalAmounts($loanId)
    {
        $result = array();
        if( $loanId >0 )
        {
            $RAW_QUERY = "SELECT * FROM loan_additional_amounts WHERE loa_id = $loanId AND laa_active = 1";                

            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();          
        }
        return $result;  
    }

    function getTotalAdditionalAmounts($loanId)
    {
        $result = 0;
        if( $loanId >0 )
        {
            $oAdditionals = $this->em->getRepository('AppBundle:LoanAdditionalAmounts')->findBy( array("loa"=>$loanId,"laaActive"=>1)  );
            if( $oAdditionals )
            {
                //$RAW_QUERY = "SELECT SUM(laa_amount + (laa_amount *  (laa_rate_interest/100))) AS total FROM loan_additional_amounts WHERE loa_id = $loanId AND laa_active = 1";
                $RAW_QUERY = "SELECT SUM(laa_amount) AS total FROM loan_additional_amounts WHERE loa_id = $loanId AND laa_active = 1";                
                $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
                $statement->execute();
                $result = $statement->fetchAll();  
                $total = $result[0]["total"]; 
                if( $total > 0)
                {
                    $result = $total;
                } 
            }     
        }
        return $result;  
    }

    //Get last payment done for the client
    function getLastAddAmountJoined($loanId)
    {
        if( !empty($loanId) )
        {
            $RAW_QUERY  = "SELECT * FROM loan_additional_amounts WHERE loa_id = $loanId AND laa_splitted_balance = 0 ORDER BY laa_id DESC LIMIT 1 ";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return ($result);   
        }
    }

    function getTotalAdditionalAmountsJoinedMainBalance($loanId)
    {
        $result = 0;
        if( $loanId >0 )
        {
            $oAdditionals = $this->em->getRepository('AppBundle:LoanAdditionalAmounts')->findBy( array("loa"=>$loanId,"laaActive"=>1, "laaSplittedBalance"=>0 )  );
            if( $oAdditionals )
            {
                //$RAW_QUERY = "SELECT SUM(laa_amount + (laa_amount *  (laa_rate_interest/100))) AS total FROM loan_additional_amounts WHERE loa_id = $loanId AND laa_active = 1";
                $RAW_QUERY = "SELECT SUM(laa_amount) AS total FROM loan_additional_amounts WHERE loa_id = $loanId AND laa_splitted_balance = 0 AND laa_active = 1";                
                $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
                $statement->execute();
                $result = $statement->fetchAll();  
                $total = $result[0]["total"]; 
                if( $total > 0)
                {
                    $result = $total;

                    $paid = 0;    
                    foreach ( $oAdditionals as $item)
                    {
                       $res = $this->getTotalPaidCapitalAddAmount($item->getLaaId());
                       $paid = $paid + $res;
                    }

                    $result = $result - $paid;
                } 
            }     
        }
        return $result;  
    }

    function getTotalPaidCapitalAddAmount($laaId)
    {
        if( !empty($laaId) )
        {
            $RAW_QUERY  = "SELECT SUM(lpa_paid_capital) as total FROM loan_payment WHERE laa_id = $laaId AND lpa_paid_date !='' ";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return ($result[0]["total"]);   
        }
    }


    //New balance
    //Get return amount paid at the moment (no interest rate)
    function hasCurrentAdditionalAmount($loanId)
    {
        $result = array();
        if( !empty($loanId) && $loanId > 1 )
        {
            $RAW_QUERY  = "SELECT * FROM loan_additional_amounts WHERE loa_id = $loanId AND laa_splitted_balance = 1 AND laa_completed = 0 AND laa_active = 1 order by laa_id DESC LIMIT 1";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
        }
        return $result; 
    }

    function getAdditionalAmountById($id)
    {
        $result = array();
        if( !empty($id) && $id > 0 )
        {
            $RAW_QUERY  = "SELECT * FROM loan_additional_amounts WHERE laa_id = $id AND laa_active = 1";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
        }
        return $result; 
    }

    //Get all detail about the loan
    function getAdditionalPaymentByHash($hash)
    {
        $result = array();
        if( !empty($hash) && $hash != "" )
        {
            $RAW_QUERY  = "SELECT * FROM loan_payment WHERE lpa_hash =:p_hash AND laa_id IS NOT NULL";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->bindValue('p_hash',  $hash);
            $statement->execute();
            $result = $statement->fetchAll();
        }
        return $result;
    }

    function getActiveNumAdditionalAmount($loanId)
    {
        $result = array();
        if( !empty($loanId) && $loanId != "" )
        {
            $RAW_QUERY  = "SELECT * FROM loan_additional_amounts WHERE loa_id =:loanId AND laa_active = 1 AND laa_splitted_balance = 1";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->bindValue('loanId',  $loanId);
            $statement->execute();
            $result = $statement->fetchAll();
        }
        return $result;
    }



    function totalHashesPerPayment($hash)
    {
        $result = array();
        if( !empty($hash) && $hash != "" )
        {
            $RAW_QUERY  = "SELECT * FROM loan_historical_payments WHERE lhp_hash =:p_hash";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->bindValue('p_hash',  $hash);
            $statement->execute();
            $result = $statement->fetchAll();
        }
        return $result;
    }

    //===========================================================
    //This function gets all payments history
    //===========================================================
    public function getPaymentsByLoandId($loanId)
    {
        $arrHashes = array();
        $arrData = array();
        $numPayment = 0;
        if( isset($loanId) && $loanId > 0)
        {
            $oPayments= $this->em->getRepository('AppBundle:LoanHistoricalPayments')->findBy( array( "loa"=> $loanId) );
            if( $oPayments )
            {
                foreach( $oPayments as $item )
                {

                    //echo $item->getLpaHash()."-";
                    if( $item->getLhpHash() )
                    {
                        if ( !in_array( $item->getLhpHash() , $arrHashes) )
                        {
                            $totalHashes = $this->totalHashesPerPayment( $item->getLhpHash() );
                                            //if( count($totalHashes) == 2 )
                                            //{
                            foreach( $totalHashes as $hash )
                            {
                                $arrData[$numPayment][] = array(
                                                                "lhp_id"=>$hash["lhp_id"],
                                                                "lhp_deadline"=>$hash["lhp_deadline"],
                                                                "lhp_paid_date"=>$hash["lhp_paid_date"],
                                                                "lhp_prev_amount"=>$hash["lhp_prev_amount"],
                                                                "lhp_prev_interest"=>$hash["lhp_prev_interest"],

                                                                "lhp_last_paid_amount"=>$hash["lhp_last_paid_amount"],
                                                                "lhp_last_paid_interest"=>$hash["lhp_last_paid_interest"],
                                                                "lhp_last_paid_capital"=>$hash["lhp_last_paid_capital"],

                                                                "lhp_next_capital"=>$hash["lhp_next_capital"],
                                                                "lhp_next_interest"=>$hash["lhp_next_interest"],
                                                                "lhp_next_payment_date"=>$hash["lhp_next_payment_date"],

                                                                "lhp_hash"=>$hash["lhp_hash"],
                                                                "lhp_active"=>$hash["lhp_active"],
                                                                "lhp_created"=>$hash["lhp_created"],
                                                                "lhp_note"=>$hash["lhp_note"],

                                                                "loa_id"=>$hash["loa_id"],
                                                                "laa_id"=>$hash["laa_id"]
                                                            );


                            }
                                            //}
                                        
                                    array_push($arrHashes, $item->getLhpHash() );
                                    $numPayment++;
                        }
                    }
                    else //
                    {
                        $lhp_paid_date = ( $item->getLhpPaidDate() !="" )?$item->getLhpPaidDate()->format("Y-m-d"):"";
                        $lhp_next_payment_date = ( $item->getLhpNextPaymentDate() !="" )?$item->getLhpNextPaymentDate()->format("Y-m-d"):"";
                        $lhp_deadline = ( $item->getLhpDeadline() != "" )? $item->getLhpDeadline()->format("Y-m-d"):"";
                        $arrData[$numPayment][] = array(
                                        /*
                                            "lpa_id"=>$item->getLpaId(),
                                            "lpa_max_payment_date"=>$lpa_max_payment_date,
                                            "lpa_paid_date"=>$lpa_paid_date,
                                            "lpa_paid_rate_interest"=>$item->getLpaPaidRateInterest(),
                                            "lpa_paid_capital"=>$item->getLpaPaidCapital(),
                                            "lpa_current_rate_interest"=>$item->getLpaCurrentRateInterest(),
                                            "lpa_multiplied_interest_by"=>$item->getLpaMultipliedInterestBy(),
                                            "lpa_current_amount"=>$item->getLpaCurrentAmount(),
                                            "lpa_current_amount_joined"=>$item->getlpaCurrentAmountJoined(),
                                            "lpa_total_amount_paid"=>$item->getLpaTotalAmountPaid(),
                                            "lpa_next_rate_interest"=>$item->getLpaNextRateInterest(),

                                            "lpa_next_amount"=>$item->getLpaNextAmount(),
                                            "lpa_next_payment_date"=>$lpa_next_payment_date,
                                            "lpa_note"=>$item->getLpaNote(),
                                            "lpa_is_payment"=>$item->getLpaIsPayment(),

                                            "lpa_changed_amount"=>$item->getLpaChangedAmount(),
                                            "lpa_hash"=>"",

                                            "loa_id"=>$item->getLoa(),
                                            "laa_id"=>$item->getLaa(),
                                        */


                                        "lhp_id"=>$item->getLhpId(),
                                        "lhp_deadline"=>$lhp_deadline,
                                        "lhp_paid_date"=>$lhp_paid_date,
                                        "lhp_prev_amount"=>$item->getLhpPrevAmount(),
                                        "lhp_prev_interest"=>$item->getLhpPrevInterest(),

                                        "lhp_last_paid_amount"=>$item->getLhpLastPaidAmount(),
                                        "lhp_last_paid_interest"=>$item->getLhpLastPaidInterest(),
                                        "lhp_last_paid_capital"=>$item->getLhpLastPaidCapital(),

                                        "lhp_next_capital"=>$item->getLhpNextCapital(),
                                        "lhp_next_interest"=>$item->getLhpNextInterest(),
                                        "lhp_next_payment_date"=>$lhp_next_payment_date,

                                        "lhp_hash"=>"",
                                        "lhp_active"=>$item->getLhpActive(),
                                        "lhp_created"=>$item->getLhpCreated(),
                                        "lhp_note"=>$item->getLhpNote(),

                                        "loa_id"=>$item->getLoa(),
                                        "laa_id"=>$item->getLaa()
                                    );

                        $numPayment++;
                    }
                }
            }
        }
        return $arrData;            
    }
}
