<?php
namespace Iga\BuilderBundle\Model\Generator;

class EntityFieldGeneratorModel {

    public $type;
    public $name;
    public $length;

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