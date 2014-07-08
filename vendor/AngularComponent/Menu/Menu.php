<?php
namespace AngularComponent\Menu;

class Menu extends \AngularComponent\Panel\Container{
    public function __construct($id=null){
        parent::__construct($id);
        $this->setDom('ul');
        $this->addClass('nav navbar-nav');
    }
    public function addItem($label, $url=null){
        $this->addChild($item = new Item($label, $url));
        return $item;
    }
    public function right(){
        $this->addClass('navbar-right');
        return $this;
    }
}