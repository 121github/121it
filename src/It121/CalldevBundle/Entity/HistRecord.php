<?php

namespace It121\CalldevBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistRecord
 *
 * @ORM\Table(name="`histcomp-academy`")
 * @ORM\Entity(repositoryClass="It121\CalldevBundle\Entity\HistRecordRepository")
 */
class HistRecord
{
    /**
     * @var string
     *
     * @ORM\Column(name="urn", type="string", length=15)
     * @ORM\Id
     */
    private $urn;

    /**
     * @var string
     *
     * @ORM\Column(name="contact", type="datetime")
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="exit", type="string", length=30)
     */
    private $exit;

    /**
     * @var string
     *
     * @ORM\Column(name="advisor", type="string", length=3)
     */
    private $advisor;

    /**
     * @return string
     */
    public function getUrn()
    {
        return $this->urn;
    }

    /**
     * @param string $urn
     */
    public function setUrn($urn)
    {
        $this->urn = $urn;
    }

    /**
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @param string $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    /**
     * @return string
     */
    public function getExit()
    {
        return $this->exit;
    }

    /**
     * @param string $exit
     */
    public function setExit($exit)
    {
        $this->exit = $exit;
    }

    /**
     * @return string
     */
    public function getAdvisor()
    {
        return $this->advisor;
    }

    /**
     * @param string $advisor
     */
    public function setAdvisor($advisor)
    {
        $this->advisor = $advisor;
    }
}
