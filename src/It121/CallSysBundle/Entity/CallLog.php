<?php

namespace It121\CallSysBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CallLog
 *
 * @ORM\Table(name="call_log")
 * @ORM\Entity(repositoryClass="It121\CallSysBundle\Entity\CallLogRepository")
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
     * @var integer
     *
     * @ORM\Column(name="ring_time", type="integer")
     */
    private $ringTime;

    /**
     * @var string
     *
     * @ORM\Column(name="call_id", type="string")
     */
    private $callId;

    /**
     * @var string
     *
     * @ORM\Column(name="call_from", type="string", length=100)
     */
    private $callFrom;

    /**
     * @var string
     *
     * @ORM\Column(name="name_from", type="string", length=100)
     */
    private $nameFrom;

    /**
     * @var string
     *
     * @ORM\Column(name="ref_from", type="string", length=100)
     */
    private $refFrom;

    /**
     * @var string
     *
     * @ORM\Column(name="call_to", type="string", length=100)
     */
    private $callTo;

    /**
     * @var string
     *
     * @ORM\Column(name="call_to_ext", type="string", length=100)
     */
    private $callToExt;

    /**
     * @var string
     *
     * @ORM\Column(name="name_to", type="string", length=100)
     */
    private $nameTo;

    /**
     * @var string
     *
     * @ORM\Column(name="ref_to", type="string", length=100)
     */
    private $refTo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="inbound", type="boolean")
     */
    private $inbound;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="It121\CallSysBundle\Entity\CallLogFile")
     */
    private $file;

    /**
     * @var integer
     *
     * @ORM\Column(name="campaign_id", type="integer", nullable=true)
     */
    private $campaignId;

    /**
     * @var string
     *
     * @ORM\Column(name="ext", type="string", length=3, nullable=true)
     */
    private $ext;

    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=40, nullable=true)
     */
    private $user;

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
     * @return int
     */
    public function getRingTime()
    {
        return $this->ringTime;
    }

    /**
     * @param int $ringTime
     */
    public function setRingTime($ringTime)
    {
        $this->ringTime = $ringTime;
    }

    /**
     * @return string
     */
    public function getCallId()
    {
        return $this->callId;
    }

    /**
     * @param string $callId
     */
    public function setCallId($callId)
    {
        $this->callId = $callId;
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
     * @return string
     */
    public function getNameFrom()
    {
        return $this->nameFrom;
    }

    /**
     * @param string $nameFrom
     */
    public function setNameFrom($nameFrom)
    {
        $this->nameFrom = $nameFrom;
    }

    /**
     * @return string
     */
    public function getRefFrom()
    {
        return $this->refFrom;
    }

    /**
     * @param string $refFrom
     */
    public function setRefFrom($refFrom)
    {
        $this->refFrom = $refFrom;
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
     * @return string
     */
    public function getCallToExt()
    {
        return $this->callToExt;
    }

    /**
     * @param string $callToExt
     */
    public function setCallToExt($callToExt)
    {
        $this->callToExt = $callToExt;
    }

    /**
     * @return string
     */
    public function getNameTo()
    {
        return $this->nameTo;
    }

    /**
     * @param string $nameTo
     */
    public function setNameTo($nameTo)
    {
        $this->nameTo = $nameTo;
    }

    /**
     * @return string
     */
    public function getRefTo()
    {
        return $this->refTo;
    }

    /**
     * @param string $refTo
     */
    public function setRefTo($refTo)
    {
        $this->refTo = $refTo;
    }

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
     * Get inbound
     *
     * @return boolean
     */
    public function getInbound()
    {
        return $this->inbound;
    }

    /**
     * Set file
     *
     * @param \It121\CallSysBundle\Entity\CallLogFile $file
     * @return CallLogFile
     */
    public function setFile(\It121\CallSysBundle\Entity\CallLogFile $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \It121\CallSysBundle\Entity\CallLogFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return int
     */
    public function getCampaignId()
    {
        return $this->campaignId;
    }

    /**
     * @param int $campaignId
     */
    public function setCampaignId($campaignId)
    {
        $this->campaignId = $campaignId;
    }

    /**
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * @param string $ext
     */
    public function setExt($ext)
    {
        $this->ext = $ext;
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

}
