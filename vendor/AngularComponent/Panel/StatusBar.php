<?php

namespace AngularComponent\Panel;

class StatusBar extends Container{
    public function __construct(){
        parent::__construct();
        $this->setDom('div');
        $this->addClass('navbar navbar-default');
        $this->addAttribute('role',"navigation");
    }
    
    public function preRender(){
        parent::preRender();
        echo '<div class="navbar-collapse collapse">';
        echo '<div class="container">';
    }
    
    public function postRender(){
        echo '</div>';
        echo '</div>';
        parent::postRender();
        
    }
}