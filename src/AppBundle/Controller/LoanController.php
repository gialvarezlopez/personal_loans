<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Loan;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Entity\LoanPayment;

/**
 * Loan controller.
 *
 */
class LoanController extends Controller
{
    private $session;
	
	public function __construct() {
		$this->session = new Session();
	}
    /**
     * Lists all loan entities.
     *
     */
    public function indexAction(Request $request)
    {
        //EXIT("CCCC");
        //printf("uniqid(): %s\r\n", uniqid())
        $em = $this->getDoctrine()->getManager();
        $userId = $this->getUser()->getUsrId();

        $status = $request->get("sta");

        $client = $request->get("client");

        switch($status)
        {
            case "2":
            case "1":
            case "0":
               // $loans = $em->getRepository('AppBundle:Loan')->findBy( array("loaCompleted"=>$status, "loa")  );
                $filter = " AND l.loa_completed = $status ";
                break;
            default:
                //$loans = $em->getRepository('AppBundle:Loan')->findAll(); 
                $filter = "";
                break;    
        }

        $filterClient = "";
        if( isset($client) && $client > 0 )
        {
            $oClient = $em->getRepository('AppBundle:Client')->findBy( array( "usr"=>$userId)  );
            if( $oClient )
            {
                $filterClient = " AND c.cli_id = $client ";
            }
            
        }
       $RAW_QUERY  = "SELECT * FROM loan l 
                        INNER JOIN loan_category lc ON l.loc_id = lc.loc_id
                        INNER JOIN client c ON l.cli_id = c.cli_id 
                        WHERE c.usr_id = $userId ".$filter.$filterClient;

        $statement  = $em->getConnection()->prepare($RAW_QUERY);
        $statement->execute();
        $result    	= $statement->fetchAll();

        

        return $this->render('app/loan/index.html.twig', array(
            'loans' => $result,
        ));
    }

    /**
     * Creates a new loan entity.
     *
     */
    public function newAction(Request $request)
    {
        date_default_timezone_set("UTC");

        $loan = new Loan();
        $form = $this->createForm('AppBundle\Form\LoanType', $loan);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        $clientId = $request->get("clientId");
        if( !is_numeric($clientId) || $clientId <= 0 )
        {
            throw new NotFoundHttpException("Record not found");
        }
        else
        {
            
            $userId = $this->getUser()->getUsrId();
            $oUser= $em->getRepository('AppBundle:Client')->findOneBy( array( "usr"=> $userId, "cliId"=>$clientId) );
            if( !$oUser )
            {
                return $this->redirectToRoute('client_index');
            }

        }

        if ($form->isSubmitted() && $form->isValid()) {

            $loanCatId = $request->get("type");
            if( $loanCatId > 0 )
            {
                $oLoanCategory= $em->getRepository('AppBundle:loanCategory')->findOneBy( array("locId"=>$loanCatId, "locActive"=>1) );
                if( !$oLoanCategory )
                {
                    throw new NotFoundHttpException("Loan Type is not exist");
                }
                else
                {
                    $loan->setLoc($oLoanCategory);
                }
            }
            else
            {
                throw new NotFoundHttpException("Loan Type is incorrect or is missing");
            }
           
            $rate = $form->get("loaRateInterestByDefault")->getData();
            //exit("sali");
            $loan->setCli($oUser);
            $loan->setLoaCreated( new \DateTime() );
            $loan->setLoaCode( strtoupper( uniqid() ) );
            $loan->setLoaRateInterest( $rate );
            $loan->setLoaCompleted(0);
            $em->persist($loan);
            $em->flush();

            if( $oLoanCategory->getLocKey() == "inactive_rate" )
            {
                $paymentDates = $request->get("paymentDates");
                if( isset($paymentDates) && !empty($paymentDates) )
                {
                    $arr = explode(",", $paymentDates);
                    for ($i = 0; $i < count($arr); $i++) {
                        $item = explode("=", $arr[$i]);
                        $maxDate = $item[0];
                        $amount = @$item[1];

                        $oLoanPayment = new LoanPayment();
                        $oLoanPayment->setLpaMaxPaymentDate( new \datetime($maxDate) );
                        $oLoanPayment->setLoa( $loan );
                        $oLoanPayment->setLpaCurrentAmount( $amount );
                        $em->persist($oLoanPayment);
                        $em->flush();
                    }
                }
            }
            else if( $oLoanCategory->getLocKey() == "active_rate"  )
            {
                $deadline = $form->get("loaDeadline")->getData();
                $rate = $form->get("loaRateInterest")->getData();
                $oLoanPayment = new LoanPayment();
                $oLoanPayment->setLpaMaxPaymentDate( $deadline );
                $oLoanPayment->setLoa( $loan );
                $oLoanPayment->setLpaCurrentRateInterest( $rate );
                //$oLoanPayment->setLpaCurrentAmount( $amount );
                $em->persist($oLoanPayment);
                $em->flush();
            }

            $msg = "Data created successfully";
            $this->session->getFlashBag()->add("success", $msg);
            return $this->redirectToRoute('loan_show', array('loaId' => $loan->getLoaid()));
        }

        $loanCatId = $request->get("type");
        $loanType = FALSE;
        $oLoanCategory= $em->getRepository('AppBundle:loanCategory')->findBy( array( "locActive"=>1) );
        if( isset($loanCatId) && $loanCatId > 0)
        {
            foreach( $oLoanCategory as $item)
            {
                
                if( $item->getLocId() == $loanCatId)
                {
                    $loanType = array("id"=>$item->getLocId(), "name"=>$item->getLocType(), "key"=>$item->getLocKey() );
                    break;
                }
            }
        }
        else
        {
            
        }
        

        return $this->render('app/loan/new.html.twig', array(
            'loan' => $loan,
            'form' => $form->createView(),
            "client" => $oUser,
            "loanType" => $loanType,
            "oLoanCategory" => $oLoanCategory
        ));
    }

    /**
     * Finds and displays a loan entity.
     *
     */
    public function showAction(Loan $loan)
    {
        $deleteForm = $this->createDeleteForm($loan);

        $loaId =  $loan->getLoaId();
        if( isset($loaId) && !empty($loaId) )
        {
            $userId = $this->getUser()->getUsrId();
            if( $userId != $loan->getCli()->getUsr()->getUsrId() )
            {
                throw new AccessDeniedHttpException("Access Denied");
            }
        } 
        //echo "xxx";
        $em = $this->getDoctrine()->getManager();
        $oPaymentType = $em->getRepository('AppBundle:LoanPaymentType')->findAll();
        return $this->render('app/loan/show.html.twig', array(
            'loan' => $loan,
            'delete_form' => $deleteForm->createView(),
            'paymentType'=> $oPaymentType
        ));
    }

    /**
     * Displays a form to edit an existing loan entity.
     *
     */
    public function editAction(Request $request, Loan $loan)
    {
        

        $deleteForm = $this->createDeleteForm($loan);
        $editForm = $this->createForm('AppBundle\Form\LoanType', $loan);
        $editForm->handleRequest($request);

        $loanId = $request->get("loaId");
        if( $loanId > 0 )
        {
            $userId = $this->getUser()->getUsrId();
            //$em = $this->getDoctrine()->getManager();
            //$oLoan= $em->getRepository('AppBundle:Loan')->findOneBy( array( "loaId"=>$loanId) );
            if( $loan )
            {
                if( $userId != $loan->getCli()->getUsr()->getUsrId() )
                {
                    throw new AccessDeniedHttpException("Access Denied");
                }
            }
            else
            {
                throw new NotFoundHttpException("Record not found");
            }
        }
        else
        {
            throw new NotFoundHttpException("Record not found");
        }
        //loaId

        //$oUser= $em->getRepository('AppBundle:Client')->findOneBy( array( "usr"=> $userId, "cliId"=>$clientId) );
        $em = $this->getDoctrine()->getManager();
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            date_default_timezone_set("UTC");

            echo $rate = $editForm->get("loaRateInterestByDefault")->getData();
            $loan->setLoaRateInterest($rate);

            //echo $statusList = $editForm->get("statusList")->getData();
            //exit("xxxx");
            //$loan->setLoaCompleted($statusList);
            //exit();
            $this->getDoctrine()->getManager()->flush();

            
            $loanType =  $loan->getLoc()->getLocKey();
            if( $loanType == "inactive_rate" )
            {
                
                /*
                $itemsLoan = $em->getRepository('AppBundle:LoanPayment')->findBy( array( "lpaPaidDate" => null, "loa"=>$loanId ) ) ;
                $em->remove($itemsLoan);
                $res = $em->flush(); 
                */
                
                $q = $em->createQuery("DELETE FROM AppBundle\Entity\LoanPayment tb WHERE tb.loa=".$loanId." AND tb.lpaPaidDate IS NULL ");
                $res = $q->execute();

                //if( $res  )
                //{
                    $paymentDates = $request->get("paymentDates");
                    //exit();
                    if( isset($paymentDates) && !empty($paymentDates) )
                    {
                        var_dump($paymentDates);
                        $arr = explode(",", $paymentDates);
                        for ($i = 0; $i < count($arr); $i++) {
                            $item = explode("=", $arr[$i]);
                            $maxDate = $item[0];
                            $amountPlusId = @$item[1];

                            $str = explode("|", $amountPlusId);
                            $amount = @$str[0];
                            $paymentId = @$str[1];
                            $paymentTypeId = @$str[2];

                            if( isset($paymentId) and $paymentId > 0 )
                            {
                                $oLoanPayment = $em->getRepository('AppBundle:LoanPayment')->findOneBy( array( "lpaId"=> $paymentId, "loa"=>$loanId ) );
                                if( !$oLoanPayment )
                                {
                                    $oLoanPayment = new LoanPayment();
                                }
                            }else{
                                //echo "maxdate".$maxDate;
                                $oLoanPayment = new LoanPayment();
                                
                                
                                
                            }

                           
                            if( $paymentTypeId > 0 )
                            {
                                $oLoanPaymentType = $em->getRepository('AppBundle:LoanPaymentType')->findOneBy( array( "lptId"=> $paymentTypeId) );
                                if( $oLoanPaymentType )
                                {
                                    $oLoanPayment->setLpt( $oLoanPaymentType );
                                }
                                
                            }
                            
                            $oLoanPayment->setLoa( $loan );
                            $oLoanPayment->setLpaMaxPaymentDate( new \datetime($maxDate) );
                            $oLoanPayment->setLpaCurrentAmount( $amount );
                            $em->persist($oLoanPayment);
                            $em->flush();
                        }
                    }
                    //var_dump($paymentDates);
                    //exit();
                    //check all total of payments
                    /*
                    $srvLoan = $this->get('srv_Loans');  
                    $checkPayments = $srvLoan->checkPaymentsPerLoan($loanId);  
                    if( $checkPayments )
                    {
                        $oLoan= $em->getRepository('AppBundle:Loan')->findOneBy( array( "loaId"=> $loanId, "loaActive"=>1) );
                        if( $checkPayments[0]['paidTotal'] >= $checkPayments[0]['currentAmount'] )
                        {
                            $oLoan->setLoaCompleted(1);
                        }
                        else
                        {
                            $oLoan->setLoaCompleted(0);
                        }
                        $em->persist($oLoan);
                        $flus = $em->flush();
                    }    
                    */

                //}
                //var_dump($paymentDates);
                //exit("xxx");
            }
            else if( $loanType == "active_rate"  )
            {  
                $deadline = $editForm->get("loaDeadline")->getData();
                //$rate = $editForm->get("loaRateInterestByDefault")->getData();

                //$rate = $editForm->get("loaRateInterest")->getData();
                    //$pays = $em->getRepository('AppBundle:Payer')->findBy( array("usr"=> $userId,"payActive"=>1), array('payId' => 'DESC'), "LIMIT" );
                    $oLoanPayment = $em->getRepository('AppBundle:LoanPayment')->findOneBy( array( "loa"=> $loanId), array("lpaMaxPaymentDate"=>'DESC' ), 1 );
                    
                    
                    if( $oLoanPayment )
                    {
                        //echo "dentro";
                        if( $oLoanPayment->getLpaTotalAmountPaid() > 0 )
                        {
                            //$oLoanPayment->setLpaMaxPaymentDate($deadline);
                            $oLoanPayment = new LoanPayment();
                            echo "nuevo";
                        }
                        else
                        {
                            echo "update";
                            
                        }
                        
                        //exit("<hr> entre");

                    }
                    else
                    {
                        $oLoanPayment = new LoanPayment();
                    }
                    $oLoanPayment->setLpaMaxPaymentDate( $deadline );
                    $oLoanPayment->setLoa( $loan );
                    $oLoanPayment->setLpaCurrentRateInterest( $rate );
                    //$oLoanPayment->setLpaCurrentAmount( $amount );    

                    $em->persist($oLoanPayment);
                    $em->flush();
            }
            //exit("<hr>fin");
            $msg = "Data saved successfully";
            $this->session->getFlashBag()->add("success", $msg);

            return $this->redirectToRoute('loan_edit', array('loaId' => $loan->getLoaid()));
        }

        $oPaymentType = $em->getRepository('AppBundle:LoanPaymentType')->findAll();

        return $this->render('app/loan/edit.html.twig', array(
            'loan' => $loan,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'paymentType'=> $oPaymentType
        ));
    }

    /**
     * Deletes a loan entity.
     *
     */
    public function deleteAction(Request $request, Loan $loan)
    {
        $form = $this->createDeleteForm($loan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($loan);
            $em->flush();
        }

        return $this->redirectToRoute('loan_index');
    }

    /**
     * Creates a form to delete a loan entity.
     *
     * @param Loan $loan The loan entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Loan $loan)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('loan_delete', array('loaId' => $loan->getLoaid())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
