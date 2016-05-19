<?php

namespace It121\CalldevBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdvisorPhones
 *
 * @ORM\Table(name="`advisor-phones`")
 * @ORM\Entity(repositoryClass="It121\CalldevBundle\Entity\AdvisorRepository")
 */
class AdvisorPhones
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ext", type="string", length=3)
     *
     */
    private $ext;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=3)
     */
    private $phone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime")
     */
    private $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finish", type="datetime")
     */
    private $finish;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     *
     * @ORM\ManyToOne(targetEntity="It121\CalldevBundle\Entity\Advisor")
     * @ORM\JoinColumn(name="ext", referencedColumnName="ext")
     */
    private $advisor;

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getExt()." - ".$this->getPhone()." - ".$this->getStart()->format("Ymd H:i:s")." - ".$this->getFinish()->format("Ymd H:i:s");
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param string $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return string
     */
    public function getFinish()
    {
        return $this->finish;
    }

    /**
     * @param string $finish
     */
    public function setFinish($finish)
    {
        $this->finish = $finish;
    }

    /**
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     *
     * @return \It121\CalldevBundle\Entity\Advisor
     */
    public function getAdvisor()
    {
        return $this->advisor;
    }

    /**
     * @param \It121\CalldevBundle\Entity\Advisor $ext
     * @return Advisor
     *
     */
    public function setAdvisor(\It121\CalldevBundle\Entity\Advisor $advisor = null)
    {
        $this->advisor = $advisor;
    }

}
