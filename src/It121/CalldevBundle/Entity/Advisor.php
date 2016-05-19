<?php

namespace It121\CalldevBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Advisor
 *
 * @ORM\Table(name="advisor")
 * @ORM\Entity(repositoryClass="It121\CalldevBundle\Entity\AdvisorRepository")
 */
class Advisor
{
    /**
     * @var string
     *
     * @ORM\Column(name="ext", type="string", length=3)
     * @ORM\Id
     */
    private $ext;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=40)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=30)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=32)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="lastlog", type="datetime")
     */
    private $lastlog;

    /**
     * @var string
     *
     * @ORM\Column(name="manager", type="string", length=30)
     */
    private $manager;

    /**
     * @var string
     *
     * @ORM\Column(name="live", type="integer", length=2)
     */
    private $live;

    /**
     * @var string
     *
     * @ORM\Column(name="desk", type="string", length=10)
     */
    private $desk;

    /**
     * @var string
     *
     * @ORM\Column(name="last_ip", type="string", length=16)
     */
    private $last_ip;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getLastlog()
    {
        return $this->lastlog;
    }

    /**
     * @param string $lastlog
     */
    public function setLastlog($lastlog)
    {
        $this->lastlog = $lastlog;
    }

    /**
     * @return string
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param string $manager
     */
    public function setManager($manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return string
     */
    public function getLive()
    {
        return $this->live;
    }

    /**
     * @param string $live
     */
    public function setLive($live)
    {
        $this->live = $live;
    }

    /**
     * @return string
     */
    public function getDesk()
    {
        return $this->desk;
    }

    /**
     * @param string $desk
     */
    public function setDesk($desk)
    {
        $this->desk = $desk;
    }

    /**
     * @return string
     */
    public function getLastIp()
    {
        return $this->last_ip;
    }

    /**
     * @param string $last_ip
     */
    public function setLastIp($last_ip)
    {
        $this->last_ip = $last_ip;
    }

}
