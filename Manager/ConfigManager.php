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

		$file = new FileModel();
        $file->name = 'README.md';
        $file->route = '..';
        $files[] = $file;

		$file = new FileModel();
        $file->name = 'AppKernel.php';
        $files[] = $file;

		$file = new FileModel();
        $file->name = 'composer.json';
        $file->route = '..';
        $files[] = $file;

		$baseConfigPath = $this->rootDir."/../app/config/";
		if ($handle = opendir($baseConfigPath)) {
    		while (false !== ($entry = readdir($handle))) {
        		if ($entry != "." && $entry != ".." && !is_dir($baseConfigPath.$entry)) {
            		$file = new FileModel();
            		$file->name = $entry;
            		$file->route = "config";
            		$files[] = $file;
        		}
    		}
    		closedir($handle);
		}	
		return $files;
	}

	public function saveConfigFile($file){
		$route = "";
		if(!empty($file->route)){ $route = $file->route . "/"; }
		return file_put_contents($this->rootDir."/../app/".$route.$file->name,$file->content);
	}

	public function openConfigFileByNameAndRoute($name,$route){
		$file = new FileModel();
		$file->name =$name;
		if(!empty($route)){ $route = $route . "/"; }
		$file->setContent(file_get_contents($this->rootDir."/../app/".$route.$name));
		return $file;
	}

	public function getFile($file){
		$route = "";
		if(!empty($file->route)){ $route = $file->route . "/"; }
		return json_decode(file_get_contents($this->rootDir."/../app/".$route.$file->name));
	}

}