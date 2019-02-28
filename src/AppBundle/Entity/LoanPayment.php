<?php

namespace AppBundle\Entity;

/**
 * LoanPayment
 */
class LoanPayment
{
    /**
     * @var integer
     */
    private $lpaId;

    /**
     * @var \DateTime
     */
    private $lpaMaxPaymentDate;

    /**
     * @var \DateTime
     */
    private $lpaPaidDate;

    /**
     * @var float
     */
    private $lpaPaidRateInterest;

    /**
     * @var float
     */
    private $lpaPaidCapital;

    /**
     * @var float
     */
    private $lpaCurrentRateInterest;

    /**
     * @var integer
     */
    private $lpaMultipliedInterestBy;

    /**
     * @var float
     */
    private $lpaCurrentAmount;

    /**
     * @var float
     */
    private $lpaTotalAmountPaid;

    /**
     * @var float
     */
    private $lpaNextRateInterest;

    /**
     * @var float
     */
    private $lpaNextAmount;

    /**
     * @var string
     */
    private $lpaNote;

    /**
     * @var boolean
     */
    private $lpaIsPayment = '0';

    /**
     * @var boolean
     */
    private $lpaChangedAmount = '0';

    /**
     * @var \AppBundle\Entity\Loan
     */
    private $loa;

    /**
     * @var \AppBundle\Entity\LoanPaymentType
     */
    private $lpt;


    /**
     * Get lpaId
     *
     * @return integer
     */
    public function getLpaId()
    {
        return $this->lpaId;
    }

    /**
     * Set lpaMaxPaymentDate
     *
     * @param \DateTime $lpaMaxPaymentDate
     *
     * @return LoanPayment
     */
    public function setLpaMaxPaymentDate($lpaMaxPaymentDate)
    {
        $this->lpaMaxPaymentDate = $lpaMaxPaymentDate;

        return $this;
    }

    /**
     * Get lpaMaxPaymentDate
     *
     * @return \DateTime
     */
    public function getLpaMaxPaymentDate()
    {
        return $this->lpaMaxPaymentDate;
    }

    /**
     * Set lpaPaidDate
     *
     * @param \DateTime $lpaPaidDate
     *
     * @return LoanPayment
     */
    public function setLpaPaidDate($lpaPaidDate)
    {
        $this->lpaPaidDate = $lpaPaidDate;

        return $this;
    }

    /**
     * Get lpaPaidDate
     *
     * @return \DateTime
     */
    public function getLpaPaidDate()
    {
        return $this->lpaPaidDate;
    }

    /**
     * Set lpaPaidRateInterest
     *
     * @param float $lpaPaidRateInterest
     *
     * @return LoanPayment
     */
    public function setLpaPaidRateInterest($lpaPaidRateInterest)
    {
        $this->lpaPaidRateInterest = $lpaPaidRateInterest;

        return $this;
    }

    /**
     * Get lpaPaidRateInterest
     *
     * @return float
     */
    public function getLpaPaidRateInterest()
    {
        return $this->lpaPaidRateInterest;
    }

    /**
     * Set lpaPaidCapital
     *
     * @param float $lpaPaidCapital
     *
     * @return LoanPayment
     */
    public function setLpaPaidCapital($lpaPaidCapital)
    {
        $this->lpaPaidCapital = $lpaPaidCapital;

        return $this;
    }

    /**
     * Get lpaPaidCapital
     *
     * @return float
     */
    public function getLpaPaidCapital()
    {
        return $this->lpaPaidCapital;
    }

    /**
     * Set lpaCurrentRateInterest
     *
     * @param float $lpaCurrentRateInterest
     *
     * @return LoanPayment
     */
    public function setLpaCurrentRateInterest($lpaCurrentRateInterest)
    {
        $this->lpaCurrentRateInterest = $lpaCurrentRateInterest;

        return $this;
    }

    /**
     * Get lpaCurrentRateInterest
     *
     * @return float
     */
    public function getLpaCurrentRateInterest()
    {
        return $this->lpaCurrentRateInterest;
    }

    /**
     * Set lpaMultipliedInterestBy
     *
     * @param integer $lpaMultipliedInterestBy
     *
     * @return LoanPayment
     */
    public function setLpaMultipliedInterestBy($lpaMultipliedInterestBy)
    {
        $this->lpaMultipliedInterestBy = $lpaMultipliedInterestBy;

        return $this;
    }

    /**
     * Get lpaMultipliedInterestBy
     *
     * @return integer
     */
    public function getLpaMultipliedInterestBy()
    {
        return $this->lpaMultipliedInterestBy;
    }

    /**
     * Set lpaCurrentAmount
     *
     * @param float $lpaCurrentAmount
     *
     * @return LoanPayment
     */
    public function setLpaCurrentAmount($lpaCurrentAmount)
    {
        $this->lpaCurrentAmount = $lpaCurrentAmount;

        return $this;
    }

    /**
     * Get lpaCurrentAmount
     *
     * @return float
     */
    public function getLpaCurrentAmount()
    {
        return $this->lpaCurrentAmount;
    }

    /**
     * Set lpaTotalAmountPaid
     *
     * @param float $lpaTotalAmountPaid
     *
     * @return LoanPayment
     */
    public function setLpaTotalAmountPaid($lpaTotalAmountPaid)
    {
        $this->lpaTotalAmountPaid = $lpaTotalAmountPaid;

        return $this;
    }

    /**
     * Get lpaTotalAmountPaid
     *
     * @return float
     */
    public function getLpaTotalAmountPaid()
    {
        return $this->lpaTotalAmountPaid;
    }

    /**
     * Set lpaNextRateInterest
     *
     * @param float $lpaNextRateInterest
     *
     * @return LoanPayment
     */
    public function setLpaNextRateInterest($lpaNextRateInterest)
    {
        $this->lpaNextRateInterest = $lpaNextRateInterest;

        return $this;
    }

    /**
     * Get lpaNextRateInterest
     *
     * @return float
     */
    public function getLpaNextRateInterest()
    {
        return $this->lpaNextRateInterest;
    }

    /**
     * Set lpaNextAmount
     *
     * @param float $lpaNextAmount
     *
     * @return LoanPayment
     */
    public function setLpaNextAmount($lpaNextAmount)
    {
        $this->lpaNextAmount = $lpaNextAmount;

        return $this;
    }

    /**
     * Get lpaNextAmount
     *
     * @return float
     */
    public function getLpaNextAmount()
    {
        return $this->lpaNextAmount;
    }

    /**
     * Set lpaNote
     *
     * @param string $lpaNote
     *
     * @return LoanPayment
     */
    public function setLpaNote($lpaNote)
    {
        $this->lpaNote = $lpaNote;

        return $this;
    }

    /**
     * Get lpaNote
     *
     * @return string
     */
    public function getLpaNote()
    {
        return $this->lpaNote;
    }

    /**
     * Set lpaIsPayment
     *
     * @param boolean $lpaIsPayment
     *
     * @return LoanPayment
     */
    public function setLpaIsPayment($lpaIsPayment)
    {
        $this->lpaIsPayment = $lpaIsPayment;

        return $this;
    }

    /**
     * Get lpaIsPayment
     *
     * @return boolean
     */
    public function getLpaIsPayment()
    {
        return $this->lpaIsPayment;
    }

    /**
     * Set lpaChangedAmount
     *
     * @param boolean $lpaChangedAmount
     *
     * @return LoanPayment
     */
    public function setLpaChangedAmount($lpaChangedAmount)
    {
        $this->lpaChangedAmount = $lpaChangedAmount;

        return $this;
    }

    /**
     * Get lpaChangedAmount
     *
     * @return boolean
     */
    public function getLpaChangedAmount()
    {
        return $this->lpaChangedAmount;
    }

    /**
     * Set loa
     *
     * @param \AppBundle\Entity\Loan $loa
     *
     * @return LoanPayment
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
     * Set lpt
     *
     * @param \AppBundle\Entity\LoanPaymentType $lpt
     *
     * @return LoanPayment
     */
    public function setLpt(\AppBundle\Entity\LoanPaymentType $lpt = null)
    {
        $this->lpt = $lpt;

        return $this;
    }

    /**
     * Get lpt
     *
     * @return \AppBundle\Entity\LoanPaymentType
     */
    public function getLpt()
    {
        return $this->lpt;
    }
    /**
     * @var \DateTime
     */
    private $lpaNextPaymentDate;


    /**
     * Set lpaNextPaymentDate
     *
     * @param \DateTime $lpaNextPaymentDate
     *
     * @return LoanPayment
     */
    public function setLpaNextPaymentDate($lpaNextPaymentDate)
    {
        $this->lpaNextPaymentDate = $lpaNextPaymentDate;

        return $this;
    }

    /**
     * Get lpaNextPaymentDate
     *
     * @return \DateTime
     */
    public function getLpaNextPaymentDate()
    {
        return $this->lpaNextPaymentDate;
    }
    /**
     * @var string
     */
    private $lpaHash;

    /**
     * @var \AppBundle\Entity\LoanAdditionalAmounts
     */
    private $laa;


    /**
     * Set lpaHash
     *
     * @param string $lpaHash
     *
     * @return LoanPayment
     */
    public function setLpaHash($lpaHash)
    {
        $this->lpaHash = $lpaHash;

        return $this;
    }

    /**
     * Get lpaHash
     *
     * @return string
     */
    public function getLpaHash()
    {
        return $this->lpaHash;
    }

    /**
     * Set laa
     *
     * @param \AppBundle\Entity\LoanAdditionalAmounts $laa
     *
     * @return LoanPayment
     */
    public function setLaa(\AppBundle\Entity\LoanAdditionalAmounts $laa = null)
    {
        $this->laa = $laa;

        return $this;
    }

    /**
     * Get laa
     *
     * @return \AppBundle\Entity\LoanAdditionalAmounts
     */
    public function getLaa()
    {
        return $this->laa;
    }
}
