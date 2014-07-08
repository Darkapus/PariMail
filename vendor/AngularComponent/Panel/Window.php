<?php
namespace AngularComponent\Panel;

class Window extends Container{
    public function __construct($label, $id=null){
        parent::__construct($id);
        $this->setDom('div');
        $this->addClass("modal fade");
        $this->addAttribute('tabindex',"'-1'");
        $this->addAttribute('role',"'dialog'");
        $this->addAttribute('aria-hidden',"'true'");
        $this->setLabel($label);
    }   
    protected $label;
    public function getLabel(){
        return $this->label;
    }
    public function setLabel($label){
        $this->label = $label;
        return $this;
    }
    public function preRender(){
        parent::preRender();
        echo '<div class="modal-dialog modal-lg">';
        echo '<div class="modal-content">';
        echo '<div class="modal-header">';
        echo '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
        echo '<h4 class="modal-title">'.$this->getLabel().'</h4>';
        echo '</div>';
        echo '<div class="modal-body">';
    }
    
    
    public function postRender(){
        echo '</div>';
        echo '<div class="modal-footer">';
        foreach($this->getButtons() as $button){
            $button->preRender();
            $button->render();
            $button->postRender();
        }
        echo '</div>';
        echo '</div>';
        echo '</div>';
        parent::postRender();
    }
    
    protected $buttons = array();
    public function addButton($button){
        if(!is_object($button)){
            $button = new \AngularComponent\Form\Button($button);
        }
        
        array_push($this->buttons, $button);
        return $button;
    }
    public function getButtons(){
        return $this->buttons;
    }
}