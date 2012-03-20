<?php

/**
 * 
 * Dies ist ein PHP-Beispiel für einen SOAP-Client, der zeigt,
 * wie ein SOAP-Client für die neue SOAP API 
 * aufgebaut ist und wie ein Call ausgeführt wird.
 * 
 * In diesem Beispiel werden die Paramater als array()
 * übergeben.
 * 
 * Rückfragen hierzu schicken Sie bitte an Oliver Skrzipek 
 * os@plentysystems.de
 * 
 * @author plentyDeveloper
 * @version version100
 * 
 */

namespace Acme\BSLableBundle\Controller;
use \SoapFault;
use Acme\BSLableBundle\Entity\Token;

class PlentySoapClient extends \SoapClient
{
	
	/**
	 * URL zur WSDL-Datei. 
	 * Die URL zur WSDL-Datei entnehmen Sie bitte aus Ihrem Admin-Bereich unter: 
	 * Einstellungen » plentyAPI Daten
	 * SOAP WSDL
	 * 
	 * Die hinterlegte URL ist eine lokale Test-URL eines plentyMarkets-Entwicklungsrechners
	 * und wird daher bei Ihnen nicht funktionieren.
	 * 
	 * Bitte tragen Sie hier Ihre korrekte WSDL-URL ein.
	 */
	private $WSDL_URL		=	'http://blumenschule-11117.plenty-test.de/plenty/api/soap/version103/?xml';
	
	/**
     * Die Benutzerdaten für den SOAP-Benutzer. Im Admin-Bereich unter 
     * Einstellungen » Benutzerkonten
     * muss ein entsprechender Benutzer für die SOAP-Calls eingerichtet werden. 
     * 
     * Lesen Sie hierzu bitte auch das Handbuch: 
     * http://man.plentysystems.de/soap-api/benutzereinrichtung/
     * 
     * Die hinterlegten Daten sind aus einem lokalen Testsystem eines plentyMarkets-Entwicklungsrechners
	 * und werden daher bei Ihnen nicht funktionieren.
	 * 
	 * Bitte tragen Sie hier Ihre korrekten Benutzerdaten ein.
	 */
	private $SOAP_USERNAME	=	'Florian';
	private $SOAP_USERPASS	=	'blumenschule';
  private $controller;
	
	/**
     * Der Konstruktor übergibt die WSDL-URL an die Superklasse SoapClient.
     * SoapClient ist eine PHP-Standardklasse. 
     * Genauere Informationen dazu finden Sie unter folgendem Link: 
     * 
     * http://www.php.net/manual/de/class.soapclient.php
	 */
	public function __construct($controller)
	{
		parent::__construct($this->WSDL_URL, $this->getSoapClientOptions());
    $this->controller = $controller;
    $this->doAuthenticication();
  }
	
	/**
	 * Es werden die für den SOAP-Client zu verwendenden Paramter erstellt.
     * Diese sind nicht alle zwingend erforderlich. 
     * Sie können den array zu Testzwecken erweitern und/oder bearbeiten.
     * 
     * Eine Liste der verfügbaren Parameter finden Sie unter : 
     * 
     * http://www.php.net/manual/de/soapclient.soapclient.php
	 */
	private function getSoapClientOptions()
	{
		$aSoapClientOptions				=	array();
		$aSoapClientOptions["features"] = 	SOAP_SINGLE_ELEMENT_ARRAYS;
		$aSoapClientOptions["version"] 	= 	SOAP_1_2;
		$aSoapClientOptions["trace"] 	= 	1;
		$aSoapClientOptions["exceptions"] 	= 	0;
		return $aSoapClientOptions;
	}
	
	/**
 	 * Mit dieser Methode wird versucht, sich am Server zu authentifizieren und
 	 * die für den SOAP-Header relevanten Daten - UserID und Token - werden 
 	 * abgerufen.
 	 * 
 	 * Lesen Sie hierzu auch die Beschreibung des Calls 
 	 * GetAuthentificationToken
 	 * im Handbuch unter folgendem Link: 
 	 * 
 	 * http://man.plentysystems.de/soap-api/calls/authentification/
	 */
	public function doAuthenticication()
	{
		/**
		 * Array mit den User-Daten erstellen.
		 * Achten Sie hier auf die Schreibweise der Parameter - die Schreibweise aller Parameter
		 * ist case-sensitive. 
		 */
		$LoginData	=	array(
								array(
									'Username'=>$this->SOAP_USERNAME, 
									'Userpass'=>$this->SOAP_USERPASS	
									)
							);
		/**
		 * Nun erfolgt der eigentliche Call der Funtion 
		 * GetAuthentificationToken
		 * Als Parameter werden hier die Login-Daten übergeben.
		 * 
		 * Durch den try-catch Block werden SoapFault abgefangen. Hierbei
		 * handelt es sich um allgemeine SOAP-Fehler.
		 */
      $repository = $this->controller->getDoctrine()
          ->getRepository('AcmeBSLableBundle:Token');

      $query = $repository->createQueryBuilder('t')
          ->where(' t.date = CURRENT_DATE()')
          ->getQuery();

      $aToken = $query->getResult();

    $today = new \DateTime('now');


    if(count($aToken) == 0 ){
        try
        {
          $oResponse	=	$this->__soapCall('GetAuthentificationToken', $LoginData);
        }
        catch(\SoapFault $sf)
        {
          print_r("Es kam zu einem Fehler beim Call GetAuthentificationToken<br>");
          print_r($sf->getMessage());
        }


        if( $oResponse->Success == true )
        {
          /**
           * Der Abruf hat erfolgreich UserID und Token zurückgeliefert.
           * Der Token ist bis 00:00:00 Uhr eines Tages gültig und muss daher nicht
           * bei jedem Call erneut abgerufen werden.
           */
          $UserID		=	$oResponse->UserID;
          $Token		=	$oResponse->Token;
          /**
           * Mit diesen Daten kann nun der SOAP-Header erzeugt und hinzugefügt werden
           */
          $oToken = new Token();
          $oToken->setToken($Token);
          $oToken->setUserid($UserID);
          $oToken->setDate($today);
          $em = $this->controller->getDoctrine()->getEntityManager();
          $em->persist($oToken);
          $em->flush();
          $this->createSoapHeader($UserID, $Token);
        }
        else
        {
          /**
           * Der Abruf war leider kein Erfolg.
           * Hier kann Ihre eigene Logik für nicht erfolgreiche Abrufe folgen.
           *
           * Genauere Informationen zu den zurückgelieferten Messages erhalten Sie im Handbuch unter
           * folgendem Link:
           *
           * http://man.plentysystems.de/soap-api/fehlerbehandlung/
           */
          return("Es ist folgender Fehler aufgetreten : ".$oResponse->ErrorMessages->item[0]->Message);
        }
    }
    else {
        $this->createSoapHeader($aToken[0]->getUserid(), $aToken[0]->getToken());
    }

	}

	/**
	 * Mit dem Call GetServerTime wird geprüft, ob der SoapHeader korrekt ist und
	 * die Benutzerrechte stimmen. Bekommt man einen Zeitstempel zurückgeliefert,
	 * sind alle Einstellungen korrekt.
	 */
	public function doGetServerTime()

	{
     $oResponse = null;

		try
		{
			$oResponse	=	$this->__soapCall('GetServerTime', array());
		}
		catch(\SoapFault $sf)
		{
			echo("Es kam zu einem Fehler beim Call GetAuthentificationToken<br>");
			echo($sf->getMessage());
		}

    //echo var_dump($oResponse);


		if( $oResponse->Success == true )
		{
			return("Servertime : ".$oResponse->Timestamp);
		}
		else
		{
			return("Es ist folgender Fehler aufgetreten : ");//.$oResponse->ErrorMessages->item[0]->Message
		}
	}

    /**
     * Get Orders mit einem bestimmten Status
     */
    public function doGetOrdersWithState($option = array() )
    {

        $oResponse	= null;
        $options = $option;

        $options['OrderType'] = null;
        $options['OrderStatus'] =  doubleval(6.0);
        $options['MultishopID'] = 0;
        $options['OrderID'] = null;
        $options['LastUpdate'] = null;
        $options['GetOrderDeliveryAddress'] = true;
        $options['GetOrderCustomerAddress'] = true;
        $options['Page'] = null;

        try
        {
            $oResponse	=	$this->__soapCall('SearchOrders',array( $options));
        }
        catch(\SoapFault $sf)
            {
            print_r("Es kam zu einem Fehler beim Call GetAuthentificationToken<br>");
            print_r($sf->getMessage());
        }


        if( $oResponse->Success == true )
        {
            return($oResponse->Orders);
        }
        else
        {
            return($oResponse->ErrorMessages);
        }
    }

    public function doGetOrder($option = array() )
    {
        $options = $option;

        $options['OrderType'] = 'order';
        $options['OrderStatus'] = 6.0;
        $options['MultishopID'] = 0;
        $options['OrderID'] = '';
        $options['LastUpdate'] = '';
        $options['GetOrderDeliveryAddress'] = true;
        $options['GetOrderCustomerAddress'] = true;
        $options['Page'] = true;

        try
        {
            $oResponse	=	$this->__soapCall('SearchOrders',array( $options));
        }
        catch(SoapFault $sf)
        {
            print_r("Es kam zu einem Fehler beim Call GetAuthentificationToken<br>");
            print_r($sf->getMessage());
        }


        if( $oResponse->Success == true )
        {
            return($oResponse->PlentySoapObject_SearchOrders);
        }
        else
        {
            return($oResponse->ErrorMessages);
        }
    }
	
	/**
     * Diese Funktion erstellt aus den Parametern UserID und Token
     * den SOAP-Header, der für alle folgenden Calls zur Authentifizierung relevant ist.
     * 
     * Der Header wird global zu diesem Client hinzugefügt.
	 */


	private function createSoapHeader($UserID, $Token)
	{
		// HeaderDaten

		$aHeader			=	array(
										'UserID' 	=> $UserID,
										'Token'		=> $Token
									);
		// Header-Daten als 	SoapVar						
		$oSoapHeaderVar		= 	new \SoapVar($aHeader, SOAP_ENC_OBJECT);
		// Namespace des Headers
		$sNamespaceHeader	=	"Authentification";
		// der fertige SOAP-Header
		$oSoapHeader 		=	new \SoapHeader($sNamespaceHeader,'verifyingToken', $oSoapHeaderVar, false);
		// der Header wird dem Client hinzugefügt
		$this->__setSoapHeaders($oSoapHeader);
	}
}
