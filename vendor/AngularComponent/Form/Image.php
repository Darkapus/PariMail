<?php
namespace AngularComponent\Form;

class Image extends \AngularComponent\Core\Element
{
    
    public function __construct($src, $id){
       
        $this->addAttribute("ng-src", $src);
        parent::__construct($id);
        $this->setDom('img');
    }
}