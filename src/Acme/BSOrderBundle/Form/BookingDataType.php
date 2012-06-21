<?php

namespace Acme\BSOrderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class BookingDataType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('attachment', null,array('label'  => 'Buchungsdaten'));

        ;
    }

    public function getName()
    {
        return 'acme_bsorderbundle_bookingdatatype';
    }
}
