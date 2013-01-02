<?php
namespace Iga\BuilderBundle\Model;

class FileModel {

    public $content;
    public $name;
    public $route;
    public $isDirectory = false;

    public function __toString(){
        return $this->content;
    }

    public function __get($property) {
            if (property_exists($this, $property)) {
                return $this->$property;
            }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    public function setContent($content){
        $this->content = $content;
    }

    public function getContent(){
        return $this->content;
    }

    public function getRoutingRoute(){
        return str_replace("/","-_-",$this->route);
    }
	
}