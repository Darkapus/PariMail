<?php
namespace AngularComponent\Form;

class Element extends \AngularComponent\Core\Element
{
    public function __construct($label, $name ,$value=null, $model=null, $id=null){
        $name   && $this->addAttribute("name", $name);
        $model  && $this->addAttribute("ng-model", $model);
        $value  && $this->setValue($value);
        $label  && $this->setLabel($label);
        
        parent::__construct($id);
        $this->setDom('textarea');
    }
    protected $value;
    public function setValue($value){
        $this->value = $value;
        return $this;
    }
    public function getValue(){
        return $this->value;
    }
    
    protected $label;
    public function setLabel($label){
        $this->label = $label;
        return $this;
    }
    public function getLabel(){
        return $this->label;
    }
    
    public function preRender(){
        echo '<div class="ac-form-element">'.RC;
        if($this->getLabel()){
            echo '<label for="'.$this.'">'.$this->getLabel();
            echo '</label>';
        }
        parent::preRender();
    }
    
    public function render(){
        parent::render();
    }
    
    public function postRender(){
        parent::postRender();
        echo '</div>';
    }
}