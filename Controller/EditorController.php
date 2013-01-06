<?php

namespace Iga\BuilderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Iga\BuilderBundle\Model\FileModel;
use Iga\BuilderBundle\Model\BundleModel;

/**
 * @Route("/editor")
 */
class EditorController extends Controller
{
    /**
     * @Route("/{namespace}_{name}",name="builder_editor")
     * @Template()
     */
    public function indexAction($namespace,$name)
    {
        $namespace = str_replace("-","/",$namespace);
        $bundle = $this->get('bundle_manager')->get($namespace,$name);
        $files = $this->get('bundle_manager')->explore($bundle);

        $namespace = str_replace("/","-",$namespace);
        return array('bundle' => $bundle,'files'=>$files,'namespace'=>$namespace,'name'=>$name);
    }


    /**
     * @Route("/{namespace}_{name}/{route}",name="builder_editor_edit")
     * @Template()
     */
    public function editAction($namespace,$name,$route){
        $request = $this->getRequest();
        $bundle = new BundleModel();
        $bundle->namespace = $namespace;
        $bundle->name = $name;
        $file = new FileModel();
        $file->route = $route;

        $file = $this->get('bundle_manager')->openBundleFile($bundle,$file);
        $form = $this->createFormBuilder($file)->add('content', 'textarea')->getForm();
        
        if($request->getMethod() == "POST"){
            $form->bind($request);
            if($form->isValid()){
                $this->get('bundle_manager')->saveFileBundle($bundle,$file);
                $request->getSession()->setFlash('message','Archivo guardado correctamente a las '.date('H:i'));
            }
        }

        $namespace = str_replace("/","-",$namespace);

        return array("form"=>$form->createView(),'namespace'=>$namespace,'name'=>$name);

    }

    /**
     * @Route("/{namespace}_{name}/{route}/create",name="builder_editor_create")
     * @Template()
     */
    public function createAction($namespace,$name,$route){
        
        $request = $this->getRequest();
        $file = new FileModel();
        $bundle = new BundleModel();
        $file->content = "<?php \n // code";
        $bundle->namespace = $namespace;
        $bundle->name = $name;

        $form = $this->createFormBuilder($file)->add('name', 'text')->add('isDirectory','checkbox')->getForm();

        if($request->getMethod() == "POST"){
            $form->bind($request);
            if($form->isValid()){
                $file->route = $route . "-_-" . $file->name;
                $this->get('bundle_manager')->saveFileBundle($bundle,$file);
                $request->getSession()->setFlash('message','Archivo guardado correctamente a las '.date('H:i'));
                $route = $file->getRoutingRoute();
                if($file->isDirectory){
                    $this->redirect($this->generateUrl('builder_editor',array('namespace'=>$namespace,'name'=>$name)));
                }
                return $this->redirect($this->generateUrl('bundle_editor_edit',array('namespace'=>$namespace,'name'=>$name,'route'=>$route)));
            }
        }else
        
        $namespace = str_replace("/","-",$namespace);
        $route = str_replace("/","-_-",$route);
        return array("form"=>$form->createView(),'namespace'=>$namespace,'name'=>$name,'route'=>$route);

    }

    public function explorerAction($namespace,$name)
    {
        $namespace = str_replace("-","/",$namespace);
        $bundle = $this->get('bundle_manager')->get($namespace,$name);
        $files = $this->get('bundle_manager')->explore($bundle);

        $namespace = str_replace("/","-",$namespace);
        return $this->render('IgaBuilderBundle:Editor:explorer.html.twig',array('bundle' => $bundle,'files'=>$files,'namespace'=>$namespace,'name'=>$name));
    }

}
