<?php
namespace Mailer;

class Imap extends \Zend\Mail\Storage\Imap{
    public static $instance = null;
    public static function i(){
        if(is_null(self::$instance)){
            self::$instance = new Imap();
        }
        return self::$instance;
    }
    
    public function __construct(){
        $this->imap = parent::__construct(array('user'=>Config::IMAP_LOGIN, 'password'=>Config::IMAP_PWD, 'ssl'=>Config::IMAP_SSL, 'port'=>Config::IMAP_PORT, 'host'=>Config::IMAP_HOST));
    }
    /**
     * get mails into folder
     */
    public function getMails($folder){
        $this->selectFolder($folder);
		$array = array();
		foreach($this as $id=>$mail){
			$flags      = $mail->getFlags();
			$o          = new \stdClass();
   		    $o->id      = $id;
			$o->from    = ( $mail->getHeaders()->get('from')->getFieldValue());
			$o->subject = ( $mail->getHeaders()->get('subject')->getFieldValue());
			$o->date    = new \DateTime( $mail->getHeaders()->get('date')->getFieldValue());
			$o->date    = $o->date->format('Y-m-d H:i:s');
			$o->seen    = $mail->hasFlag('\Seen')?'read':'unread';
			$o->objectType = 'message';
			$contenttype = $mail->getHeaders()->get('contenttype')->getFieldValue();
			if(substr($contenttype,0,15)=="multipart/mixed"){
			    $o->file    = true;
			}
			else{
			    $o->file    = false;
			}
			
			$o->folder  = $folder;
			$array[] = $o;
		}
		//krsort($array);
		return $array;
	}
	/**
	 * get recursive folders
	 */
	public function getTreeFolders(){
	    $folders = array();
	    $last = null;
		foreach(new \RecursiveIteratorIterator($this->getFolders(), \RecursiveIteratorIterator::SELF_FIRST) as $local=>$folder){
		    if ($folder->isSelectable()) {
		        $o              = new \stdClass();
		        $o->name        = $folder.'';
		        $o->label       = $local;
		        $o->children    = array();
		        $o->objectType  = 'folder';
		        $tbl = explode('.', $o->name);
		        
		        if(count($tbl)>1){
		            $folders[$tbl[0]]->children[] = $o;
		        }
		        else{
		            $folders[$o->label] = $o;
		        }
		        //array_push($folders, $o);
		    }
		}
		return $folders;
	}
	/**
	 * save into sent directory
	 */
	public function save($content){
		// on sauvegarde le mail
		$this->appendMessage($content, Config::IMAP_SENT_DIR);
	}
	/**
	 * @return html or plain
	 */
	public function getFormatedMessage($id){
		
		return new FormatedMessage($this->getMessage($id));
	}
}