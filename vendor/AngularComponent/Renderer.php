<?php
namespace AngularComponent;

define('RC', "\r\n");

class Renderer{
    
    public static $i = null;
    public static function i(){
        if(is_null(self::$i)){
            self::$i = new Renderer();
        }
        return self::$i;
    }
    public function __construct(){
        $this->application = new Core\Application();
    }
    protected $application = null;
    public function setApplication(Core\Application $application){
        $this->application = $application;
        return $this;
    }
    public function getApplication(){
        return $this->application;
    }
    public function flush(){
        self::$i = new Renderer();
        return self::$i;
    }
    public function show(Core\Element $element){
        // rendu html
        echo '<div class="container-fluid" ng-app="'.$this->getApplication().'">';
        $element->preRender();
        $element->render();
        $element->postRender();
        echo '</div>';
        
        // rendu javascript
        echo '<script>';
        
        
            $this->getApplication()->preRender();
            $this->getApplication()->render();
            $this->getApplication()->postRender();
        
        echo '</script>';
    }
}