<?php
namespace Iga\BuilderBundle\Form\Type\Generator;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\DBAL\Types\Type;


class EntityFieldGeneratorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$choices = array_keys(Type::getTypesMap());
        $builder->add('name',null,array('attr'=>array('class'=>'input-medium')))->add('type','choice',array('choices'=>$choices,'attr'=>array('class'=>'input-small'),'expanded'=>false,'preferred_choices'=>array(8)))->add('length','integer',array('attr'=>array('class'=>'input-mini')));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Iga\BuilderBundle\Model\Generator\EntityFieldGeneratorModel',
        ));
    }

    public function getName()
    {
        return 'entity_field_generator_type';
    }
}