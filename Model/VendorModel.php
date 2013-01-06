<?php
namespace Iga\BuilderBundle\Model;

class VendorModel 
{
    //github parameters vendor model
    public $vendor;
	public $bundle;

    public function __toString(){
        return $this->vendor."/".$this->bundle;
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