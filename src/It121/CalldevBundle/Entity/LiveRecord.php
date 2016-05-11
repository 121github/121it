<?php

namespace It121\CalldevBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LiveRecord
 *
 * @ORM\Table(name="`live-academy`")
 * @ORM\Entity(repositoryClass="It121\CalldevBundle\Entity\LiveRecordRepository")
 */
class LiveRecord
{
    /**
     * @var string
     *
     * @ORM\Column(name="origurn", type="string", length=10)
     * @ORM\Id
     */
    private $origurn;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=5)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="forename", type="string", length=30)
     */
    private $forename;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=30)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="add1", type="string", length=30)
     */
    private $add1;


    /**
     * @var string
     *
     * @ORM\Column(name="add2", type="string", length=30)
     */
    private $add2;

    /**
     * @var string
     *
     * @ORM\Column(name="add3", type="string", length=30)
     */
    private $add3;

    /**
     * @var string
     *
     * @ORM\Column(name="town", type="string", length=30)
     */
    private $town;

    /**
     * @var string
     *
     * @ORM\Column(name="postcode", type="string", length=10)
     */
    private $postcode;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=15)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="tps", type="string", length=5)
     */
    private $tps;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=15)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="mob_tps", type="string", length=5)
     */
    private $mobTps;

    /**
     * @var string
     *
     * @ORM\Column(name="altnumber", type="string", length=15)
     */
    private $altnumber;

    /**
     * @var string
     *
     * @ORM\Column(name="agerange", type="string", length=10)
     */
    private $agerange;

    /**
     * @var string
     *
     * @ORM\Column(name="postdist", type="string", length=8)
     */
    private $postdist;

    /**
     * @var string
     *
     * @ORM\Column(name="postarea", type="string", length=6)
     */
    private $postarea;

    /**
     * @var string
     *
     * @ORM\Column(name="gender", type="string", length=1)
     */
    private $gender;

    /**
     * @var string
     *
     * @ORM\Column(name="dob", type="date")
     */
    private $dob;

    /**
     * @var string
     *
     * @ORM\Column(name="renewal", type="date")
     */
    private $renewal;

    /**
     * @var string
     *
     * @ORM\Column(name="branchno", type="string", length=3)
     */
    private $branchno;

    /**
     * @var string
     *
     * @ORM\Column(name="branchdesc", type="string", length=30)
     */
    private $branchdesc;

    /**
     * @var string
     *
     * @ORM\Column(name="sourcecode", type="string", length=30)
     */
    private $sourcecode;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=20)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="record_type", type="string", length=20)
     */
    private $recordType;

    /**
     * @var string
     *
     * @ORM\Column(name="fieldid", type="string", length=15)
     */
    private $fieldid;

    /**
     * @var string
     *
     * @ORM\Column(name="premium", type="float")
     */
    private $premium;

    /**
     * @var string
     *
     * @ORM\Column(name="gid", type="string", length=20)
     */
    private $gid;

    /**
     * @var string
     *
     * @ORM\Column(name="lastcall", type="datetime")
     */
    private $lastcall;

    /**
     * @var string
     *
     * @ORM\Column(name="nextcall", type="datetime")
     */
    private $nextcall;

    /**
     * @var string
     *
     * @ORM\Column(name="dials", type="integer", length=3)
     */
    private $dials;

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
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=20)
     */
    private $source;


    /**
     * @var string
     *
     * @ORM\Column(name="transferno", type="string", length=15)
     */
    private $transferno;

    /**
     * @var string
     *
     * @ORM\Column(name="campurn", type="string", length=10)
     */
    private $campurn;

    /**
     * @var string
     *
     * @ORM\Column(name="comments", type="text")
     */
    private $comments;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="pmc_transferno", type="string", length=15)
     */
    private $pmcTransferno;

    /**
     * @var string
     *
     * @ORM\Column(name="cv_transferno", type="string", length=15)
     */
    private $cvTransferno;

    /**
     * @var string
     *
     * @ORM\Column(name="pmc_renewal", type="date")
     */
    private $pmcRenewal;

    /**
     * @var string
     *
     * @ORM\Column(name="branch_drop", type="text")
     */
    private $branchDrop;

    /**
     * @var string
     *
     * @ORM\Column(name="premium_value", type="text")
     */
    private $premiumValue;

    /**
     * @var string
     *
     * @ORM\Column(name="urn", type="string", length=15)
     */
    private $urn;

    /**
     * @var string
     *
     * @ORM\Column(name="import_details", type="string", length=30)
     */
    private $importDetails;


    /**
     * @return string
     */
    public function getOrigurn()
    {
        return $this->origurn;
    }

    /**
     * @param string $origurn
     */
    public function setOrigurn($origurn)
    {
        $this->origurn = $origurn;
    }

    public function getTableName() {
        return "asd";
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getForename()
    {
        return $this->forename;
    }

    /**
     * @param string $forename
     */
    public function setForename($forename)
    {
        $this->forename = $forename;
    }

    /**
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return string
     */
    public function getAdd1()
    {
        return $this->add1;
    }

    /**
     * @param string $add1
     */
    public function setAdd1($add1)
    {
        $this->add1 = $add1;
    }

    /**
     * @return string
     */
    public function getAdd2()
    {
        return $this->add2;
    }

    /**
     * @param string $add2
     */
    public function setAdd2($add2)
    {
        $this->add2 = $add2;
    }

    /**
     * @return string
     */
    public function getAdd3()
    {
        return $this->add3;
    }

    /**
     * @param string $add3
     */
    public function setAdd3($add3)
    {
        $this->add3 = $add3;
    }

    /**
     * @return string
     */
    public function getTown()
    {
        return $this->town;
    }

    /**
     * @param string $town
     */
    public function setTown($town)
    {
        $this->town = $town;
    }

    /**
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
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
    public function getTps()
    {
        return $this->tps;
    }

    /**
     * @param string $tps
     */
    public function setTps($tps)
    {
        $this->tps = $tps;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * @return string
     */
    public function getMobTps()
    {
        return $this->mobTps;
    }

    /**
     * @param string $mobTps
     */
    public function setMobTps($mobTps)
    {
        $this->mobTps = $mobTps;
    }

    /**
     * @return string
     */
    public function getAltnumber()
    {
        return $this->altnumber;
    }

    /**
     * @param string $altnumber
     */
    public function setAltnumber($altnumber)
    {
        $this->altnumber = $altnumber;
    }

    /**
     * @return string
     */
    public function getAgerange()
    {
        return $this->agerange;
    }

    /**
     * @param string $agerange
     */
    public function setAgerange($agerange)
    {
        $this->agerange = $agerange;
    }

    /**
     * @return string
     */
    public function getPostdist()
    {
        return $this->postdist;
    }

    /**
     * @param string $postdist
     */
    public function setPostdist($postdist)
    {
        $this->postdist = $postdist;
    }

    /**
     * @return string
     */
    public function getPostarea()
    {
        return $this->postarea;
    }

    /**
     * @param string $postarea
     */
    public function setPostarea($postarea)
    {
        $this->postarea = $postarea;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param string $dob
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
    }

    /**
     * @return string
     */
    public function getRenewal()
    {
        return $this->renewal;
    }

    /**
     * @param string $renewal
     */
    public function setRenewal($renewal)
    {
        $this->renewal = $renewal;
    }

    /**
     * @return string
     */
    public function getBranchno()
    {
        return $this->branchno;
    }

    /**
     * @param string $branchno
     */
    public function setBranchno($branchno)
    {
        $this->branchno = $branchno;
    }

    /**
     * @return string
     */
    public function getBranchdesc()
    {
        return $this->branchdesc;
    }

    /**
     * @param string $branchdesc
     */
    public function setBranchdesc($branchdesc)
    {
        $this->branchdesc = $branchdesc;
    }

    /**
     * @return string
     */
    public function getSourcecode()
    {
        return $this->sourcecode;
    }

    /**
     * @param string $sourcecode
     */
    public function setSourcecode($sourcecode)
    {
        $this->sourcecode = $sourcecode;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getRecordType()
    {
        return $this->recordType;
    }

    /**
     * @param string $recordType
     */
    public function setRecordType($recordType)
    {
        $this->recordType = $recordType;
    }

    /**
     * @return string
     */
    public function getFieldid()
    {
        return $this->fieldid;
    }

    /**
     * @param string $fieldid
     */
    public function setFieldid($fieldid)
    {
        $this->fieldid = $fieldid;
    }

    /**
     * @return string
     */
    public function getPremium()
    {
        return $this->premium;
    }

    /**
     * @param string $premium
     */
    public function setPremium($premium)
    {
        $this->premium = $premium;
    }

    /**
     * @return string
     */
    public function getGid()
    {
        return $this->gid;
    }

    /**
     * @param string $gid
     */
    public function setGid($gid)
    {
        $this->gid = $gid;
    }

    /**
     * @return string
     */
    public function getLastcall()
    {
        return $this->lastcall;
    }

    /**
     * @param string $lastcall
     */
    public function setLastcall($lastcall)
    {
        $this->lastcall = $lastcall;
    }

    /**
     * @return string
     */
    public function getNextcall()
    {
        return $this->nextcall;
    }

    /**
     * @param string $nextcall
     */
    public function setNextcall($nextcall)
    {
        $this->nextcall = $nextcall;
    }

    /**
     * @return string
     */
    public function getDials()
    {
        return $this->dials;
    }

    /**
     * @param string $dials
     */
    public function setDials($dials)
    {
        $this->dials = $dials;
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

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getTransferno()
    {
        return $this->transferno;
    }

    /**
     * @param string $transferno
     */
    public function setTransferno($transferno)
    {
        $this->transferno = $transferno;
    }

    /**
     * @return string
     */
    public function getCampurn()
    {
        return $this->campurn;
    }

    /**
     * @param string $campurn
     */
    public function setCampurn($campurn)
    {
        $this->campurn = $campurn;
    }

    /**
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param string $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPmcTransferno()
    {
        return $this->pmcTransferno;
    }

    /**
     * @param string $pmcTransferno
     */
    public function setPmcTransferno($pmcTransferno)
    {
        $this->pmcTransferno = $pmcTransferno;
    }

    /**
     * @return string
     */
    public function getCvTransferno()
    {
        return $this->cvTransferno;
    }

    /**
     * @param string $cvTransferno
     */
    public function setCvTransferno($cvTransferno)
    {
        $this->cvTransferno = $cvTransferno;
    }

    /**
     * @return string
     */
    public function getPmcRenewal()
    {
        return $this->pmcRenewal;
    }

    /**
     * @param string $pmcRenewal
     */
    public function setPmcRenewal($pmcRenewal)
    {
        $this->pmcRenewal = $pmcRenewal;
    }

    /**
     * @return string
     */
    public function getBranchDrop()
    {
        return $this->branchDrop;
    }

    /**
     * @param string $branchDrop
     */
    public function setBranchDrop($branchDrop)
    {
        $this->branchDrop = $branchDrop;
    }

    /**
     * @return string
     */
    public function getPremiumValue()
    {
        return $this->premiumValue;
    }

    /**
     * @param string $premiumValue
     */
    public function setPremiumValue($premiumValue)
    {
        $this->premiumValue = $premiumValue;
    }

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
    public function getImportDetails()
    {
        return $this->importDetails;
    }

    /**
     * @param string $importDetails
     */
    public function setImportDetails($importDetails)
    {
        $this->importDetails = $importDetails;
    }
}
