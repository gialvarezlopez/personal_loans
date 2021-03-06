<?php

namespace AppBundle\Entity;
use \Symfony\Component\Security\Core\User\UserInterface;
use \Symfony\Component\Security\Core\User\AdvancedUserInterface;


/**
 * User
 */
//class User implements UserInterface
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @var integer
     */
    private $usrId;

    /**
     * @var string
     */
    private $usrUsername;

    /**
     * @var string
     */
    private $usrPassword;

    /**
     * @var string
     */
    private $usrEmail;

    /**
     * @var string
     */
    private $usrRole;

    /**
     * @var boolean
     */
    private $usrStatus = false;

    /**
     * @var string
     */
    private $usrTokenConfirmation;

    /**
     * @var \DateTime
     */
    private $usrCreated = 'CURRENT_TIMESTAMP';

    /**
     * @var \DateTime
     */
    private $usrUpdated = 'CURRENT_TIMESTAMP';

    /**
     * @var boolean
     */
    private $usrActive = true;


    /**
     * Get usrId
     *
     * @return integer
     */
    public function getUsrId()
    {
        return $this->usrId;
    }

    /**
     * Set usrUsername
     *
     * @param string $usrUsername
     *
     * @return User
     */
    public function setUsrUsername($usrUsername)
    {
        $this->usrUsername = $usrUsername;

        return $this;
    }

    /**
     * Get usrUsername
     *
     * @return string
     */
    public function getUsrUsername()
    {
        return $this->usrUsername;
    }

    /**
     * Set usrPassword
     *
     * @param string $usrPassword
     *
     * @return User
     */
    public function setUsrPassword($usrPassword)
    {
        $this->usrPassword = $usrPassword;

        return $this;
    }

    /**
     * Get usrPassword
     *
     * @return string
     */
    public function getUsrPassword()
    {
        return $this->usrPassword;
    }

    /**
     * Set usrEmail
     *
     * @param string $usrEmail
     *
     * @return User
     */
    public function setUsrEmail($usrEmail)
    {
        $this->usrEmail = $usrEmail;

        return $this;
    }

    /**
     * Get usrEmail
     *
     * @return string
     */
    public function getUsrEmail()
    {
        return $this->usrEmail;
    }

    /**
     * Set usrRole
     *
     * @param string $usrRole
     *
     * @return User
     */
    public function setUsrRole($usrRole)
    {
        $this->usrRole = $usrRole;

        return $this;
    }

    /**
     * Get usrRole
     *
     * @return string
     */
    public function getUsrRole()
    {
        return $this->usrRole;
    }

    /**
     * Set usrStatus
     *
     * @param boolean $usrStatus
     *
     * @return User
     */
    public function setUsrStatus($usrStatus)
    {
        $this->usrStatus = $usrStatus;

        return $this;
    }

    /**
     * Get usrStatus
     *
     * @return boolean
     */
    public function getUsrStatus()
    {
        return $this->usrStatus;
    }

    /**
     * Set usrTokenConfirmation
     *
     * @param string $usrTokenConfirmation
     *
     * @return User
     */
    public function setUsrTokenConfirmation($usrTokenConfirmation)
    {
        $this->usrTokenConfirmation = $usrTokenConfirmation;

        return $this;
    }

    /**
     * Get usrTokenConfirmation
     *
     * @return string
     */
    public function getUsrTokenConfirmation()
    {
        return $this->usrTokenConfirmation;
    }

    /**
     * Set usrCreated
     *
     * @param \DateTime $usrCreated
     *
     * @return User
     */
    public function setUsrCreated($usrCreated)
    {
        $this->usrCreated = $usrCreated;

        return $this;
    }

    /**
     * Get usrCreated
     *
     * @return \DateTime
     */
    public function getUsrCreated()
    {
        return $this->usrCreated;
    }

    /**
     * Set usrUpdated
     *
     * @param \DateTime $usrUpdated
     *
     * @return User
     */
    public function setUsrUpdated($usrUpdated)
    {
        $this->usrUpdated = $usrUpdated;

        return $this;
    }

    /**
     * Get usrUpdated
     *
     * @return \DateTime
     */
    public function getUsrUpdated()
    {
        return $this->usrUpdated;
    }

    /**
     * Set usrActive
     *
     * @param boolean $usrActive
     *
     * @return User
     */
    public function setUsrActive($usrActive)
    {
        $this->usrActive = $usrActive;

        return $this;
    }

    /**
     * Get usrActive
     *
     * @return boolean
     */
    public function getUsrActive()
    {
        return $this->usrActive;
    }
    
    //AUTH
    public function getPassword() {
        return $this->usrPassword;
    }
    public function getUsername(){
        return $this->usrEmail;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return array($this->usrRole);
    }

    public function eraseCredentials()
    {

    }

    public function __toString() {
        if(is_null($this->usrRole)) {
            return 'Ajua';
        }
        return $this->usrRole;
    } 
    
    
    public function __construct()
    {
        $this->usrCreated = new \DateTime();
    }

    //END AUTH
    /**
     * @var boolean
     */
    private $usrNotificationContactForm = '1';

    /**
     * @var boolean
     */
    private $usrNotificationPayment = '1';


    /**
     * Set usrNotificationContactForm
     *
     * @param boolean $usrNotificationContactForm
     *
     * @return User
     */
    public function setUsrNotificationContactForm($usrNotificationContactForm)
    {
        $this->usrNotificationContactForm = $usrNotificationContactForm;

        return $this;
    }

    /**
     * Get usrNotificationContactForm
     *
     * @return boolean
     */
    public function getUsrNotificationContactForm()
    {
        return $this->usrNotificationContactForm;
    }

    /**
     * Set usrNotificationPayment
     *
     * @param boolean $usrNotificationPayment
     *
     * @return User
     */
    public function setUsrNotificationPayment($usrNotificationPayment)
    {
        $this->usrNotificationPayment = $usrNotificationPayment;

        return $this;
    }

    /**
     * Get usrNotificationPayment
     *
     * @return boolean
     */
    public function getUsrNotificationPayment()
    {
        return $this->usrNotificationPayment;
    }
    /**
     * @var boolean
     */
    private $usrForgotPassword = '0';


    /**
     * Set usrForgotPassword
     *
     * @param boolean $usrForgotPassword
     *
     * @return User
     */
    public function setUsrForgotPassword($usrForgotPassword)
    {
        $this->usrForgotPassword = $usrForgotPassword;

        return $this;
    }

    /**
     * Get usrForgotPassword
     *
     * @return boolean
     */
    public function getUsrForgotPassword()
    {
        return $this->usrForgotPassword;
    }



    //===============================================================
    //Campo extra para validar
    //===============================================================
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->usrStatus;
    }

    // serialize and unserialize must be updated - see below
    public function serialize()
    {
        
        return serialize(array(
            // ...
            $this->usrStatus,
            $this->usrId,
            $this->usrEmail,
        ));
        
    }
    public function unserialize($serialized)
    {
        
        list (
            // ...
            $this->usrStatus,
            $this->usrId,
            $this->usrEmail,
        ) = unserialize($serialized);
        
    }
    //===============================================================
    //Fin campo extra
    //===============================================================
    /**
     * @var boolean
     */
    private $usrShow = '1';


    /**
     * Set usrShow
     *
     * @param boolean $usrShow
     *
     * @return User
     */
    public function setUsrShow($usrShow)
    {
        $this->usrShow = $usrShow;

        return $this;
    }

    /**
     * Get usrShow
     *
     * @return boolean
     */
    public function getUsrShow()
    {
        return $this->usrShow;
    }
    /**
     * @var string
     */
    private $usrProfileImage;


    /**
     * Set usrProfileImage
     *
     * @param string $usrProfileImage
     *
     * @return User
     */
    public function setUsrProfileImage($usrProfileImage)
    {
        $this->usrProfileImage = $usrProfileImage;

        return $this;
    }

    /**
     * Get usrProfileImage
     *
     * @return string
     */
    public function getUsrProfileImage()
    {
        return $this->usrProfileImage;
    }
    /**
     * @var integer
     */
    private $stId;


    /**
     * Set stId
     *
     * @param integer $stId
     *
     * @return User
     */
    public function setStId($stId)
    {
        $this->stId = $stId;

        return $this;
    }

    /**
     * Get stId
     *
     * @return integer
     */
    public function getStId()
    {
        return $this->stId;
    }
    /**
     * @var \DateTime
     */
    private $usrLastLogin;


    /**
     * Set usrLastLogin
     *
     * @param \DateTime $usrLastLogin
     *
     * @return User
     */
    public function setUsrLastLogin($usrLastLogin)
    {
        $this->usrLastLogin = $usrLastLogin;

        return $this;
    }

    /**
     * Get usrLastLogin
     *
     * @return \DateTime
     */
    public function getUsrLastLogin()
    {
        return $this->usrLastLogin;
    }
    /**
     * @var string
     */
    private $usrDateFormat = 'mm/dd/yyyy';


    /**
     * Set usrDateFormat
     *
     * @param string $usrDateFormat
     *
     * @return User
     */
    public function setUsrDateFormat($usrDateFormat)
    {
        $this->usrDateFormat = $usrDateFormat;

        return $this;
    }
    /**
     * Get usrDateFormat
     *
     * @return string
     */
    public function getUsrDateFormat()
    {
        return $this->usrDateFormat;
    }
}
