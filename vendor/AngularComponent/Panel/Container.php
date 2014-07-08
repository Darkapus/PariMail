<?php
namespace AngularComponent\Panel;

class Container extends \AngularComponent\Core\Element{
    public function __construct($id = null){
        parent::__construct($id);
        $this->setDom('div');
    }
    /**
     * on ajoute un controller que si c'est necessaire, donc que lorsque qu'on fait l'appel au moins une fois. 
     * Inutile de créer un controller js systematiquement pour éviter d'alourdir le poste client'
     */
    public function getController(){
        if(!$this->controller){
            // creation d'un controller
            $this->setController($controller = new \AngularComponent\Core\Controller());
            
            // on ajoute la chaine behavior
            $this->getController()->addChild(new \AngularComponent\Core\Behavior('$scope.'.$this.'={}'));
            
            // on ajoute la fonction load à ce panel controller
            $chaine = '$scope.'.$this.'.load = function(url, dest, type, params){$http({\'url\': url, method: type, data: params}).success(function(data) { $("#"+dest+"").html(data);});};';
            $this->getController()->addChild(new \AngularComponent\Core\Behavior($chaine));
        }
        
        return $this->controller;
    }
    protected $draggable = false;
    public function setDraggable($bool){
        $this->draggable = $bool;
        return $this;
    }
    public function getDraggable(){
        return $this->draggable;
    }
    
    
    protected $width;
    /**
     * define width's component
     */
    public function setWidth($width){
        $this->width = $width;
        return $this;
    }
    /**
     * get width's component
     */
    public function getWidth(){
        return $this->width;
    }
    /**
     * Manage Childs
     */
    protected $childs = array();
    /**
     * add a child to the panel. Childs are rendered in cascade
     */
    public function addChild(\AngularComponent\Core\Element $element){
        array_push($this->childs, $element);
        return $this;
    }
    public function getChilds(){
        return $this->childs;
    }
    public function preRender(){
        if($this->getWidth()){
            $this->addStyle('width', $this->getWidth());
        }
        
        $this->getStyle() && $this->addAttribute('ng-style', str_replace('"', "'", json_encode($this->getStyle())));
        
        parent::preRender();
        if($this->getTopBar()){
            $this->getTopBar()->addClass('ac-panel-status-top');
            $this->getTopBar()->addClass('ac-panel-top'.$top);
            $top++;
            $this->getTopBar()->preRender();
            $this->getTopBar()->render();
            $this->getTopBar()->postRender();
        }
    }
    public function setInclude($template){
        $this->addAttribute('ng-include', "'$template'");
        return $this;
    }
    public function render(){
        foreach($this->getChilds() as $element){
            $element->preRender();
            $element->render();
            $element->postRender();
        }
        echo $this->getContent();
        parent::render();
    }
    protected $content;
    public function setContent($content){
        $this->content = $content;
        return $this;
    }
    public function getContent(){
        return $this->content;
    }
    public function postRender(){
        if($this->getBottomBar()){
            $this->getBottomBar()->addClass('ac-panel-status-bottom');
            $this->getBottomBar()->addClass('ac-panel-bottom0');
            $this->getBottomBar()->preRender();
            $this->getBottomBar()->render();
            $this->getBottomBar()->postRender();
        }
        
        parent::postRender();
    }
    
    public function bLoad($url, $method='POST', $data='null'){
        return new \AngularComponent\Core\Behavior('load('.$url.', \''.$this.'\', \''.$method.'\', '.$data.')');
    }
    
    public function setHide($cond){
        $this->addAttribute('ng-hide', $cond);
        return $this;
    }
    
    
    protected $topBar;
    public function getTopBar(){
        return $this->topBar;
    }
    public function setTopBar(StatusBar $status){
        $this->topBar = $status;
        return $this;
    }
    
    protected $bottomBar;
    public function getBottomBar(){
        return $this->bottomBar;
    }
    public function setBottomBar(StatusBar $status){
        $this->bottomBar = $status;
        return $this;
    }
}