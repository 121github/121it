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
     * @ORM\Column(name="col_h", type="string", length=100)
     */
    private $colH;

    /**
     * @var integer
     *
     * @ORM\Column(name="col_i", type="integer")
     */
    private $colI;

    /**
     * @var integer
     *
     * @ORM\Column(name="col_k", type="integer")
     */
    private $colK;

    /**
     * @var integer
     *
     * @ORM\Column(name="col_p", type="integer")
     */
    private $colP;

    /**
     * @var integer
     *
     * @ORM\Column(name="col_q", type="integer")
     */
    private $colQ;

    /**
     * @var string
     *
     * @ORM\Column(name="col_r", type="string", length=100)
     */
    private $colR;

    /**
     * @var string
     *
     * @ORM\Column(name="col_s", type="string", length=100)
     */
    private $colS;

    /**
     * @var string
     *
     * @ORM\Column(name="col_t", type="string", length=100)
     */
    private $colT;

    /**
     * @var integer
     *
     * @ORM\Column(name="col_u", type="integer")
     */
    private $colU;

    /**
     * @var string
     *
     * @ORM\Column(name="col_v", type="string", length=100)
     */
    private $colV;

    /**
     * @var integer
     *
     * @ORM\Column(name="col_w", type="integer")
     */
    private $colW;

    /**
     * @var integer
     *
     * @ORM\Column(name="col_x", type="integer")
     */
    private $colX;

    /**
     * @var integer
     *
     * @ORM\Column(name="col_y", type="integer")
     */
    private $colY;

    /**
     * @var integer
     *
     * @ORM\Column(name="col_z", type="integer")
     */
    private $colZ;

    /**
     * @var integer
     *
     * @ORM\Column(name="col_a_a", type="integer")
     */
    private $colAA;

    /**
     * @var string
     *
     * @ORM\Column(name="col_a_b", type="string", length=100)
     */
    private $colAB;

    /**
     * @var string
     *
     * @ORM\Column(name="col_a_c", type="string", length=100)
     */
    private $colAC;


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
     * @return string
     */
    public function getColH()
    {
        return $this->colH;
    }

    /**
     * @param string $colH
     */
    public function setColH($colH)
    {
        $this->colH = $colH;
    }

    /**
     * @return int
     */
    public function getColI()
    {
        return $this->colI;
    }

    /**
     * @param int $colI
     */
    public function setColI($colI)
    {
        $this->colI = $colI;
    }

    /**
     * @return int
     */
    public function getColK()
    {
        return $this->colK;
    }

    /**
     * @param int $colK
     */
    public function setColK($colK)
    {
        $this->colK = $colK;
    }

    /**
     * @return int
     */
    public function getColP()
    {
        return $this->colP;
    }

    /**
     * @param int $colP
     */
    public function setColP($colP)
    {
        $this->colP = $colP;
    }

    /**
     * @return int
     */
    public function getColQ()
    {
        return $this->colQ;
    }

    /**
     * @param int $colQ
     */
    public function setColQ($colQ)
    {
        $this->colQ = $colQ;
    }

    /**
     * @return string
     */
    public function getColR()
    {
        return $this->colR;
    }

    /**
     * @param string $colR
     */
    public function setColR($colR)
    {
        $this->colR = $colR;
    }

    /**
     * @return string
     */
    public function getColS()
    {
        return $this->colS;
    }

    /**
     * @param string $colS
     */
    public function setColS($colS)
    {
        $this->colS = $colS;
    }

    /**
     * @return string
     */
    public function getColT()
    {
        return $this->colT;
    }

    /**
     * @param string $colT
     */
    public function setColT($colT)
    {
        $this->colT = $colT;
    }

    /**
     * @return int
     */
    public function getColU()
    {
        return $this->colU;
    }

    /**
     * @param int $colU
     */
    public function setColU($colU)
    {
        $this->colU = $colU;
    }

    /**
     * @return string
     */
    public function getColV()
    {
        return $this->colV;
    }

    /**
     * @param string $colV
     */
    public function setColV($colV)
    {
        $this->colV = $colV;
    }

    /**
     * @return int
     */
    public function getColW()
    {
        return $this->colW;
    }

    /**
     * @param int $colW
     */
    public function setColW($colW)
    {
        $this->colW = $colW;
    }

    /**
     * @return int
     */
    public function getColX()
    {
        return $this->colX;
    }

    /**
     * @param int $colX
     */
    public function setColX($colX)
    {
        $this->colX = $colX;
    }

    /**
     * @return int
     */
    public function getColY()
    {
        return $this->colY;
    }

    /**
     * @param int $colY
     */
    public function setColY($colY)
    {
        $this->colY = $colY;
    }

    /**
     * @return int
     */
    public function getColZ()
    {
        return $this->colZ;
    }

    /**
     * @param int $colZ
     */
    public function setColZ($colZ)
    {
        $this->colZ = $colZ;
    }

    /**
     * @return int
     */
    public function getColAA()
    {
        return $this->colAA;
    }

    /**
     * @param int $colAA
     */
    public function setColAA($colAA)
    {
        $this->colAA = $colAA;
    }

    /**
     * @return string
     */
    public function getColAB()
    {
        return $this->colAB;
    }

    /**
     * @param string $colAB
     */
    public function setColAB($colAB)
    {
        $this->colAB = $colAB;
    }

    /**
     * @return string
     */
    public function getColAC()
    {
        return $this->colAC;
    }

    /**
     * @param string $colAC
     */
    public function setColAC($colAC)
    {
        $this->colAC = $colAC;
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
