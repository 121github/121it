<?php

namespace It121\CalldevBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DataLeft
 *
 * @ORM\Table(name="DataLeft")
 * @ORM\Entity(repositoryClass="It121\CalldevBundle\Entity\CalldevRepository")
 */
class DataLeft
{
    /**
     * @var string
     *
     * @ORM\Column(name="callbacks", type="string", length=5)
     * @ORM\Id
     */
    private $callbacks;

    /**
     * @var string
     *
     * @ORM\Column(name="campaign", type="string", length=30)
     */
    private $campaign;

    /**
     * @var string
     *
     * @ORM\Column(name="averageDials", type="string", length=10)
     */
    private $averageDials;

    /**
     * @var string
     *
     * @ORM\Column(name="agents", type="string", length=3)
     */
    private $agents;

    /**
     * @var string
     *
     * @ORM\Column(name="minsRemain", type="string", length=10)
     */
    private $minsRemain;

    /**
     * @return string
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * @param string $campaign
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * @return string
     */
    public function getCallbacks()
    {
        return $this->callbacks;
    }

    /**
     * @param string $callbacks
     */
    public function setCallbacks($callbacks)
    {
        $this->callbacks = $callbacks;
    }

    /**
     * @return string
     */
    public function getAverageDials()
    {
        return $this->averageDials;
    }

    /**
     * @param string $averageDials
     */
    public function setAverageDials($averageDials)
    {
        $this->averageDials = $averageDials;
    }

    /**
     * @return string
     */
    public function getAgents()
    {
        return $this->agents;
    }

    /**
     * @param string $agents
     */
    public function setAgents($agents)
    {
        $this->agents = $agents;
    }

    /**
     * @return string
     */
    public function getMinsRemain()
    {
        return $this->minsRemain;
    }

    /**
     * @param string $minsRemain
     */
    public function setMinsRemain($minsRemain)
    {
        $this->minsRemain = $minsRemain;
    }
}
