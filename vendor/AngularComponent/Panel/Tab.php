<?php 
namespace AngularComponent\Panel;

class Tab extends Container{
    public function __construct($title, $id = null){
        parent::__construct($id);
        $this->setDom('tab');
        $this->addAttribute("heading", $title);
    }
}