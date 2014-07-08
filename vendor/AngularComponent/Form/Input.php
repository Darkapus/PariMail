<?php
namespace AngularComponent\Form;

class Input extends \AngularComponent\Form\Element
{
    public function __construct($label, $name, $type='text' ,$value=null, $model=null, $id=null){
        $this->addAttribute("type", $type);
        parent::__construct($label, $name, $value, $model, $id);
        $this->setDom('input');
        $this->addClass('form-control');
    }
    public function preRender(){
        $this->getValue()  && $this->addAttribute("value", $this->getValue());
        parent::preRender();
    }
    public function autocomplete($options = '{}'){
        \AngularComponent\Renderer::i()->getApplication()->addChild(new \AngularComponent\Core\Behavior('$("#'.$this.'").typeahead('.$options.')'));
        return $this;
    }
}