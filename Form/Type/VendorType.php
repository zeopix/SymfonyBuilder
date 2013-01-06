<?php
namespace Iga\BuilderBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class VendorType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('vendor');
        $builder->add('bundle');
    }

    public function getName()
    {
        return 'vendortype';
    }
}