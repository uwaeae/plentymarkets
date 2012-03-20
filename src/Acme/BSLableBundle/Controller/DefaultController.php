<?php

namespace Acme\BSLableBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class DefaultController extends Controller
{

    public function indexAction()
    {
		/**
		 * Es wird ein neuer Soap-Client angelegt.
		 */
		$oPlentySoapClient	=	new PlentySoapClient($this);

		/**
		 * Nachdem dieser angelegt wurde, startet die Authentifizierung
		 */
		//$oPlentySoapClient->doAuthenticication();

		/**
		 * Die Authentifizierung ist abgeschlossen und der SOAP-Header wurde
		 * dem Client hinzugefügt.
		 * 
		 * Dies alles kann am einfachsten mit dem Call GetServerTime getestet werden,
		 * da dieser keine Request-Parameter benötigt.
		 */
		//$time = $oPlentySoapClient->doGetServerTime();
     $orders = array();
     $orders = $oPlentySoapClient->doGetOrdersWithState(array());
     $time = $oPlentySoapClient->doGetServerTime();

     $OrderArray = $orders->item;






        return $this->render('AcmeBSLableBundle:Default:base.html.twig', array('time' => $time,'orders'=>$OrderArray));
    }

}
