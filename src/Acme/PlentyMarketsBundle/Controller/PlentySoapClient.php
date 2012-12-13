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
use Acme\BSDataBundle\Entity\Product;
use Acme\BSDataBundle\Entity\Orders;
use Acme\BSDataBundle\Entity\OrdersItem;
use Acme\BSDataBundle\Entity\OrdersInfo;
use Acme\BSDataBundle\Entity\PaymentMethods;


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
	private $WSDL_URL		=	'http://shop2.blumenschule.de/plenty/api/soap/version106/?xml';
	
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
  private $doctrine;

	
	/**
     * Der Konstruktor übergibt die WSDL-URL an die Superklasse SoapClient.
     * SoapClient ist eine PHP-Standardklasse. 
     * Genauere Informationen dazu finden Sie unter folgendem Link: 
     * 
     * http://www.php.net/manual/de/class.soapclient.php
	 */
	public function __construct($controller,$doctrine)
	{

		parent::__construct($this->WSDL_URL, $this->getSoapClientOptions());
    $this->controller = $controller;
    $this->doctrine = $doctrine;
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
      $repository = $this->doctrine->getRepository('PlentyMarketsBundle:Token');

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
          $em = $this->doctrine->getEntityManager();
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
     * Mit dem Call GetMethodOfPayments werden die Zahlungsarten abgerufen.
     * Eine Zahlungsart enthält jeweils eine Liste mit den aktiven Lieferländern und eine Liste mit den aktiven Multishops.
     */
    public function doGetMethodOfPayments()

    {
        $oResponse = null;


        $options['ActiveMethodOfPayments'] =  true;


        try
        {
            $oResponse	=	$this->__soapCall('GetMethodOfPayments', array( $options));
        }
        catch(\SoapFault $sf)
        {
            echo("Es kam zu einem Fehler beim Call GetAuthentificationToken<br>");
            echo($sf->getMessage());
        }

        //echo var_dump($oResponse);


        if( isset($oResponse->Success) )
        {
            return $this->syncMethodsOfPayment($oResponse->MethodOfPayment);
        }
        else
        {
            return("Es ist ein Fehler aufgetreten  ");//.$oResponse->ErrorMessages->item[0]->Message;
        }
    }



    /**
     * Mit diesem Call kann der Status einer Bestellung verändert werden.
     *
     */
    public function doSetOrderStatus( $orderID, $state)

    {
        $oResponse	= null;

        $options['OrderID'] = null;
        $options['OrderStatus'] =  doubleval($state);


        try
        {
            $oResponse	=	$this->__soapCall('SetOrderStatus',array( $options));
        }
        catch(\SoapFault $sf)
        {
            echo("Es kam zu einem Fehler beim Call GetAuthentificationToken<br>");
            echo($sf->getMessage());
        }


        if( isset($oResponse->Success) )
        {
            return(true);
        }
        else
        {
            return("Es ist folgender Fehler aufgetreten : ");//.$oResponse->ErrorMessages->item[0]->Message;
        }
    }


    /**
     * Mit diesem Call können mehere Artikel aus dem Shop abgerufen werden
     *
     */
    public function doGetItemsBase( $lastUpdate = null,$output = null)

    {
        $oResponse	= null;
        $page = 0;
        $options['LastUpdate'] = $lastUpdate ;
        $options['LastInserted'] =null ;
        $options['Marking1ID'] =null ;
        $options['Marking2ID'] =null ;
        $options['Webshop'] = 1 ;
        $options['WebAPI'] =null;
        $options['Gimahhot'] =null;
        $options['GoogleProducts'] =null ;
        $options['Hitmeister'] =null ;
        $options['Shopgate'] =null ;
        $options['Shopperella'] =null ;
        $options['ShopShare'] =null;
        $options['Tradoria'] = null ;
        $options['Yatego'] = null ;
        $options['Quelle'] = null;
        $options['MainWarehouseID'] = null ;
        $options['GetShortDescription'] = null ;
        $options['GetLongDescription'] = null;
        $options['GetTechnicalData'] = null ;
        $options['Page'] = null;


        try
        {
            $oResponse	=	$this->__soapCall('GetItemsBase',array( $options));
        }
        catch(\SoapFault $sf)
        {
            print_r("Es kam zu einem Fehler beim Call GetAuthentificationToken<br>");
            print_r($sf->getMessage());
        }
        if ( isset($oResponse->Success) and $oResponse->ItemsBase != null ){
           // $output = array_merge($output, $oResponse->ItemsBase->item);
            $this->syncArticle( $oResponse->ItemsBase->item,$output);
            if(isset($oResponse->Pages)) $page = $oResponse->Pages;
        }

        for($i = 1; $i < $page; $i++)
        {

            $options['Page'] = $i  ;
            try
            {
                $oResponse	=	$this->__soapCall('GetItemsBase',array( $options));
            }
            catch(\SoapFault $sf)
            {
                print_r("Es kam zu einem Fehler beim Call GetAuthentificationToken<br>");
                print_r($sf->getMessage());
            }
            if ( isset($oResponse->Success) and $oResponse->ItemsBase != null ){
                //$output = array_merge($output, $oResponse->ItemsBase->item);
                $this->syncArticle( $oResponse->ItemsBase->item,$output);
            }

        }

        return $output;
    }

    private function syncArticle($Items,$output = null){


        $em = $this->doctrine->getEntityManager();
        $repository = $this->doctrine->getRepository('BSDataBundle:Product');


        foreach($Items as $item){
            // $id = explode("-",  $item->SKU);
            $product = $repository->findOneBy(array('article_id' => $item->ItemID));
            if(!$product) {
                $product = new Product();
            }

            $product->PMSoapProduct($item );

            $em->persist($product);
            if($output) $output->writeln($product->getArticleId().' '.$product->getArticleNo());
        }

        $em->flush();



    }





    /**
     * Get Orders mit einem bestimmten Status
     */
    public function doGetOrdersWithState($state = 5.5, $LastUpdate = null, $OrderType = null )
    {
        $page = 1;
        $oResponse	= null;
        $options['Page'] = null;
        $options['OrderType'] = $OrderType;
        $options['OrderStatus'] =  doubleval($state);
        $options['MultishopID'] = 0;
        $options['OrderID'] = null;
        $options['LastUpdateFrom'] = $LastUpdate ;
        $options['LastUpdateTill'] = date('U');
        $options['GetOrderDeliveryAddress'] = true;
        $options['GetOrderCustomerAddress'] = true;

        $options['GetOrderInfo'] = true;

        //$options = $option + $options;
        $output = array();
        try
        {
            $oResponse	=	$this->__soapCall('SearchOrders',array( $options));
        }
        catch(\SoapFault $sf)
        {
            print_r("Es kam zu einem Fehler beim Call GetAuthentificationToken<br>");
            print_r($sf->getMessage());
        }
        if ( isset($oResponse->Success) and $oResponse->Orders != null ){
            $output = array_merge($output, $this->syncOrders($oResponse->Orders->item));
            if(isset($oResponse->Pages)) $page = $oResponse->Pages;
        }

        for($i = 1; $i < $page; $i++)
        {

            $options['Page'] = $i  ;
            try
            {
                $oResponse	=	$this->__soapCall('SearchOrders',array( $options));
            }
            catch(\SoapFault $sf)
                {
                print_r("Es kam zu einem Fehler beim Call GetAuthentificationToken<br>");
                print_r($sf->getMessage());
            }
            if ( isset($oResponse->Success) and $oResponse->Orders != null ){
                $output = array_merge($output, $this->syncOrders($oResponse->Orders->item));

            }

            }

        return $output;

    }

    private function syncOrders($aoOrders){
        $em = $this->doctrine->getEntityManager();
        $OrderRepro = $this->doctrine
            ->getRepository('BSDataBundle:Orders');

        $OrderItemRepro = $this->doctrine
            ->getRepository('BSDataBundle:OrdersItem');

        $OrderInfoRepro = $this->doctrine
            ->getRepository('BSDataBundle:OrdersInfo');


        $orders = array();
        foreach($aoOrders  as $PMOrder){



            $BSOrder =  $OrderRepro->findOneBy(array('OrderID' => $PMOrder->OrderHead->OrderID));

            //$aOrder[] =  $this->syncOrderData($AOorder,$oOrder);
            if(!$BSOrder) {
                $OrderHead = $this->syncOrderData($PMOrder, new Orders());
                $OrderInfo = $this->syncOrderInfoData($PMOrder, $OrderHead);
                $OrderItem = $this->syncOrderItemsData($PMOrder);
            }
            else if ( $BSOrder->getLastUpdate() != $PMOrder->OrderHead->LastUpdate ){
                $OrderHead = $this->syncOrderData($PMOrder, $BSOrder);
                $OrderInfo = $this->syncOrderInfoData($PMOrder, $OrderHead);
                $OrderItem = $this->syncOrderItemsData($PMOrder);
            }
            else{
                $OrderHead = $BSOrder;
                $OrderItem = $OrderItemRepro->findBy(array('OrderID' => $OrderHead->getOrderID()));
                $OrderInfo = $OrderInfoRepro->findBy(array('OrderID' => $OrderHead->getOrderID()));

            }

            $em->flush();

            $orders[] = array('head'=> $OrderHead,'infos'=> $OrderInfo, 'items'=>$OrderItem );

        }

        return $orders;
    }

    private function syncOrderData($AOorder,Orders $order){

        $em = $this->doctrine->getEntityManager();
        // Order HEAD
        try{
            $order->setOrderID($AOorder->OrderHead->OrderID);
            $order->setLastUpdate($AOorder->OrderHead->LastUpdate);
            $order->setCustomerID($AOorder->OrderHead->CustomerID);
            $order->setPackageNumber($AOorder->OrderHead->PackageNumber);
            $order->setTotalBrutto($AOorder->OrderHead->TotalBrutto);
            $order->setShippingCosts($AOorder->OrderHead->ShippingCosts);
            $order->setDoneTimestamp($AOorder->OrderHead->DoneTimestamp);
            $order->setPaidTimestamp($AOorder->OrderHead->PaidTimestamp);
            $order->setOrderStatus($AOorder->OrderHead->OrderStatus);
            $order->setOrderType($AOorder->OrderHead->OrderType);
            $opm = $em->getRepository('BSDataBundle:PaymentMethods')->find($AOorder->OrderHead->MethodOfPaymentID);
            $order->setPaymentMethods($opm);
            $order->setInvoiceNumber($AOorder->OrderHead->InvoiceNumber);



            if(isset($AOorder->OrderDeliveryAddress->Street)){
               // $order->setTitle($AOorder->OrderDeliveryAddress->Title);
                $order->setFirstname($AOorder->OrderDeliveryAddress->FirstName);
                $order->setLastname($AOorder->OrderDeliveryAddress->Surname);
                $order->setAdditionalName($AOorder->OrderDeliveryAddress->AdditionalName);
                $order->setCompany($AOorder->OrderDeliveryAddress->Company);
                $order->setStreet($AOorder->OrderDeliveryAddress->Street);
                $order->setHouseNumber($AOorder->OrderDeliveryAddress->HouseNumber);
                $order->setCity($AOorder->OrderDeliveryAddress->City);
                $order->setZIP($AOorder->OrderDeliveryAddress->ZIP);
                $order->setCountryID($AOorder->OrderDeliveryAddress->CountryID);
                $order->setTelephone($AOorder->OrderDeliveryAddress->Telephone);
                $order->setEmail($AOorder->OrderDeliveryAddress->Email);
            }

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
            //$em->flush();
            return $order;
        }catch (\Exception $e){
            throw  $this->createNotFoundException('Some failure in Order :'. $AOorder->OrderHead->OrderID);
        }
    }

    private function syncOrderInfoData($AOorder,$order){


        $em = $this->doctrine->getEntityManager();
        $aOrderInfos = array();
        if($AOorder->OrderHead->OrderInfos != null){

            foreach($AOorder->OrderHead->OrderInfos->item as $aoOorderInfo){
                $oOrdersInfo = new OrdersInfo();
                //$oOrdersInfo->setOrderID($AOorder->OrderHead->OrderID);
                $oOrdersInfo->setText($aoOorderInfo->Info);
                $oOrdersInfo->setiscreated(new DateTime("now"));
                $oOrdersInfo->setOrderID($AOorder->OrderHead->OrderID);
                $oOrdersInfo->setOrders($order);
                $em->persist($oOrdersInfo);

                $aOrderInfos[]= $oOrdersInfo;
                //$order->addOrdersInfo($oOrdersInfo);

            }
           // $em->flush();
        }
        return $aOrderInfos;

    }

    private function syncOrderItemsData($AOorder){


        $em = $this->doctrine->getEntityManager();


        $aOrderItems = $em->getRepository('BSDataBundle:OrdersItem')->findBy(array('OrderID' => $AOorder->OrderHead->OrderID));

        if($aOrderItems){
            foreach( $aOrderItems as $item){
                $em->remove($item);

                }
            //$em->flush();
            }
        $aOrderItems = array();
        if(isset($AOorder->OrderItems->item)){
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

                 $aOrderItems[]= $orderitem;

            }
        }
        //$em->flush();

        return $aOrderItems;
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

    /**
     * Funktion um ein Customer Object mittels SOAP abzufragen
     * @param array $option
     * @return mixed
     */
    public function doGetOrdersDeliveryNoteDocumentURLs ( array $option )
    {


        $options['OrderIDs'] = null;
        $options['CustomerNumber'] = null;
        $options['ExternalCustomerID'] = null;
        $options = $option + $options;

        try
        {
            $oResponse	=	$this->__soapCall('GetOrdersDeliveryNoteDocumentURLs',array( $options));
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
                    return($oResponse->ItemBase);
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

    private function syncMethodsOfPayment($oaMOP){

        $em = $this->doctrine->getEntityManager();

        $Output = array();

        foreach($oaMOP->item as $item ){

            $Output[] =  $item->MethodOfPaymentID.' '.$item->Name;
            $oPM = $em->getRepository('BSDataBundle:PaymentMethods')->findBy(array('id' => $item->MethodOfPaymentID));
            if(!$oPM){
                $oPM = new PaymentMethods();
                $oPM->setName($item->Name);
                $oPM->setId($item->MethodOfPaymentID);
                $oPM->setDebitor('50000');
                $oPM->setBankAccount('1803');
                $em->persist($oPM);
                $em->flush();
            }



        }
        return $Output;
    }

}
