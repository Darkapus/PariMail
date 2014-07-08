<?php

namespace Core\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PmUser
 *
 * @ORM\Table(name="pm_user", uniqueConstraints={@ORM\UniqueConstraint(name="email", columns={"email"})})
 * @ORM\Entity
 */
class PmUser extends \Core\Model\Entity\Object
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
     * @ORM\Column(name="password", type="string", length=25, nullable=true)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="imap_host", type="string", length=100, nullable=true)
     */
    protected $imapHost;

    /**
     * @var string
     *
     * @ORM\Column(name="imap_login", type="string", length=100, nullable=true)
     */
    protected $imapLogin;

    /**
     * @var string
     *
     * @ORM\Column(name="imap_pwd", type="string", length=25, nullable=true)
     */
    protected $imapPwd;


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
     * @return PmUser
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
     * Set password
     *
     * @param string $password
     * @return PmUser
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
     * Set imapHost
     *
     * @param string $imapHost
     * @return PmUser
     */
    public function setImapHost($imapHost)
    {
        $this->imapHost = $imapHost;

        return $this;
    }

    /**
     * Get imapHost
     *
     * @return string 
     */
    public function getImapHost()
    {
        return $this->imapHost;
    }

    /**
     * Set imapLogin
     *
     * @param string $imapLogin
     * @return PmUser
     */
    public function setImapLogin($imapLogin)
    {
        $this->imapLogin = $imapLogin;

        return $this;
    }

    /**
     * Get imapLogin
     *
     * @return string 
     */
    public function getImapLogin()
    {
        return $this->imapLogin;
    }

    /**
     * Set imapPwd
     *
     * @param string $imapPwd
     * @return PmUser
     */
    public function setImapPwd($imapPwd)
    {
        $this->imapPwd = $imapPwd;

        return $this;
    }

    /**
     * Get imapPwd
     *
     * @return string 
     */
    public function getImapPwd()
    {
        return $this->imapPwd;
    }
}
