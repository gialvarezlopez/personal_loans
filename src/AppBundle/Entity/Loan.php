<?php

namespace AppBundle\Entity;

/**
 * Loan
 */
class Loan
{
    /**
     * @var integer
     */
    private $loaId;

    /**
     * @var string
     */
    private $loaCode;

    /**
     * @var float
     */
    private $loaAmount;

    /**
     * @var float
     */
    private $loaReturnAmount;

    /**
     * @var float
     */
    private $loaRateInterest;

    /**
     * @var float
     */
    private $loaRateInterestByDefault;

    /**
     * @var string
     */
    private $loaDescription;

    /**
     * @var \DateTime
     */
    private $loaDeadline;

    /**
     * @var \DateTime
     */
    private $loaLoanMade;

    /**
     * @var integer
     */
    private $loaQuotas;

    /**
     * @var integer
     */
    private $loaRecurringDayPayment;

    /**
     * @var float
     */
    private $loaPending;

    /**
     * @var boolean
     */
    private $loaCompleted = false;

    /**
     * @var boolean
     */
    private $loaActive = true;

    /**
     * @var \DateTime
     */
    private $loaCreated;

    /**
     * @var \AppBundle\Entity\Client
     */
    private $cli;

    /**
     * @var \AppBundle\Entity\LoanCategory
     */
    private $loc;


    /**
     * Get loaId
     *
     * @return integer
     */
    public function getLoaId()
    {
        return $this->loaId;
    }

    /**
     * Set loaCode
     *
     * @param string $loaCode
     *
     * @return Loan
     */
    public function setLoaCode($loaCode)
    {
        $this->loaCode = $loaCode;

        return $this;
    }

    /**
     * Get loaCode
     *
     * @return string
     */
    public function getLoaCode()
    {
        return $this->loaCode;
    }

    /**
     * Set loaAmount
     *
     * @param float $loaAmount
     *
     * @return Loan
     */
    public function setLoaAmount($loaAmount)
    {
        $this->loaAmount = $loaAmount;

        return $this;
    }

    /**
     * Get loaAmount
     *
     * @return float
     */
    public function getLoaAmount()
    {
        return $this->loaAmount;
    }

    /**
     * Set loaReturnAmount
     *
     * @param float $loaReturnAmount
     *
     * @return Loan
     */
    public function setLoaReturnAmount($loaReturnAmount)
    {
        $this->loaReturnAmount = $loaReturnAmount;

        return $this;
    }

    /**
     * Get loaReturnAmount
     *
     * @return float
     */
    public function getLoaReturnAmount()
    {
        return $this->loaReturnAmount;
    }

    /**
     * Set loaRateInterest
     *
     * @param float $loaRateInterest
     *
     * @return Loan
     */
    public function setLoaRateInterest($loaRateInterest)
    {
        $this->loaRateInterest = $loaRateInterest;

        return $this;
    }

    /**
     * Get loaRateInterest
     *
     * @return float
     */
    public function getLoaRateInterest()
    {
        return $this->loaRateInterest;
    }

    /**
     * Set loaRateInterestByDefault
     *
     * @param float $loaRateInterestByDefault
     *
     * @return Loan
     */
    public function setLoaRateInterestByDefault($loaRateInterestByDefault)
    {
        $this->loaRateInterestByDefault = $loaRateInterestByDefault;

        return $this;
    }

    /**
     * Get loaRateInterestByDefault
     *
     * @return float
     */
    public function getLoaRateInterestByDefault()
    {
        return $this->loaRateInterestByDefault;
    }

    /**
     * Set loaDescription
     *
     * @param string $loaDescription
     *
     * @return Loan
     */
    public function setLoaDescription($loaDescription)
    {
        $this->loaDescription = $loaDescription;

        return $this;
    }

    /**
     * Get loaDescription
     *
     * @return string
     */
    public function getLoaDescription()
    {
        return $this->loaDescription;
    }

    /**
     * Set loaDeadline
     *
     * @param \DateTime $loaDeadline
     *
     * @return Loan
     */
    public function setLoaDeadline($loaDeadline)
    {
        $this->loaDeadline = $loaDeadline;

        return $this;
    }

    /**
     * Get loaDeadline
     *
     * @return \DateTime
     */
    public function getLoaDeadline()
    {
        return $this->loaDeadline;
    }

    /**
     * Set loaLoanMade
     *
     * @param \DateTime $loaLoanMade
     *
     * @return Loan
     */
    public function setLoaLoanMade($loaLoanMade)
    {
        $this->loaLoanMade = $loaLoanMade;

        return $this;
    }

    /**
     * Get loaLoanMade
     *
     * @return \DateTime
     */
    public function getLoaLoanMade()
    {
        return $this->loaLoanMade;
    }

    /**
     * Set loaQuotas
     *
     * @param integer $loaQuotas
     *
     * @return Loan
     */
    public function setLoaQuotas($loaQuotas)
    {
        $this->loaQuotas = $loaQuotas;

        return $this;
    }

    /**
     * Get loaQuotas
     *
     * @return integer
     */
    public function getLoaQuotas()
    {
        return $this->loaQuotas;
    }

    /**
     * Set loaRecurringDayPayment
     *
     * @param integer $loaRecurringDayPayment
     *
     * @return Loan
     */
    public function setLoaRecurringDayPayment($loaRecurringDayPayment)
    {
        $this->loaRecurringDayPayment = $loaRecurringDayPayment;

        return $this;
    }

    /**
     * Get loaRecurringDayPayment
     *
     * @return integer
     */
    public function getLoaRecurringDayPayment()
    {
        return $this->loaRecurringDayPayment;
    }

    /**
     * Set loaPending
     *
     * @param float $loaPending
     *
     * @return Loan
     */
    public function setLoaPending($loaPending)
    {
        $this->loaPending = $loaPending;

        return $this;
    }

    /**
     * Get loaPending
     *
     * @return float
     */
    public function getLoaPending()
    {
        return $this->loaPending;
    }

    /**
     * Set loaCompleted
     *
     * @param boolean $loaCompleted
     *
     * @return Loan
     */
    public function setLoaCompleted($loaCompleted)
    {
        $this->loaCompleted = $loaCompleted;

        return $this;
    }

    /**
     * Get loaCompleted
     *
     * @return boolean
     */
    public function getLoaCompleted()
    {
        return $this->loaCompleted;
    }

    /**
     * Set loaActive
     *
     * @param boolean $loaActive
     *
     * @return Loan
     */
    public function setLoaActive($loaActive)
    {
        $this->loaActive = $loaActive;

        return $this;
    }

    /**
     * Get loaActive
     *
     * @return boolean
     */
    public function getLoaActive()
    {
        return $this->loaActive;
    }

    /**
     * Set loaCreated
     *
     * @param \DateTime $loaCreated
     *
     * @return Loan
     */
    public function setLoaCreated($loaCreated)
    {
        $this->loaCreated = $loaCreated;

        return $this;
    }

    /**
     * Get loaCreated
     *
     * @return \DateTime
     */
    public function getLoaCreated()
    {
        return $this->loaCreated;
    }

    /**
     * Set cli
     *
     * @param \AppBundle\Entity\Client $cli
     *
     * @return Loan
     */
    public function setCli(\AppBundle\Entity\Client $cli = null)
    {
        $this->cli = $cli;

        return $this;
    }

    /**
     * Get cli
     *
     * @return \AppBundle\Entity\Client
     */
    public function getCli()
    {
        return $this->cli;
    }

    /**
     * Set loc
     *
     * @param \AppBundle\Entity\LoanCategory $loc
     *
     * @return Loan
     */
    public function setLoc(\AppBundle\Entity\LoanCategory $loc = null)
    {
        $this->loc = $loc;

        return $this;
    }

    /**
     * Get loc
     *
     * @return \AppBundle\Entity\LoanCategory
     */
    public function getLoc()
    {
        return $this->loc;
    }
}
