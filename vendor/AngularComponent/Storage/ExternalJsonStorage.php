<?php
namespace AngularComponent\Storage;

class ExternalJsonStorage extends \AngularComponent\Core\Storage{
    
    public function __construct($name, $url, $method='POST', $params = '{}', $id = null){
        $this->setUrl($url);
        $this->setSaveUrl($url);
        $this->setMethod($method);
        $this->setParams($params);
        $content = '$http({url: "'.$this->getUrl().'", method: "'.$this->getMethod().'", data: '.$this->getParams().'}).success(function(data) { $scope.'.$name.'.data = data;});';
        parent::__construct($name, $content, $id);
        
        if(is_null($params)){
             $params = new \stdClass();
        }
        
        
    }
    protected $url;
    public function setUrl($url){
        $this->url = $url;
        return $this;
    }
    public function getUrl(){
        return $this->url; 
    }
    protected $method;
    public function setMethod($method){
        $this->method = $method;
        return $this;
    }
    public function getMethod(){
        return $this->method;
    }
    
    protected $params;
    public function setParams($params){
        $this->params = $params;
        return $this;
    }
    public function getParams(){
        return $this->params;
    }
    public function bLoad($params = '{}'){
        return new \AngularComponent\Core\Behavior($this->getName().'.reload('.$params.')');
    }
}