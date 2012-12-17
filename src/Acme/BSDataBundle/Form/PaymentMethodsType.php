<?php

namespace Acme\BSDataBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PaymentMethodsType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('id',null,array('label'=>'ID','read_only'=>true))
            ->add('Name',null,array('label'=>'Zahlungsart','read_only'=>true))
            ->add('Debitor',null,array('label'=>'Debitorkonto'))
            ->add('BankAccount',null,array('label'=>'Sachkonto'))
            ->add('payment',null,array('label'=>'Zahlungs Buchung'))
            ->add('invoice',null,array('label'=>'Rechungs Buchung'))
        ;
    }

    public function getName()
    {
        return 'acme_bsdatabundle_paymentmethodstype';
    }
}
