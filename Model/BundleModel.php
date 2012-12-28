<?php
namespace Iga\BuilderBundle\Model;

class BundleModel {

    public $namespace;
    public $routenamespace;

	public $name;

    public function __toString(){
        return $this->namespace.$this->name;
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
	
}