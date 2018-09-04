<?php

namespace AppBundle\Entity;

/**
 * Client
 */
class Client
{
    /**
     * @var integer
     */
    private $cliId;

    /**
     * @var string
     */
    private $cliFirstName;

    /**
     * @var string
     */
    private $cliMiddleName;

    /**
     * @var string
     */
    private $cliFirstSurname;

    /**
     * @var string
     */
    private $cliSecondSurname;

    /**
     * @var string
     */
    private $cliIdNumber;

    /**
     * @var string
     */
    private $cliIdType;

    /**
     * @var string
     */
    private $cliAddress;

    /**
     * @var string
     */
    private $cliPhone1;

    /**
     * @var string
     */
    private $cliPhone2;

    /**
     * @var string
     */
    private $cliEmail;

    /**
     * @var string
     */
    private $cliNote;

    /**
     * @var boolean
     */
    private $cliActive = true;


    /**
     * Get cliId
     *
     * @return integer
     */
    public function getCliId()
    {
        return $this->cliId;
    }

    /**
     * Set cliFirstName
     *
     * @param string $cliFirstName
     *
     * @return Client
     */
    public function setCliFirstName($cliFirstName)
    {
        $this->cliFirstName = $cliFirstName;

        return $this;
    }

    /**
     * Get cliFirstName
     *
     * @return string
     */
    public function getCliFirstName()
    {
        return $this->cliFirstName;
    }

    /**
     * Set cliMiddleName
     *
     * @param string $cliMiddleName
     *
     * @return Client
     */
    public function setCliMiddleName($cliMiddleName)
    {
        $this->cliMiddleName = $cliMiddleName;

        return $this;
    }

    /**
     * Get cliMiddleName
     *
     * @return string
     */
    public function getCliMiddleName()
    {
        return $this->cliMiddleName;
    }

    /**
     * Set cliFirstSurname
     *
     * @param string $cliFirstSurname
     *
     * @return Client
     */
    public function setCliFirstSurname($cliFirstSurname)
    {
        $this->cliFirstSurname = $cliFirstSurname;

        return $this;
    }

    /**
     * Get cliFirstSurname
     *
     * @return string
     */
    public function getCliFirstSurname()
    {
        return $this->cliFirstSurname;
    }

    /**
     * Set cliSecondSurname
     *
     * @param string $cliSecondSurname
     *
     * @return Client
     */
    public function setCliSecondSurname($cliSecondSurname)
    {
        $this->cliSecondSurname = $cliSecondSurname;

        return $this;
    }

    /**
     * Get cliSecondSurname
     *
     * @return string
     */
    public function getCliSecondSurname()
    {
        return $this->cliSecondSurname;
    }

    /**
     * Set cliIdNumber
     *
     * @param string $cliIdNumber
     *
     * @return Client
     */
    public function setCliIdNumber($cliIdNumber)
    {
        $this->cliIdNumber = $cliIdNumber;

        return $this;
    }

    /**
     * Get cliIdNumber
     *
     * @return string
     */
    public function getCliIdNumber()
    {
        return $this->cliIdNumber;
    }

    /**
     * Set cliIdType
     *
     * @param string $cliIdType
     *
     * @return Client
     */
    public function setCliIdType($cliIdType)
    {
        $this->cliIdType = $cliIdType;

        return $this;
    }

    /**
     * Get cliIdType
     *
     * @return string
     */
    public function getCliIdType()
    {
        return $this->cliIdType;
    }

    /**
     * Set cliAddress
     *
     * @param string $cliAddress
     *
     * @return Client
     */
    public function setCliAddress($cliAddress)
    {
        $this->cliAddress = $cliAddress;

        return $this;
    }

    /**
     * Get cliAddress
     *
     * @return string
     */
    public function getCliAddress()
    {
        return $this->cliAddress;
    }

    /**
     * Set cliPhone1
     *
     * @param string $cliPhone1
     *
     * @return Client
     */
    public function setCliPhone1($cliPhone1)
    {
        $this->cliPhone1 = $cliPhone1;

        return $this;
    }

    /**
     * Get cliPhone1
     *
     * @return string
     */
    public function getCliPhone1()
    {
        return $this->cliPhone1;
    }

    /**
     * Set cliPhone2
     *
     * @param string $cliPhone2
     *
     * @return Client
     */
    public function setCliPhone2($cliPhone2)
    {
        $this->cliPhone2 = $cliPhone2;

        return $this;
    }

    /**
     * Get cliPhone2
     *
     * @return string
     */
    public function getCliPhone2()
    {
        return $this->cliPhone2;
    }

    /**
     * Set cliEmail
     *
     * @param string $cliEmail
     *
     * @return Client
     */
    public function setCliEmail($cliEmail)
    {
        $this->cliEmail = $cliEmail;

        return $this;
    }

    /**
     * Get cliEmail
     *
     * @return string
     */
    public function getCliEmail()
    {
        return $this->cliEmail;
    }

    /**
     * Set cliNote
     *
     * @param string $cliNote
     *
     * @return Client
     */
    public function setCliNote($cliNote)
    {
        $this->cliNote = $cliNote;

        return $this;
    }

    /**
     * Get cliNote
     *
     * @return string
     */
    public function getCliNote()
    {
        return $this->cliNote;
    }

    /**
     * Set cliActive
     *
     * @param boolean $cliActive
     *
     * @return Client
     */
    public function setCliActive($cliActive)
    {
        $this->cliActive = $cliActive;

        return $this;
    }

    /**
     * Get cliActive
     *
     * @return boolean
     */
    public function getCliActive()
    {
        return $this->cliActive;
    }
    /**
     * @var \DateTime
     */
    private $cliCreated;

    /**
     * @var \DateTime
     */
    private $cliUpdated;

    /**
     * @var \AppBundle\Entity\User
     */
    private $usr;


    /**
     * Set cliCreated
     *
     * @param \DateTime $cliCreated
     *
     * @return Client
     */
    public function setCliCreated($cliCreated)
    {
        $this->cliCreated = $cliCreated;

        return $this;
    }

    /**
     * Get cliCreated
     *
     * @return \DateTime
     */
    public function getCliCreated()
    {
        return $this->cliCreated;
    }

    /**
     * Set cliUpdated
     *
     * @param \DateTime $cliUpdated
     *
     * @return Client
     */
    public function setCliUpdated($cliUpdated)
    {
        $this->cliUpdated = $cliUpdated;

        return $this;
    }

    /**
     * Get cliUpdated
     *
     * @return \DateTime
     */
    public function getCliUpdated()
    {
        return $this->cliUpdated;
    }

    /**
     * Set usr
     *
     * @param \AppBundle\Entity\User $usr
     *
     * @return Client
     */
    public function setUsr(\AppBundle\Entity\User $usr = null)
    {
        $this->usr = $usr;

        return $this;
    }

    /**
     * Get usr
     *
     * @return \AppBundle\Entity\User
     */
    public function getUsr()
    {
        return $this->usr;
    }
}
