<?php
namespace Iga\BuilderBundle\Model\Generator;
use Doctrine\DBAL\Platforms\Keywords\MySQLKeywords;

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
	
    public function isReservedKeyword(ExecutionContext $context)
    {
        // somehow you have an array of "fake names"
        $keywords = new MySQLKeywords();
        $fakeNames = $keywords->geyKeywords();

        // check if the name is actually a fake name
        if (in_array($this->name(), $reservedKeywords)) {
            $context->addViolationAtSubPath('name', 'MySQL Reserved Keyword!', array(), null);
        }
    }
}