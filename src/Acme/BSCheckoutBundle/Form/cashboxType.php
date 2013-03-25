<?php

namespace Acme\BSCheckoutBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class cashboxType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')

        ;
    }

    public function getName()
    {
        return 'acme_bscheckoutbundle_cashboxtype';
    }
}
