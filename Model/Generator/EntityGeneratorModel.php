<?php
namespace Iga\BuilderBundle\Model\Generator;
use Doctrine\Common\Collections\ArrayCollection;

class EntityGeneratorModel {
    public $bundle;
    public $name;

    public $format;
    public $fields;

    public function __construct(){
        $this->fields = new ArrayCollection();
    }

    public function __toString(){
        return $this->name;
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