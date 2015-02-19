<?php

namespace It121\LogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CallLog
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="It121\LogBundle\Entity\CallLogRepository")
 */
class CallLog
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
     * Get inbound
     *
     * @return boolean
     */
    public function getInbound()
    {
        return $this->inbound;
    }

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="call_date", type="datetime")
     */
    private $callDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="duration", type="time")
     */
    private $duration;

    /**
     * @var string
     *
     * @ORM\Column(name="call_from", type="string", length=100)
     */
    private $callFrom;

    /**
     * @var string
     *
     * @ORM\Column(name="call_to", type="string", length=100)
     */
    private $callTo;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="It121\LogBundle\Entity\CallLogFile")
     */
    private $file;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer")
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $createdDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer")
     */
    private $modifiedBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified_date", type="datetime")
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
     * @var boolean
     *
     * @ORM\Column(name="inbound", type="boolean")
     */
    private $inbound;

    /**
     * Set inbound
     *
     * @param boolean $inbound
     * @return CallLog
     */
    public function setInbound($inbound)
    {
        $this->inbound = $inbound;

        return $this;
    }

    /**
     * Set callDate
     *
     * @param \DateTime $callDate
     * @return CallLog
     */
    public function setCallDate($callDate)
    {
        $this->callDate = $callDate;

        return $this;
    }

    /**
     * Get callDate
     *
     * @return \DateTime 
     */
    public function getCallDate()
    {
        return $this->callDate;
    }

    /**
     * Set duration
     *
     * @param \DateTime $duration
     * @return CallLog
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return \DateTime 
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Set callFrom
     *
     * @param string $callFrom
     * @return CallLog
     */
    public function setCallFrom($callFrom)
    {
        $this->callFrom = $callFrom;

        return $this;
    }

    /**
     * Get callFrom
     *
     * @return string 
     */
    public function getCallFrom()
    {
        return $this->callFrom;
    }

    /**
     * Set callTo
     *
     * @param string $callTo
     * @return CallLog
     */
    public function setCallTo($callTo)
    {
        $this->callTo = $callTo;

        return $this;
    }

    /**
     * Get callTo
     *
     * @return string 
     */
    public function getCallTo()
    {
        return $this->callTo;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CallLog
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set file
     *
     * @param \It121\LogBundle\Entity\CallLogFile $file
     * @return CallLogFile
     */
    public function setFile(\It121\LogBundle\Entity\CallLogFile $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \It121\LogBundle\Entity\CallLogFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     * @return CallLog
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
     * @return CallLog
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
     * @return CallLog
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
     * @return CallLog
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
