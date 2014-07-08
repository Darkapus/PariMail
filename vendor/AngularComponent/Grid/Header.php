<?php
namespace AngularComponent\Grid;
class Header extends \AngularComponent\Core\Element{
    public function __construct($storage, $label, $index, $width=null){
        $this->setLabel($label);
        $this->setIndex($index);
        $this->setWidth($width);
        $this->setStorage($storage);
        $this->setDom('td');
        
        $content = \AngularComponent\Renderer::i()->getApplication()->getStartSymbol().' row.'.$this->getIndex().' '.\AngularComponent\Renderer::i()->getApplication()->getEndSymbol();
        $this->setContent($content);
        parent::__construct();
    }
    
    protected $storage;
    public function getStorage(){
        return $this->storage;
    }
    public function setStorage($storage){
        $this->storage = $storage;
        return $this;
    }
    
    protected $label;
    public function setLabel($label){
        $this->label=$label;
        return $this;
    }
    public function getLabel(){
        return $this->label;
    }   
    
    
    protected $index;
    public function setIndex($index){
        $this->index=$index;
        return $this;
    }
    public function getIndex(){
        return $this->index;
    }   
    
    
    protected $width;
    public function setWidth($width){
        $this->width=$width;
        return $this;
    }
    public function getWidth(){
        return $this->width;
    }   
    
    public function preRender(){
        $this->addStyle('width', ''.$this->getStorage()->getName().'header.'.$this->getIndex());
        parent::preRender();
        
    }
    public function render(){
        
    	if($this->getEditElement()){
    	    echo '<div class="ac-inner-cell" ng-style="{width: '.$this->getStorage()->getName().'.header.'.$this->getIndex().'}" ng-hide="row.class">'.RC; // ce bout est à migrer en Cell.php
        	echo $this->getContent().RC;
        	echo '</div>'.RC;
        	
        	echo '<div class="ac-inner-cell" ng-style="{width: '.$this->getStorage()->getName().'.header.'.$this->getIndex().'}" ng-hide="!row.class">'.RC; // ce bout est à migrer en Cell.php
        	$this->getEditElement()->addAttribute('ng-enter', 'load(\''.$this->getStorage()->getSaveUrl().'\', \'POST\',row, '.$this->getStorage()->getName().'.update($index), alert(\'error\'))');
        	$this->getEditElement()->preRender();
        	$this->getEditElement()->render();
        	$this->getEditElement()->postRender();
        	echo '</div>'.RC;
    	}
    	else{
    	    echo '<div class="ac-inner-cell" ng-style="{width: '.$this->getStorage()->getName().'.header.'.$this->getIndex().'}" >'.RC; // ce bout est à migrer en Cell.php
        	echo $this->getContent().RC;
        	echo '</div>'.RC;
    	}
        parent::render();
    }
    public function postRender(){
        
        parent::postRender();
    }
    protected $content;
    public function getContent(){
        return $this->content;
    }
    public function setContent($content){
        $this->content = $content;
        return $this;
    }
    
    protected $editElement;
    public function setEditElement($editElement){
        $this->editElement = $editElement;
        return $this;
    }
    public function getEditElement(){
        return $this->editElement;
    }
}