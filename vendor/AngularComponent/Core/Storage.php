<?php
namespace AngularComponent\Core;
/**
 * Le storage c'est le scope angularjs
 * Il permet de gérer la stratégie des données
 */
class Storage extends Behavior{
    protected $fields = array();
    /**
     * La définition des champs est à déclarer ici
     */
    public function addField($name, $type='string'){
        array_push($this->fields, new Field($name, $type));
        return $this;
    }
    public function getFields(){
        return $this->fields;
    }
    protected $name;
    public function setName($name){
        $this->name = $name;
        return $this;
    }
    public function getName(){
        return $this->name;
    }
    
    public function __construct($name, $content, $id=null){
        parent::__construct($content, $id);
        $this->setName($name);
    }
    
    protected $saveUrl;
    public function setSaveUrl($url){
        $this->saveUrl=$url;
        return $this;
    }
    public function getSaveUrl(){
        return $this->saveUrl;
    }
}