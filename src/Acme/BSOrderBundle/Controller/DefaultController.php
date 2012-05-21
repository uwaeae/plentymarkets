<?php

namespace Acme\BSOrderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
            $this->get('request')->query->get('page',1),
            25
        );
       // return array('pagination' => $pagination);

        return $this->render('BSOrderBundle:Order:orderslocal.html.twig', array(
            'orders'=>$pagination      ));
    }

    public function accountingAction(){

        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'o')
            ->add('from', 'BSDataBundle:Orders o')
            ->add('where',$qb->expr()->andX(
                $qb->expr()->eq('o.OrderType','order'),
                $qb->expr()->isNull('o.exportDate'),
                $qb->expr()->orX(
                    $qb->expr()->gte('o.OrderStatus','7'),
                    $qb->expr()->gte('o.OrderStatus','11'))

                ));

        $pagination = $this->get('knp_paginator')->paginate(
            $qb->getQuery(),
            $this->get('request')->attributes->get('page', 1),
            50
        );


        // return array('pagination' => $pagination);

        return $this->render('BSOrderBundle:Order:accounting.html.twig', array(
            'orders'=>$pagination      ));





    }

    public function exportAction(){

        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'o')
            ->add('from', 'BSDataBundle:Orders o')
            ->add('where',$qb->expr()->andX(
            $qb->expr()->eq('o.OrderType','order'),
            $qb->expr()->isNull('o.exportDate'),
            $qb->expr()->gte('o.OrderStatus','7')
            ));
        $orders = $qb->getQuery()->getResult();

        $export[] = array(  'Belegnummer'   => 'Belegnummer',
                            'Buchungstext'  => 'Buchungstext' ,
                            'Buchungsbetrag'=>  'Buchungsbetrag',
                            'MwSt'          =>  'MwSt',
                            'Sollkonto'     => 'Sollkonto',
                            'Habenkonto'    =>  'Habenkonto' ,
                            'Belegdatum'    =>  'Belegdatum',
                            'Währung'       => utf8_decode('Währung'),
                            'Kostenstelle'  => 'Kostenstelle',
                            'Re_Nr'         => 'Re_Nr'   );

// Rechnungen Exportieren
        foreach($orders as $order ){


            $OrderItemsVAT7 = $this->getOrderItemSumVAT($order->getOrderID(),7);
            $OrderItemsVAT19 = $this->getOrderItemSumVAT($order->getOrderID(),19);
            // Buchungssatz für 19% MwSt
            if($OrderItemsVAT19 > 0){

                $OrderItemsVAT19 += $order->getShippingCosts();


                $export[] = array(  'Belegnummer'   => $order->getOrderID(),
                                    'Buchungstext'  => utf8_decode($order->getOrderID().' '.$order->getLastname() ) ,
                                    'Buchungsbetrag'=> $OrderItemsVAT19,
                                    'MwSt'          =>  '19',
                                    'Sollkonto'     => $order->getPaymentMethods()->getDebitor(),
                                    'Habenkonto'    => 4401,
                                    'Belegdatum'    => date("d.m.y",$order->getDoneTimestamp()),
                                    'Währung'       => 'EUR',
                                    'Kostenstelle'  => '2000',
                                    'Re_Nr'         => $order->getInvoiceNumber());




                }
            else{
                $OrderItemsVAT7 =  $OrderItemsVAT7  + $order->getShippingCosts();
            }

            if($OrderItemsVAT7 > 0){


                $export[] = array(  'Belegnummer'   => $order->getOrderID(),
                    'Buchungstext'  => utf8_decode($order->getOrderID().' '.$order->getLastname() ),
                    'Buchungsbetrag'=> $OrderItemsVAT7 ,
                    'MwSt'          =>  '7',
                    'Sollkonto'     => $order->getPaymentMethods()->getDebitor(),
                    'Habenkonto'    => 4301,
                    'Belegdatum'    => date("d.m.y",$order->getDoneTimestamp()),
                    'Währung'       => 'EUR',
                    'Kostenstelle'  => '2000',
                    'Re_Nr'         => $order->getInvoiceNumber());
            }
            // Buchungssatz für die Zahlung
            if($order->getPaidTimestamp()){
                $export[] = array(  'Belegnummer'   => $order->getOrderID(),
                    'Buchungstext'  => utf8_decode($order->getOrderID().' '.$order->getLastname() ) ,
                    'Buchungsbetrag'=> $order->getTotalBrutto() + $order->getShippingCosts(),
                    'Sollkonto'     => $order->getPaymentMethods()->getBankAccount(),
                    'Habenkonto'    => $order->getPaymentMethods()->getDebitor(),
                    'Belegdatum'    => date("d.m.y",$order->getPaidTimestamp()),
                    'Währung'       => 'EUR',
                    'Kostenstelle'  => '2000',
                    'Re_Nr'         => $order->getInvoiceNumber());
                }
             $order->setExportDate(date('U'));
             $em->persist($order);

            }

     // Gutschriften Exportieren

        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'o')
            ->add('from', 'BSDataBundle:Orders o')
            ->add('where',$qb->expr()->andX(
            $qb->expr()->isNull('o.exportDate'),

            $qb->expr()->gte('o.OrderStatus','11')
        ));
        $orders = $qb->getQuery()->getResult();



        foreach($orders as $order ){


            $OrderItemsVAT7 = $this->getOrderItemSumVAT($order->getOrderID(),7);
            $OrderItemsVAT19 = $this->getOrderItemSumVAT($order->getOrderID(),19);
            if($OrderItemsVAT19 > 0){

                $OrderItemsVAT19 += $order->getShippingCosts();


                $export[] = array(  'Belegnummer'   => $order->getOrderID(),
                    'Buchungstext'  => utf8_decode($order->getOrderID().' '.$order->getLastname() ) ,
                    'Buchungsbetrag'=> $OrderItemsVAT19 * -1,
                    'MwSt'          =>  '19',
                    'Sollkonto'     => $order->getPaymentMethods()->getDebitor(),
                    'Habenkonto'    => 4401,
                    'Belegdatum'    => date("d.m.y",$order->getDoneTimestamp()),
                    'Währung'       => 'EUR',
                    'Kostenstelle'  => '2000',
                    'Re_Nr'         => $order->getInvoiceNumber());




            }
            else{
                $OrderItemsVAT7 =  $OrderItemsVAT7  + $order->getShippingCosts();
            }

            if($OrderItemsVAT7 > 0){


                $export[] = array(  'Belegnummer'   => $order->getOrderID(),
                    'Buchungstext'  => utf8_decode($order->getOrderID().' '.$order->getLastname() ),
                    'Buchungsbetrag'=> $OrderItemsVAT7 * -1 ,
                    'MwSt'          =>  '7',
                    'Sollkonto'     => $order->getPaymentMethods()->getDebitor(),
                    'Habenkonto'    => 4301,
                    'Belegdatum'    => date("d.m.y",$order->getDoneTimestamp()),
                    'Währung'       => 'EUR',
                    'Kostenstelle'  => '2000',
                    'Re_Nr'         => $order->getInvoiceNumber());
            }


        }




        $em->flush();
        $dataname = 'export_'.date('ymd');
        $fp = fopen('export/'.$dataname, 'w');
        $output = ' ';
        foreach ($export as $d) {
            fputcsv($fp, $d,";");
            $output .=  $d['Belegnummer'].';'.
                        $d['Buchungstext'].';'.
                        $d['Buchungsbetrag'].';'.
                        $d['Sollkonto'].';'.
                        $d['Habenkonto'].';'.
                        $d['Belegdatum'].';'.
                        $d['Währung'].';'.
                        $d['Kostenstelle'].';'.
                        $d['Re_Nr'].';'."\r\n";

        }




        $response = new Response();
        $response->setStatusCode(200);
        $response->headers->set('Content-Type', 'application/txt');
        $response->headers->set('Content-Disposition',
                sprintf('attachment;filename="%s.txt"', $dataname ));
        $response->setContent($output);

        $response->send();

        //return $this->render('BSOrderBundle:Order:export.html.twig' );


    }

    private function getOrderItemSumVAT($orderid,$vat){
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'sum(o.Price * o.Quantity)')
            ->add('from', 'BSDataBundle:OrdersItem o')
            ->add('where',$qb->expr()->andX(
            $qb->expr()->eq('o.OrderID',$orderid),
            $qb->expr()->eq('o.VAT',$vat)));

        return $qb->getQuery()->getSingleScalarResult();
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



    public function openAction($state )
    {
        /**
         * Es wird ein neuer Soap-Client angelegt.
         */
        $oPlentySoapClient	=	new PlentySoapClient($this,$this->getDoctrine() );


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


        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', $qb->expr()->max('o.Picklist'))
            ->add('from', 'BSDataBundle:Orders o');

        $Packlistname = $qb->getQuery()->getSingleScalarResult();
        if($Packlistname == 0 or $Packlistname == null  ) $Packlistname = date("Ym") * 1000 + 1;
        else $Packlistname ++;

        //= "Packliste-".date("Ymd-Hi");
        $aOrders = array();
        //$orders = $oPlentySoapClient->doGetOrdersWithState(array('OrderStatus'=> doubleval(5)));
        //$OrderArray = $orders->item;
        $oRequest = array();

        foreach($request->request->all() as $Order) {
            $oRequest[] = strval($Order);
        }


        $pdf = new OrderPDF($Packlistname);


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

            // Bestell Prositionen aus der localen datenbank holen
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

                    $PLIartID=  $product->getArticleNo();
                    $aSortOrderItems[$PLIstock][$PLIartID] = array('product'=>$product,'item'=>$item );

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

            // PACKLISTE
            ksort($aSortOrderItems);
            foreach($aSortOrderItems as $key => $sitem){
                    $pdf->ItemsHeader($key,$cellHight);
                    $row += 2;
                    ksort($sitem);
                    foreach($sitem as $item) {
                        $pdf->ItemsBody($item['product'],$item['item'],$cellHight);
                        $row++;

                    }
                    if($row >25 ) {
                        $row = 0;
                        $pdf->Ln(80);
                    }
                }

            // Fooder
            $pdf->OrderFooder($oOrder,$oOrderQuantity );

            }

        //PICKLISTE

        $pdf->PicklistHeader($aSortPicklistHeader,$Packlistname);

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
