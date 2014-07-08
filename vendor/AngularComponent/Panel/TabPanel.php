<?php 
namespace AngularComponent\Panel;
use AngularComponent\Core\Behavior;

class TabPanel extends Container{
    public function __construct($id = null){
        parent::__construct($id);
        //$this->addAttribute("justified", "true");
        $this->setDom('tabset');
        
        
    }
    
    public function setDynamicalTab(){
        $this->getController()->addChild(new Behavior('$scope.tabs = []'));
        $this->addChild($tab = new Tab('tab.name'));
        $tab->addAttribute('ng-repeat', 'tab in tabs');
        $tab->addAttribute('ng-template', 'tab.url');
    }
}