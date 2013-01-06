<?php

namespace Iga\BuilderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class DefaultController extends Controller
{
    /**
     * @Route("/{configFileName}",name="builder_bundle",defaults={"configFileName"=FALSE})
     * @Template()
     */
    public function indexAction($configFileName)
    {
        $request = $this->getRequest();
        $bundles = $this->get('bundle_manager')->getList();
        $composer = $this->get('bundle_manager')->getComposer();
        $vendor = new \Iga\BuilderBundle\Model\VendorModel();
        $bundle = new \Iga\BuilderBundle\Model\BundleModel();
        $form = $this->createForm(new \Iga\BuilderBundle\Form\Type\BundleType(),$bundle);
        $vendorForm = $this->createForm(new \Iga\BuilderBundle\Form\Type\VendorType(),$vendor);

        $configTree = $this->get('config_manager')->getTree();
        if($configFileName){
            $file = $this->get('config_manager')->openConfigFileByName($configFileName);

            $configForm = $this->createFormBuilder($file)->add('content', 'textarea')->getForm();

            if($request->getMethod() == "POST"){
                $configForm->bind($request);
                if($configForm->isValid()){
                    $this->get('config_manager')->saveConfigFile($file);
                    $request->getSession()->setFlash('message','Archivo guardado correctamente a las '.date('H:i'));
                }
            }
            return array('bundles' => $bundles,'form'=>$form->createView(),'configFile'=>$file,'configForm'=>$configForm->createView(),'vendorForm'=>$vendorForm->createView(),'composer'=>$composer,'configTree'=>$configTree);

        }

        $form->bind($request);
        if($request->getMethod() == "POST"){
            if($form->isValid()){    
                $success = $this->get('bundle_manager')->create($bundle);
                if($success){
                    $this->redirect($this->generateUrl("builder_bundle"));
                }            
            }
        }

        return array('bundles' => $bundles,'form'=>$form->createView(),'vendorForm'=>$vendorForm->createView(),'composer'=>$composer,'configTree'=>$configTree);
    }


    /**
     * @Route("/vendor/add",name="builder_bundle_vendor_add")
     */
    public function vendorAddAction()
    {
        $request = $this->getRequest();
        $vendor = new \Iga\BuilderBundle\Model\VendorModel();

        $form = $this->createForm(new \Iga\BuilderBundle\Form\Type\VendorType(),$vendor);

        $form->bind($request);
        if($request->getMethod() == "POST"){
            if($form->isValid()){    

                $success = $this->get('bundle_manager')->addVendor($vendor);
                if($success){
                    $request->getSession()->setFlash('message','Se ha vinculado el bundle correctamente.');
                    return $this->redirect($this->generateUrl("builder_bundle"));
                }            
            }
        }
        throw new \Exception("Error, form not valid.");
        //return $this->redirect($this->generateUrl("builder_bundle"));
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
