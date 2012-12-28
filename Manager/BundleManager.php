<?php
namespace Iga\BuilderBundle\Manager;

use Iga\BuilderBundle\Model\BundleModel;

class BundleManager {

	private $rootDir;
	private $cm;

	public function __construct($rootDir,CommandManager $commandManager)
	{
		$this->cm = $commandManager;
		$this->rootDir = $rootDir."/../src";
	}
	
	public function getList()
	{
		$bundles = $this->scanNamespace();
		return $bundles;
	}

	public function explore(BundleModel $bundle)
	{
		$basePath = $bundle->namespace."/".$bundle->name;
		$files = $this->scanDir($basePath);
		return $files;
	}
	
	public function get($namespace,$name)
	{
		if(is_dir($this->rootDir.$namespace.$name)){
			$bundle = new BundleModel();
			$bundle->name = $name;
			$bundle->namespace = $namespace;
			$bundle->routenamespace = str_replace("/","-",$namespace);
			return $bundle;
		}
		return false;
	}

	public function create(\Iga\BuilderBundle\Model\BundleModel $model)
	{
		if($this->checkConvention($model->getName())){
			$this->cm->execute("generate:bundle --namespace=".$model->namespace."/".$model->name." --dir=".$this->rootDir."/../src/ --no-interaction");
		}

	}

	public function checkConvention($name){
		$position = strrpos($name,"Bundle");
		return $position;
	}

	private function scanNamespace($namespace="/"){
		$bundles = Array();
		$raw = scandir($this->rootDir.$namespace);
		foreach($raw as $object){
			if((is_dir($this->rootDir.$namespace.$object)) && ($object !== ".") && ($object !== "..")){
				if($this->checkConvention($object)){
					$bundle = new BundleModel();
					$bundle->name = $object;
					$bundle->namespace = $namespace;
					$bundle->routenamespace = str_replace("/","-",$namespace);
					$bundles[] = $bundle;
				}else{
					$objects = $this->scanNamespace($namespace.$object."/");
					$bundles = array_merge($bundles,$objects);
				}
			}
		}
		return $bundles;
	}

	private function scanDir($basePath){
		$files = Array();
		if ($handle = opendir($this->rootDir.$basePath)) {
    		while (false !== ($entry = readdir($handle))) {
    			if(($entry == ".") || ($entry == "..")){
    				continue;
    			}
    			$dirFiles = false;
    			$dir = false;
    			if(is_dir($this->rootDir.$basePath."/".$entry)){
    				$dirFiles = $this->scanDir($basePath."/".$entry);
    				$dir = true;
    			}
    			$file = Array(
    				'name' => $entry,
    				'type' => (string) filetype($this->rootDir.$basePath . "/" . $entry),
    				'dir' => $dir,
    				'files' => $dirFiles,
    				'route' => str_replace("/","-_-",$basePath."/".$entry)
    			);
    			$files[] = $file;
    		}
    	}
    	return $files;	
	}

	public function openFile($route){
		$path = str_replace("-_-","/",$route);
		$content = file_get_contents($this->rootDir.$path);
		return $content;
	}

}