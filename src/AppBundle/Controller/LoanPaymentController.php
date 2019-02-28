<?php

namespace AppBundle\Controller;

use AppBundle\Entity\LoanPayment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpFoundation\Session\Session;

use AppBundle\Entity\LoanHistoricalAmounts;

/**
 * Loanpayment controller.
 *
 */
class LoanPaymentController extends Controller
{
    private $session;
	
	public function __construct() {
		$this->session = new Session();
	}
    /**
     * Lists all loanPayment entities.
     *
     */
    public function indexAction()
    {
        /*
        $em = $this->getDoctrine()->getManager();
        echo "aqui";
        $loanPayments = $em->getRepository('AppBundle:LoanPayment')->findAll();

        return $this->render('app/loanpayment/index.html.twig', array(
            'loanPayments' => $loanPayments,
        ));
        */
    }

    /**
     * Creates a new loanPayment entity.
     *
     */
    public function newAction(Request $request)
    {
        date_default_timezone_set("UTC");

        $loanPayment = new Loanpayment();
        $form = $this->createForm('AppBundle\Form\LoanPaymentType', $loanPayment);
        $form->handleRequest($request);

        $loanId = $request->get("loaId");
        $em = $this->getDoctrine()->getManager();

        if( !is_numeric($loanId) || $loanId <= 0  )
        {
            $msg = $this->get('translator')->trans('general_msg_record_no_found');
            throw new NotFoundHttpException($msg);
        }
        else
        {
            
            $userId = $this->getUser()->getUsrId();
            $oLoan = $em->getRepository('AppBundle:Loan')->findOneBy( array( "loaId"=> $loanId, "loaActive"=>1) );
            if( $oLoan )
            {
                if( $userId != $oLoan->getCli()->getUsr()->getUsrId() )
                {
                    $msg = $this->get('translator')->trans('general_msg_access_denied');
                    throw new AccessDeniedHttpException($msg);
                }

               
            }
        }

        $loanType = $oLoan->getLoc()->getLocKey();
        if ($form->isSubmitted() && $form->isValid()) {
            //$em = $this->getDoctrine()->getManager();
            
            //var_dump($_POST);    
            //exit("Sali"); 
            $uniqueHash = md5(uniqid(rand(), true));
            $em->getConnection()->beginTransaction(); // suspend auto-commit
            try
            {
                $paidDate = $form->get("lpaPaidDate")->getData(); //Unique input
                $nextPayment = $form->get("loaNextPaymentDate")->getData(); //Unique input
                $endLoan = $request->get("endLoan"); //Unique input
                $joinBalances = $request->get("joinBalances"); //Unique input

                $nextRate = $form->get("loaNextInterestRate")->getData();
                $nextRate_additional = $request->get("loaNextInterestRate_additional");

                $balance1 = $request->get("balance1");
                

                
                //$nextRate = $form->get("loaNextInterestRate")->getData();

                //========================================================================
                //Balance Inicial
                //========================================================================
                if( isset($balance1) && $balance1 == 1 )
                {
                    /*
                        if(isset($nextRate) || isset($nextPayment) )
                        {
                            if( $endLoan == 1 )
                            {
                                $oLoan->setLoaCompleted(1);
                                //$paidDate = $form->get("lpaPaidDate")->getData();
                                $oLoan->setLoaCompletedDate( $paidDate );
                            }
                            else
                            {
                                $oLoan->setLoaDeadline($nextPayment);
                                $oLoan->setLoaRateInterest($nextRate);
                            }
                            $oLoan->setLoaResetRateToInterestByDefault(0);
                            $em->persist($oLoan);
                            $em->flush();
                        }
                    */
                    if(isset($nextRate) || isset($nextPayment) )
                    {
                        $oLoan->setLoaDeadline($nextPayment);
                        $oLoan->setLoaRateInterest($nextRate);
                        $oLoan->setLoaResetRateToInterestByDefault(0);
                        $em->persist($oLoan);
                        $em->flush();
                    }

                    $paidInterestRate = $request->get("inputPaidInterest");
                    $paidCapital = $request->get("inputPaidCapital");

                    //loanPayment
                    //$oCheckFistPayment = $em->getRepository('AppBundle:LoanPayment')->findOneBy( array( "loa"=> $loanId, "lpaTotalAmountPaid"=>"IS NULL", "lpaPaidDate"=>"IS NULL") );
                    $oCheckFirstPayment = $em->getRepository('AppBundle:LoanPayment')->findOneBy( array( "loa"=> $loanId), array("lpaMaxPaymentDate"=>'DESC' ), 1 );
                    if(  $oCheckFirstPayment )
                    {
                        if( $oCheckFirstPayment->getLpaTotalAmountPaid() == "" )
                        {
                            $loanPayment = $oCheckFirstPayment;
                        }                
                    }
                    $currentRate = $form->get("lpaCurrentRateInterest")->getData();
                    $totalAmountPaid = $form->get("lpaTotalAmountPaid")->getData();
                    $paidDate = $form->get("lpaPaidDate")->getData();
                    $loanPayment->setLpaCurrentRateInterest($currentRate);
                    //$loanPayment->setLpaTotalAmountPaid($currentRate);
                    $loanPayment->setLpaPaidRateInterest($paidInterestRate);
                    $loanPayment->setLpaPaidCapital($paidCapital);
                    $loanPayment->setLpaPaidDate( $paidDate );
                    $loanPayment->setLpaNextRateInterest( $nextRate );
                    $loanPayment->setLpaTotalAmountPaid($totalAmountPaid);
                    $loanPayment->setLpaNextPaymentDate($nextPayment);
                    $loanPayment->setLpaHash($uniqueHash);

                    //$loanPayment->setLpaMaxPaymentDate( new \datetime($nextPayment) );
                    $loanPayment->setLpaMaxPaymentDate( $nextPayment );

                    if( $loanType == "active_rate" )
                    {
                        $loanPayment->setLpaCurrentAmount($oLoan->getLoaAmount());
                    }

                    $loanPayment->setLoa($oLoan);
                    $em->persist($loanPayment);
                    $em->flush();
                }

                //========================================================================
                //Balance adicional
                //========================================================================    
                $balance2 = $request->get("balance2");
                if( isset($balance2) && $balance2 == 1 )
                {

                    $paidInterestRate = $request->get("inputPaidInterest_additional");
                    $paidCapital = $request->get("inputPaidCapital_additional");


                    $srvLoan = $this->get('srv_Loans'); 
                    $res = $srvLoan->hasCurrentAdditionalAmount($loanId);
                    if( count($res) > 0 )
                    {
                        $addAmointId = $res[0]["laa_id"];
                        $amount = $res[0]["laa_amount"];
                        //loanPayment
                        //$oCheckFistPayment = $em->getRepository('AppBundle:LoanPayment')->findOneBy( array( "loa"=> $loanId, "lpaTotalAmountPaid"=>"IS NULL", "lpaPaidDate"=>"IS NULL") );
                        $loanPayment = new Loanpayment();
                        $oCheckFirstPayment = $em->getRepository('AppBundle:LoanPayment')->findOneBy( array( "loa"=> $loanId), array("lpaMaxPaymentDate"=>'DESC' ), 1 );
                        if(  $oCheckFirstPayment )
                        {
                            if( $oCheckFirstPayment->getLpaTotalAmountPaid() == "" )
                            {
                                $loanPayment = $oCheckFirstPayment;
                            
                            }
                            
                        }
                        $currentRate = $request->get("lpaCurrentRateInterest_additional");
                        $totalAmountPaid = $request->get("lpaTotalAmountPaid_additional"); 
                        $paidDate = $form->get("lpaPaidDate")->getData();
                        $loanPayment->setLpaCurrentRateInterest($currentRate);
                        //$loanPayment->setLpaTotalAmountPaid($currentRate);
                        $loanPayment->setLpaPaidRateInterest($paidInterestRate);
                        $loanPayment->setLpaPaidCapital($paidCapital);
                        $loanPayment->setLpaPaidDate( $paidDate );
                        $loanPayment->setLpaNextRateInterest( $nextRate );
                        $loanPayment->setLpaTotalAmountPaid($totalAmountPaid);
                        $loanPayment->setLpaNextPaymentDate($nextPayment);
                        //$loanPayment->setLpaNextRateInterest( $nextRate );
                        $loanPayment->setLpaHash($uniqueHash);

                        $loanPayment->setLpaMaxPaymentDate( $nextPayment );
                        //$loanPayment->setLpaMaxPaymentDate( new \datetime($nextPayment) );

                        $objLAA = $em->getRepository('AppBundle:LoanAdditionalAmounts')->findOneBy( array( "laaId"=> $addAmointId, "laaActive"=>1));

                        $loanPayment->setLaa( $objLAA );

                        if( $loanType == "active_rate" )
                        {
                            $loanPayment->setLpaCurrentAmount($amount);
                        }

                        $loanPayment->setLoa($oLoan);
                        $loanPayment->setLoa(NULL);
                        $em->persist($loanPayment);
                        $em->flush();


                        if( $objLAA)
                        {
                            //$em->getRepository('AppBundle:LoanAdditionalAmounts')->findOneBy( array( "laaId"=> $$addAmointId, "loaActive"=>1) );
                            if( $nextRate == $nextRate_additional )
                            {
                                $objLAA->setLaaSplittedBalance(0);
                            }

                            //$loanPayment->setLoa(NULL);
                            //$objLAA->setLaaNextPaymentDate( new \datetime($nextPayment) );
                            $objLAA->setLaaNextPaymentDate( $nextPayment );
                            $em->persist($objLAA);
                            $em->flush();
                            
                        }
                    }
                }


                if( isset($endLoan) )
                {
                    if( $endLoan == 1 )
                    {
                        $oLoan->setLoaCompleted(1);
                        //$paidDate = $form->get("lpaPaidDate")->getData();
                        $oLoan->setLoaCompletedDate( $paidDate );
                        $oLoan->setLoaResetRateToInterestByDefault(0);
                        $em->persist($oLoan);
                        $em->flush();
                    }
                        
                }

                //$amountRequested = $oLoan->getLoaAmount();

                //$remainingAmount = 

                //exit("<br> Fin");
                $em->getConnection()->commit();

                

                $msg = "Success";
                $this->session->getFlashBag()->add("success", $msg);
                return $this->redirectToRoute('loanpayment_quotasHistory', array('loaId' => $loanId ));
            }
            catch (Exception $e) {
                $this->session->getFlashBag()->add("error", $e);
                $em->getConnection()->rollBack();
                throw $e;
            }

            
        }

        //$loanType =  $oLoan->getLoc()->getLocKey();
        if( $loanType == "active_rate" )
        {
            $template = "active_rate";
        }
        else
        {
            $template = "inactive_rate";
        }    

        $oPaymentType = $em->getRepository('AppBundle:LoanPaymentType')->findAll();
        return $this->render("app/loanpayment/".$template.".html.twig", array(
            'loanPayment' => $loanPayment,
            'form' => $form->createView(),
            'loan' => $oLoan,
            'paymentType'=> $oPaymentType
        ));
    }

    //This is only for loans with no insterest rate
    public function doPaymentNoRateAction( Request $request )
    {
        $loanId = $request->get("loanId");
        $paymentType = $request->get("type");
        $paymentId = $request->get("paymentId");
        $amount = trim($request->get("amount"));
        $date = trim($request->get("date"));
        $note = trim($request->get("note"));
        $userId = $this->getUser()->getUsrId();
        $em = $this->getDoctrine()->getManager();

        if( isset($loanId) && $loanId > 0 /*&&  $amount > 0 && $date != ""*/ )
        {
            $oLoan= $em->getRepository('AppBundle:Loan')->findOneBy( array( "loaId"=> $loanId, "loaActive"=>1) );
            if( $oLoan )
            {
                if( $userId != $oLoan->getCli()->getUsr()->getUsrId() )
                {
                    throw new AccessDeniedHttpException("Access Denied");
                }
                else
                {
                    $oPayment = $em->getRepository('AppBundle:LoanPayment')->findOneBy( array( "loa"=> $loanId, "lpaId"=>$paymentId ) );
                    if( $oPayment )
                    {
                        if( $loanId != $oPayment->getLoa()->getLoaId() )
                        {
                            throw new AccessDeniedHttpException("Access Denied");
                        }
                        else
                        {
                            $srvLoan = $this->get('srv_Loans');  
                            $checkPayments = $srvLoan->checkPaymentsPerLoan($loanId);

                            if( isset($amount) && $amount > 0 )
                            {
                                $oPayment->setLpaTotalAmountPaid($amount);
                                //logic here
                                
                                $amountRequested = $oLoan->getLoaAmount();
                                $checkPayments[0]['paidTotal'];

                                if( ($checkPayments[0]['paidTotal'] < $amountRequested) ||  ($amount < $amountRequested) )
                                {
                                    //$t = $checkPayments[0]['paidTotal'] +  $amount;
                                    $t = $checkPayments[0]['paidCapital'] +  $amount;
                                    if($t < $amountRequested)
                                    {
                                        $oPayment->setLpaPaidCapital($amount);
                                    }
                                    else
                                    {
                                        $payInterest = ($t - $amountRequested);
                                        $payCapital = ($amount - $payInterest);
                                        if( $payCapital > 0 ){
                                            $oPayment->setLpaPaidCapital($payCapital);
                                        }
                                        if( $payInterest > 0 ){
                                            $oPayment->setLpaPaidRateInterest( ($amount-$payCapital)/*$payInterest*/);
                                        }
                                        
                                    }
                                    
                                }else{
                                    $oPayment->setLpaPaidRateInterest($amount);
                                }
                            }

                            if( isset($date) && $date != "" )
                            {
                                $oPayment->setLpaPaidDate( new \datetime($date));
                            }

                            if( isset($note) && $note != "" )
                            {
                                $oPayment->setLpaNote($note);
                            }

                            $oPaymentType = $em->getRepository('AppBundle:LoanPaymentType')->findOneBy( array( "lptKey"=> $paymentType ) );
                            if( $oPaymentType )
                            {
                                $oPayment->setLpt( $oPaymentType );    
                                $em->persist($oPayment);
                                $flus = $em->flush();
                                if($flus == null )
                                {
                                    $msg = "Record processed successfully";
                                    

                                    //$srvLoan = $this->get('srv_Loans');  
                                    $checkPayments = $srvLoan->checkPaymentsPerLoan($loanId);  
                                    if( $checkPayments )
                                    {
                                        if( $checkPayments[0]['paidTotal'] >= $checkPayments[0]['currentAmount'] )
                                        {
                                            //$em->getRepository('AppBundle:LoanPaymentType')->findOneBy( array( "lptKey"=> $paymentType ) );
                                            $oLoan->setLoaCompleted(1);
                                            $oLoan->setLoaCompletedDate( new \datetime($date) );
                                        }
                                        else
                                        {
                                            $oLoan->setLoaCompleted(0);
                                        }
                                        $em->persist($oLoan);
                                        $flus = $em->flush();
                                    }
                                    //$msg .= "paidTotal:".$checkPayments[0]['paidTotal']." - currentAmount:".$checkPayments[0]['currentAmount'];
                                    $this->session->getFlashBag()->add("success", $msg);

                                    exit("success");
                                }else{
                                    //$msg = "There was an error to try to do the payment";
                                    //$this->session->getFlashBag()->add("error", $msg);
                                    exit("Error");
                                }
                            }else{
                                throw new NotFoundHttpException("Bad parameters");
                            }
                            
                        }
                    }
                }
            }
        }
        else
        {
            throw new NotFoundHttpException("Bad parameters");
        }

    }

    public function calculatePaymentWithRate()
    {
        
    }

    public function quotasHistoryAction(Request $request)
    {
        $loanId = $request->get("loaId");
        
        $userId = $this->getUser()->getUsrId();
        $em = $this->getDoctrine()->getManager();

        if( isset($loanId) && $loanId > 0 )
        {
            $oLoan= $em->getRepository('AppBundle:Loan')->findOneBy( array( "loaId"=> $loanId, "loaActive"=>1) );
            if( $oLoan )
            {
                if( $userId != $oLoan->getCli()->getUsr()->getUsrId() )
                {
                    throw new AccessDeniedHttpException("Access Denied");
                }
                else
                {
                    $oPayments= $em->getRepository('AppBundle:LoanPayment')->findBy( array( "loa"=> $loanId, "laa"=>NULL) );
                }
            }
        }
        else
        {
            throw new NotFoundHttpException("Bad parameters, select a loan to see the details");
        }
        
        //exit();
        return $this->render('app/loanpayment/quotasHistory.html.twig', array(
            //'loanPayment' => $loanPayment,
            //'form' => $form->createView(),
            'payments' => $oPayments,
            "loan"=>$oLoan
        ));
    }

    public function setHistoricalAmountsAction()
    {
        date_default_timezone_set("UTC");
        $em = $this->getDoctrine()->getManager();

        $oHistoricalAmounts = $em->getRepository('AppBundle:LoanHistoricalAmounts')->findBy( array("lhaActive"=>1) );
        //echo count($oHistoricalAmounts);
        if( count($oHistoricalAmounts) == 0 )
        {
            // echo "aqui";
            $oLoans = $em->getRepository('AppBundle:Loan')->findBy( array("loaActive"=> 1 ));
            if( $oLoans )
            {
                $total =  count($oLoans);
                $pros = 0;
                $em->getConnection()->beginTransaction(); // suspend auto-commit
                try {
                    foreach( $oLoans as $item)
                    {

                        $oHistoricalAmount = new LoanHistoricalAmounts();
                        $oLoan = $em->getRepository('AppBundle:Loan')->find( $item->getLoaId() );
                        $oHistoricalAmount->setLoa( $oLoan );
                        $oHistoricalAmount->setLhaAmount( $item->getLoaAmount() );
                        $oHistoricalAmount->setLhaCreated( new \datetime("now") );
                        $em->persist($oHistoricalAmount);			
                        $flush = $em->flush();
                        if( $flush == null)
                        {
                            $pros++;
                        }

                       
                    }
                    if( $total == $pros)
                    {
                        $em->getConnection()->commit();
                        echo "Ok!. Registros procesados: ".$total;
                    }
                    else
                    {
                        echo "No se pudo hacer las actualizaciones debido a que no se pudieron crear todos los registros";
                    }
                    
                }catch (Exception $e) {
                    $em->getConnection()->rollBack();
                    throw $e;
                }
            }
            else
            {
                echo "No se encontraron prestamos a procesar";
            }
            
        }
        else
        {
            echo "Datos no procesados debido a que ya existen datos";
        }
        
        
        exit("<br><br>Fin");
    }

    /**
     * Finds and displays a loanPayment entity.
     *
     */
    public function showAction(LoanPayment $loanPayment)
    {
        $deleteForm = $this->createDeleteForm($loanPayment);

        return $this->render('app/loanpayment/show.html.twig', array(
            'loanPayment' => $loanPayment,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing loanPayment entity.
     *
     */
    public function editAction(Request $request, LoanPayment $loanPayment)
    {
        $deleteForm = $this->createDeleteForm($loanPayment);
        $editForm = $this->createForm('AppBundle\Form\LoanPaymentType', $loanPayment);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('loanpayment_edit', array('lpaId' => $loanPayment->getLpaid()));
        }

        return $this->render('app/loanpayment/edit.html.twig', array(
            'loanPayment' => $loanPayment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a loanPayment entity.
     *
     */
    public function deleteAction(Request $request, LoanPayment $loanPayment)
    {
        $form = $this->createDeleteForm($loanPayment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($loanPayment);
            $em->flush();
        }

        return $this->redirectToRoute('loanpayment_index');
    }

    /**
     * Creates a form to delete a loanPayment entity.
     *
     * @param LoanPayment $loanPayment The loanPayment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LoanPayment $loanPayment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('loanpayment_delete', array('lpaId' => $loanPayment->getLpaid())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
