<?php
namespace AngularComponent\Storage;

class JsonStorage extends \AngularComponent\Core\Storage{
    protected $jsonData = '{}';
    public function __construct($name, $jsonData, $id = null){
        $content = '$scope.'.$name.' = '.$jsonData;
        parent::__construct($name, $content, $id);
        $this->setData($jsonData);
    }
    
    public function setData($jsonData){
        $this->jsonData = $jsonData;
        return $this;
    }
    public function getData(){
        return $this->jsonData;
    }
}