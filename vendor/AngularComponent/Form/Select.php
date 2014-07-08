<?php
namespace AngularComponent\Form;

class Select extends \AngularComponent\Core\Element
{
    protected $displayValue, $displayLabel, $model, $scope;
    public function setDisplayValue($displayValue){
        $this->displayValue = $displayValue;
        return $this;
    }
    public function getDisplayValue(){
        return $this->displayValue;
    }
    public function setDisplayLabel($displayLabel){
        $this->displayLabel = $displayLabel;
        return $this;
    }
    public function getDisplayLabel(){
        return $this->displayLabel;
    }
    public function setModel($model){
        $this->addAttribute('ng-model', $model);
        $this->model = $model;
        return $this;
    }
    public function getModel(){
        return $this->model;
    }
    public function setScope($scope){
        $this->scope = $scope;
        return $this;
    }
    public function getScope(){
        return $this->scope;
    }
        
    
    
    
    public function __construct($label, $name, $value=null, $scope, $displayValue, $displayLabel, $model=null, $id=null){
        parent::__construct( $id);
        $this->setDom('select');
        $this->setScope($scope);
        $this->setValue($value);
        $this->addAttribute('name', $name);
        $this->setDisplayValue($displayValue);
        $this->setDisplayLabel($displayLabel);
        $this->setModel($model);
        $this->addClass('form-control');
        //$this->addAttribute('ng-options','row.'.$displayLabel.' for row in '.$scope);
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
    
    public function postRender(){
        parent::postRender();
        echo '</div>';
    }
    
    public function render(){
        //echo '<option value="">-- choose --</option>';
        echo '<option ng-value="option.'.$this->getDisplayValue().'" ng-repeat="option in '.$this->getScope().'">'.\AngularComponent\Renderer::i()->getApplication()->getStartSymbol().'option.'.$this->getDisplayLabel().\AngularComponent\Renderer::i()->getApplication()->getEndSymbol().'</option>';
        parent::render();
    }
}