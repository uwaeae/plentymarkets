<?php

namespace Acme\BSDataBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PlantType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('code')
            ->add('latein')
            ->add('hardy')
            ->add('place')
            ->add('LastUpdate')
            ->add('synonym')
            ->add('instructions')
            ->add('h_from')
            ->add('h_to')
            ->add('b_from')
            ->add('b_to')
            ->add('b_color')
            ->add('flavour')
            ->add('light')
            ->add('base')
            ->add('labeltext')
            ->add('habitus')
            ->add('pricegroup')
            ->add('potsize')
            ->add('aviable')
            ->add('comment')
            ->add('stock')
        ;
    }

    public function getName()
    {
        return 'acme_bsdatabundle_planttype';
    }
}
