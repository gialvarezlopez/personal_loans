<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

//use AppBundle\Entity\User;



class UsersControlAppController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $userId = $this->getUser()->getUsrId();

        $users = $em->getRepository('AppBundle:User')->findBy( array("usrRole"=>"ROLE_USER")  );

        //$contactForm = $this->getUser()->getUsrNotificationContactForm();
        return $this->render('app/usersControlApp/index.html.twig', array(
            'users' => $users,
        ));
    }

    public function checkUserControlEventAction( Request $request )
    {
        
        $action = $request->get('action');
        $user_id = $request->get('user_id');
        $em   = $this->getDoctrine()->getManager();
        $oUser = $em->getRepository('AppBundle:User')->findOneBy( array("usrId"=> $user_id, "usrRole"=>"ROLE_USER") );
        if( $oUser )
        {
            $update = false;
            switch($action)
            {
                case "validateToken":
                    $oUser->setUsrStatus(1);
                    $update = true;
                    break;
                case "activeUser":
                    $oUser->setUsrActive(1);
                    $update = true;
                    break;
                case "deactivateUser":
                    $oUser->setUsrActive(0);
                    $update = true;
                    break; 
                case "setFreeMembership":
                    $membership = $this->get('srv_PayerTransactions');			
                    $res = $membership->freePaymentAccount( $user_id );
                    $json = new JsonResponse($res);
                    return ($json);
                    break;            
            }

            if($update == true)
            {
                $oUser->setUsrStatus(1);
                $oUser->setUsrUpdated( new \datetime("now") );
                $em->persist($oUser);
                $flush = $em->flush();
                if($flush == null )
                {
                    //echo 1;
                    return  new JsonResponse(array("res"=>1));
                }
            }

            return  new JsonResponse(array("res"=>0));
        }

        return  new JsonResponse(array("res"=>0));
    }

    public function changepasswordAction(Request $request) {
        $session = $request->getSession();

        if($request->getMethod() == 'POST') {
            $old_pwd = $request->get('currentPass');
            $new_pwd = $request->get('newPass');
            $repeatPass = $request->get("repeatPass");
            //echo $user = $this->getUser();

            $user= $this->get('security.token_storage')->getToken()->getUser();
            //$user->getPassword();

            $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
            $old_pwd_encoded = $encoder->encodePassword($old_pwd, $user->getSalt());
            //echo $old_pwd_encoded;
            if($user->getPassword() != $old_pwd_encoded) {
                $session->getFlashBag()->set('error', "Wrong old password!");
            } else {
                if( $new_pwd != $repeatPass )
                {
                    $session->getFlashBag()->set('error', "The passwords do not match, try again!");
                }
                else if( $old_pwd == "" || $repeatPass == "" )
                {
                    $session->getFlashBag()->set('error', "The passwords can not be empty");
                }
                else
                {
                    $new_pwd_encoded = $encoder->encodePassword($new_pwd, $user->getSalt());
                    $user->setUsrPassword($new_pwd_encoded);
                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($user);
                    $manager->flush();
                    $session->getFlashBag()->set('success', "Password changed successfully!");
                }
            }
        }
        return $this->redirectToRoute('settings_show');
    }

    public function setNotificationsAction(Request $request)
    {
        $session = $request->getSession();
        if($request->getMethod() == 'POST')
        {
            $contactForm = ( ($request->get('contactForm'))?1:0) ;
            $payment = ( ($request->get('payment'))?1:0);
            $user= $this->get('security.token_storage')->getToken()->getUser();
            $user->setUsrNotificationContactForm( $contactForm);
            $user->setUsrNotificationPayment( $payment );
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
            $session->getFlashBag()->set('success', "Notifications updated successfully!");
        }
        return $this->redirectToRoute('settings_show');
    }

}
