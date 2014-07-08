<?php
namespace AngularComponent\Core;

class Behavior extends Object{
    protected $arguments = array();
    public function __construct($content, $id=null){
        
        parent::__construct($id);
        preg_match_all('/(\$[a-zA-Z]+|\$[a-zA-Z]+).*/', $content, $arguments);
        
        $this->setContent($content);
        $this->arguments = $arguments[1];
        
    }
    protected $content ;
    public function setContent($content){
        $this->content = $content;
        return $this;
    }
    public function getContent(){
        return $this->content;
    }
    public function addArgument($name){
    	array_push($this->arguments, $name);
    	return $this;
    }
    public function getArguments(){
    	return $this->arguments;
    }
    
    
    public function render(){
        echo $this->getContent().';'.RC;
    }
}