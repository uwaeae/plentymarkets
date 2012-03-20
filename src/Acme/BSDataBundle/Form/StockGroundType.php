<?php

namespace Acme\BSDataBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class StockGroundType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('stock')
        ;
    }

    public function getName()
    {
        return 'acme_plentymarketsorderbundle_stockgroundtype';
    }
}
