<?php

namespace It121\AddressBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use It121\AddressBundle\EventListener\PafPostcodeListener;

/**
 * PafPostcode
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="It121\AddressBundle\Entity\PafPostcodeRepository")
// * @ORM\EntityListeners("It121\AddressBundle\EventListener\PafPostcodeListener")
 */
class PafPostcode
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
     * @ORM\Column(name="post_town", type="string", length=30, nullable=true)
     */
    private $postTown;

    /**
     * @var string
     *
     * @ORM\Column(name="dependent_locality", type="string", length=35, nullable=true)
     */
    private $dependentLocality;

    /**
     * @var string
     *
     * @ORM\Column(name="double_dependent_locality", type="string", length=35, nullable=true)
     */
    private $doubleDependentLocality;

    /**
     * @var string
     *
     * @ORM\Column(name="thoroughfare_and_descriptor", type="string", length=80, nullable=true)
     */
    private $thoroughfareAndDescriptor;

    /**
     * @var string
     *
     * @ORM\Column(name="dependent_thoroughfare_and_descriptor", type="string", length=80, nullable=true)
     */
    private $dependentThoroughfareAndDescriptor;

    /**
     * @var integer
     *
     * @ORM\Column(name="building_number", type="integer", nullable=true)
     */
    private $buildingNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="building_name", type="string", length=50, nullable=true)
     */
    private $buildingName;

    /**
     * @var string
     *
     * @ORM\Column(name="sub_building_name", type="string", length=30, nullable=true)
     */
    private $subBuildingName;

    /**
     * @var string
     *
     * @ORM\Column(name="po_box", type="string", length=6, nullable=true)
     */
    private $poBox;

    /**
     * @var string
     *
     * @ORM\Column(name="department_name", type="string", length=60, nullable=true)
     */
    private $departmentName;

    /**
     * @var string
     *
     * @ORM\Column(name="organisation_name", type="string", length=60, nullable=true)
     */
    private $organisationName;

    /**
     * @var integer
     *
     * @ORM\Column(name="udprn", type="integer", nullable=true)
     */
    private $udprn;

    /**
     * @var string
     *
     * @ORM\Column(name="postcode_type", type="string", length=1, nullable=true)
     */
    private $postcodeType;

    /**
     * @var string
     *
     * @ORM\Column(name="su_organisation_indicator", type="string", length=1, nullable=true)
     */
    private $suOrganisationIndicator;

    /**
     * @var string
     *
     * @ORM\Column(name="delivery_point_suffix", type="string", length=2, nullable=true)
     */
    private $deliveryPointSuffix;

    /**
     * @ORM\OneToOne(targetEntity="It121\AddressBundle\Entity\PostcodeIo", mappedBy="pafPostcode", cascade={"persist", "remove" })
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
     * Set postTown
     *
     * @param string $postTown
     *
     * @return PafPostcode
     */
    public function setPostTown($postTown)
    {
        $this->postTown = $postTown;

        return $this;
    }

    /**
     * Get postTown
     *
     * @return string
     */
    public function getPostTown()
    {
        return $this->postTown;
    }

    /**
     * Set dependentLocality
     *
     * @param string $dependentLocality
     *
     * @return PafPostcode
     */
    public function setDependentLocality($dependentLocality)
    {
        $this->dependentLocality = $dependentLocality;

        return $this;
    }

    /**
     * Get dependentLocality
     *
     * @return string
     */
    public function getDependentLocality()
    {
        return $this->dependentLocality;
    }

    /**
     * Set doubleDependentLocality
     *
     * @param string $doubleDependentLocality
     *
     * @return PafPostcode
     */
    public function setDoubleDependentLocality($doubleDependentLocality)
    {
        $this->doubleDependentLocality = $doubleDependentLocality;

        return $this;
    }

    /**
     * Get doubleDependentLocality
     *
     * @return string
     */
    public function getDoubleDependentLocality()
    {
        return $this->doubleDependentLocality;
    }

    /**
     * Set thoroughfareAndDescriptor
     *
     * @param string $thoroughfareAndDescriptor
     *
     * @return PafPostcode
     */
    public function setThoroughfareAndDescriptor($thoroughfareAndDescriptor)
    {
        $this->thoroughfareAndDescriptor = $thoroughfareAndDescriptor;

        return $this;
    }

    /**
     * Get thoroughfareAndDescriptor
     *
     * @return string
     */
    public function getThoroughfareAndDescriptor()
    {
        return $this->thoroughfareAndDescriptor;
    }

    /**
     * Set dependentThoroughfareAndDescriptor
     *
     * @param string $dependentThoroughfareAndDescriptor
     *
     * @return PafPostcode
     */
    public function setDependentThoroughfareAndDescriptor($dependentThoroughfareAndDescriptor)
    {
        $this->dependentThoroughfareAndDescriptor = $dependentThoroughfareAndDescriptor;

        return $this;
    }

    /**
     * Get dependentThoroughfareAndDescriptor
     *
     * @return string
     */
    public function getDependentThoroughfareAndDescriptor()
    {
        return $this->dependentThoroughfareAndDescriptor;
    }

    /**
     * Set buildingNumber
     *
     * @param integer $buildingNumber
     *
     * @return PafPostcode
     */
    public function setBuildingNumber($buildingNumber)
    {
        $this->buildingNumber = $buildingNumber;

        return $this;
    }

    /**
     * Get buildingNumber
     *
     * @return integer
     */
    public function getBuildingNumber()
    {
        return $this->buildingNumber;
    }

    /**
     * Set buildingName
     *
     * @param string $buildingName
     *
     * @return PafPostcode
     */
    public function setBuildingName($buildingName)
    {
        $this->buildingName = $buildingName;

        return $this;
    }

    /**
     * Get buildingName
     *
     * @return string
     */
    public function getBuildingName()
    {
        return $this->buildingName;
    }

    /**
     * Set subBuildingName
     *
     * @param string $subBuildingName
     *
     * @return PafPostcode
     */
    public function setSubBuildingName($subBuildingName)
    {
        $this->subBuildingName = $subBuildingName;

        return $this;
    }

    /**
     * Get subBuildingName
     *
     * @return string
     */
    public function getSubBuildingName()
    {
        return $this->subBuildingName;
    }

    /**
     * Set poBox
     *
     * @param string $poBox
     *
     * @return PafPostcode
     */
    public function setPoBox($poBox)
    {
        $this->poBox = $poBox;

        return $this;
    }

    /**
     * Get poBox
     *
     * @return string
     */
    public function getPoBox()
    {
        return $this->poBox;
    }

    /**
     * Set departmentName
     *
     * @param string $departmentName
     *
     * @return PafPostcode
     */
    public function setDepartmentName($departmentName)
    {
        $this->departmentName = $departmentName;

        return $this;
    }

    /**
     * Get departmentName
     *
     * @return string
     */
    public function getDepartmentName()
    {
        return $this->departmentName;
    }

    /**
     * Set organisationName
     *
     * @param string $organisationName
     *
     * @return PafPostcode
     */
    public function setOrganisationName($organisationName)
    {
        $this->organisationName = $organisationName;

        return $this;
    }

    /**
     * Get organisationName
     *
     * @return string
     */
    public function getOrganisationName()
    {
        return $this->organisationName;
    }

    /**
     * Set udprn
     *
     * @param integer $udprn
     *
     * @return PafPostcode
     */
    public function setUdprn($udprn)
    {
        $this->udprn = $udprn;

        return $this;
    }

    /**
     * Get udprn
     *
     * @return integer
     */
    public function getUdprn()
    {
        return $this->udprn;
    }

    /**
     * Set postcodeType
     *
     * @param string $postcodeType
     *
     * @return PafPostcode
     */
    public function setPostcodeType($postcodeType)
    {
        $this->postcodeType = $postcodeType;

        return $this;
    }

    /**
     * Get postcodeType
     *
     * @return string
     */
    public function getPostcodeType()
    {
        return $this->postcodeType;
    }

    /**
     * Set suOrganisationIndicator
     *
     * @param string $suOrganisationIndicator
     *
     * @return PafPostcode
     */
    public function setSuOrganisationIndicator($suOrganisationIndicator)
    {
        $this->suOrganisationIndicator = $suOrganisationIndicator;

        return $this;
    }

    /**
     * Get suOrganisationIndicator
     *
     * @return string
     */
    public function getSuOrganisationIndicator()
    {
        return $this->suOrganisationIndicator;
    }

    /**
     * Set deliveryPointSuffix
     *
     * @param string $deliveryPointSuffix
     *
     * @return PafPostcode
     */
    public function setDeliveryPointSuffix($deliveryPointSuffix)
    {
        $this->deliveryPointSuffix = $deliveryPointSuffix;

        return $this;
    }

    /**
     * Get deliveryPointSuffix
     *
     * @return string
     */
    public function getDeliveryPointSuffix()
    {
        return $this->deliveryPointSuffix;
    }

    /**
     * Set PostcodeIo
     *
     * @param \It121\AddressBundle\Entity\PostcodeIo $postcodeIo
     * @return PafPostcode
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

