<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * County
 *
 * @ORM\Table(name="county")
 * @ORM\Entity
 */
class County
{
    /**
     * @var integer
     *
     * @ORM\Column(name="county_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $countyId;

    /**
     * @var integer
     *
     * @ORM\Column(name="county_code", type="integer", nullable=false)
     */
    private $countyCode;

    /**
     * @var string
     *
     * @ORM\Column(name="county_name", type="string", length=100, nullable=false)
     */
    private $countyName;

    /**
     * @var string
     *
     * @ORM\Column(name="county_description", type="text", length=16777215, nullable=false)
     */
    private $countyDescription;

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Get countyId
     *
     * @return integer
     */
    public function getCountyId()
    {
        return $this->countyId;
    }

    /**
     * Set countyCode
     *
     * @param integer $countyCode
     *
     * @return County
     */
    public function setCountyCode($countyCode)
    {
        $this->countyCode = $countyCode;

        return $this;
    }

    /**
     * Get countyCode
     *
     * @return integer
     */
    public function getCountyCode()
    {
        return $this->countyCode;
    }

    /**
     * Set countyName
     *
     * @param string $countyName
     *
     * @return County
     */
    public function setCountyName($countyName)
    {
        $this->countyName = $countyName;

        return $this;
    }

    /**
     * Get countyName
     *
     * @return string
     */
    public function getCountyName()
    {
        return $this->countyName;
    }

    /**
     * Set countyDescription
     *
     * @param string $countyDescription
     *
     * @return County
     */
    public function setCountyDescription($countyDescription)
    {
        $this->countyDescription = $countyDescription;

        return $this;
    }

    /**
     * Get countyDescription
     *
     * @return string
     */
    public function getCountyDescription()
    {
        return $this->countyDescription;
    }
}
