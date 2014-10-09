<?php
namespace Mailer\Model;
use Core\Model\Entity\PmUser as User;

class Auth extends \Zend\Session\Container{
    /**
	 * pour singleton
	 * @var Auth
	 */
	static public $i=null;
	
	/**
	 * singleton
	 * @return Auth
	 */
	static public function i()
	{
		if(is_null(self::$i))
		{
			self::$i = new Auth();
		}
		return self::$i;
	}
    /**
     * use this method to connect
     */
    public function connect($login, $passwd)   {
        return $this->instanciateUser(User::findOneBy(array('email'=>$login, 'password'=>$passwd)));
    }
    /**
     * create an user and instanciate
     */
    public function createUser(){
        $user = new User();
        $user->persist();
        $user->save();
        
        return $this->instanciateUser($user);
    }
    /**
     * instanciate this user to this session
     */
    public function instanciateUser(User $user = null){
        if(is_null($user)){
            return false;
        }
        $this->email        = $user->getEmail();
        $this->imap_host    = $user->getImapHost();
        $this->imap_login   = $user->getImapLogin();
        $this->imap_pwd     = $user->getImapPwd();
        return true;
    }
    
    public function getEmail(){
        return $this->email;
    }
    
    public function getSmtpHost(){
        return $this->smtp_host;
    }
    
    public function getSmtpLogin(){
        return $this->smtp_login;
    }
    
    public function getSmtpPwd(){
        return $this->smtp_pwd;
    }
}
