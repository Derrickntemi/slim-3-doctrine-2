<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Constituency
 *
 * @ORM\Table(name="constituency", indexes={@ORM\Index(name="FK_5F38776985E73F45", columns={"county_id"})})
 * @ORM\Entity
 */
class Constituency
{
    /**
     * @var integer
     *
     * @ORM\Column(name="constituency_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $constituencyId;

    /**
     * @var string
     *
     * @ORM\Column(name="constituency_name", type="string", length=100, nullable=false)
     */
    private $constituencyName;

    /**
     * @var string
     *
     * @ORM\Column(name="constituency_description", type="text", length=16777215, nullable=false)
     */
    private $constituencyDescription;

    /**
     *
     *
     * * @ORM\ManyToOne(targetEntity="County")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="county_id", referencedColumnName="county_id")
     * })
     */
    private $countyId;

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Get constituencyId
     *
     * @return integer
     */
    public function getConstituencyId()
    {
        return $this->constituencyId;
    }

    /**
     * Set constituencyName
     *
     * @param string $constituencyName
     *
     * @return Constituency
     */
    public function setConstituencyName($constituencyName)
    {
        $this->constituencyName = $constituencyName;

        return $this;
    }

    /**
     * Get constituencyName
     *
     * @return string
     */
    public function getConstituencyName()
    {
        return $this->constituencyName;
    }

    /**
     * Set constituencyDescription
     *
     * @param string $constituencyDescription
     *
     * @return Constituency
     */
    public function setConstituencyDescription($constituencyDescription)
    {
        $this->constituencyDescription = $constituencyDescription;

        return $this;
    }

    /**
     * Get constituencyDescription
     *
     * @return string
     */
    public function getConstituencyDescription()
    {
        return $this->constituencyDescription;
    }
    /**
     * Get countyId
     *
     * @return integer
     */
    public function getCountyId()
    {
        // return $this->countyId;

        return get_object_vars($this->countyId);
    }

    /**
     * Set countyId
     *
     * @param integer $countyId
     *
     * @return Constituency
     */
    public function setCountyId($countyId)
    {
        $this->countyId = $countyId;

        return $this;
    }


}
