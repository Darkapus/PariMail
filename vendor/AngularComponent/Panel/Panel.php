<?php
namespace AngularComponent\Panel;

class Panel extends Container{
    public function __construct($title = null, $id = null){
        parent::__construct($id);
        $title && $this->setTitle($title);
        
        $this->addClass('panel panel-primary');
    }
    
    
    /**
     * Manage title
     */
    protected $title;
    /**
     * define title's component
     */
    public function setTitle($title){
        $this->title = $title;
        return $this;
    }
    /**
     * get title's component
     */
    public function getTitle(){
        return $this->title;
    }
    
    public function preRender(){
        $top    = 0; // number of jump top
        $bottom = 0; // number of jump bottom 
        
        parent::preRender();
        if($this->getTitle()){
            if($this->getDraggable()){
                echo '<div class="panel-heading" ng-style="{\'cursor\':\'move\'}" ng-draggable>'.RC;
            }
            else{
                echo '<div class="panel-heading" >'.RC;
            }
            $top++;
            echo '<h3 class="panel-title">'.$this->getTitle().'</h3>'.RC;
            echo '</div>'.RC;
        }
        
        
        echo '<div class="panel-body" id="'.$this.'_body">'.RC;
    }
    public function setInclude($template){
        $this->addAttribute('ng-include', "'$template'");
        return $this;
    }
   
    public function postRender(){
        echo '</div>';

        parent::postRender();
    }
    

}