<?php

namespace Acme\PlentyMarketsOrderBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Acme\PlentyMarketsOrderBundle\Controller\OrderPDF;

define('EURO',chr(128));

class DefaultController extends Controller
{
    public function indexAction()
    {
        /**
         * Es wird ein neuer Soap-Client angelegt.
         */
        $oPlentySoapClient	=	new PlentySoapClient($this);

        $orders = $oPlentySoapClient->doGetOrdersWithState(array('OrderStatus'=> null));
        $time = $oPlentySoapClient->doGetServerTime();

        $OrderArray = $orders->item;


        return $this->render('PlentyMarketsOrderBundle:Default:orders.html.twig', array('time' => $time,'orders'=>$OrderArray));
    }



}
