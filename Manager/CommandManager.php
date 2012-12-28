<?php
namespace Iga\BuilderBundle\Manager;


class CommandManager {

	private $rootDir;

	public function __construct($rootDir){
		$this->rootDir = $rootDir;
	}
	
	public function execute($command,$output=""){
		$cli = 'php '.$this->rootDir .'/console '.$command;
		exec($cli,$output);
		return $output;
	}

}