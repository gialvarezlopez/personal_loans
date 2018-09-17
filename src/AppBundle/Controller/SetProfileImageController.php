<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
//use \Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * MedicalDetail controller.
 *
 */
class SetProfileImageController extends Controller
{
    public function getUploadRootDir()
	{
		// the absolute directory path where uploaded
		// documents should be saved
		return  $this->get('kernel')->getRootDir().'/../web/uploads/'; //__DIR__.'/../../../web/uploads/';
	}
    /**
     * Lists all MedicalDetail entities.
     *
     */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $userId = $this->getUser()->getUsrId();

        $clientId = $request->get("clientId");
        
        $image = "";
        if( isset($clientId) && $clientId != "" &&  $clientId > 0 )
        {
            $oProfile = $em->getRepository('AppBundle:Client')->findOneBy( array("cliId"=>$clientId, "usr"=> $userId) );
            if( $oProfile )
            {
                $image = $oProfile->getCliProfileImage();
            }
            else
            {
                throw new AccessDeniedHttpException("Access Denied");
            }
        }
        else
        {
            $oProfile = $em->getRepository('AppBundle:User')->findOneBy( array( "usrId"=> $userId) );
            if( $oProfile )
            {
                $image = $oProfile->getUsrProfileImage();
            }
        }
        
   
        return $this->render('app/setProfileImage/index.html.twig', array(
            'image' => $image,
            "profile"=>$oProfile
          
        ));
    }

    public function uploadAction( Request $request )
    {
        date_default_timezone_set("UTC");
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();
        $userId = $this->getUser()->getUsrId();
        

        $clientId = $request->get("clientId");

        $croped_image =  $categoryId = $request->get("image");
        if( isset($croped_image) && $croped_image != "" && $request->isMethod('post') )
        {
            try
            {
                list($type, $croped_image) = explode(';', $croped_image);
                list(, $croped_image)      = explode(',', $croped_image);
                $croped_image = base64_decode($croped_image);
                $image_name = uniqid().time().'.png';

                if( isset($clientId) && $clientId != "" )
                {
                    $oProfile = $em->getRepository('AppBundle:Client')->findOneBy( array("cliId"=>$clientId, "usr"=> $userId) );
                    if( $oProfile)
                    {
                        if(  $oProfile->getCliProfileImage() != ""  )
                        {
                            $oldImage = $oProfile->getCliProfileImage();
                        }

                        $oProfile->setCliProfileImage($image_name);
                        $oProfile->setCliUpdated( new \DateTime() );
                        
                    }
                    else
                    {
                        throw new AccessDeniedHttpException("Access Denied");
                    }

                    
                }
                else
                {
                    $oProfile = $em->getRepository('AppBundle:User')->findOneBy( array( "usrId"=> $userId) );
                    if( $oProfile)
                    {
                        if( $oProfile->getUsrProfileImage() != ""  )
                        {
                            $oldImage = $oProfile->getUsrProfileImage();
                        }

                        $oProfile->setUsrProfileImage($image_name);
                    }                   
                }
                /*
                    //$oProfile = $em->getRepository('AppBundle:MedicalDetail')->findOneBy( array( "usr"=> $userId) );

                    if( $oProfile && $oProfile->getMdProfileImage() != "" )
                    {
                        $oldImage = $oProfile->getMdProfileImage();
                    }
                */

                
                // upload cropped image to server 
                if( file_put_contents($this->getUploadRootDir().$image_name, $croped_image) )
                {
                    //$oProfile->setMdProfileImage($image_name);
                    $em->persist($oProfile);
                    $flush = $em->flush();
                    if( $flush == null)
                    {
                        if( isset($oldImage) && $oldImage != "" )
                        {
                            $this->fileExist( $this->getUploadRootDir().$oldImage);
                            $status = "Image was uploaded successfully";
                            $session->getFlashBag()->add("success", $status);
                            
                        }

                        echo 1;
                    }
                    else
                    {
                        $this->fileExist( $this->getUploadRootDir().$oProfile->getMdProfileImage());
                        echo 0;
                    }
                    
                }
                else
                {
                    
                }

                
            }
            catch (\Exception $e)
            {
                throw $e;
                //echo ($e->getMessage());
            }
        }
        else
        {
            throw new Exception('Error');
        }
        exit();
    }

	
	
	public function fileExist($path)
	{
		if( !empty($path) )
		{
			$fullPath = $path;
			$file_exists = file_exists($fullPath);
			if( $file_exists )
			{
				@unlink($fullPath);
			}else{
                echo "fileexiste";
            }
		}		
	}
	

}
