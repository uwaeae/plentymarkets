<?php

namespace Acme\BSDataBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class StockType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('number')
            ->add('name')
        ;
    }

    public function getName()
    {
        return 'acme_bsdatabundle_stocktype';
    }
}
