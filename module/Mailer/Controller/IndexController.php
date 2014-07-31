<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Mailer\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

define('SMTP_FROM' , 'root@waste.email');

class IndexController extends AbstractActionController
{
    private $view;
    public function getView(){
        if(is_null($this->view)){
            $this->view = new ViewModel();
        }
        return $this->view;
    }
    public function connectAction(){
        header('Content-Type: application/json');
        $object = new \stdClass();
        if($this->getRequest()->getPost('email')){
            $auth = new \Mailer\Model\Auth();
            if($auth->connect($this->getRequest()->getPost('email'), $this->getRequest()->getPost('password'))){
                $object->success = true;
                $object->url = "/mailer";
            }
            else{
                $object->success = false;
            }
        }
        else{
            $object->success = false;
        }
        
        echo json_encode($object);
        return $this->response;
    }
    public function indexAction()
    {
        return new ViewModel();
    }
    
	public function checkmailAction(){
		header('Content-Type: application/json');
        echo json_encode(\Mailer\Imap::i()->getMails($this->getRequestData()->folder));
        
        return $this->response;
	}
	public function sendmaxAction(){
		$start = new \DateTime();
		for($i=0;$i<100;$i++){
			$when = new \DateTime();
			\Mailer\Smtp::i()->sendmail(\Mailer\Config::SMTP_FROM, 'benjamin@baschet.fr', 'Email test '.$start->format('Y-m-d h:i:s'), $when->format('Y-m-d h:i:s'));
		}
		echo 'ok';
		
		return $this->response;
	}
	public function foldersAction(){
        header('Content-Type: application/json');
		
		echo json_encode(\Mailer\Imap::i()->getTreeFolders());
		return $this->response;
	}
	public function sendmailAction(){
		$message = \Mailer\Smtp::i()->sendmail(\Mailer\Config::SMTP_FROM, $this->getRequestData()->message->contacts, $this->getRequestData()->message->subject, $this->getRequestData()->message->body);
		
		\Mailer\Imap::i()->appendMessage($message->toString(), 'Sent');
		return $this->response;
	}
	public function readAction(){
		\Mailer\Imap::i()->selectFolder($this->getRequestData()->folder);
		$formated = \Mailer\Imap::i()->getFormatedMessage($this->getRequestData()->id);
		$content = $formated->getContent();
		$parts = $formated->getAllParts();
		$files = array();
		if(array_key_exists('file', $files))
		foreach($parts['file'] as $file){
		    if(!$file->getHeaders()->get('Content-Disposition')) {
		        $files[] = 'mail.txt';
		        continue;
		    }
		    
		    $input_line = $file->getHeaders()->get('Content-Disposition')->getFieldValue();
		    preg_match('/filename="(.*)"/', $input_line, $output_array);
		    $files[] = $output_array[1];
		}
		
		$headers = \Mailer\Imap::i()->getMessage($this->getRequestData()->id)->getHeaders();
		
		$viewModel  = new ViewModel(array('id'=>$this->getRequestData()->id,'folder'=>$this->getRequestData()->folder,'content'=>$content, 'headers'=>$headers, 'files'=>$files));
		$viewModel->setTerminal(true);
		
		return $viewModel;
	}
	public function readmailAction(){
	    \Mailer\Imap::i()->selectFolder($this->getRequestData()->folder);
		echo \Mailer\Imap::i()->getFormatedMessage($this->getRequestData()->id)->getContent();
		$this->getView()->setNoRender();
		return $this->getView();
	}
	public function removemailAction(){
	    header('Content-Type: application/json');
	    \Mailer\Imap::i()->selectFolder($this->getRequestData()->folder);
	    \Mailer\Imap::i()->removeMessage($this->getRequestData()->id);
	    echo '{"success":true}';
	    return $this->response;
	}
	public function movemailAction(){
	    header('Content-Type: application/json');
		
	    if(property_exists($this->getRequestData(), 'target')){
    	    \Mailer\Imap::i()->selectFolder($this->getRequestData()->folder);
    		\Mailer\Imap::i()->moveMessage($this->getRequestData()->id, $this->getRequestData()->target);
	    }
	    elseif(property_exists($this->getRequestData(), 'contact')){
	        \Mailer\Imap::i()->selectFolder($this->getRequestData()->folder);
	        
	        $message = \Mailer\Imap::i()->getMessage($this->getRequestData()->id);
	        $content = \Mailer\Imap::i()->getFormatedMessage($this->getRequestData()->id)->getContent();
	        $subject = $message->getHeaders()->get('subject')->getFieldValue();
	        
	        $row = \Core\Model\Entity\PmContact::findOneBy(array('email'=> $this->getRequestData()->contact));
            $contacts = array();
            $contact = new \stdClass();
            $contact->email = $row->getEmail();
            $contact->type = 0;
            $contacts[] = $contact;
            
	        \Mailer\Smtp::i()->sendmail(\Mailer\Config::SMTP_FROM, $contacts, $subject, $content);
	    }
	    echo '{"success":true}';
	    return $this->response;
	}
	
	protected $data;
	public function getRequestData(){
		if(is_null($this->data)){
			$this->data = json_decode(file_get_contents("php://input"));
		}
		return $this->data;
	}
}
