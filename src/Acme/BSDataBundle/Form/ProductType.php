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
            ->add('price')
            ->add('description')
            ->add('short_description')
            ->add('label_text')
            ->add('botanical')
            ->add('stockground')
        ;
    }

    public function getName()
    {
        return 'acme_plentymarketsorderbundle_producttype';
    }
}
