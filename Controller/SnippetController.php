<?php

namespace Iga\BuilderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Iga\BuilderBundle\Model\FileModel;


/**
 * @Route("/snippet")
 */
class SnippetController extends Controller
{
    /**
     * @Route("/",name="builder_snippets")
     * @Template()
     */
    public function indexAction()
    {
        $snippets = $this->get('snippet_manager')->getSnippets();
        
        return array('snippets'=>$snippets);
    }

}
