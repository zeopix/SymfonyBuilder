<?php

namespace Iga\BuilderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class DefaultController extends Controller
{
    /**
     * @Route("/",name="builder_bundle")
     * @Template()
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        $bundles = $this->get('bundle_manager')->getList();

        $bundle = new \Iga\BuilderBundle\Model\BundleModel();
        $form = $this->createForm(new \Iga\BuilderBundle\Form\Type\BundleType(),$bundle);
        $form->bind($request);
        if($request->getMethod() == "POST"){
            if($form->isValid()){    
                $success = $this->get('bundle_manager')->create($bundle);
                if($success){
                    $this->redirect($this->generateUrl("builder_bundle"));
                }            
            }
        }
        return array('bundles' => $bundles,'form'=>$form->createView());
    }
    /**
     * @Route("/{namespace}_{name}",name="builder_bundle_show")
     * @Template()
     */
    public function showAction($namespace,$name)
    {
        $namespace = str_replace("-","/",$namespace);
        $bundle = $this->get('bundle_manager')->get($namespace,$name);
        return array('bundle' => $bundle);
    }
    
    /**
     * @Route("/new",name="builder_bundle_new")
     * @Template()
     */
    public function newAction()
    {
    	$request = $this->getRequest();

    	$type = new \Iga\BuilderBundle\Form\Type\BundleType();
    	$model = new \Iga\BuilderBundle\Model\BundleModel();

    	$form = $this->createForm($type,$model);

		if($request->getMethod() == "POST"){
			if($form->isValid()){
				$success = $this->get('bundle_manager')->create($model);
				if($success){
					$this->redirect($this->generateUrl("builder_bundle"));
				}
			}
		}
        return array('code' => $code, 'statements'=>$statements);
    }

    public function sniffAction(){

        $code = '<?php $foo = array("bar",3); ?>';
        $parser = new \PHPParser_Parser(new \PHPParser_Lexer);
        try {
            $statements = $parser->parse($code);
        } catch (\PHPParser_Error $e) {
            throw new \Exception("Parse Error: ". $e->getMessage());
        }
        return array('code' => $code, 'statements'=>$statements);
    }

}
