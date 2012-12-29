<?php

namespace Iga\BuilderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Iga\BuilderBundle\Form\Type\Generator\EntityFieldGeneratorType;
use Iga\BuilderBundle\Form\Type\Generator\EntityGeneratorType;

use Iga\BuilderBundle\Model\Generator\EntityFieldGeneratorModel;
use Iga\BuilderBundle\Model\Generator\EntityGeneratorModel;
use Doctrine\DBAL\Types\Type;

/**
 * @Route("/generator")
 */
class GeneratorController extends Controller
{

 	/**
     * @Route("/entity/{namespace}_{name}",name="builder_generator_entity")
     */
    public function entityAction($namespace,$name){
     
     	$request = $this->getRequest();
     	$entity = new EntityGeneratorModel();

        $referer = $request->query->get('referer');
        if($referer){ $referer = $this->getRequest()->headers->get('referer'); }
        
        // dummy code - this is here just so that the Task has some tags
        // otherwise, this isn't an interesting example
        //$field = new EntityFieldGeneratorModel();
        //$field->type = 'string';
        //$field->name = 'name';
        //$entity->fields->add($field);
        $types = array_values(array_flip(Type::getTypesMap()));
        $form = $this->createForm(new EntityGeneratorType(), $entity);

        //proccess form
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()){

                //form to command
                $plainFields = Array();
                foreach($entity->fields as $field){
                    $extra = ($field->type == 8) ? "(".$field->length.")" : "";
                    $plainFields[] = $field->name.":".$types[$field->type].$extra;
                }
                $plainNamespace = str_replace("-","",$namespace);
                $plainName = str_replace("_","",$name);
                $command = 'doctrine:generate:entity --entity='.$plainNamespace.$plainName.':'.$entity->name.' --format='.$entity->format.' --fields="'.implode(" ",$plainFields).'" --no-interaction';
                
                $response = $this->get('command_manager')->execute($command);

                $request->getSession()->setFlash('message','Entidad "'.$namespace.$name.':'.$entity->name.'" creada con éxito.');
                //redirect if needed
                if($referer){ return $this->redirect($referer); }
            }else{
                $request->getSession()->setFlash('dialog','entity');
            }
        }

        $namespace = str_replace("/","-",$namespace);
        return $this->render('IgaBuilderBundle:Generator:entity.html.twig',array('form'=>$form->createView(),'namespace'=>$namespace,'name'=>$name));


    }


}
