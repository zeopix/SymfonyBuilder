<?php

namespace Iga\BuilderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Iga\BuilderBundle\Model\FileModel;

/**
 * @Route("/editor")
 * @Template()
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
        $file = $this->get('bundle_manager')->openFile($route);
        $form = $this->createFormBuilder($file)->add('content', 'textarea')->getForm();
        
        if($request->getMethod() == "POST"){
            $form->bind($request);
            if($form->isValid()){
                $this->get('bundle_manager')->saveFile($file);
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
        $form = $this->createFormBuilder($file)->add('name', 'text')->add('content','textarea')->getForm();

        if($request->getMethod() == "POST"){
            $form->bind($request);
            if($form->isValid()){
                $directory = $this->get('bundle_manager')->openFile($route);
                $file->route = str_replace("-_-","/",$route . "-_-" . $file->name);
                $this->get('bundle_manager')->saveFile($file);
                $request->getSession()->setFlash('message','Archivo guardado correctamente a las '.date('H:i'));
                $route = $file->getRoutingRoute();
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
        return array('bundle' => $bundle,'files'=>$files,'namespace'=>$namespace,'name'=>$name);
    }

}
