<?php
namespace AngularComponent\Core;

class Object{
    
    
    public function __construct($id=null){
        error_log(get_called_class());
        if(is_null($id)){
            $this->setId(uniqid(str_replace('\\','_',get_called_class()).'_'));
        }
    }
    
    protected $id = null;
    public function getId(){
        return $this->id;
    }
    public function setId($id){
        $this->id = $id;
        return $this;
    }
    
    public function preRender(){
	    
	}
	public function render(){
	    error_log(get_called_class().' rendu ok de '.$this);
	}
	public function postRender(){
	    
	}
	
	public function addJsElement(Element $element){
	    \AngularComponent\Renderer::i()->addJsElement($element);
	}
	
	public function __toString()	{
		return $this->getId();
	}
}