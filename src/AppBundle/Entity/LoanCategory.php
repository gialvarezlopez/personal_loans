<?php

namespace AppBundle\Entity;

/**
 * LoanCategory
 */
class LoanCategory
{
    /**
     * @var integer
     */
    private $locId;

    /**
     * @var string
     */
    private $locType;

    /**
     * @var string
     */
    private $locDescription;

    /**
     * @var boolean
     */
    private $locActive = '1';


    /**
     * Get locId
     *
     * @return integer
     */
    public function getLocId()
    {
        return $this->locId;
    }

    /**
     * Set locType
     *
     * @param string $locType
     *
     * @return LoanCategory
     */
    public function setLocType($locType)
    {
        $this->locType = $locType;

        return $this;
    }

    /**
     * Get locType
     *
     * @return string
     */
    public function getLocType()
    {
        return $this->locType;
    }

    /**
     * Set locDescription
     *
     * @param string $locDescription
     *
     * @return LoanCategory
     */
    public function setLocDescription($locDescription)
    {
        $this->locDescription = $locDescription;

        return $this;
    }

    /**
     * Get locDescription
     *
     * @return string
     */
    public function getLocDescription()
    {
        return $this->locDescription;
    }

    /**
     * Set locActive
     *
     * @param boolean $locActive
     *
     * @return LoanCategory
     */
    public function setLocActive($locActive)
    {
        $this->locActive = $locActive;

        return $this;
    }

    /**
     * Get locActive
     *
     * @return boolean
     */
    public function getLocActive()
    {
        return $this->locActive;
    }
    /**
     * @var string
     */
    private $locKey;


    /**
     * Set locKey
     *
     * @param string $locKey
     *
     * @return LoanCategory
     */
    public function setLocKey($locKey)
    {
        $this->locKey = $locKey;

        return $this;
    }

    /**
     * Get locKey
     *
     * @return string
     */
    public function getLocKey()
    {
        return $this->locKey;
    }
}
