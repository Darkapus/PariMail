<?php
namespace AngularComponent\Form;

class Textarea extends \AngularComponent\Form\Element
{
    public function __construct($label, $name, $value=null, $model=null, $id=null){
        parent::__construct($label, $name, $value, $model, $id);
        $this->setDom('textarea');
    }
    
    public function render(){
        parent::render();
        echo $this->getValue();
    }
    
}