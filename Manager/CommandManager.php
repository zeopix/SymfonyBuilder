<?php
namespace Iga\BuilderBundle\Manager;


class CommandManager {

	private $rootDir;
	private $rootGitDir;

	public function __construct($rootDir){
		$this->rootDir = $rootDir;
		$this->rootGitDir = $rootDir."/../";
	}
	
	public function execute($command,$output=""){
		//need security fix
		$cli = 'php '.$this->rootDir .'/console '.$command;
		exec($cli,$output);
		return $output;
	}
	
	public function executeGit($command,&$request){

		//need security fix
		$cli = 'git '.$this->getGitCommand($command,$request);
		exec($cli,$output);
		return $output;
	}
	
	public function executeSf($command,&$request){

		return $this->execute($this->getSfCommand($command,$request));
		
	}

	public function getGitCommand($command,&$request){
	switch($command){
    		case "push":
	    		$finalCommand="push origin master";
    			break;
    		case "pull":
    			$finalCommand="pull origin master";
    			break;
    		case "add":
    			$finalCommand="add ".$this->rootGitDir.$request->query->get('path');
    			break;
    		case "commit":
    			$finalCommand="commit -a -m '".$request->query->get('message')."'";
    			break;
    		case "status":
    		default:
	    		$finalCommand="status";
    			break;
    	}
    	return $finalCommand;
    }

	public function getSfCommand($command,&$request){
		$finalCommand="";
	switch($command){
    		case "cache":
	    		$finalCommand="cache:clear --env=".$request->query->get('env');
    			break;
    		case "assets":
	    		$finalCommand="assets:install --symlink web";
    			break;
    		case "assetic":
	    		$finalCommand="assetic:dump";
    			break;
    		case "schema":
	    		$finalCommand="doctrine:schema:update --dump-sql";
    			break;
    		case "schema-confirm":
	    		$finalCommand="doctrine:schema:update --force";
    			break;
    	}
    	return $finalCommand;
    }

}