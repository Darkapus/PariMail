<?php
namespace AngularComponent\Core;

class Element extends Object{
    
    protected $class        = array();
    public function addClass($class){
        array_push($this->class, $class);
        return $this;
    }
    public function getClass(){
        return $this->class;
    }
    
    protected $dom      = 'div';
    public function setDom($dom){
        $this->dom = $dom;
        return $this;
    }
    public function getDom(){
        return $this->dom;
    }
    
    protected $controller;
    public function setController($controller){
        $this->controller = $controller;
    }
    public function getController(){
        return $this->controller;
    }
	/**
     * Est ce qu'il possede un controller ?
     */
    public function hasController(){
        return is_object($this->controller);
    }
    
	protected $attributes = array();
	public function addAttribute($name, $content=null){
	    array_push($this->attributes, new Attribute($name, $content));
	    return $this;
	}
	public function getAttributes(){
	    return $this->attributes;
	}
	public function preRender(){
	    // si il existe un controller, par defaut reprend le controller du parent si non déclaré
	    $this->hasController() && $this->addAttribute("ng-controller", $this->getController().'');
	    $this->addAttribute("id", $this->getId());
	    $this->addAttribute("class", implode(' ', $this->getClass()));
	    
	    echo '<'.$this->getDom().' ';
	    foreach($this->getAttributes() as $attribute){
	        echo $attribute->__toString();
	    }
	    echo '>';
	}
	public function render(){
	    parent::render();
	}
	public function postRender(){
	    echo '</'.$this->getDom().'>';
	}
	
	public function addFunction($name, $function){
	    $this->getController()->addChild(new Behavior('$scope.'.$name.'='.$function.';'));
	    return $this;
	}
	
	protected $style = null;
    public function getStyle(){
        return $this->style;
    }
    public function addStyle($name, $value){
        if(is_null($this->getStyle())){
            $this->style = new \stdClass();
        }
        $this->style->$name = $value;
        return $this->style;
    }
    
    public function setToolTip($title, $position='bottom'){
        //$this->addAttribute("data-toggle", "tooltip");
        $this->addAttribute("popover-placement",$position);
        $this->addAttribute("popover-trigger","mouseenter");
        $this->addAttribute("popover",$title);
        $this->addAttribute("popover-append-to-body");
        return $this;
    }
}