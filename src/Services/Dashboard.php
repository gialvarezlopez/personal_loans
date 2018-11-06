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

class Dashboard
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
        /*
        if( !empty($clientId) )
        {
            //$em = $this->getDoctrine()->getManager();
            $loans = $this->em->getRepository('AppBundle:Loan')->findBy( array("cli"=>$clientId,"loaCompleted"=>0, "loaActive"=>1)  );
            return $loans;
        }
        */
        
    }
    /*
    function getDetailLoanPayments($loanId)
    {
        if( !empty($loanId) )
        {
            //$em = $this->getDoctrine()->getManager();
            $payments = $this->em->getRepository('AppBundle:LoanPayment')->findBy( array("loa"=>$loanId )  );
            return $payments;
        }
        
    }
    */
    /*
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
    */
    /*
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
    */

    public function getLoans($userId, $count = false,  $category=false, $limit=false, $completed="0,1,2", $periodDays=false, $periodCompletedDate=false)
    {
        
        $RAW_QUERY  = "SELECT";
        
        if( $count )
        {
            $RAW_QUERY .= " COUNT(*) AS total ";
        }
        else
        {
            $RAW_QUERY  .= " * ";
        }
        $RAW_QUERY  .= " FROM loan l 
                        INNER JOIN `client` c ON l.cli_id = c.cli_id
                        INNER JOIN `user` u ON c.usr_id = u.usr_id
                        INNER JOIN loan_category loc ON l.loc_id = loc.loc_id
                        WHERE l.loa_active = 1 ";

        $RAW_QUERY  .= " AND u.usr_id = $userId ";
        //$category = "";
        if( $category )
        {
            $RAW_QUERY .= " AND loc.loc_key = '".$category."'"; 
        }

        if( $completed >=0 )
        {
            $RAW_QUERY .= " AND l.loa_completed in (".$completed.")"; 
        }

        if( is_array($periodDays) )
        {
            //echo "ayy";
            $RAW_QUERY .= " AND l.loa_loan_made >= '".$periodDays["startDate"]."' and  l.loa_loan_made <= '".$periodDays['endDate']."' ";
        }

        if( is_array($periodCompletedDate) )
        {
            //echo "ayy";
            $RAW_QUERY .= " AND l.loa_completed_date >= '".$periodCompletedDate["startDate"]."' and  l.loa_completed_date <= '".$periodCompletedDate['endDate']."' ";
        }

        if( $limit )
        {
            $RAW_QUERY .= " ORDER BY l.loa_id DESC LIMIT ".$limit; 
        }
        //echo $RAW_QUERY;
        $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();
        return $result;
    }

    public function getLoanLastPaymentDetail($userId, $count = false,  $category=false, $limit=false, $completed="0,1,2", $periodDays=false, $optOption = false)
    {
        $itemLoans = array();
        $arrIdLoans = array();
        if( is_array($periodDays) )
        {
            $RAW_QUERY  = " SELECT * FROM loan_payment lp
                            INNER JOIN loan l ON l.loa_id = lp.loa_id
                            INNER JOIN `client` c ON c.cli_id = l.cli_id
                            WHERE c.usr_id = $userId ";

            if( is_array($periodDays) )
            {
                // 1 = Next Payment, 2 = Prev Payment, 3 = Both
                if( $optOption == 3 )
                {
                    $RAW_QUERY .= " AND lp.lpa_paid_date >= '".$periodDays["startDate"]."' and  lp.lpa_paid_date <= '".$periodDays['endDate']."'
                            OR
                        (lp.lpa_max_payment_date >= '".$periodDays["startDate"]."' 
                            AND lp.lpa_max_payment_date <= '".$periodDays['endDate']."' 
                            AND lp.lpa_total_amount_paid IS NULL
                        )
                    ";
                }
                else
                { 
                    if( $optOption == 1 )
                    {
                        $RAW_QUERY .=  " AND (
                                                (
                                                    lp.lpa_max_payment_date >= '".$periodDays["startDate"]."' AND lp.lpa_max_payment_date <= '".$periodDays['endDate']."' 
                                                    AND lp.lpa_total_amount_paid IS NULL
                                                )
                                                OR
                                                (
                                                    l.loa_deadline >= '".$periodDays["startDate"]."' AND l.loa_deadline <= '".$periodDays['endDate']."' 
                                                   
                                                )
                                            ) ";
                    }
                    else if( $optOption == 2 )
                    {
                        $RAW_QUERY .=  " AND lp.lpa_paid_date >= '".$periodDays["startDate"]."' and  lp.lpa_paid_date <= '".$periodDays['endDate']."'";
                    }
                }
            }
            //echo $RAW_QUERY;
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            if( count($result) > 0 )
            {
                foreach($result as $value)
                {
                    $itemLoans[$value["loa_id"]] = array(
                                                        "lpa_id"=>$value["lpa_id"],  
                                                        "loa_id"=>$value["loa_id"], 
                                                        "note"=>$value["lpa_note"],
                                                        "lpa_current_rate_interest"=>$value["lpa_current_rate_interest"],
                                                        "lpa_paid_rate_interest"=>$value["lpa_paid_rate_interest"],
                                                        "lpa_paid_capital"=>$value["lpa_paid_capital"],
                                                        "lpa_total_amount_paid"=>$value["lpa_total_amount_paid"],
                                                        "lpa_current_amount"=>$value["lpa_current_amount"], // just no rate
                                                        "lpa_next_rate_interest"=>$value["lpa_next_rate_interest"]
                                                    );
                }
            }
        }
        //var_dump( $itemLoans);
        if( count($itemLoans) > 0 )
        {
            foreach($itemLoans as $v)
            {
                array_push($arrIdLoans, $v["loa_id"]);
                //echo $v["loa_id"]." - ";
            }
            $RAW_QUERY  = "SELECT";
            
            if( $count )
            {
                $RAW_QUERY .= " COUNT(*) AS total ";
            }
            else
            {
                $RAW_QUERY  .= " l.loa_id, l.loa_code, loc.loc_key,loc.loc_type, 
                            CONCAT_WS(' ', c.cli_first_name, c.cli_middle_name, c.cli_first_surname, c.cli_second_surname) AS name, 
                            l.loa_rate_interest, l.loa_recurring_day_payment, l.loa_deadline, l.loa_amount";
            }
            $RAW_QUERY  .= " FROM loan l 
                            INNER JOIN `client` c ON l.cli_id = c.cli_id
                            INNER JOIN `user` u ON c.usr_id = u.usr_id
                            INNER JOIN loan_category loc ON l.loc_id = loc.loc_id
                            WHERE l.loa_active = 1 AND l.loa_id in (".implode(',', $arrIdLoans) .") ";

            $RAW_QUERY  .= " AND u.usr_id = $userId ";
            //$category = "";
            if( $category )
            {
                $RAW_QUERY .= " AND loc.loc_key = '".$category."'"; 
            }

            if( $completed >=0 )
            {
                $RAW_QUERY .= " AND l.loa_completed in (".$completed.")"; 
            }

            if( $limit )
            {
                $RAW_QUERY .= " ORDER BY l.loa_id DESC LIMIT ".$limit; 
            }

            $arr = array();
            //echo $RAW_QUERY;
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            //var_dump($result);
            $num = 0;
            foreach( $result  as $item)
            {
                //echo $item["name"]."- ";
                $arr[$num]["loa_id"] = $item["loa_id"];
                $arr[$num]["loa_code"] = $item["loa_code"];
                $arr[$num]["loc_key"] = $item["loc_key"];
                $arr[$num]["loc_type"] = $item["loc_type"];
                $arr[$num]["name"] = $item["name"];
                $arr[$num]["loa_rate_interest"] = $item["loa_rate_interest"];
                $arr[$num]["loa_recurring_day_payment"] = $item["loa_recurring_day_payment"];
                $arr[$num]["loa_deadline"] = $item["loa_deadline"];
                $arr[$num]["loa_amount"] = $item["loa_amount"];

                $arr[$num]["lpa_current_rate_interest"] =  $itemLoans[$item["loa_id"]]["lpa_current_rate_interest"];
                $arr[$num]["lpa_paid_rate_interest"] =  $itemLoans[$item["loa_id"]]["lpa_paid_rate_interest"];  
                $arr[$num]["lpa_paid_capital"] =  $itemLoans[$item["loa_id"]]["lpa_paid_capital"]; 
                $arr[$num]["lpa_total_amount_paid"] =  $itemLoans[$item["loa_id"]]["lpa_total_amount_paid"];
                $arr[$num]["lpa_current_amount"] =  $itemLoans[$item["loa_id"]]["lpa_current_amount"];
                $arr[$num]["lpa_next_rate_interest"] =  $itemLoans[$item["loa_id"]]["lpa_next_rate_interest"];    

                
                $arr[$num]["note"] =  $itemLoans[$item["loa_id"]]["note"];
                
                $num++;
            }
            return $arr;
        }
    }

    public function getLoansDetail($userId, $periodCompletedDate=false, $statusCompleted)
    {
        $RAW_QUERY  = "SELECT l.*, c.* FROM loan l 
                        INNER JOIN `client` c ON l.cli_id = c.cli_id
                        INNER JOIN `user` u ON c.usr_id = u.usr_id
                        INNER JOIN loan_category loc ON l.loc_id = loc.loc_id
                        WHERE l.loa_active = 1
                        AND u.usr_id = $userId 
                        AND l.loa_completed in ($statusCompleted) ";

        //1 =  Complted, 2 =  Freezed
        if( $statusCompleted == 1 || $statusCompleted == 2 )
        {
            $RAW_QUERY .= " AND l.loa_completed_date >= '".$periodCompletedDate["startDate"]."' and  l.loa_completed_date <= '".$periodCompletedDate['endDate']."'";
        }
        else
        {
            $RAW_QUERY .= " AND l.loa_loan_made >= '".$periodCompletedDate["startDate"]."' and  l.loa_loan_made <= '".$periodCompletedDate['endDate']."'";
        }   

        
        $RAW_QUERY .= " ORDER BY l.loa_id DESC ";

        //echo $RAW_QUERY;
        $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result = $statement->fetchAll();

        //var_dump($result);
        $totalDetail =  array();
        $totalDetail["totalPaidCapital"] = "0.00";
        $totalDetail["totalPaidRate"] = "0.00";
        $totalDetail["totalCurrentAmount"] = "0.00";
        $totalDetail["totalPaidTotal"] = "0.00";
        $totalDetail["totalInvestedMoney"] = "0.00";

        $totalpaidCapital = 0;
        $totalPaidRate = 0;
        $totalCurrentAmount = 0;
        $totalPaidTotal = 0;
        $totalInvestedMoney = 0;

        $periodDetail =  array();
        $periodDetail["periodPaidCapital"] = "0.00";
        $periodDetail["periodPaidRate"] = "0.00";

        $periodPaidCapital = 0;
        $periodPaidRate = 0;

        if( count($result) > 0  )
        {


            $srvLoan = $this->container->get('srv_Loans');
            foreach( $result as $item)
            {
                $loanId = $item['loa_id'];
                //Call service srv_Loans
                
                /*
                $res = $srvLoan->checkPaymentsPerLoan($loanId);

                //full history
                $totalpaidCapital = $totalpaidCapital + $res[0]['paidCapital'];
                $totalPaidRate = $totalPaidRate + $res[0]['paidRate'];
                $totalCurrentAmount = $totalCurrentAmount + $res[0]['currentAmount'];
                $totalPaidTotal = $totalPaidTotal + $res[0]['paidTotal'];
                */
                $res = $srvLoan->getAllDetailLoan($loanId);
                if( count($res) > 0 )
                {
                    foreach($res as $value)
                    {
                        $totalpaidCapital = $totalpaidCapital + $value['lpa_paid_capital'];
                        $totalPaidRate = $totalPaidRate + $value['lpa_paid_rate_interest'];
                        $totalCurrentAmount = $totalCurrentAmount + $value['lpa_current_amount'];
                        $totalPaidTotal = $totalPaidTotal + $value['lpa_total_amount_paid'];
                        
                        $startDate = $periodCompletedDate["startDate"];
                        $startDate = date("Y-m-d", strtotime($startDate));

                        $endDate = $periodCompletedDate["endDate"];
                        $endDate = date("Y-m-d", strtotime($endDate));
                        
                        if( $value["lpa_paid_date"] >= $startDate && $value["lpa_paid_date"] <= $endDate ){
                            $periodPaidCapital = $periodPaidCapital + $value['lpa_paid_capital'];
                            $periodPaidRate = $periodPaidRate + $value['lpa_paid_rate_interest'];
                        }
                    }
                }    

                $totalInvestedMoney = $totalInvestedMoney + $item['loa_amount'];

                //just the period
                //if( $res[0]['paidCapital'] )


                //var_dump($res);
            }

            $totalDetail["totalPaidCapital"] = $totalpaidCapital; 
            $totalDetail["totalPaidRate"] = $totalPaidRate;
            $totalDetail["totalCurrentAmount"] = $totalCurrentAmount;
            $totalDetail["totalPaidTotal"] = $totalPaidTotal;
            $totalDetail["totalInvestedMoney"] = $totalInvestedMoney;

            $periodDetail["periodPaidCapital"] = $periodPaidCapital; 
            $periodDetail["periodPaidRate"] = $periodPaidRate; 
        }
        return array("total"=>count($result), "totalDetail" => $totalDetail, "periodDetail"=> $periodDetail); 
    }

    public function getPrevAndNextPaymentNoRate($loanId, $type=false)
    {
        if( !empty($loanId) && $type !="" )
        {
            if($type == "prev")
            {
                $filter  = " AND lpa_total_amount_paid IS NOT NULL ORDER BY lpa_id DESC LIMIT 1";
            }
            else
            {
                $filter  = " AND lpa_total_amount_paid IS NULL ORDER BY lpa_id ASC LIMIT 1 ";
            }
            $RAW_QUERY  = "SELECT * FROM loan_payment WHERE loa_id = $loanId  $filter ";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return $result;
            
        }
    }

    public function getClients($userId)
    {
        if( !empty($userId) )
        {
            //$em = $this->getDoctrine()->getManager();
            //$payments = $this->em->getRepository('AppBundle:LoanPayment')->findBy( array("loa"=>$loanId )  );
            //return $payments;
            $RAW_QUERY  = "SELECT COUNT(*) as total FROM client WHERE usr_id = $userId AND cli_active = 1 ";
            $statement  = $this->em->getConnection()->prepare($RAW_QUERY);
            $statement->execute();
            $result = $statement->fetchAll();
            return ($result[0]["total"]);
            
        }
    }


    public function langDatata($lang)
    {
        //header('Content-Type: text/html; charset=utf-8');
        $lang = ( $lang == "es")?"datatable-es.json":"datatable-en.json";
        $content = file_get_contents('../web/'.$lang);
        echo utf8_decode($content);
        //return json_decode($content, true);
        //return new JsonResponse(json_encode($content, true));
        

        //exit();
    }
    

}
