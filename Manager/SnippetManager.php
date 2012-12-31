<?php
namespace Iga\BuilderBundle\Manager;

use Iga\BuilderBundle\Model\BundleModel;
use Iga\BuilderBundle\Model\FileModel;

use Symfony\Component\Yaml\Yaml;

class SnippetManager {

	private $rootDir;

	public function __construct($rootDir)
	{
		$this->rootDir = $rootDir."/../src";
	}
	
	public function getSnippets()
	{
		$path = __DIR__.'/../Resources/snippets/snippets.yml';
		$yaml = Yaml::parse($path);
		return $yaml;

	}

}