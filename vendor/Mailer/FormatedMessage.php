<?php
namespace Mailer;
class FormatedMessage{
    private $message = null;
    
    public function __construct(\Zend\Mail\Storage\Message $message){
        $this->message = $message;
    }
    public function getMessage(){
        return $this->message;
    }
    public function getContent(){
        $parts = $this->getAllParts();
        
        if(array_key_exists('html', $parts)){
			$part = $parts['html'][0];
			return $this->getUnencodedPart($part);
		}
		elseif(array_key_exists('plain', $parts)){
			$part = $parts['plain'][0];
			return nl2br($this->getUnencodedPart($part));
		}
    }
    
    public function getUnencodedPart($part){
        $content = $part->getContent();
        
        switch($this->getPartEncodage($part)){
            case 'base64':
                $content = base64_decode($content);
                break;
            case 'quoted-printable':
                $content = quoted_printable_decode($content);
                break;
            //case '7bit':
            //    $content = imap_qprint($content);
            //    break;
            default:
                $content = $content;
                break;
        }
        if($this->getPartCharset($part) != 'UTF-8'){
            $content = iconv($this->getPartCharset($part), 'UTF-8', $content);
        }
        /*switch($this->getPartCharset($part)){
            case 'ISO-8859-1':
                $content = utf8_encode($content);
            default:
                $content = $content;
        }*/
        return $content;
    }
    private function getPartEncodage($part){
         if($part->getHeaders()->get('contenttransferencoding') ){
		    return $part->getHeaders()->get('contenttransferencoding')->getFieldValue();
         }
         else{
             return false;
         }
    }
    private function getPartType($part){
        $mode = 'file';
        if($part->getHeaders()->get('contenttype')){
				$content_type = $part->getHeaders()->get('contenttype')->getFieldValue();
				preg_match('/.*(html|plain).*/', $content_type, $charset);
				if(count($charset)){
		        	$mode = $charset[1];
		        }
        }
        return $mode;
    }
    private function getPartCharset($part){
        $mode = 'utf8';
        if($part->getHeaders()->get('contenttype')){
				$content_type = $part->getHeaders()->get('contenttype')->getFieldValue();
				//echo $part->getHeaders()->get('contenttype')->getFieldValue();
				preg_match('/.*charset.*"(.*)"/', $content_type, $charset);
				if(count($charset)){
		        	$mode = $charset[1];
		        }
        }
        return strtoupper($mode);
    }
    public function getAllParts(){
        $contents   = array();
		
		if(!$this->getMessage()->countParts()){
		    $parts = array($this->getMessage());
		}
		else{
		    $parts = new \RecursiveIteratorIterator($this->getMessage());
		}
		
		// on parcours chacune des parti du mail
		foreach ($parts as $part) {
		    $mode = $this->getPartType($part);
		    if(!array_key_exists($mode, $contents)){
		        $contents[$mode] = array();
		    }
	        $contents[$mode][] = $part;
		}
        return $contents;
    }
}