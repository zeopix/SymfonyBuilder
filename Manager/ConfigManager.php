<?php
namespace Iga\BuilderBundle\Manager;

use Iga\BuilderBundle\Model\BundleModel;
use Iga\BuilderBundle\Model\FileModel;

class ConfigManager {

	private $rootDir;
	private $cm;

	public function __construct($rootDir,CommandManager $commandManager)
	{
		$this->cm = $commandManager;
		$this->rootDir = $rootDir."/../src";
	}
	
	public function getTree(){
		$files = array();
		$basePath = $this->rootDir."/../app/config/";
		if ($handle = opendir($basePath)) {
    		while (false !== ($entry = readdir($handle))) {
        		if ($entry != "." && $entry != ".." && !is_dir($basePath.$entry)) {
            		$file = new FileModel();
            		$file->name = $entry;
            		$files[] = $file;
        		}
    		}
    		closedir($handle);
		}	
		return $files;
	}

	public function getFile($file){
		return json_decode(file_get_contents($this->rootDir."/../app/config/".$file->name));
	}

}