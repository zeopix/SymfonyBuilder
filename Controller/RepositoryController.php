<?php

namespace Iga\BuilderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Iga\BuilderBundle\Model\FileModel;


/**
 * @Route("/repository")
 */
class RepositoryController extends Controller
{
    /**
     * @Route("/git/{command}",name="builder_repository_git",defaults={"command"="status"})
     * @Template()
     */
    public function gitAction($command)
    {
    	$request =$this->getRequest();
    	//execute command
    	$output = $this->get('command_manager')->executeGit($command,$request);
        
        return array('output'=>$output,'command'=>$command,'repositoryActive'=>true);
    }

    /**
     * @Route("/sf/{command}",name="builder_repository_sf")
     * @Template()
     */
    public function sfAction ($command){


    	$request =$this->getRequest();
    	//execute command
    	$output = $this->get('command_manager')->executeSf($command,$request);
        
        return array('output'=>$output,'command'=>$command,'repositoryActive'=>true);
    	
    }

}
