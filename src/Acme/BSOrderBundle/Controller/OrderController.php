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
use Acme\BSOrderBundle\Form\BookingDataType;

use Acme\PlentyMarketsBundle\Controller\PlentySoapClient;

define('EURO',chr(128));

class OrderController extends Controller
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

        $paginate = $this->get('knp_paginator');
        $pagination = $paginate->paginate(
            $query,
            $this->get('request')->query->get('page',1),
            25
        );
       // return array('pagination' => $pagination);

        return $this->render('BSOrderBundle:Order:orderslocal.html.twig', array(
            'orders'=>$pagination      ));
    }


    public function checkFormAction(){



    }



    public function checkAction(Request $request){

        $form = $this->createFormBuilder()
            ->add('data', 'file',array('label'=>'Buchungsdaten'))
            ->getForm();


        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);



                $someNewFilename = "import_".date("YmdHi").".csv";

                $form['data']->getData()->move("import/", $someNewFilename);

                $aIN = array();
                $aOUT = array();


                if (($handle = fopen("import/".$someNewFilename, "r")) !== FALSE) {
                    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

                        $aIN[] = $data;
                    }
                    fclose($handle);

                }
                else{
                    return $this->redirect($this->generateUrl('check_failure'));
                }
                $em = $this->getDoctrine()->getEntityManager();
                $qb = $em->createQueryBuilder();
                $qb->add('select', 'o')
                    ->add('from', 'BSDataBundle:Orders o');

                $qb  ->add('where',$qb->expr()->andX(
                    $qb->expr()->like('o.OrderType','Order'),
                    $qb->expr()->eq('o.OrderStatus','7')

                ));

                $orders = $qb->getQuery()->getResult();
                $output_fail_orders = array();
                $output_fail_booking = array();

                $Accounting = array("50000","50001","50002","50003","1801","1800","1802","1803");

                foreach($orders as $order){

                    //$key = array_search(strval($order->getOrderID()),$aIN);
                    $konto = array();
                    foreach($aIN as $row){
                        if(!empty($row[0])){
                            $r =  strstr($row[0],strval($order->getOrderID()) );
                            //foreach($Accounting as $k)    $konto[] = array_search($k,$row);
                            if($r){
                                //  $output->writeln( "     ".implode($row, ';')."\r\n");
                                $result[$order->getOrderID()] = $row;
                           }
                        }
                    }

                    if(!isset($result[$order->getOrderID()])) {
                        // $output->writeln( $order->getOrderID() );
                        $output_fail_orders[] = $order->getOrderID();
                    }
                    else{
                        $delta = 0;
                        foreach($result as  $r){

                            $r[2] += $delta;
                        }
                        if($delta != 0) {
                            //$output->writeln( "Buchungsfehler "."\r\n");
                            $BookingRows = array();
                            foreach($result as  $r){
                                $BookingRows[] = $r; //$output->writeln( "     ".implode($r, ';')."\r\n");
                            }
                             $output_fail_booking[$order->getOrderID()] = $BookingRows;
                        }

                    }

            }
        return $this->render('BSOrderBundle:Order:check.html.twig', array( 'orders' => $output_fail_orders, 'booking' => $output_fail_booking
            ));



        }



        return $this->render('BSOrderBundle:Order:checkForm.html.twig', array(
            'form'=>$form->createView()     ));


    }

    public function exportedAction(){

        // $oPlentySoapClient	=	new PlentySoapClient($this,$this->getDoctrine());
        // $back =  7;
        // $date = date('U',mktime(0, 0, 0, date("m")  , date("d") - $back , date("Y"))) ;
        // $oPlentySoapClient->doGetOrdersWithState( 7, $date  );
        // $oPlentySoapClient->doGetOrdersWithState( 11, $date  );



        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'o')
            ->add('from', 'BSDataBundle:Orders o')
            ->add('where',$qb->expr()->andX(
            $qb->expr()->neq('o.OrderType','?1'),
            $qb->expr()->eq('o.exportDate',$this->get('request')->attributes->get('date',1)),
            $qb->expr()->orX(
                $qb->expr()->eq('o.OrderStatus','7'),
                $qb->expr()->eq('o.OrderStatus','11')
            )

        ))
            ->setParameter(1, 'delivery');

        $pagination = $this->get('knp_paginator')->paginate(
            $qb->getQuery(),
            $this->get('request')->attributes->get('page', 1),
            50
        );


        // return array('pagination' => $pagination);

        return $this->render('BSOrderBundle:Order:exported.html.twig', array(
            'orders'=>$pagination      ));





    }




    public function accountingAction(){

       // $oPlentySoapClient	=	new PlentySoapClient($this,$this->getDoctrine());
       // $back =  7;
       // $date = date('U',mktime(0, 0, 0, date("m")  , date("d") - $back , date("Y"))) ;
       // $oPlentySoapClient->doGetOrdersWithState( 7, $date  );
       // $oPlentySoapClient->doGetOrdersWithState( 11, $date  );



        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'o')
            ->add('from', 'BSDataBundle:Orders o')
            ->add('where',$qb->expr()->andX(
                $qb->expr()->neq('o.OrderType','?1'),
                $qb->expr()->isNull('o.exportDate'),
                $qb->expr()->eq('o.PaidStatus', '?2'),
                $qb->expr()->orX(
                    $qb->expr()->eq('o.OrderStatus','7'),
                    $qb->expr()->eq('o.OrderStatus','11')
                   )

                ))
                ->setParameter(1, 'delivery') // keine liefer aufträge
                ->setParameter(2, 1); // als Bezahlt markiert

        $pagination = $this->get('knp_paginator')->paginate(
            $qb->getQuery(),
            $this->get('request')->attributes->get('page', 1),
            50
        );

        $qb = $em->createQueryBuilder();
        $qb->add('select', 'o.exportDate')
            ->add('from', 'BSDataBundle:Orders o')
            ->add('where',$qb->expr()->andX(
            //$qb->expr()->neq('o.OrderType','?1'),
            $qb->expr()->isNotNull('o.exportDate'),
            $qb->expr()->orX(
                $qb->expr()->eq('o.OrderStatus','7'),
                $qb->expr()->eq('o.OrderStatus','11')
            )

        ))
            //->setParameter(1, 'delivery');
            ->groupBy('o.exportDate');

        $lastExports =   $qb->getQuery()->getResult();




        // return array('pagination' => $pagination);

        return $this->render('BSOrderBundle:Order:accounting.html.twig', array(
            'orders'=>$pagination  ,
            'exported' => $lastExports ));





    }

    public function syncAction($state,Request $request )
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



            return "Syncronisiert  ".count($orders);


    }



    public function exportAction(Request $request){

        if ($request->getMethod() == 'POST') {

            $date =  $this->get('request')->attributes->get('date');

            $em = $this->getDoctrine()->getEntityManager();
            $qb = $em->createQueryBuilder();
            $qb->add('select', 'o')
                ->add('from', 'BSDataBundle:Orders o');
            if($date and $date != 1){
                $qb  ->add('where',$qb->expr()->andX(
                    //$qb->expr()->neq('o.OrderType','?1'),
                    $qb->expr()->eq('o.exportDate',$date),
                    $qb->expr()->eq('o.PaidStatus', '?2'),
                    $qb->expr()->eq('o.OrderStatus','7')

                ));
            }else
            {
                $qb  ->add('where',$qb->expr()->andX(
                    //$qb->expr()->neq('o.OrderType','?1'),
                    $qb->expr()->eq('o.PaidStatus', '?2'),
                    $qb->expr()->isNull('o.exportDate'),
                    $qb->expr()->eq('o.OrderStatus','7')
                ));
            }
         //   $qb ->setParameter(1, 'delivery');
            $qb ->setParameter(2, 1);


            $orders = $qb->getQuery()->getResult();
            $exportSumme = array();
            $export[] = array(  'Buchungstext'  => 'Buchungstext' ,
                                'Belegnummer'   => 'Belegnummer',
                                'Buchungsbetrag'=>  'Buchungsbetrag',
                                'MwSt'          =>  'MwSt',
                                'Sollkonto'     => 'Sollkonto',
                                'Habenkonto'    =>  'Habenkonto' ,
                                'Belegdatum'    =>  'Belegdatum',
                                'Währung'       => 'Währung',
                                'Kostenstelle'  => 'Kostenstelle',
                                'Re_Nr'         => 'Re_Nr'   );
            $exportDate = date('U');
    // Rechnungen Exportieren
            foreach($orders as $order ){


                    $OrderItemsVAT7 = $this->getOrderItemSumVAT($order->getOrderID(),7);
                    $OrderItemsVAT19 = $this->getOrderItemSumVAT($order->getOrderID(),19);


                    // Buchungssatz für 19% MwSt
                    if($OrderItemsVAT19 > 0 and  $order->getPaymentMethods()->getInvoice()){

                        $OrderItemsVAT19 += $order->getShippingCosts();

                        if(isset($exportSumme[$order->getPaymentMethods()->getDebitor()]))   $exportSumme[$order->getPaymentMethods()->getDebitor()] += $OrderItemsVAT19;
                        else  $exportSumme[$order->getPaymentMethods()->getDebitor()] = $OrderItemsVAT19;


                        if($order->getPaymentMethods()->getInvoice())  $export[] = array(
                            'Buchungstext'  => 'A '.$order->getOrderID().' '.$order->getLastname()  ,
                            'Belegnummer'   => $order->getOrderID(),
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

                    if($OrderItemsVAT7 > 0 and  $order->getPaymentMethods()->getInvoice()){
                        if(isset($exportSumme[$order->getPaymentMethods()->getDebitor()]))  $exportSumme[$order->getPaymentMethods()->getDebitor()] += $OrderItemsVAT7;
                        else $exportSumme[$order->getPaymentMethods()->getDebitor()] = $OrderItemsVAT7;

                        $export[] = array(
                            'Buchungstext'  => 'A '.$order->getOrderID().' '.$order->getLastname() ,
                            'Belegnummer'   => $order->getOrderID(),
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
                //if($order->getPaidTimestamp() AND $order->getPaymentMethods()->getID()!= 12 AND $order->getPaymentMethods()->getID()!= 9 ){
                if($order->getPaymentMethods()->getPayment()){
                    if(isset($exportSumme[$order->getPaymentMethods()->getBankAccount()])) $exportSumme[$order->getPaymentMethods()->getBankAccount()] += $order->getTotalBrutto() + $order->getShippingCosts();
                    else $exportSumme[$order->getPaymentMethods()->getBankAccount()] = $order->getTotalBrutto() + $order->getShippingCosts();
                    $export[] = array(
                        'Buchungstext'  => 'Z '.$order->getOrderID().' '.$order->getLastname() ,
                        'Belegnummer'   => $order->getOrderID(),
                        'Buchungsbetrag'=> $order->getTotalBrutto() + $order->getShippingCosts(),
                        'MwSt'          =>  '',
                        'Sollkonto'     => $order->getPaymentMethods()->getBankAccount(),
                        'Habenkonto'    => $order->getPaymentMethods()->getDebitor(),
                        'Belegdatum'    => date("d.m.y",$order->getPaidTimestamp()),
                        'Währung'       => 'EUR',
                        'Kostenstelle'  => '2000',
                        'Re_Nr'         => $order->getInvoiceNumber());
                    //  }
                }

                 $order->setExportDate($exportDate);
                 $em->persist($order);

                }

         // Gutschriften Exportieren

            $em = $this->getDoctrine()->getEntityManager();
            $qb = $em->createQueryBuilder();
            $qb->add('select', 'o')
                ->add('from', 'BSDataBundle:Orders o');
                //->add('where',$qb->expr()->andX(
                //$qb->expr()->isNull('o.exportDate'),
                //$qb->expr()->eq('o.OrderStatus','11')
                //));
            if($date > 1){
                $qb  ->add('where',$qb->expr()->andX(
                    $qb->expr()->eq('o.exportDate',$date),
                    $qb->expr()->eq('o.OrderStatus','11')
                ));
            }else
            {
                $qb  ->add('where',$qb->expr()->andX(
                    $qb->expr()->isNull('o.exportDate'),
                    $qb->expr()->eq('o.OrderStatus','11')
                ));
            }


            $orders = $qb->getQuery()->getResult();



            foreach($orders as $order ){


                $OrderItemsVAT7 = $this->getOrderItemSumVAT($order->getOrderID(),7);
                $OrderItemsVAT19 = $this->getOrderItemSumVAT($order->getOrderID(),19);
                if($OrderItemsVAT19 > 0){

                    $OrderItemsVAT19 += $order->getShippingCosts();
                    if( isset($exportSumme[$order->getPaymentMethods()->getDebitor()])) $exportSumme[$order->getPaymentMethods()->getDebitor()] += $OrderItemsVAT19 * -1;
                    else $exportSumme[$order->getPaymentMethods()->getDebitor()] = $OrderItemsVAT19 * -1;
                    $export[] = array(
                        'Buchungstext'  => 'G '.$order->getOrderID().' '.$order->getLastname()  ,
                        'Belegnummer'   => $order->getOrderID(),
                        'Buchungsbetrag'=> $OrderItemsVAT19 * -1,
                        'MwSt'          =>  '19',
                        'Sollkonto'     => $order->getPaymentMethods()->getDebitor(),
                        'Habenkonto'    => 4401,
                        'Belegdatum'    => date("d.m.y",$order->getLastUpdate()),
                        'Währung'       => 'EUR',
                        'Kostenstelle'  => '2000',
                        'Re_Nr'         => $order->getInvoiceNumber());




                }
                else{
                    $OrderItemsVAT7 =  $OrderItemsVAT7  + $order->getShippingCosts();
                }

                if($OrderItemsVAT7 > 0){

                    if(isset($exportSumme[$order->getPaymentMethods()->getDebitor()])) $exportSumme[$order->getPaymentMethods()->getDebitor()] += $OrderItemsVAT7 * -1;
                    else $exportSumme[$order->getPaymentMethods()->getDebitor()] = $OrderItemsVAT7 * -1;
                    $export[] = array(
                        'Buchungstext'  => 'G '.$order->getOrderID().' '.$order->getLastname() ,
                        'Belegnummer'   => $order->getOrderID(),
                        'Buchungsbetrag'=> $OrderItemsVAT7 * -1 ,
                        'MwSt'          =>  '7',
                        'Sollkonto'     => $order->getPaymentMethods()->getDebitor(),
                        'Habenkonto'    => 4301,
                        'Belegdatum'    => date("d.m.y",$order->getLastUpdate()),
                        'Währung'       => 'EUR',
                        'Kostenstelle'  => '2000',
                        'Re_Nr'         => $order->getInvoiceNumber());
                }

                $order->setExportDate($exportDate);
                $em->persist($order);


            }
            $dataname = 'export_'.date('ymd',$exportDate);

            $pdf = new exportPDF($dataname,8);

            ksort($export);
            ksort($exportSumme);
            $exportSummeGesamt = 0;
            foreach($exportSumme as $s){
                $exportSummeGesamt += $s;
            }

            //$pdf->exportHeader(8);

            $fp = fopen('export/broot/'.$dataname.'.txt', 'w');
            $output = ' ';


            foreach ($export as $d) {
                $d['Buchungstext'] =  utf8_decode($d['Buchungstext']);
                fputs($fp, implode($d, ';')."\r\n");
            }
            foreach ($export as $d) {
                $pdf->Body($d,8);
            }

              /*  $output .=  $d['Belegnummer'].';'.
                            $d['Buchungstext'].';'.
                            $d['Buchungsbetrag'].';'.
                            $d['MwSt'].';'.
                            $d['Sollkonto'].';'.
                            $d['Habenkonto'].';'.
                            $d['Belegdatum'].';'.
                            $d['Währung'].';'.
                            $d['Kostenstelle'].';'.
                            $d['Re_Nr'].';'."\r\n";
              */


            $pdf->exportFooder($exportSumme,8);



    //        $response = new Response();
    //        $response->setStatusCode(200);
    //        $response->headers->set('Content-Type', 'application/txt');
    //        $response->headers->set('Content-Disposition',
    //                sprintf('attachment;filename="%s.txt"', $dataname ));
    //        $response->setContent($output);

            //$response->send();
    //         return $response;



            $pdf->Output("export/broot/".$dataname.".pdf",'F');

            if($date == 1) $em->flush();
            unset($export[0]);

            return $this->render('BSOrderBundle:Order:export.html.twig' ,array('urlPDF'=> "/export/broot/".$dataname.".pdf",
            'export'=> $export,
            'summe'=>$exportSumme,
            'summeGesamt'=> $exportSummeGesamt));
        }else
        return $this->redirect('accounting');


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
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'o.Picklist')
            ->add('from', 'BSDataBundle:Orders o')
            ->add('where',$qb->expr()->isNotNull('o.Picklist'))
            ->groupBy('o.Picklist');


        return $this->render('BSOrderBundle:Order:orders.html.twig', array(
            'orders'=>$orders , 'state'=> $state ,'pick' =>  $qb->getQuery()->getResult()     ));
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


    if ($request->getMethod() == 'POST') {

        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', $qb->expr()->max('o.Picklist'))
            ->add('from', 'BSDataBundle:Orders o');

        $PickListName = $qb->getQuery()->getSingleScalarResult();
        if($PickListName == 0 or $PickListName == null  ) $PickListName = date("Ym") * 1000 + 1;
        else $PickListName ++;

        //= "Packliste-".date("Ymd-Hi");
        $aOrders = array();
        //$orders = $oPlentySoapClient->doGetOrdersWithState(array('OrderStatus'=> doubleval(5)));
        //$OrderArray = $orders->item;
        $oRequest = array();

        foreach($request->request->all() as $Order) {
            $oRequest[] = strval($Order);
        }


        $pdf = new OrderPDF($PickListName);


        $cellHight= 6;
        $aSortPicklistItems = array();
        $aSortPicklistHeader = array();

        foreach($oRequest as $rOrder){

            $pdf->AddPage();
           // $pdf->AliasNbPages();

            //Bestellungsdaten aus localer Datenbank holen
            $repository = $this->getDoctrine()->getRepository('BSDataBundle:Orders');
            $oOrder =  $repository->findOneBy(array('OrderID' => $rOrder));
            $oOrder->setPicklist($PickListName);
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
            $aCareList = array();

            $oOrderQuantity = 0;
            //Bestellprositonen zusammen stellen und nach Lagerort Sortieren
            foreach($aOrderItem as $item){

                $p = $this->getItem( $item);
                $items = $p->getBundleitems();
                if(count($items) > 0){
                    $productList = $items;
                }else{
                    $productList = array($p);
                }

                foreach($productList as $product){
                    if(strlen($product->getLabelText()) > 10) $aCareList[$product->getArticleNo()] =  $product;
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

            if(count($aCareList)> 0){
                    ksort($aCareList);
                    $pdf->AddPage();
                    $pdf->CareListHeader( $oOrder);
                    foreach($aCareList as $item){

                        $pdf->CareListBody($item,$oOrder);
                    }
                }
            }


        //PICKLISTE

        $pdf->PicklistHeader($aSortPicklistHeader,$PickListName);

        ksort($aSortPicklistItems);

        foreach($aSortPicklistItems as $key => $sitem){



            $pdf->ItemsPickHeader($key,$cellHight);

            ksort($sitem);

            foreach($sitem as $item) {
                $pdf->ItemsPickBody($item,$cellHight);
                $row++;
            }
        }




        $pdf->Output("print/".$PickListName.".pdf",'F');


        return $this->render('BSOrderBundle:Order:print.html.twig', array(
            'urlPDF'=> "/print/".$PickListName.".pdf",
            "orders"=> $oRequest
        ));
        }
        else
        {
            $PickListName = $this->get('request')->query->get('picklist');

            return $this->render('BSOrderBundle:Order:print.html.twig', array(
                'urlPDF'=> "/print/".$PickListName.".pdf",
                 "orders"=> array()
            ));

        }

    }




    public function getItem($OrderItem){
        $oPlentySoapClient	=	new PlentySoapClient($this,$this->getDoctrine());
        $em = $this->getDoctrine()->getEntityManager();
        $ArtileID = explode("-",  $OrderItem->getSKU());
        $repository = $this->getDoctrine()->getRepository('BSDataBundle:Product');
        $product = $repository->findOneBy(array('article_id' => $ArtileID));
        if(!$product) {
            $product = new Product();
            $PMItem = $oPlentySoapClient->doGetItemBase(array('ItemID'=>$ArtileID[0]));
            if(isset($PMItem->ItemID)) {
                $product->PMSoapProduct($PMItem );
            }
            else{
                $product->newPMSoapOrderProduct($OrderItem);
            }
            $em->persist($product);
            $em->flush();
        }

        return $product;

    }





}
