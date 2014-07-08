<?php

namespace Core\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PmContact
 *
 * @ORM\Table(name="pm_contact", indexes={@ORM\Index(name="qualify", columns={"qualify"}), @ORM\Index(name="email", columns={"email"})})
 * @ORM\Entity
 */
class PmContact extends \Core\Model\Entity\Object
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
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=100, nullable=true)
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=100, nullable=true)
     */
    protected $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=100, nullable=true)
     */
    protected $fullname;

    /**
     * @var boolean
     *
     * @ORM\Column(name="checked", type="boolean", nullable=true)
     */
    protected $checked;

    /**
     * @var integer
     *
     * @ORM\Column(name="discuss", type="integer", nullable=true)
     */
    protected $discuss;

    /**
     * @var string
     *
     * @ORM\Column(name="qualify", type="string", length=100, nullable=true)
     */
    protected $qualify;


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
     * Set email
     *
     * @param string $email
     * @return PmContact
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     * @return PmContact
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return PmContact
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set fullname
     *
     * @param string $fullname
     * @return PmContact
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string 
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return PmContact
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * Get checked
     *
     * @return boolean 
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set discuss
     *
     * @param integer $discuss
     * @return PmContact
     */
    public function setDiscuss($discuss)
    {
        $this->discuss = $discuss;

        return $this;
    }

    /**
     * Get discuss
     *
     * @return integer 
     */
    public function getDiscuss()
    {
        return $this->discuss;
    }

    /**
     * Set qualify
     *
     * @param string $qualify
     * @return PmContact
     */
    public function setQualify($qualify)
    {
        $this->qualify = $qualify;

        return $this;
    }

    /**
     * Get qualify
     *
     * @return string 
     */
    public function getQualify()
    {
        return $this->qualify;
    }
}
