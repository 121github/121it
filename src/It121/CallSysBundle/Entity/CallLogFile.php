<?php

namespace It121\CallSysBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CallLogFile
 *
 * @ORM\Table(name="call_log_file")
 * @ORM\Entity
 */
class CallLogFile
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
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="file_date", type="date")
     */
    private $fileDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="unit", type="integer")
     */
    private $unit;

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
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CallLogFile
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
     * Set fileDate
     *
     * @param \DateTime $fileDate
     * @return CallLog
     */
    public function setFileDate($fileDate)
    {
        $this->fileDate = $fileDate;

        return $this;
    }

    /**
     * Get fileDate
     *
     * @return \DateTime
     */
    public function getFileDate()
    {
        return $this->fileDate;
    }

    /**
     * Set unit
     *
     * @param integer $unit
     * @return CallLog
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return integer
     */
    public function getUnit()
    {
        return $this->unit;
    }

}
