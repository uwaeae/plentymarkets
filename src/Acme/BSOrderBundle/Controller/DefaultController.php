<?php

namespace Acme\BSOrderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Acme\BSDataBundle\Entity\Product;
use Acme\BSDataBundle\Entity\Orders;

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

        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT a FROM BSDataBundle:Orders a";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1),
            10
        );
       // return array('pagination' => $pagination);

        return $this->render('BSOrderBundle:Order:orderslocal.html.twig', array(
            'orders'=>$pagination      ));
    }

    public function stateAction($state )
    {
        /**
         * Es wird ein neuer Soap-Client angelegt.
         */
        $oPlentySoapClient	=	new PlentySoapClient($this,$this->getDoctrine() );

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


    public function setStateAction(Request $request,$state){
        $oRequest = array();

        foreach($request->request->all() as $Order) {
            $oRequest[] = strval($Order);
        }

        $oPlentySoapClient	=	new PlentySoapClient($this,$this->getDoctrine());

        foreach($oRequest as $rOrder){
            $oPlentySoapClient->doSetOrderStatus($rOrder,$state);

        }


    }

    public function printAction(Request $request)
    {

        $Packlistname = "Packliste-".date("YmdHis");
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
        $aSortPicklistHeader = array();

        foreach($oRequest as $rOrder){

            $pdf->AddPage();

            //Bestellungsdaten aus localer Datenbank holen
            $repository = $this->getDoctrine()->getRepository('BSDataBundle:Orders');
            $oOrder =  $repository->findOneBy(array('OrderID' => $rOrder));
            $oOrder->setPicklist($Packlistname);
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($oOrder);
            $em->flush();
            $aSortPicklistHeader[] = $oOrder;
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
                    $PLIartID=  $product->getArticleNo();


                    if(isset($aSortPicklistItems[ $PLIstock][$PLIartID])){
                        $PLIorder = $aSortPicklistItems[ $PLIstock][$PLIartID]['orders'];
                        $PLIorder[] = array('OrderID'=>$rOrder,'Name'=> utf8_decode($oOrder->getLastname()),'Quantity'=>$item->getQuantity());
                        $PLIarray = array(  'item'=>$item,
                            'product'=>$product,
                            'orders'=> $PLIorder,
                            'quantity'=>( $aSortPicklistItems[ $PLIstock][$PLIartID]['quantity'] + $item->getQuantity())
                            );
                    }else{
                        $PLIorder = array();
                        $PLIorder[] = array('OrderID'=>$rOrder,'Name'=> utf8_decode($oOrder->getLastname()),'Quantity'=>$item->getQuantity());
                        $PLIarray = array(  'item'=>$item,
                            'product'=>$product,
                            'orders'=> $PLIorder,
                            'quantity'=>( $item->getQuantity())
                        );
                    }



                    $aSortPicklistItems[$PLIstock][$PLIartID] = $PLIarray;



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

        $pdf->AddPage('');
        $pdf->PicklistHeader($aSortPicklistHeader);
        $pdf->AddPage('L');
        ksort($aSortPicklistItems);

        foreach($aSortPicklistItems as $key => $sitem){



            $pdf->ItemsPickHeader($key,$cellHight);

            ksort($sitem);

            foreach($sitem as $item) {
                $pdf->ItemsPickBody($item,$cellHight);
                $row++;
            }
        }




        $pdf->Output("print/".$Packlistname.".pdf",'F');


        return $this->render('BSOrderBundle:Order:print.html.twig', array(
            'urlPDF'=> "/print/".$Packlistname.".pdf",
            "orders"=> $oRequest
        ));


    }




    public function getItem($OrderItem){

        $ArtileID = explode("-",  $OrderItem->getSKU());

        $repository = $this->getDoctrine()->getRepository('BSDataBundle:Product');
        $item = $repository->findOneBy(array('article_id' => $ArtileID));
        if($item) return $item;
        else{
            $em = $this->getDoctrine()->getEntityManager();
            $oPlentySoapClient	=	new PlentySoapClient($this,$this->getDoctrine());
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
