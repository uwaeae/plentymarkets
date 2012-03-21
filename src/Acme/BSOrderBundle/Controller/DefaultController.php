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

    public function stateAction($state = 5)
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
        $orders = $oPlentySoapClient->doGetOrdersWithState( doubleval($state));
        // $time = $oPlentySoapClient->doGetServerTime();
        $OrderArray = array();
        if(!is_null($orders))  $OrderArray = $orders->item;




        return $this->render('BSOrderBundle:Order:orders.html.twig', array(
            'orders'=>$OrderArray
        ));
    }

    public function invoiceAction(Request $request){
        return "TEST";//$pdf->Output();
    }

    public function printAction(Request $request)
    {

        $oPlentySoapClient	=	new PlentySoapClient($this);
        $aOrders = array();
        //$orders = $oPlentySoapClient->doGetOrdersWithState(array('OrderStatus'=> doubleval(5)));
        //$OrderArray = $orders->item;
        $oRequest = array();

        foreach($request->request->all() as $Order) {
            $oRequest[] = strval($Order);
        }


        $pdf = new OrderPDF();



        $data = array();

        foreach($oRequest as $rOrder){
            $cellHight= 8;
            $pdf->AddPage();
            //Bestellung aus der SOAP Api abrufen
            //$oOrder = $oPlentySoapClient->doGetOrder($rOrder);
            //$aOrders[] = $oOrder;
            //$oCustomer = $oPlentySoapClient->doGetCustomer(array('CustomerID'=> $oOrder->OrderHead->CustomerID));
            //$aWarehouseList = $oPlentySoapClient->doGetWarehouseList();
            //Bestellungskopf erstellen
            $repository = $this->getDoctrine()->getRepository('BSDataBundle:Orders');
            $oOrder = $repository->findOneBy(array('OrderID' => $rOrder));

            $repository = $this->getDoctrine()->getRepository('BSDataBundle:OrdersInfo');
            $aOrderInfo = $repository->findBy(array('OrderID' => $rOrder));



            $pdf->OrderHeader($oOrder,$aOrderInfo);


            //$pdf->ItemsHeader($cellHight);
            $pdf->SetFont('Arial','',10);
            $repository = $this->getDoctrine()->getRepository('BSDataBundle:OrdersItem');
            $aOrderItem = $repository->findBy(array('OrderID' => $rOrder));
            $aSortOrderItems = array();
            $aSortPicklistItems = array();
            foreach($aOrderItem as $item){
                $SKU = explode("-", $item->getSKU());
                //$product = $oPlentySoapClient->getItem($SKU[0]);
                $repository = $this->getDoctrine()->getRepository('BSDataBundle:Product');
                $product = $repository->findOneBy(array('article_id' => $SKU[0]));
                if($product){
                    $aSortOrderItems[($product->getStockground()?$product->getStockground():'KeinLager')][] = array('product'=>$product,'item'=>$item );

                    $PLIstock = ($product->getStockground()?$product->getStockground():'KeinLager');
                    $PLIartID=  $product->getArticleID();

                    $PLIarray = array('name'=>utf8_decode($item->getItemText()),
                                      'quantity'=>(isset($aSortPicklistItems[ $PLIstock][$PLIartID])? $aSortPicklistItems[ $PLIstock][$PLIartID]['quantity'] + $item->getQuantity():$item->getQuantity()));
                    $aSortPicklistItems[ $PLIstock][$PLIartID] = $PLIarray;
                    //$oItem = $oPlentySoapClient->doGetItemBase(array('ItemNo'=> $SKU[0] ));
                    //$oItemStock = $oPlentySoapClient->doGetItemsStock(array('SKU' => $item->getSKU() ));}
                    }
                }
            foreach($aSortOrderItems as $key => $sitem){
                    $pdf->ItemsHeader($key,$cellHight);
                foreach($sitem as $item) {

                    $pdf->ItemsBody($item['product'],$item['item'],$cellHight);
                }




            }



            // Fooder

            $pdf->OrderFooder($oOrder);



        }
        $timestamp = date("YmdHis");
        $pdf->Output("print/Packliste".$timestamp.".pdf",'F');


        return $this->render('BSOrderBundle:Order:print.html.twig', array(
            'urlPDF'=> "/print/Packliste".$timestamp.".pdf"
        ));


    }


}
