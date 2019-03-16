<?php

namespace AppBundle\Entity;

/**
 * LoanHistoricalPayments
 */
class LoanHistoricalPayments
{
    /**
     * @var integer
     */
    private $lhpId;

    /**
     * @var \DateTime
     */
    private $lhpDeadline;

    /**
     * @var \DateTime
     */
    private $lhpPaidDate;

    /**
     * @var float
     */
    private $lhpPrevAmount;

    /**
     * @var float
     */
    private $lhpPrevInterest;

    /**
     * @var float
     */
    private $lhpLastPaidAmount;

    /**
     * @var float
     */
    private $lhpLastPaidInterest;

    /**
     * @var float
     */
    private $lhpLastPaidCapital;

    /**
     * @var float
     */
    private $lhpNextCapital;

    /**
     * @var float
     */
    private $lhpNextInterest;

    /**
     * @var \DateTime
     */
    private $lhpNextPaymentDate;

    /**
     * @var string
     */
    private $lhpHash;

    /**
     * @var boolean
     */
    private $lhpActive = '1';

    /**
     * @var \DateTime
     */
    private $lhpCreated;

    /**
     * @var \AppBundle\Entity\Loan
     */
    private $loa;

    /**
     * @var \AppBundle\Entity\LoanAdditionalAmounts
     */
    private $laa;


    /**
     * Get lhpId
     *
     * @return integer
     */
    public function getLhpId()
    {
        return $this->lhpId;
    }

    /**
     * Set lhpDeadline
     *
     * @param \DateTime $lhpDeadline
     *
     * @return LoanHistoricalPayments
     */
    public function setLhpDeadline($lhpDeadline)
    {
        $this->lhpDeadline = $lhpDeadline;

        return $this;
    }

    /**
     * Get lhpDeadline
     *
     * @return \DateTime
     */
    public function getLhpDeadline()
    {
        return $this->lhpDeadline;
    }

    /**
     * Set lhpPaidDate
     *
     * @param \DateTime $lhpPaidDate
     *
     * @return LoanHistoricalPayments
     */
    public function setLhpPaidDate($lhpPaidDate)
    {
        $this->lhpPaidDate = $lhpPaidDate;

        return $this;
    }

    /**
     * Get lhpPaidDate
     *
     * @return \DateTime
     */
    public function getLhpPaidDate()
    {
        return $this->lhpPaidDate;
    }

    /**
     * Set lhpPrevAmount
     *
     * @param float $lhpPrevAmount
     *
     * @return LoanHistoricalPayments
     */
    public function setLhpPrevAmount($lhpPrevAmount)
    {
        $this->lhpPrevAmount = $lhpPrevAmount;

        return $this;
    }

    /**
     * Get lhpPrevAmount
     *
     * @return float
     */
    public function getLhpPrevAmount()
    {
        return $this->lhpPrevAmount;
    }

    /**
     * Set lhpPrevInterest
     *
     * @param float $lhpPrevInterest
     *
     * @return LoanHistoricalPayments
     */
    public function setLhpPrevInterest($lhpPrevInterest)
    {
        $this->lhpPrevInterest = $lhpPrevInterest;

        return $this;
    }

    /**
     * Get lhpPrevInterest
     *
     * @return float
     */
    public function getLhpPrevInterest()
    {
        return $this->lhpPrevInterest;
    }

    /**
     * Set lhpLastPaidAmount
     *
     * @param float $lhpLastPaidAmount
     *
     * @return LoanHistoricalPayments
     */
    public function setLhpLastPaidAmount($lhpLastPaidAmount)
    {
        $this->lhpLastPaidAmount = $lhpLastPaidAmount;

        return $this;
    }

    /**
     * Get lhpLastPaidAmount
     *
     * @return float
     */
    public function getLhpLastPaidAmount()
    {
        return $this->lhpLastPaidAmount;
    }

    /**
     * Set lhpLastPaidInterest
     *
     * @param float $lhpLastPaidInterest
     *
     * @return LoanHistoricalPayments
     */
    public function setLhpLastPaidInterest($lhpLastPaidInterest)
    {
        $this->lhpLastPaidInterest = $lhpLastPaidInterest;

        return $this;
    }

    /**
     * Get lhpLastPaidInterest
     *
     * @return float
     */
    public function getLhpLastPaidInterest()
    {
        return $this->lhpLastPaidInterest;
    }

    /**
     * Set lhpLastPaidCapital
     *
     * @param float $lhpLastPaidCapital
     *
     * @return LoanHistoricalPayments
     */
    public function setLhpLastPaidCapital($lhpLastPaidCapital)
    {
        $this->lhpLastPaidCapital = $lhpLastPaidCapital;

        return $this;
    }

    /**
     * Get lhpLastPaidCapital
     *
     * @return float
     */
    public function getLhpLastPaidCapital()
    {
        return $this->lhpLastPaidCapital;
    }

    /**
     * Set lhpNextCapital
     *
     * @param float $lhpNextCapital
     *
     * @return LoanHistoricalPayments
     */
    public function setLhpNextCapital($lhpNextCapital)
    {
        $this->lhpNextCapital = $lhpNextCapital;

        return $this;
    }

    /**
     * Get lhpNextCapital
     *
     * @return float
     */
    public function getLhpNextCapital()
    {
        return $this->lhpNextCapital;
    }

    /**
     * Set lhpNextInterest
     *
     * @param float $lhpNextInterest
     *
     * @return LoanHistoricalPayments
     */
    public function setLhpNextInterest($lhpNextInterest)
    {
        $this->lhpNextInterest = $lhpNextInterest;

        return $this;
    }

    /**
     * Get lhpNextInterest
     *
     * @return float
     */
    public function getLhpNextInterest()
    {
        return $this->lhpNextInterest;
    }

    /**
     * Set lhpNextPaymentDate
     *
     * @param \DateTime $lhpNextPaymentDate
     *
     * @return LoanHistoricalPayments
     */
    public function setLhpNextPaymentDate($lhpNextPaymentDate)
    {
        $this->lhpNextPaymentDate = $lhpNextPaymentDate;

        return $this;
    }

    /**
     * Get lhpNextPaymentDate
     *
     * @return \DateTime
     */
    public function getLhpNextPaymentDate()
    {
        return $this->lhpNextPaymentDate;
    }

    /**
     * Set lhpHash
     *
     * @param string $lhpHash
     *
     * @return LoanHistoricalPayments
     */
    public function setLhpHash($lhpHash)
    {
        $this->lhpHash = $lhpHash;

        return $this;
    }

    /**
     * Get lhpHash
     *
     * @return string
     */
    public function getLhpHash()
    {
        return $this->lhpHash;
    }

    /**
     * Set lhpActive
     *
     * @param boolean $lhpActive
     *
     * @return LoanHistoricalPayments
     */
    public function setLhpActive($lhpActive)
    {
        $this->lhpActive = $lhpActive;

        return $this;
    }

    /**
     * Get lhpActive
     *
     * @return boolean
     */
    public function getLhpActive()
    {
        return $this->lhpActive;
    }

    /**
     * Set lhpCreated
     *
     * @param \DateTime $lhpCreated
     *
     * @return LoanHistoricalPayments
     */
    public function setLhpCreated($lhpCreated)
    {
        $this->lhpCreated = $lhpCreated;

        return $this;
    }

    /**
     * Get lhpCreated
     *
     * @return \DateTime
     */
    public function getLhpCreated()
    {
        return $this->lhpCreated;
    }

    /**
     * Set loa
     *
     * @param \AppBundle\Entity\Loan $loa
     *
     * @return LoanHistoricalPayments
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
     * Set laa
     *
     * @param \AppBundle\Entity\LoanAdditionalAmounts $laa
     *
     * @return LoanHistoricalPayments
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
    /**
     * @var string
     */
    private $lhpNote;


    /**
     * Set lhpNote
     *
     * @param string $lhpNote
     *
     * @return LoanHistoricalPayments
     */
    public function setLhpNote($lhpNote)
    {
        $this->lhpNote = $lhpNote;

        return $this;
    }

    /**
     * Get lhpNote
     *
     * @return string
     */
    public function getLhpNote()
    {
        return $this->lhpNote;
    }
}
