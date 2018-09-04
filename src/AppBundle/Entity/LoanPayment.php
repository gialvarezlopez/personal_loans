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
     * @var string
     */
    private $lpaNote;

    /**
     * @var \AppBundle\Entity\Loan
     */
    private $loa;


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
     * @var float
     */
    private $lpaCurrentRateInterest;

    /**
     * @var float
     */
    private $lpaCurrentAmount;


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
     * @var boolean
     */
    private $lpaIsPayment = '0';


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
     * @var float
     */
    private $lpaTotalAmountPaid;


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
     * @var float
     */
    private $lpaNextRateInterest;


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
     * @var \AppBundle\Entity\LoanPaymentType
     */
    private $lpt;


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
}
