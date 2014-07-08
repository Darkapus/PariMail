<?php

namespace AngularComponent\Panel;

class Form extends Container{
    public function __construct(){
        parent::__construct();
        $this->addClass('ac-form');
    }
}