<?php
namespace Iga\BuilderBundle\Form\Type\Generator;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class EntityGeneratorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name');
        $builder->add('format','choice',array('expanded'=>false,'choices'=>Array('annotation'=>'annotation','xml'=>'xml','yml'=>'yml','php'=>'php')));
        $builder->add('fields', 'collection', array('type' => new EntityFieldGeneratorType(),'allow_add' => true,'by_reference' => false,));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Iga\BuilderBundle\Model\Generator\EntityGeneratorModel',
        ));
    }

    public function getName()
    {
        return 'entity_generator_type';
    }
}
