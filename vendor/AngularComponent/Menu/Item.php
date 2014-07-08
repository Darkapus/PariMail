<?php
namespace AngularComponent\Menu;
use AngularComponent\Panel\Container;
use AngularComponent\Core\Element;
class Item extends Element{
    protected $url;
    public function getUrl(){
        return $this->url;
    }
    public function setUrl($url){
        $this->url  = $url;
        return $this;
    }
    public function __construct($label, $url='#', $id = null){
        parent::__construct($id);
        $this->setDom('li');
        $this->setLabel($label);
        $this->setUrl($url);
        
        $link = new Container();
        $link->setDom('a');
        $url && $link->addAttribute('href', $url);
        $link->setContent($label);
        
        $this->setLink($link);
    }
    /**
     * Manage Childs
     */
    protected $childs = array();
    /**
     * add a child to the panel. Childs are rendered in cascade
     */
    public function addChild(\AngularComponent\Menu\Item $element){
        array_push($this->childs, $element);
        return $this;
    }
    public function getChilds(){
        return $this->childs;
    }
    
    protected $link;
    public function getLink(){
        return $this->link;
    }
    public function setLink($link){
        $this->link = $link;
        return $this;
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
        if(count($this->getChilds())){
            $this->addClass('dropdown');
        }
        
        parent::preRender();
    }
    public function render(){
        $count = count($this->getChilds());
        
        if($count){
            $this->getLink()->addAttribute('data-toggle', 'dropdown');
            //$this->getLink()->setContent($this->getLink()->getContent().' <b class="caret"></b>');
            $this->getLink()->preRender();
            $this->getLink()->render();
            $this->getLink()->postRender();
            //echo '<a href="'.$this->getUrl().'" class="dropdown-toggle" data-toggle="dropdown">'.$this->getLabel().' <b class="caret"></b></a>';
            echo '<ul class="dropdown-menu">';
        } 
        else{
            $this->getLink()->preRender();
            $this->getLink()->render();
            $this->getLink()->postRender();
            //echo '<a href="'.$this->getUrl().'">'.$this->getLabel()."</a>";
        }
        foreach($this->getChilds() as $element){
            $element->preRender();
            $element->render();
            $element->postRender();
        }
        if($count) echo '</ul>';
        parent::render();
    }
    public function setToggleWindow(\AngularComponent\Panel\Window $window){
          $this->addAttribute('data-toggle',"modal");
          $this->addAttribute('data-target',"#$window");
          return $this;
    }
    public function postRender(){
        parent::postRender();
    }
}