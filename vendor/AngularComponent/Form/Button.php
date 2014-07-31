<?php
namespace AngularComponent\Form;

class Button extends \AngularComponent\Core\Element
{
    
    public function __construct($value, $model=null, $id=null){
        $this->setValue( $value);
        $model  && $this->addAttribute("ng-model", $model);
        parent::__construct($id);
        $this->addClass('btn btn-primary');
        $this->setDom('button');
    }
    protected $value;
    public function setValue($value){
        $this->value= $value;
        return $this;
    }
    public function getValue(){
        return $this->value;
    }
    public function render(){
        echo $this->getValue();
    }
}