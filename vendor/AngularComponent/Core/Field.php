<?php
namespace AngularComponent\Core;

class Field {
    protected $name;
    public function __construct($name, $type = 'string'){
        $this->setName($name);
        $this->setType($type);
    }
    
    public function setName($name){
        $this->name=$name;
        return $this;
    }
    public function getName(){
        return $this->name;
    }
    protected $type;
    public function setType($type){
        $this->type=$type;
        return $this;
    }
    public function getType(){
        return $this->type;
    }
    
}