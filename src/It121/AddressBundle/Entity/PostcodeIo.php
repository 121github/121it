<?php

namespace It121\AddressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * PostcodeIo
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class PostcodeIo
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
     * @ORM\OneToOne(targetEntity="It121\AddressBundle\Entity\PafPostcode", inversedBy="postcodeIo")
     */
    private $pafPostcode;

    /**
     * @var string
     *
     * @ORM\Column(name="postcode", type="string", length=8, nullable=true)
     */
    private $postcode;

    /**
     * @var integer
     *
     * @ORM\Column(name="quality", type="integer", length=2, nullable=true)
     */
    private $quality;

    /**
     * @var integer
     *
     * @ORM\Column(name="eastings", type="integer", length=8, nullable=true)
     */
    private $eastings;

    /**
     * @var integer
     *
     * @ORM\Column(name="northings", type="integer", length=8, nullable=true)
     */
    private $northings;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=30, nullable=true)
     */
    private $country;

    /**
     * @var string
     *
     * @ORM\Column(name="nhs_ha", type="string", length=35, nullable=true)
     */
    private $nhs_ha;

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
     * @var string
     *
     * @ORM\Column(name="parliamentary_constituency", type="string", length=80, nullable=true)
     */
    private $parliamentary_constituency;

    /**
     * @var string
     *
     * @ORM\Column(name="european_electoral_region", type="string", length=80, nullable=true)
     */
    private $european_electoral_region;

    /**
     * @var string
     *
     * @ORM\Column(name="primary_care_trust", type="string", length=80, nullable=true)
     */
    private $primary_care_trust;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=35, nullable=true)
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="lsoa", type="string", length=45, nullable=true)
     */
    private $lsoa;

    /**
     * @var string
     *
     * @ORM\Column(name="msoa", type="string", length=45, nullable=true)
     */
    private $msoa;

    /**
     * @var string
     *
     * @ORM\Column(name="incode", type="string", length=4, nullable=true)
     */
    private $incode;

    /**
     * @var string
     *
     * @ORM\Column(name="outcode", type="string", length=4, nullable=true)
     */
    private $outcode;

    /**
     * @var string
     *
     * @ORM\Column(name="admin_district", type="string", length=35, nullable=true)
     */
    private $admin_district;

    /**
     * @var string
     *
     * @ORM\Column(name="parish", type="string", length=80, nullable=true)
     */
    private $parish;

    /**
     * @var string
     *
     * @ORM\Column(name="admin_county", type="string", length=80, nullable=true)
     */
    private $admin_county;

    /**
     * @var string
     *
     * @ORM\Column(name="admin_ward", type="string", length=80, nullable=true)
     */
    private $admin_ward;

    /**
     * @var string
     *
     * @ORM\Column(name="ccg", type="string", length=80, nullable=true)
     */
    private $ccg;

    /**
     * @var string
     *
     * @ORM\Column(name="nuts", type="string", length=80, nullable=true)
     */
    private $nuts;

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
     * Set pafPostcode
     *
     * @param \It121\AddressBundle\Entity\PafPostcode $user
     * @return poscodeIo
     */
    public function setPafPostcode(\It121\AddressBundle\Entity\PafPostcode $pafPostcode = null)
    {
        $this->pafPostcode = $pafPostcode;

        return $this;
    }


    /**
     * Get pafPostcode
     *
     * @return \It121\AddressBundle\Entity\PafPostcode
     */
    public function getPafPostcode()
    {
        return $this->pafPostcode;
    }

    /**
     * @return int
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * @param int $quality
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;
    }

    /**
     * @return int
     */
    public function getEastings()
    {
        return $this->eastings;
    }

    /**
     * @param int $eastings
     */
    public function setEastings($eastings)
    {
        $this->eastings = $eastings;
    }

    /**
     * @return int
     */
    public function getNorthings()
    {
        return $this->northings;
    }

    /**
     * @param int $northings
     */
    public function setNorthings($northings)
    {
        $this->northings = $northings;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getNhsHa()
    {
        return $this->nhs_ha;
    }

    /**
     * @param string $nhs_ha
     */
    public function setNhsHa($nhs_ha)
    {
        $this->nhs_ha = $nhs_ha;
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
     * @return string
     */
    public function getParliamentaryConstituency()
    {
        return $this->parliamentary_constituency;
    }

    /**
     * @param string $parliamentary_constituency
     */
    public function setParliamentaryConstituency($parliamentary_constituency)
    {
        $this->parliamentary_constituency = $parliamentary_constituency;
    }

    /**
     * @return string
     */
    public function getEuropeanElectoralRegion()
    {
        return $this->european_electoral_region;
    }

    /**
     * @param string $european_electoral_region
     */
    public function setEuropeanElectoralRegion($european_electoral_region)
    {
        $this->european_electoral_region = $european_electoral_region;
    }

    /**
     * @return string
     */
    public function getPrimaryCareTrust()
    {
        return $this->primary_care_trust;
    }

    /**
     * @param string $primary_care_trust
     */
    public function setPrimaryCareTrust($primary_care_trust)
    {
        $this->primary_care_trust = $primary_care_trust;
    }

    /**
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param string $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getLsoa()
    {
        return $this->lsoa;
    }

    /**
     * @param string $lsoa
     */
    public function setLsoa($lsoa)
    {
        $this->lsoa = $lsoa;
    }

    /**
     * @return string
     */
    public function getMsoa()
    {
        return $this->msoa;
    }

    /**
     * @param string $msoa
     */
    public function setMsoa($msoa)
    {
        $this->msoa = $msoa;
    }

    /**
     * @return string
     */
    public function getIncode()
    {
        return $this->incode;
    }

    /**
     * @param string $incode
     */
    public function setIncode($incode)
    {
        $this->incode = $incode;
    }

    /**
     * @return string
     */
    public function getOutcode()
    {
        return $this->outcode;
    }

    /**
     * @param string $outcode
     */
    public function setOutcode($outcode)
    {
        $this->outcode = $outcode;
    }

    /**
     * @return string
     */
    public function getAdminDistrict()
    {
        return $this->admin_district;
    }

    /**
     * @param string $admin_district
     */
    public function setAdminDistrict($admin_district)
    {
        $this->admin_district = $admin_district;
    }

    /**
     * @return string
     */
    public function getParish()
    {
        return $this->parish;
    }

    /**
     * @param string $parish
     */
    public function setParish($parish)
    {
        $this->parish = $parish;
    }

    /**
     * @return string
     */
    public function getAdminCounty()
    {
        return $this->admin_county;
    }

    /**
     * @param string $admin_county
     */
    public function setAdminCounty($admin_county)
    {
        $this->admin_county = $admin_county;
    }

    /**
     * @return string
     */
    public function getAdminWard()
    {
        return $this->admin_ward;
    }

    /**
     * @param string $admin_ward
     */
    public function setAdminWard($admin_ward)
    {
        $this->admin_ward = $admin_ward;
    }

    /**
     * @return string
     */
    public function getCcg()
    {
        return $this->ccg;
    }

    /**
     * @param string $ccg
     */
    public function setCcg($ccg)
    {
        $this->ccg = $ccg;
    }

    /**
     * @return string
     */
    public function getNuts()
    {
        return $this->nuts;
    }

    /**
     * @param string $nuts
     */
    public function setNuts($nuts)
    {
        $this->nuts = $nuts;
    }

    /**
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     * @return UserDetail
     */
    public function setCreatedBy($createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return integer 
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     * @return UserDetail
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime 
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set modifiedBy
     *
     * @param integer $modifiedBy
     * @return UserDetail
     */
    public function setModifiedBy($modifiedBy)
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    /**
     * Get modifiedBy
     *
     * @return integer 
     */
    public function getModifiedBy()
    {
        return $this->modifiedBy;
    }

    /**
     * Set modifiedDate
     *
     * @param \DateTime $modifiedDate
     * @return UserDetail
     */
    public function setModifiedDate($modifiedDate)
    {
        $this->modifiedDate = $modifiedDate;

        return $this;
    }

    /**
     * Get modifiedDate
     *
     * @return \DateTime 
     */
    public function getModifiedDate()
    {
        return $this->modifiedDate;
    }
}
