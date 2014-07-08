<?php
namespace Mailer;

class Smtp extends \Zend\Mail\Transport\Smtp{
    public static $instance = null;
    
    public static function i(){
        if(is_null(self::$instance)){
            self::$instance = new Smtp();
        }
        return self::$instance;
    }
    
    public function __construct(){
        $options = new \Zend\Mail\Transport\SmtpOptions();
		$options->setHost(Config::SMTP_HOST);
		$options->setPort(Config::SMTP_PORT);
		
		parent::__construct($options);
		
		$auth = new \Zend\Mail\Protocol\Smtp\Auth\Login();
		$auth->setUsername(Config::SMTP_LOGIN);
		$auth->setPassword(Config::SMTP_PWD);
		$auth->connect();
		$this->setConnection($auth);
    }
    
    public function sendmail($from, $contacts, $subject, $content){
		$body = new \Zend\Mime\Message();

		$part = new \Zend\Mime\Part($content);
		$part->type = 'text/html';

		$body->addPart($part);

		$message = new \Zend\Mail\Message();
		$message->setFrom($from);
		$message->setBody($body);
		foreach($contacts as $contact){
		    switch($contact->type){
		        case 0:
		            $message->addTo($contact->email);
		            break;
	            case 1:
	                $message->addCc($contact->email);
	                break;
                case 2:
                    $message->addBcc($contact->email);
                    break;
		    }
		    
		}
		$message->setSubject($subject);
        $message->setEncoding("UTF-8");
        $this->send($message);
        
        return $message;
	}
}