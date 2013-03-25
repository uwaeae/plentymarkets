<?php

namespace Acme\BSCheckoutBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class quickbuttonType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('pos','number',array('label'=>'Position'))
            ->add('name','text',array('label'=>'Titel'))
            ->add('code','text',array('label'=>'Artikel Code'))
            ->add('price','money',array('label'=>'Standart Preis','required'=>false))
            ->add('quickkey','text',array('label'=>'Schnellwahltaste','required'=>false))
            ->add('cashbox')
        ;
    }

    public function getName()
    {
        return 'acme_bscheckoutbundle_quickbuttontype';
    }
}
