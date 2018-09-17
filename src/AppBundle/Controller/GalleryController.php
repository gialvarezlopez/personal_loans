<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//use AppBundle\Entity\User;
use AppBundle\Entity\Gallery;
use AppBundle\Form\GalleryType;

/**
 * Gallery controller.
 *
 */
class GalleryController extends Controller
{

    //var $pathImages = __DIR__.'/../../web/uploads/';
    /**
     * Lists all Gallery entities.
     *
     */
    public function indexAction( Request $request )
    {
        $em = $this->getDoctrine()->getManager();
        $userId = $this->getUser()->getUsrId();

        $loanId = $request->get("loanId");
        $oLoan = array();
        if( isset($loanId) && $loanId != "" &&  $loanId > 0 )
        {
            //$userId = $this->getUser()->getUsrId();
            $oLoan = $em->getRepository('AppBundle:Loan')->findOneBy( array("loaId"=> $loanId) );
            if( $oLoan )
            {
                if( $userId != $oLoan->getCli()->getUsr()->getUsrId() )
                {
                    throw new AccessDeniedHttpException("Access Denied");
                }
                else
                {
                    $galleries = $em->getRepository('AppBundle:Gallery')->findBy( array("loa"=> $loanId) );
                }
            }
            else
            {
                throw new NotFoundHttpException("Loan was not found");
            }
   
        }
        else
        {
            $galleries = $em->getRepository('AppBundle:Gallery')->findBy( array("usr"=> $userId) );
        }
        


        return $this->render('app/gallery/index.html.twig', array(
            'galleries' => $galleries,
            "loan"=>$oLoan
        ));
    }

    /**
     * Creates a new Gallery entity.
     *
     */
    public function newAction(Request $request)
    {
        $file = $request->files->get('images');
        $loanId = $request->get("loanId");
        if (!$file) {
            echo json_encode(['error'=>'No files found for upload.']); 
            exit(); 
        }

        $ext=$file[0]->guessExtension();
        $file_name=md5(uniqid()).".".$ext;

        if( !$file[0]->move("uploads", $file_name) )
        {
            echo json_encode(['error'=>'No files found for upload.']); 
        }else{
            
            $em = $this->getDoctrine()->getManager();
            
            $userId = $this->getUser()->getUsrId();
            $gallery = new Gallery();
            $gallery->setGaName($file_name);

            if( isset( $loanId ) && $loanId !="" )
            {
                $oLoan = $em->getRepository('AppBundle:Loan')->findOneBy( array("loaId"=> $loanId) );
                if( $oLoan )
                {
                    if( $userId != $oLoan->getCli()->getUsr()->getUsrId() )
                    {
                        //throw new AccessDeniedHttpException("Access Denied");
                        echo json_encode(['error'=>"Access Denied"]);
                        exit();
                    }
                    else
                    {
                        $gallery->setLoa($oLoan);
                    }
                }
                else
                {
                    echo json_encode(['error'=>"There was an error"]);
                    exit();
                }
                
            }
            else
            {
                $oUser = $em->getRepository('AppBundle:User')->findOneBy( array("usrId"=> $userId) );
                $gallery->setUsr($oUser);
            }
            
           
            if( isset($oUser) || isset($oLoan) )
            {
                $em->persist($gallery);
                $flush = $em->flush();
                if( $flush == null )
                {
                    echo 1;
                }
                else
                {
                    echo json_encode(['error'=>'No record was no saved.']);
                }
            }
            
        }
        exit();
                    
    }

    public function deleteAction( Request $request, Gallery $gallery ){
        
        $id = $request->get("id");
        $key = $request->get("key");
        $loanId = $request->get("loanId");
        $userId = $this->getUser()->getUsrId();

        $em = $this->getDoctrine()->getManager();
		if($id && $key)
		{
            if( isset( $loanId ) && $loanId !="" )
            {
                $oLoan = $em->getRepository('AppBundle:Loan')->findOneBy( array("loaId"=> $loanId) );
                if( $oLoan )
                {
                    if( $userId != $oLoan->getCli()->getUsr()->getUsrId() )
                    {
                        //throw new AccessDeniedHttpException("Access Denied");
                        echo json_encode(['error'=>"Access Denied"]);
                    }
                    else
                    {
                        
                        $filter = " AND tb.loa=".$loanId;
                    }
                }
                else
                {
                    echo json_encode(['error'=>"There was an error"]);
                }
                
            }
            else
            {
                $filter = " AND tb.usr=".$userId;
            }
            //echo $filter;
            if( $filter !="" )
            {
                
                $q = $em->createQuery("DELETE FROM AppBundle\Entity\Gallery tb WHERE tb.gaId = ".$id ." AND tb.gaName='".$key."'  $filter " );
                if( $q->execute() ){
                    @unlink( __DIR__.'/../../../web/uploads/'.$key);
                    echo 1;
                }else{
                    echo json_encode(['error'=>"Record couldn't be deleted."]);
                }
            }
		}else{
            echo json_encode(['error'=>'Select a file to delete it.']);
        }
        exit();
    }
    
    public function readFileTxtAction( Request $request )
    {
        //$galleries = "Hola";
        $txt = $request->get("txt");
        $file = __DIR__.'/../../../web/uploads/'.$txt;
        $file_exists = file_exists($file);
		if( $file_exists )
		{
            $info = trim(file_get_contents($file));
            //echo $file;
		}else{
            $info = "The file no exist";
        }
        return $this->render('app/gallery/readTxt.html.twig', array(
            'info' => ".$info.",
        ));
    }

}
