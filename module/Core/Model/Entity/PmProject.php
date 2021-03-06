<?php

namespace Core\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PmProject
 *
 * @ORM\Table(name="pm_project", indexes={@ORM\Index(name="status", columns={"status"}), @ORM\Index(name="user", columns={"user"}), @ORM\Index(name="depend", columns={"depend"})})
 * @ORM\Entity
 */
class PmProject extends \Core\Model\Entity\Object
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=100, nullable=true)
     */
    protected $label;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority", type="integer", nullable=true)
     */
    protected $priority;

    /**
     * @var integer
     *
     * @ORM\Column(name="length", type="integer", nullable=true)
     */
    protected $length;

    /**
     * @var integer
     *
     * @ORM\Column(name="estimated_time", type="integer", nullable=true)
     */
    protected $estimatedTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="realized_time", type="integer", nullable=true)
     */
    protected $realizedTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="percent", type="integer", nullable=true)
     */
    protected $percent;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    protected $note;

    /**
     * @var \Core\Model\Entity\PmProjectStatus
     *
     * @ORM\ManyToOne(targetEntity="Core\Model\Entity\PmProjectStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status", referencedColumnName="id")
     * })
     */
    protected $status;

    /**
     * @var \Core\Model\Entity\PmUser
     *
     * @ORM\ManyToOne(targetEntity="Core\Model\Entity\PmUser")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    protected $user;

    /**
     * @var \Core\Model\Entity\PmProject
     *
     * @ORM\ManyToOne(targetEntity="Core\Model\Entity\PmProject")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="depend", referencedColumnName="id")
     * })
     */
    protected $depend;


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
     * Set label
     *
     * @param string $label
     * @return PmProject
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     * @return PmProject
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set length
     *
     * @param integer $length
     * @return PmProject
     */
    public function setLength($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length
     *
     * @return integer 
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set estimatedTime
     *
     * @param integer $estimatedTime
     * @return PmProject
     */
    public function setEstimatedTime($estimatedTime)
    {
        $this->estimatedTime = $estimatedTime;

        return $this;
    }

    /**
     * Get estimatedTime
     *
     * @return integer 
     */
    public function getEstimatedTime()
    {
        return $this->estimatedTime;
    }

    /**
     * Set realizedTime
     *
     * @param integer $realizedTime
     * @return PmProject
     */
    public function setRealizedTime($realizedTime)
    {
        $this->realizedTime = $realizedTime;

        return $this;
    }

    /**
     * Get realizedTime
     *
     * @return integer 
     */
    public function getRealizedTime()
    {
        return $this->realizedTime;
    }

    /**
     * Set percent
     *
     * @param integer $percent
     * @return PmProject
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * Get percent
     *
     * @return integer 
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return PmProject
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set status
     *
     * @param \Core\Model\Entity\PmProjectStatus $status
     * @return PmProject
     */
    public function setStatus(\Core\Model\Entity\PmProjectStatus $status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Core\Model\Entity\PmProjectStatus 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set user
     *
     * @param \Core\Model\Entity\PmUser $user
     * @return PmProject
     */
    public function setUser(\Core\Model\Entity\PmUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Core\Model\Entity\PmUser 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set depend
     *
     * @param \Core\Model\Entity\PmProject $depend
     * @return PmProject
     */
    public function setDepend(\Core\Model\Entity\PmProject $depend = null)
    {
        $this->depend = $depend;

        return $this;
    }

    /**
     * Get depend
     *
     * @return \Core\Model\Entity\PmProject 
     */
    public function getDepend()
    {
        return $this->depend;
    }
}
