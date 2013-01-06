<?php
namespace Iga\BuilderBundle\Manager;

use Iga\BuilderBundle\Model\BundleModel;
use Iga\BuilderBundle\Model\FileModel;

class BundleManager {

	private $rootDir;
	private $cm;

	public function __construct($rootDir,CommandManager $commandManager)
	{
		$this->cm = $commandManager;
		$this->rootDir = $rootDir."/../src";
	}
	
	public function getComposer(){
		return json_decode(file_get_contents($this->rootDir."/../composer.json"));
	}
	
	public function setComposer($composer){
		return file_put_contents($this->rootDir."/../composer.json",json_encode($composer));
	}

	public function getList()
	{
		$bundles = $this->scanNamespace();
		return $bundles;
	}

	public function addVendor(\Iga\BuilderBundle\Model\VendorModel $vendor){
		$composer = $this->getComposer();
		$bundle = (string) $vendor;
        if(!isset($composer->require->$bundle)){
        	$composer->require->$bundle = '*';
        }
        $this->setComposer($composer);
		$this->cm->execute("php composer.phar update ".$bundle);
		return true;
	}

	public function explore(BundleModel $bundle)
	{
		$basePath = $bundle->namespace."/".$bundle->name;
		$files = $this->scanDir($basePath);
		return $files;
	}

	public function create(\Iga\BuilderBundle\Model\BundleModel $model)
	{
		if($this->checkConvention($model->name)){
			$this->cm->execute("generate:bundle --namespace=".$model->namespace."/".$model->name." --dir=".$this->rootDir."/../src/ --no-interaction");
		}

	}

	public function checkConvention($name){
		$position = strrpos($name,"Bundle");
		return $position;
	}
	
	public function get($namespace,$name)
	{
		if(is_dir($this->rootDir."/".$namespace."/".$name)){
			$bundle = new BundleModel();
			$bundle->name = $name;
			$bundle->namespace = $namespace;
			$bundle->routenamespace = str_replace("/","-",$namespace);
			return $bundle;
		}
		return false;
	}

	private function scanNamespace($namespace=""){
		$bundles = Array();
		$path = empty($namespace) ? "": $namespace."/";
		$raw = scandir($this->rootDir."/".$path);
		foreach($raw as $object){
			if((is_dir($this->rootDir."/".$path.$object)) && ($object !== ".") && ($object !== "..")){
				if($this->checkConvention($object)){
					$bundle = new BundleModel();
					$bundle->name = $object;
					$bundle->namespace = $namespace;
					$bundle->routenamespace = str_replace("/","-",$namespace);
					$bundles[] = $bundle;
				}else{
					$objects = empty($namespace) ? $this->scanNamespace($object) : $this->scanNamespace($path.$object);
					$bundles = array_merge($bundles,$objects);
				}
			}
		}
		return $bundles;
	}

	private function scanDir($basePath,$path=""){
		$files = Array();
		$targetPath = empty($path) ? $basePath : $basePath."/".$path;
		//if($path=="config")
		//die($this->rootDir."/".$targetPath);
		if ($handle = opendir($this->rootDir."/".$targetPath)) {
    		while (false !== ($entry = readdir($handle))) {
    			if(($entry == ".") || ($entry == "..")){
    				continue;
    			}
    			$dirFiles = false;
    			$dir = false;
    			$routePath = empty($path) ? "" : $path."/";
    			if(is_dir($this->rootDir."/".$targetPath."/".$entry)){
    				$dirFiles = $this->scanDir($basePath,$routePath.$entry);
    				$dir = true;
    			}
    			$file = Array(
    				'name' => $entry,
    				'type' => (string) filetype($this->rootDir."/".$targetPath . "/" . $entry),
    				'dir' => $dir,
    				'files' => $dirFiles,
    				'route' => str_replace("/","-_-",$routePath.$entry)
    			);
    			$files[] = $file;
    		}
    	}
    	return $files;	
	}

	public function openBundleFile(BundleModel $bundle,FileModel $file){

		$fileRoute = str_replace("-_-","/",$file->route);
		$route="/".$bundle->namespace."/".$bundle->name."/".$fileRoute;
		return $this->openFile($route);
	}

	public function openFile($path){
		$file = new FileModel();
		if(is_dir($this->rootDir.$path)){
			$file->isDirectory = true;
		}else{
			$file->content = file_get_contents($this->rootDir.$path);	
		}
		$file->route = $path;
		return $file;
	}

	public function saveFileBundle(BundleModel $bundle,FileModel $file){
		$fileRoute = str_replace("-_-","/",$file->route);
		$route = $fileRoute;
		if($file->isDirectory){
			return mkdir($this->rootDir.$route);
		}else{
			return file_put_contents($this->rootDir.$route,$file->content);	
		}
	}

	public function saveFile(FileModel $file,$fix=true){
		$fixStr = str_replace("\\\\","\\",$file->content);
		$content = ($fix) ?  $fixStr : $file->content;
		$content = $fixStr;
		if($file->isDirectory){
			return mkdir($this->rootDir.$file->route);
		}else{
			return file_put_contents($this->rootDir.$file->route,$content);	
		}
	}

}