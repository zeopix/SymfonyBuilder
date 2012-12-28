<?php
namespace Iga\BuilderBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class BundleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('namespace');
        $builder->add('name');
    }

    public function getName()
    {
        return 'bundletype';
    }
}