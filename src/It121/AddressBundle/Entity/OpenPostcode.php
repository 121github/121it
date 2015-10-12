<?php

namespace It121\AddressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PafPostcode
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="It121\AddressBundle\Entity\OpenPostcodeRepository")
 */
class OpenPostcode
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="postcode", type="string", length=8, nullable=true)
     */
    private $postcode;

    /**
     * @var string
     *
     * @ORM\Column(name="add1", type="string", length=255, nullable=true)
     */
    private $add1;

    /**
     * @var string
     *
     * @ORM\Column(name="add2", type="string", length=255, nullable=true)
     */
    private $add2;

    /**
     * @var string
     *
     * @ORM\Column(name="add3", type="string", length=255, nullable=true)
     */
    private $add3;

    /**
     * @var string
     *
     * @ORM\Column(name="add4", type="string", length=255, nullable=true)
     */
    private $add4;

    /**
     * @var string
     *
     * @ORM\Column(name="locality", type="string", length=100, nullable=true)
     */
    private $locality;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=100, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="county", type="string", length=100, nullable=true)
     */
    private $county;

    /**
     * @var decimal
     *
     * @ORM\Column(name="latitude", type="decimal", length=18, scale=12, precision=18, nullable=true)
     */
    private $latitude;

    /**
     * @var decimal
     *
     * @ORM\Column(name="longitude", type="decimal", length=18, scale=12, precision=18, nullable=true)
     */
    private $longitude;

    /**
     *
     * @ORM\ManyToOne(targetEntity="It121\AddressBundle\Entity\PostcodeIo")
     */
    private $postcodeIo;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", nullable=true)
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=true)
     * @ORM\Version()
     */
    private $createdDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", nullable=true)
     */
    private $modifiedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_date", type="datetime", nullable=true)
     */
    private $modifiedDate;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     *
     * @return PafPostcode
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get postcode
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @return string
     */
    public function getAdd1()
    {
        return $this->add1;
    }

    /**
     * @param string $add1
     */
    public function setAdd1($add1)
    {
        $this->add1 = $add1;
    }

    /**
     * @return string
     */
    public function getAdd2()
    {
        return $this->add2;
    }

    /**
     * @param string $add2
     */
    public function setAdd2($add2)
    {
        $this->add2 = $add2;
    }

    /**
     * @return string
     */
    public function getAdd3()
    {
        return $this->add3;
    }

    /**
     * @param string $add3
     */
    public function setAdd3($add3)
    {
        $this->add3 = $add3;
    }

    /**
     * @return string
     */
    public function getAdd4()
    {
        return $this->add4;
    }

    /**
     * @param string $add4
     */
    public function setAdd4($add4)
    {
        $this->add4 = $add4;
    }

    /**
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * @param string $locality
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * @param string $county
     */
    public function setCounty($county)
    {
        $this->county = $county;
    }

    /**
     * @return decimal
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param decimal $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return decimal
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param decimal $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Set PostcodeIo
     *
     * @param \It121\AddressBundle\Entity\PostcodeIo $postcodeIo
     * @return OpenPostcode
     */
    public function setPostcodeIo(\It121\AddressBundle\Entity\PostcodeIo $postcodeIo = null)
    {
        $this->postcodeIo = $postcodeIo;

        return $this;
    }

    /**
     * Get PostcodeIo
     *
     * @return \It121\AddressBundle\Entity\PostcodeIo
     */
    public function getPostcodeIo()
    {
        return $this->postcodeIo;
    }

    /**
     * @return int
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * @param int $createdBy
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param \DateTime $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return int
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * @param int $modifiedBy
     */
    public function setModifiedBy($modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;
    }

    /**
     * @return \DateTime
     */
    public function getModifiedDate()
    {
        return $this->modifiedDate;
    }

    /**
     * @param \DateTime $modifiedDate
     */
    public function setModifiedDate($modifiedDate)
    {
        $this->modifiedDate = $modifiedDate;
    }
}

