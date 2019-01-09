<?php

namespace AppBundle\Entity;

/**
 * LoanHistoricalAmounts
 */
class LoanHistoricalAmounts
{
    /**
     * @var integer
     */
    private $lhaId;

    /**
     * @var float
     */
    private $lhaAmount;

    /**
     * @var \DateTime
     */
    private $lhaCreated;

    /**
     * @var boolean
     */
    private $lhaActive = '1';

    /**
     * @var \AppBundle\Entity\Loan
     */
    private $loa;


    /**
     * Get lhaId
     *
     * @return integer
     */
    public function getLhaId()
    {
        return $this->lhaId;
    }

    /**
     * Set lhaAmount
     *
     * @param float $lhaAmount
     *
     * @return LoanHistoricalAmounts
     */
    public function setLhaAmount($lhaAmount)
    {
        $this->lhaAmount = $lhaAmount;

        return $this;
    }

    /**
     * Get lhaAmount
     *
     * @return float
     */
    public function getLhaAmount()
    {
        return $this->lhaAmount;
    }

    /**
     * Set lhaCreated
     *
     * @param \DateTime $lhaCreated
     *
     * @return LoanHistoricalAmounts
     */
    public function setLhaCreated($lhaCreated)
    {
        $this->lhaCreated = $lhaCreated;

        return $this;
    }

    /**
     * Get lhaCreated
     *
     * @return \DateTime
     */
    public function getLhaCreated()
    {
        return $this->lhaCreated;
    }

    /**
     * Set lhaActive
     *
     * @param boolean $lhaActive
     *
     * @return LoanHistoricalAmounts
     */
    public function setLhaActive($lhaActive)
    {
        $this->lhaActive = $lhaActive;

        return $this;
    }

    /**
     * Get lhaActive
     *
     * @return boolean
     */
    public function getLhaActive()
    {
        return $this->lhaActive;
    }

    /**
     * Set loa
     *
     * @param \AppBundle\Entity\Loan $loa
     *
     * @return LoanHistoricalAmounts
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
}
