<?php

namespace AppBundle\Entity;

/**
 * LoanPaymentType
 */
class LoanPaymentType
{
    /**
     * @var integer
     */
    private $lptId;

    /**
     * @var string
     */
    private $lptName;

    /**
     * @var string
     */
    private $lptKey;


    /**
     * Get lptId
     *
     * @return integer
     */
    public function getLptId()
    {
        return $this->lptId;
    }

    /**
     * Set lptName
     *
     * @param string $lptName
     *
     * @return LoanPaymentType
     */
    public function setLptName($lptName)
    {
        $this->lptName = $lptName;

        return $this;
    }

    /**
     * Get lptName
     *
     * @return string
     */
    public function getLptName()
    {
        return $this->lptName;
    }

    /**
     * Set lptKey
     *
     * @param string $lptKey
     *
     * @return LoanPaymentType
     */
    public function setLptKey($lptKey)
    {
        $this->lptKey = $lptKey;

        return $this;
    }

    /**
     * Get lptKey
     *
     * @return string
     */
    public function getLptKey()
    {
        return $this->lptKey;
    }
}
