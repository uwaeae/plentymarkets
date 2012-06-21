<?php

namespace Acme\BSDataBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PlantType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name',null,array("label"=>'Name'))
            ->add('code',null,array("label"=>'Code'))
            ->add('latein',null,array("label"=>'Latein'))
            ->add('hardy',null,array("label"=>'Winterhart'))
            ->add('place',null,array("label"=>'Standort'))
            ->add('LastUpdate',null,array("label"=>'Letzte Akutalisierung'))
            ->add('synonym',null,array("label"=>'Synonym'))
            ->add('instructions',null,array("label"=>'Pflegeanleitung'))
            ->add('h_from',null,array("label"=>'Höhe von'))
            ->add('h_to',null,array("label"=>'bis'))
            ->add('b_from',null,array("label"=>'Blüte von'))
            ->add('b_to',null,array("label"=>' bis'))
            ->add('b_color',null,array("label"=>'Farbe'))
            ->add('flavour',null,array("label"=>'Duft'))
            ->add('light',null,array("label"=>'Licht'))
            ->add('base',null,array("label"=>'Boden'))
            ->add('labeltext',null,array("label"=>'Etiketentext'))
            ->add('habitus',null,array("label"=>'Habitus'))
            ->add('pricegroup',null,array("label"=>'Preisgruppe'))
            ->add('potsize',null,array("label"=>'Topfgröße'))
            ->add('aviable',null,array("label"=>'Lieferbar'))
            ->add('comment',null,array("label"=>'Kommentar'))
            ->add('stock',null,array("label"=>'Lager'))
        ;
    }

    public function getName()
    {
        return 'acme_bsdatabundle_planttype';
    }
}
