<?php

namespace Iga\BuilderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Iga\BuilderBundle\Form\Type\Generator\EntityFieldGeneratorType;
use Iga\BuilderBundle\Form\Type\Generator\EntityGeneratorType;

use Iga\BuilderBundle\Model\Generator\EntityFieldGeneratorModel;
use Iga\BuilderBundle\Model\Generator\EntityGeneratorModel;


/**
 * @Route("/generator")
 */
class GeneratorController extends Controller
{

 	/**
     * @Route("/entity/{namespace}_{name}",name="builder_generator_entity")
     * @Template()
     */
    public function entityAction($namespace,$name){
     
     	$request = $this->getRequest();
     	$entity = new EntityGeneratorModel();

        // dummy code - this is here just so that the Task has some tags
        // otherwise, this isn't an interesting example
        $field = new EntityFieldGeneratorModel();
        $field->type = 'string';
        $field->name = 'id';
        $entity->fields->add($field);

        $form = $this->createForm(new EntityGeneratorType(), $entity);

        // process the form on POST
        if ($request->isMethod('POST')) {
            $form->bind($request);
            if ($form->isValid()) {
            	die("Generation dooo");
                // maybe do some form processing, like saving the Task and Tag objects
            }
        }

        $namespace = str_replace("/","-",$namespace);
        return array('form'=>$form->createView(),'namespace'=>$namespace,'name'=>$name);


    }


}
