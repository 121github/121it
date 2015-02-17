<?php

namespace It121\ServerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Server
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="It121\ServerBundle\Entity\ServerRepository")
 */
class Server
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
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=100)
     */
    private $domain;
    
    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=100, nullable=true)
     */
    private $path;
    
    /**
     * @var string
     *
     * @ORM\Column(name="user", type="string", length=100, nullable=true)
     */
    private $user;
    
    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     * 
     */
    private $password;

    /**
     * @var integer
     *
     * @ORM\Column(name="port", type="integer", nullable=true)
     */
    private $port;

    /**
     *
     * @ORM\ManyToOne(targetEntity="It121\ServerBundle\Entity\ServerType")
     */
    private $type;

    /**
     *
     * @ORM\ManyToOne(targetEntity="It121\ServerBundle\Entity\ServerSubtype")
     */
    private $subtype;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="It121\ServerBundle\Entity\ServerEnvironment")
     */
    private $environment;

    /**
     * @var string
     *
     * @ORM\Column(name="rss_url", type="string", length=255, nullable=true)
     *
     */
    private $rssUrl;
    
    /**
     * @var float
     *
     * @ORM\Column(name="latency", type="float", nullable=true)
     */
    private $latency;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_online", type="datetime", nullable=true)
     */
    private $lastOnline;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_check", type="datetime", nullable=true)
     */
    private $lastCheck;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="send_email", type="boolean")
     */
    private $sendEmail;

    /**
     * @var boolean
     *
     * @ORM\Column(name="monitoring", type="boolean")
     */
    private $monitoring;

    /**
     * @var string
     *
     * @ORM\OneToOne(targetEntity="It121\ProjectBundle\Entity\Project", mappedBy="server")
     */
    private $project;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="It121\ServerBundle\Entity\ServerStatus")
     * 
     */
    private $status;
    
    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=100, nullable=true)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=100, nullable=true)
     */
    private $logo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="shortcut", type="boolean")
     */
    private $shortcut;
    
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
     *
     * @return string
     */
    public function __toString()
    {
    	return $this->getName();
    }
    
    /**
     * Get Url
     *
     * @return string
     */
    public function getUrl()
    {
    	$prefix = "";
    	if ($this->getSubtype()->getName() == "FTP") {
    		$prefix = "ftp://";
    	}
    	elseif ($this->getType()->getName() == "Website") {
    		$prefix = "http://";
    	}
    	if ($this->getSubtype()->getName() == "SFTP") {
    		$prefix = "sftp://";
    	}
    	elseif ($this->getPort() == 80) {
    		$prefix = "http://";
    	}
    	return $prefix.$this->domain.":".$this->port."/".$this->path;
    }

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
     * Set name
     *
     * @param string $name
     * @return Server
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
     * Set domain
     *
     * @param string $domain
     * @return Server
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain
     *
     * @return string 
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set port
     *
     * @param integer $port
     * @return Server
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * Get port
     *
     * @return integer 
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * Set latency
     *
     * @param float $latency
     * @return Server
     */
    public function setLatency($latency)
    {
        $this->latency = $latency;

        return $this;
    }

    /**
     * Get latency
     *
     * @return float 
     */
    public function getLatency()
    {
        return $this->latency;
    }

    /**
     * Set lastOnline
     *
     * @param \DateTime $lastOnline
     * @return Server
     */
    public function setLastOnline($lastOnline)
    {
        $this->lastOnline = $lastOnline;

        return $this;
    }

    /**
     * Get lastOnline
     *
     * @return \DateTime 
     */
    public function getLastOnline()
    {
        return $this->lastOnline;
    }

    /**
     * Set sendEmail
     *
     * @param boolean $sendEmail
     * @return Server
     */
    public function setSendEmail($sendEmail)
    {
        $this->sendEmail = $sendEmail;

        return $this;
    }

    /**
     * Get sendEmail
     *
     * @return boolean 
     */
    public function getSendEmail()
    {
        return $this->sendEmail;
    }

    /**
     * Set monitoring
     *
     * @param boolean $monitoring
     * @return Server
     */
    public function setMonitoring($monitoring)
    {
        $this->monitoring = $monitoring;

        return $this;
    }

    /**
     * Get monitoring
     *
     * @return boolean 
     */
    public function getMonitoring()
    {
        return $this->monitoring;
    }

    /**
     * Set createdBy
     *
     * @param integer $createdBy
     * @return Server
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
     * @return Server
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
     * @return Server
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
     * @return Server
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

    /**
     * Set type
     *
     * @param \It121\ServerBundle\Entity\ServerType $type
     * @return Server
     */
    public function setType(\It121\ServerBundle\Entity\ServerType $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \It121\ServerBundle\Entity\ServerType 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set subtype
     *
     * @param \It121\ServerBundle\Entity\ServerSubtype $subtype
     * @return Server
     */
    public function setSubtype(\It121\ServerBundle\Entity\ServerSubtype $subtype = null)
    {
        $this->subtype = $subtype;

        return $this;
    }

    /**
     * Get subtype
     *
     * @return \It121\ServerBundle\Entity\ServerSubtype 
     */
    public function getSubtype()
    {
        return $this->subtype;
    }
    
    /**
     * Set environment
     *
     * @param \It121\ServerBundle\Entity\ServerEnvironment $environment
     * @return Server
     */
    public function setEnvironment(\It121\ServerBundle\Entity\ServerEnvironment $environment = null)
    {
    	$this->environment = $environment;
    
    	return $this;
    }
    
    /**
     * Get environment
     *
     * @return \It121\ServerBundle\Entity\ServerEnvironment
     */
    public function getEnvironment()
    {
    	return $this->environment;
    }

    
    /**
     * Set lastCheck
     *
     * @param \DateTime $lastCheck
     * @return Server
     */
    public function setLastCheck($lastCheck)
    {
        $this->lastCheck = $lastCheck;

        return $this;
    }

    /**
     * Get lastCheck
     *
     * @return \DateTime 
     */
    public function getLastCheck()
    {
        return $this->lastCheck;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return Server
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Server
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Server
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set status
     *
     * @param \It121\ServerBundle\Entity\ServerStatus $status
     * @return Server
     */
    public function setStatus(\It121\ServerBundle\Entity\ServerStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \It121\ServerBundle\Entity\ServerStatus 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set project
     *
     * @param \It121\ProjectBundle\Entity\Project $project
     * @return Server
     */
    public function setProject(\It121\ProjectBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \It121\ProjectBundle\Entity\Project 
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Server
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set rssUrl
     *
     * @param string $rssUrl
     * @return Server
     */
    public function setRssUrl($rssUrl)
    {
        $this->rssUrl = $rssUrl;

        return $this;
    }

    /**
     * Get rssUrl
     *
     * @return string 
     */
    public function getRssUrl()
    {
        return $this->rssUrl;
    }

    /**
     * Set logo
     *
     * @param string $logo
     * @return Server
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set shortcut
     *
     * @param boolean $shortcut
     * @return Server
     */
    public function setShortcut($shortcut)
    {
        $this->shortcut = $shortcut;

        return $this;
    }

    /**
     * Get shortcut
     *
     * @return boolean
     */
    public function getShortcut()
    {
        return $this->shortcut;
    }
}
