<?php

namespace Acme\BSDataBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('article_id',null, array('label'=>'ID','read_only'=>true))
            ->add('article_no',null, array('label'=>'CODE'))
            ->add('name',null, array('label'=>'Name'))
            ->add('name2',null, array('label'=>'Name2'))
            ->add('name3',null, array('label'=>'Name3'))
            ->add('price',null, array('label'=>'Preis'))
            ->add('label_text',null, array('label'=>'TEXT'))
            ->add('quantity',null, array('label'=>'Verfügbar'))
            ->add('stock',null, array('label'=>'Lager'))

        ;
    }

    public function getName()
    {
        return 'acme_bsdatabundle_producttype';
    }
}
