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

namespace Acme\PlentyMarketsBundle\Controller;
use \DateTime;
use \SoapFault;
use Acme\PlentyMarketsBundle\Entity\Token;
use Acme\BSDataBundle\Entity\Orders;
use Acme\BSDataBundle\Entity\OrdersItem;
use Acme\BSDataBundle\Entity\OrdersInfo;


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
          ->getRepository('PlentyMarketsBundle:Token');

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


		if( isset($oResponse->Success) )
		{
			return("Servertime : ".$oResponse->Timestamp);
		}
		else
		{
			return("Es ist folgender Fehler aufgetreten : ");//.$oResponse->ErrorMessages->item[0]->Message;
		}
	}

    /**
     * Get Orders mit einem bestimmten Status
     */
    public function doGetOrdersWithState($state = 0 )
    {

        $oResponse	= null;

        $options['OrderType'] = null;
        $options['OrderStatus'] =  doubleval($state);
        $options['MultishopID'] = 0;
        $options['OrderID'] = null;
        $options['LastUpdate'] = null;
        $options['GetOrderDeliveryAddress'] = true;
        $options['GetOrderCustomerAddress'] = true;
        $options['Page'] = null;
        $options['GetOrderInfo'] = true;

        //$options = $option + $options;


        try
        {
            $oResponse	=	$this->__soapCall('SearchOrders',array( $options));
        }
        catch(\SoapFault $sf)
            {
            print_r("Es kam zu einem Fehler beim Call GetAuthentificationToken<br>");
            print_r($sf->getMessage());
        }


        if( isset($oResponse->Success) )
        {

            $aOrder = array();

            foreach( $oResponse->Orders->item as $AOorder){


                $repository = $this->controller->getDoctrine()
                    ->getRepository('BSDataBundle:Orders');

                $oOrder = $repository->findOneBy(array('OrderID' => $AOorder->OrderHead->OrderID));

                //$aOrder[] =  $this->syncOrderData($AOorder,$oOrder);

                if(!$oOrder)  $aOrder[] =  $this->syncOrderData($AOorder,new Orders());

                else if($oOrder->getLastUpdate() != $AOorder->OrderHead->LastUpdate )
                    $aOrder[] =  $this->syncOrderData($AOorder,$oOrder);

            }


            return($oResponse->Orders);
        }
        else
        {
            return($oResponse->ErrorMessages);
        }
    }

    private function syncOrderData($AOorder,Orders $order){
        $em = $this->controller->getDoctrine()->getEntityManager();
        // Order HEAD
        $order->setOrderID($AOorder->OrderHead->OrderID);
        $order->setLastUpdate($AOorder->OrderHead->LastUpdate);
        $order->setCustomerID($AOorder->OrderHead->CustomerID);




        //$order->setInfoCustomer($AOorder->OrderHead->OrderInfos); // TODO: Florian OrderInfos Testen
        $order->setPackageNumber($AOorder->OrderHead->PackageNumber);
        $order->setTotalBrutto($AOorder->OrderHead->TotalBrutto);
        $order->setOrderStatus($AOorder->OrderHead->OrderStatus);
        //Adresse
        $order->setTitle($AOorder->OrderCustomerAddress->Title);
        $order->setFirstname($AOorder->OrderCustomerAddress->FirstName);
        $order->setLastname($AOorder->OrderCustomerAddress->Surname);
        $order->setAdditionalName($AOorder->OrderCustomerAddress->AdditionalName);
        $order->setCompany($AOorder->OrderCustomerAddress->Company);
        $order->setStreet($AOorder->OrderCustomerAddress->Street);
        $order->setHouseNumber($AOorder->OrderCustomerAddress->HouseNumber);
        $order->setCity($AOorder->OrderCustomerAddress->City);
        $order->setZIP($AOorder->OrderCustomerAddress->ZIP);
        $order->setCountryID($AOorder->OrderCustomerAddress->CountryID);
        $order->setTelephone($AOorder->OrderCustomerAddress->Telephone);
        $order->setEmail($AOorder->OrderCustomerAddress->Email);
        $em->persist($order);
        $em->flush();


        if($AOorder->OrderHead->OrderInfos != null){
            foreach($AOorder->OrderHead->OrderInfos->item as $aoOorderInfo){
                $oOrdersInfo = new OrdersInfo();
                //$oOrdersInfo->setOrderID($AOorder->OrderHead->OrderID);
                $oOrdersInfo->setText($aoOorderInfo->Info);
                $oOrdersInfo->setiscreated(new DateTime("now"));
                $oOrdersInfo->setOrderID($AOorder->OrderHead->OrderID);
                $oOrdersInfo->setOrders($order);
                $em->persist($oOrdersInfo);
                $em->flush();

                //$order->addOrdersInfo($oOrdersInfo);

            }
        }



        //$order->setInfo($AOorder->OrderHead->OrderInfos);


        $aOrderItems = $em->getRepository('BSDataBundle:OrdersItem')->findBy(array('OrderID' => $AOorder->OrderHead->OrderID));
        if($aOrderItems){
            foreach( $aOrderItems as $item){
                $em->remove($item);
                $em->flush();
                }

            }

        foreach($AOorder->OrderItems->item as $item){
            $orderitem = new OrdersItem();
            $orderitem->setSKU($item->SKU);
            $SKU = explode("-", $item->SKU);
            $orderitem->setArticleCode($item->SKU);
            $orderitem->setArticleID($SKU[0]);
            $orderitem->setItemText($item->ItemText);
            $orderitem->setOrderID($item->OrderID);
            $orderitem->setPrice($item->Price);
            $orderitem->setQuantity($item->Quantity);
            $orderitem->setVAT($item->VAT);
            $em->persist($orderitem);
            $em->flush();

        }


        return $order;
    }


     /**
      * @param $orderID
      * @return PlentyOrderObject
      */
    public function doGetOrder( $orderID )
    {


        $options['OrderID'] = $orderID;
        $options['ExternalOrderID'] = null;


        try
        {
            $oResponse	=	$this->__soapCall('GetOrder',array( $options));
        }
        catch(SoapFault $sf)
        {
            print_r("Es kam zu einem Fehler beim Call GetAuthentificationToken<br>");
            print_r($sf->getMessage());
        }


        if( isset($oResponse->Success)  )
        {
            return($oResponse->ResponseObject);
        }
        else
        {
            return($oResponse->ErrorMessages);
        }
    }

    public function doGetWarehouseList( )
    {


        $options['UserID'] = null;


        try
        {
            $oResponse	=	$this->__soapCall('GetWarehouseList',array( $options));
        }
        catch(SoapFault $sf)
        {
            print_r("Es kam zu einem Fehler beim Call GetAuthentificationToken<br>");
            print_r($sf->getMessage());
        }


        if( isset($oResponse->Success)  )
        {
            return($oResponse->WarehouseList);
        }
        else
        {
            return($oResponse->ErrorMessages);
        }
    }


    /**
     * Funktion um ein Customer Object mittels SOAP abzufragen
     * @param array $option
     * @return mixed
     */
    public function doGetCustomer( array $option )
    {


        $options['CustomerID'] = null;
        $options['CustomerNumber'] = null;
        $options['ExternalCustomerID'] = null;
        $options = $option + $options;

        try
        {
            $oResponse	=	$this->__soapCall('GetCustomer',array( $options));
        }
        catch(SoapFault $sf)
        {
            print_r("Es kam zu einem Fehler beim Call GetAuthentificationToken<br>");
            print_r($sf->getMessage());
        }


        if( $oResponse->Success == true)
        {
            return($oResponse->ResponseObject);
        }
        else
        {
            return($oResponse->ErrorMessages);
        }
    }


    public function doGetItemBase( array $option )
    {


        $options['ItemID'] = null;
        $options['ItemNo'] = null;
        $options['ExternalItemID'] = null;
        $options['EAN1'] = null;
        $options['GetShortDescription'] = TRUE;
        $options['GetLongDescription'] = FALSE;
        $options['GetTechnicalData'] = FALSE;
        $options['GetItemSuppliers'] = FALSE;
        $options['GetItemProperties'] = FALSE;
        $options['GetItemOthers'] = TRUE;
        $options = $option + $options;

        try
        {
            $oResponse	=	$this->__soapCall('GetItemBase',array( $options));
        }
        catch(SoapFault $sf)
        {
            print_r("Es kam zu einem Fehler beim Call GetAuthentificationToken<br>");
            print_r($sf->getMessage());
        }

        if(isset($oResponse->Success)){
        if(  $oResponse->Success == TRUE)
        {
            return($oResponse->ResponseObject);
        }
        else
        {
            return($oResponse->ErrorMessages);
        }
        }
        else return($oResponse->Message);
    }

    public function doGetItemsStock( $SKU )
    {

        try
        {
            $oResponse	=	$this->__soapCall('GetItemsStock',array( array('SKU'=> $SKU)));
        }
        catch(SoapFault $sf)
        {
            print_r("Es kam zu einem Fehler beim Call GetAuthentificationToken<br>");
            print_r($sf->getMessage());
        }


        if( $oResponse->Success == TRUE)
        {
            return($oResponse->ResponseObject);
        }
        else
        {
            return($oResponse->ErrorMessages);
        }
    }


}
