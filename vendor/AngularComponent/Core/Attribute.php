<?php 
namespace AngularComponent\Core;

class Attribute{
    public function __construct($name, $content=null){
        
        $this->setName($name);
        $content && $this->setContent($content);
    }
    
    protected $name;
    public function setName($name){
        $this->name = $name;
        return $this;
    }
    public function getName(){
        return $this->name;
    }
    protected $content;
    public function setContent($content){
        $this->content = $content;
        return $this;
    }
    public function getContent(){
        return $this->content;
    }
    public function __toString(){
        if($this->getContent()){
            return $this->getName().'="'.$this->getContent().'" ';
        }
		else{
		    return $this->getName().' ';
		}
	}
}