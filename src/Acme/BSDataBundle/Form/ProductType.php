<?php

namespace Acme\BSDataBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('article_id')
            ->add('article_no')
            ->add('name')
            ->add('name2')
            ->add('name3')
            ->add('price')
            ->add('label_text')
            ->add('quantity')
            ->add('stock')
        ;
    }

    public function getName()
    {
        return 'acme_bsdatabundle_producttype';
    }
}
