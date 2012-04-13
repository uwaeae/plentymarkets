<?php

namespace Acme\BSOrderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Acme\BSDataBundle\Entity\Product;

use Acme\BSDataBundle\Form\ProductType;
//use Acme\BSOrderBundle\Controller\OrderPDF;
use Acme\PlentyMarketsBundle\Controller\PlentySoapClient;

define('EURO',chr(128));

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('BSOrderBundle:Default:index.html.twig');
    }

    public function stateAction($state )
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
        $orders = $oPlentySoapClient->doGetOrdersWithState( $state);
        // $time = $oPlentySoapClient->doGetServerTime();




        return $this->render('BSOrderBundle:Order:orders.html.twig', array(
            'orders'=>$orders , 'state'=> $state       ));
    }

    public function invoiceAction(Request $request){
        return "TEST";//$pdf->Output();
    }

    public function printAction(Request $request)
    {


        $aOrders = array();
        //$orders = $oPlentySoapClient->doGetOrdersWithState(array('OrderStatus'=> doubleval(5)));
        //$OrderArray = $orders->item;
        $oRequest = array();

        foreach($request->request->all() as $Order) {
            $oRequest[] = strval($Order);
        }


        $pdf = new OrderPDF();


        $cellHight= 6;
        $aSortPicklistItems = array();

        foreach($oRequest as $rOrder){

            $pdf->AddPage();

            //Bestellungsdaten aus localer Datenbank holen
            $repository = $this->getDoctrine()->getRepository('BSDataBundle:Orders');
            $oOrder = $repository->findOneBy(array('OrderID' => $rOrder));
            //Betsellinformattionen holen aus localer Datenbank
            $repository = $this->getDoctrine()->getRepository('BSDataBundle:OrdersInfo');
            $aOrderInfo = $repository->findBy(array('OrderID' => $rOrder));
            //Bestellungskopf erstellen
            $pdf->OrderHeader($oOrder,$aOrderInfo);
            $pdf->SetFont('Arial','',10);
            $repository = $this->getDoctrine()->getRepository('BSDataBundle:OrdersItem');
            $aOrderItem = $repository->findBy(array('OrderID' => $rOrder));
            $aSortOrderItems = array();
            $oOrderQuantity = 0;
            //Bestellprositonen zusammen stellen und nach Lagerort Sortieren
            foreach($aOrderItem as $item){
                $product = $this->getItem( $item);
                if($product){
                    if($product->getStock()){
                        $PLIstock = "[".$product->getStock()->getNumber()."] ".$product->getStock()->getName() ;
                    }else{
                        $PLIstock = "[0] Kein Lager";
                    }
                    $aSortOrderItems[$PLIstock][] = array('product'=>$product,'item'=>$item );
                    $PLIartID=  $product->getArticleID();
                    $PLIarray = array('item'=>$item,'product'=>$product,
                            'quantity'=>(isset($aSortPicklistItems[ $PLIstock][$PLIartID])? $aSortPicklistItems[ $PLIstock][$PLIartID]['quantity'] + $item->getQuantity():$item->getQuantity()));
                    $aSortPicklistItems[ $PLIstock][$PLIartID] = $PLIarray;
                    $oOrderQuantity +=  $item->getQuantity();
                    }
                }
            $row = 0;
            foreach($aSortOrderItems as $key => $sitem){
                    $pdf->ItemsHeader($key,$cellHight);
                    $row++;
                    foreach($sitem as $item) {
                        $pdf->ItemsBody($item['product'],$item['item'],$cellHight);
                        $row++;

                    }
                    if($row >21 ) {
                        $row = 0;
                        $pdf->Ln(80);
                    }
                }

            // Fooder
            $pdf->OrderFooder($oOrder,$oOrderQuantity );

            }

        $pdf->AddPage();
        $pdf->PicklistHeader($oRequest);
        foreach($aSortPicklistItems as $key => $sitem){
            $pdf->ItemsPickHeader($key,$cellHight);

            foreach($sitem as $item) {
                $pdf->ItemsPickBody($item['quantity'],$item['product'],$item['item'],$cellHight);
                $row++;
            }
        }



        $timestamp = date("YmdHis");
        $pdf->Output("print/Packliste".$timestamp.".pdf",'F');


        return $this->render('BSOrderBundle:Order:print.html.twig', array(
            'urlPDF'=> "/print/Packliste".$timestamp.".pdf"
        ));


    }




    public function getItem($OrderItem){

        $ArtileID = explode("-",  $OrderItem->getSKU());

        $repository = $this->getDoctrine()->getRepository('BSDataBundle:Product');
        $item = $repository->findOneBy(array('article_id' => $ArtileID));
        if($item) return $item;
        else{
            $em = $this->getDoctrine()->getEntityManager();
            $oPlentySoapClient	=	new PlentySoapClient($this);
            $item = new Product();
            $PMItem = $oPlentySoapClient->doGetItemBase(array('ItemID'=>$ArtileID[0]));
            if(isset($PMItem->ItemID)) {
                $item->newPMSoapProduct($PMItem );
                }
            else{
                $item->newPMSoapOrderProduct($OrderItem);
                 }
            $em->persist($item);
            $em->flush();
            return $item;
        }
    }


}
