<?php

namespace AppBundle\Entity;

/**
 * LoanAdditionalAmounts
 */
class LoanAdditionalAmounts
{
    /**
     * @var integer
     */
    private $laaId;

    /**
     * @var float
     */
    private $laaAmount;

    /**
     * @var float
     */
    private $laaRateInterest;

    /**
     * @var \DateTime
     */
    private $laaDeliveredDate;

    /**
     * @var string
     */
    private $laaComment;

    /**
     * @var \DateTime
     */
    private $laaCreated;

    /**
     * @var boolean
     */
    private $laaActive = '1';

    /**
     * @var \AppBundle\Entity\Loan
     */
    private $loa;


    /**
     * Get laaId
     *
     * @return integer
     */
    public function getLaaId()
    {
        return $this->laaId;
    }

    /**
     * Set laaAmount
     *
     * @param float $laaAmount
     *
     * @return LoanAdditionalAmounts
     */
    public function setLaaAmount($laaAmount)
    {
        $this->laaAmount = $laaAmount;

        return $this;
    }

    /**
     * Get laaAmount
     *
     * @return float
     */
    public function getLaaAmount()
    {
        return $this->laaAmount;
    }

    /**
     * Set laaRateInterest
     *
     * @param float $laaRateInterest
     *
     * @return LoanAdditionalAmounts
     */
    public function setLaaRateInterest($laaRateInterest)
    {
        $this->laaRateInterest = $laaRateInterest;

        return $this;
    }

    /**
     * Get laaRateInterest
     *
     * @return float
     */
    public function getLaaRateInterest()
    {
        return $this->laaRateInterest;
    }

    /**
     * Set laaDeliveredDate
     *
     * @param \DateTime $laaDeliveredDate
     *
     * @return LoanAdditionalAmounts
     */
    public function setLaaDeliveredDate($laaDeliveredDate)
    {
        $this->laaDeliveredDate = $laaDeliveredDate;

        return $this;
    }

    /**
     * Get laaDeliveredDate
     *
     * @return \DateTime
     */
    public function getLaaDeliveredDate()
    {
        return $this->laaDeliveredDate;
    }

    /**
     * Set laaComment
     *
     * @param string $laaComment
     *
     * @return LoanAdditionalAmounts
     */
    public function setLaaComment($laaComment)
    {
        $this->laaComment = $laaComment;

        return $this;
    }

    /**
     * Get laaComment
     *
     * @return string
     */
    public function getLaaComment()
    {
        return $this->laaComment;
    }

    /**
     * Set laaCreated
     *
     * @param \DateTime $laaCreated
     *
     * @return LoanAdditionalAmounts
     */
    public function setLaaCreated($laaCreated)
    {
        $this->laaCreated = $laaCreated;

        return $this;
    }

    /**
     * Get laaCreated
     *
     * @return \DateTime
     */
    public function getLaaCreated()
    {
        return $this->laaCreated;
    }

    /**
     * Set laaActive
     *
     * @param boolean $laaActive
     *
     * @return LoanAdditionalAmounts
     */
    public function setLaaActive($laaActive)
    {
        $this->laaActive = $laaActive;

        return $this;
    }

    /**
     * Get laaActive
     *
     * @return boolean
     */
    public function getLaaActive()
    {
        return $this->laaActive;
    }

    /**
     * Set loa
     *
     * @param \AppBundle\Entity\Loan $loa
     *
     * @return LoanAdditionalAmounts
     */
    public function setLoa(\AppBundle\Entity\Loan $loa = null)
    {
        $this->loa = $loa;

        return $this;
    }

    /**
     * Get loa
     *
     * @return \AppBundle\Entity\Loan
     */
    public function getLoa()
    {
        return $this->loa;
    }
    /**
     * @var float
     */
    private $laaRateInterestByDefault;

    /**
     * @var boolean
     */
    private $laaCompleted = '0';


    /**
     * Set laaRateInterestByDefault
     *
     * @param float $laaRateInterestByDefault
     *
     * @return LoanAdditionalAmounts
     */
    public function setLaaRateInterestByDefault($laaRateInterestByDefault)
    {
        $this->laaRateInterestByDefault = $laaRateInterestByDefault;

        return $this;
    }

    /**
     * Get laaRateInterestByDefault
     *
     * @return float
     */
    public function getLaaRateInterestByDefault()
    {
        return $this->laaRateInterestByDefault;
    }

    /**
     * Set laaCompleted
     *
     * @param boolean $laaCompleted
     *
     * @return LoanAdditionalAmounts
     */
    public function setLaaCompleted($laaCompleted)
    {
        $this->laaCompleted = $laaCompleted;

        return $this;
    }

    /**
     * Get laaCompleted
     *
     * @return boolean
     */
    public function getLaaCompleted()
    {
        return $this->laaCompleted;
    }
    /**
     * @var boolean
     */
    private $laaSplittedBalance = '1';


    /**
     * Set laaSplittedBalance
     *
     * @param boolean $laaSplittedBalance
     *
     * @return LoanAdditionalAmounts
     */
    public function setLaaSplittedBalance($laaSplittedBalance)
    {
        $this->laaSplittedBalance = $laaSplittedBalance;

        return $this;
    }

    /**
     * Get laaSplittedBalance
     *
     * @return boolean
     */
    public function getLaaSplittedBalance()
    {
        return $this->laaSplittedBalance;
    }
    /**
     * @var \DateTime
     */
    private $laaNextPaymentDate;


    /**
     * Set laaNextPaymentDate
     *
     * @param \DateTime $laaNextPaymentDate
     *
     * @return LoanAdditionalAmounts
     */
    public function setLaaNextPaymentDate($laaNextPaymentDate)
    {
        $this->laaNextPaymentDate = $laaNextPaymentDate;

        return $this;
    }

    /**
     * Get laaNextPaymentDate
     *
     * @return \DateTime
     */
    public function getLaaNextPaymentDate()
    {
        return $this->laaNextPaymentDate;
    }
}
