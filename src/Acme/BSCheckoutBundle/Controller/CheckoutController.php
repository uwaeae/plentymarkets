<?php

namespace Acme\BSCheckoutBundle\Controller;

use Acme\BSCheckoutBundle\Entity\checkout;
use Acme\BSCheckoutBundle\Entity\checkoutItem;
use Acme\PlentyMarketsBundle\Controller\PMDeliveryAddress;
use Acme\PlentyMarketsBundle\Controller\PMOrder;
use Acme\PlentyMarketsBundle\Controller\PMOrderHead;
use Acme\PlentyMarketsBundle\Controller\PMOrderItem;
use Acme\PlentyMarketsBundle\Controller\RequestAddOrders;
use Acme\PlentyMarketsBundle\Controller\RequestAddOrdersItems;
use Acme\PlentyMarketsBundle\PlentyMarketsBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Acme\PlentyMarketsBundle\Controller\PlentySoapClient;
/**
 * cashbox controller.
 *
 * @Route("/cashbox")
 */

class CheckoutController extends Controller
{
    /**
     * @Route("/{cashbox_id}/checkout",name="BSCheckout_home")
     * @Template()
     */
    public function indexAction($cashbox_id = 1)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $currentBaskets = $em->getRepository('BSCheckoutBundle:checkout')->getCurrentBaskets($cashbox_id);
        $cashbox = $em->getRepository('BSCheckoutBundle:cashbox')->find($cashbox_id);
        $quickbuttons = $em->getRepository('BSCheckoutBundle:quickbutton')->getQuickbuttons($cashbox_id);

         $checkout = $this->getRequest()->query->get('checkout');
        $basket = null;
        if($checkout){

             $basket = $em->getRepository('BSCheckoutBundle:checkout')->find($checkout);
         }

         if(!$basket){
             $basket = $currentBaskets[0];
         }

        $last = $em->getRepository('BSCheckoutBundle:checkout')->getLastBasket($cashbox,$basket);

        return $this->render('BSCheckoutBundle:Default:index.html.twig', array(
                'basket' => $basket,
                'last'=> $last,
                'baskets' => $currentBaskets,
                'cashbox' => $cashbox,
                'quickbuttons' => $quickbuttons,
                'form' => $this->buildOrderForm()->createView(),
            //    'StdArticle'=> $STD_article
            )
        );
    }

    /**
     * @Route("/{cashbox_id}/checkout/new",name="BSCheckout_new")
     * @Template()
     */
    public function newAction($cashbox_id = 1)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $cashbox = $em->getRepository('BSCheckoutBundle:cashbox')->find($cashbox_id);
        $basket = new Checkout();
       // $basket->setBuydate(new \DateTime());
        $basket->setCashbox($cashbox);
        $basket->setFinish(false);
        $basket->setClosed(false);
        $basket->setSummary(0);
        $basket->setPayment(0);
        $em->persist($basket);
        $em->flush();

        return $this->redirect($this->generateUrl('BSCheckout_home',array('cashbox_id'=>$cashbox_id,'checkout'=>$basket->getID())));
    }




    /**
     * @Route("/{cashbox_id}/checkout/history/{date}",name="BSCheckout_history")

     * @Template()
     */
    public function historyAction($cashbox_id,$date)
    {
        if($date == null ) $date  = date("d-m-Y");
        $em = $this->getDoctrine()->getEntityManager();

        $Baskets = $em->getRepository('BSCheckoutBundle:checkout')->getHistory($cashbox_id,$date);
        return $this->render('BSCheckoutBundle:Default:history.html.twig', array(
            'Baskets' => $Baskets,
            'cashbox_id'=>$cashbox_id,
            'date' => $date
        ));
    }





    /**
     * @Route("/{cashbox_id}/checkout/{checkout}/add",name="BSCheckout_add")

     * @Template()
     */
    public function addAction($cashbox_id,$checkout)
    {

        $code = $this->getRequest()->request->get('code');
        $price = $this->getRequest()->request->get('price');
        $quantity = $this->getRequest()->request->get('quantity');
        $name = trim($this->getRequest()->request->get('name'));
        $price = floatval(str_replace(',','.',$price));

        $em = $this->getDoctrine()->getEntityManager();

       // $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->getCurrentBasket($cashbox_id);
        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->find($checkout);



        if(is_float($price) && $price > 0 ){
            $em->getRepository('BSCheckoutBundle:checkoutItem')->addItem($currentBasket,$code,$price,$quantity,$name);
        }else{
            $em->getRepository('BSCheckoutBundle:checkoutItem')->addItem($currentBasket,$code,0,$quantity,$name);
        }



       // return $this->render('BSCheckoutBundle:Default:index.html.twig', array('basket' => $currentBasket));


        return $this->createCurrentBasketJSON($currentBasket);
    }






    private function createCurrentBasketJSON($currentBasket){

        $result = array();
        $index = 1;
        foreach($currentBasket->getCheckoutItems() as $product){
            $item['id'] =  $product->getId();
            $item['code'] = $product->getArticleCode();
            $item['quantity'] = $product->getQuantity();
            $item['description'] = $product->getDescription();
            $item['VAT'] = $product->getVAT();
            $item['price'] = $product->getPrice();
            $item['pa'] = false;
            $item['sum'] = $product->getQuantity() *  $product->getPrice();
            $result[$index] = $item;
            $index ++;
        }

        $response = new Response( json_encode($result));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    /**
     * @Route("/{cashbox_id}/checkout/{checkout}/clear",name="BSCheckout_clear")
     * @Method({ "POST"})
     * @Template()
     */
    public function clearAction($cashbox_id,$checkout)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $checkout = $em->getRepository('BSCheckoutBundle:checkout')->clearBasket($cashbox_id,$checkout);
        //$checkout = $em->getRepository('BSCheckoutBundle:checkout')->find($checkout);

        $em->remove($checkout);
        $em->flush();
       //  return $this->render('BSCheckoutBundle:Default:index.html.twig', array('basket' => $currentBasket));
        return $this->redirect($this->generateUrl('BSCheckout_home',array('cashbox_id'=>$cashbox_id)));
    }

    /**
     * @Route("/{cashbox_id}/checkout/{checkout}/finish",name="BSCheckout_finish")
     * @Method({ "POST" })
     * @Template()
     */
    public function finishAction($cashbox_id,$checkout)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $payment_id = 0;
        $payment_id = $this->getRequest()->request->get('payment_id');
        //$cashbox_id = $this->getRequest()->request->get('cashbox_id');

        if(!$cashbox_id){
            throw $this->createNotFoundException("Keine Parameter übergeben");

        }

        $cb = $em->getRepository('BSCheckoutBundle:checkout')->find($checkout);
            $sum = 0.0;
        foreach($cb->getCheckoutItems() as $product){

            $sum += $product->getQuantity() *  $product->getPrice();

        }

        if($sum != 0){
            $cb->setPayment($payment_id);
            $cb->setBuydate(new \DateTime());
            $cb->setFinish(true);
            $cb->setSummary($sum);
            $em->persist($cb); 
            $em->flush();
        }



        return $this->redirect($this->generateUrl('BSCheckout_home',array('cashbox_id'=>$cashbox_id)));

    }




    /**
     * @Route("/{cashbox_id}/checkout/{checkout}/order",name="BSCheckout_order")
     * @Method({ "POST"})
     * @Template()
     */
    public function orderAction($cashbox_id ,$checkout)
    {
        $em = $this->getDoctrine()->getEntityManager();

        //$currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->getCurrentBasket($cashbox_id);
        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->find($checkout);

        $oPlentySoapClient	=	new PlentySoapClient($this,$this->getDoctrine());

        $request = $this->getRequest();

        $form = $request->request->get('form');

        $OrderHead = new PMOrderHead();
        $OrderHead->OrderStatus = 7.3;
        $OrderHead->PaymentStatus = 1;
        $OrderHead->MultishopID = 0;
        $OrderHead->ReferrerID = 9;
        //$OrderHead->PaidTimestamp = date('U');
        $OrderHead->MethodOfPaymentID = 2;
        $OrderHead->ResponsibleID = 13;
        $OrderHead->ExternalOrderID = 'kasse'.$checkout;
        $OrderHead->OrderID = null;
        $OrderHead->SalesAgentID = 13;
        $OrderHead->TotalBrutto = $currentBasket->getSummary();
        $OrderHead->ShippingMethodID = 4;
        $OrderHead->ShippingProfileID = 6;

        if(!empty($form['customerno'])){
            $customerNO =     $form['customerno'];
        }
        else{
            $customerNO =    $oPlentySoapClient->doAddCustomers($form);
        }
        $OrderHead->CustomerID = $customerNO;
        //todo Create New Customer


        $OrderDeliveryAddress = new PMDeliveryAddress();
        $OrderDeliveryAddress->CustomerID = $customerNO;
        $OrderDeliveryAddress->FirstName = $form['firstname'];
        $OrderDeliveryAddress->Surname = $form['lastname'];
        $OrderDeliveryAddress->Street = $form['street'];
        $OrderDeliveryAddress->HouseNumber = $form['HouseNo'];
        $OrderDeliveryAddress->City = $form['city'];
        $OrderDeliveryAddress->ZIP = $form['zip'];
        $OrderDeliveryAddress->CountryISO2 = $form['country'];
        $OrderDeliveryAddress->Email = $form['email'];


        $OrderItems = array();
        foreach($currentBasket->getCheckoutItems() as $item){

            $OrderItem = new PMOrderItem();
            $OrderItem->ItemID = $item->getArticleId();
            $OrderItem->SKU = $item->getArticleId();
            $OrderItem->ItemNo =  $item->getArticleCode();
            $OrderItem->Price = $item->getPrice();
            $OrderItem->Quantity = $item->getQuantity();
            $OrderItem->OrderID = null;
            $OrderItem->SalesOrderProperties = array();
            $OrderItems[] = $OrderItem;
        }



        $pm_order = new PMOrder();
        $pm_order->OrderHead = $OrderHead;
        $pm_order->OrderDeliveryAddress = $OrderDeliveryAddress;
        $pm_order->OrderItems = $OrderItems;


        $pm_orders = new RequestAddOrders();
        $pm_orders->Orders = array($pm_order);

        //$pmorder->Orders->item->OrderItems->item = array();

        $response = $oPlentySoapClient->doAddOrders($pm_orders);

        // Zurückgegebene Auftragsnummer extrahieren
        $message  = explode(";",$response[0]->Message);
        $OrderID = $message[1];

        sleep(5);
        $response = $oPlentySoapClient->doGetOrdersInvoiceDocumentURLs($OrderID);

        if(isset($response[0])){
            // URL aus dem Response holen und anzeigen lassen
            $PDFURL = $response[0]->InvoiceDocumentURL;
            // Warenkorb Löschen
            $checkout = $em->getRepository('BSCheckoutBundle:checkout')->clearBasket($cashbox_id,$checkout);
            $em->remove($checkout);
            $em->flush();

        }



        return $this->render('BSCheckoutBundle:Default:order.html.twig', array(
                'PDFURL' => $PDFURL,
                'cashbox_id' => $cashbox_id
            )
        );

    }



    /**
     * @Route("/{cashbox_id}/checkout/{checkout}/itemaction",name="BSCheckout_item")
     * @Method({ "POST"})
     * @Template()
     */
    public function itemAction($cashbox_id,$checkout)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $action = $this->getRequest()->request->get('action');
        $quantity = $this->getRequest()->request->get('quantity');
        $price = $this->getRequest()->request->get('price');
        $price = floatval(str_replace(',','.',$price));
        $id = $this->getRequest()->request->get('id');

        $item = $em->getRepository('BSCheckoutBundle:checkoutItem')->find($id);
        if($item){
            switch($action){
                case 'quantity':
                    $item->setQuantity($quantity);
                    $em->persist($item);
                    break;

                case 'price':
                    $item->setPrice($price);
                    $em->persist($item);
                    break;



                case 'delete':
                    $em->remove($item);
                    break;



            }
            $em->flush();


        }

        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->find($checkout);

        return $this->createCurrentBasketJSON($currentBasket);

    }

    /**
     * @Route("/{cashbox_id}/checkout/{id}/bontext",name="BSCheckout_bontext")

     * @Template()
     */
    public function bontextAction( $cashbox_id,$id )
    {
        $bontext = $this->getRequest()->request->get('bontext');
        $em = $this->getDoctrine()->getEntityManager();

        //$cashbox = $em->getRepository('BSCheckoutBundle:cashbox')->find($cashbox_id);
        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->find($id);

        $currentBasket->setBontext($bontext);
        $em->persist($currentBasket);
        $em->flush();
        return $this->redirect($this->generateUrl('BSCheckout_home',array('cashbox_id'=>$cashbox_id)));
    }

    /**
     * @Route("/{cashbox_id}/checkout/{id}/receipt",name="BSCheckout_receipt")

     * @Template()
     */
    public function receiptAction( $cashbox_id,$id )
    {
        $bontext = $this->getRequest()->request->get('bontext');

        $bontext = nl2br($bontext);

        $em = $this->getDoctrine()->getEntityManager();

        $cashbox = $em->getRepository('BSCheckoutBundle:cashbox')->find($cashbox_id);
        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->find($id);

        $currentBasket->setBontext($bontext);
        $em->persist($currentBasket);
        $em->flush();

        if( $bontext == '' ){
            $bontext = $currentBasket->getBontext();
        }

        $summary = array(
            'netto' => 0.0,
            'mwst19'=> 0.0,
            'mwst7' => 0.0,
            'sum'   => 0.0);

        foreach($currentBasket->getCheckoutitems() as $item){

            $price = $item->getPrice()* $item->getQuantity();
            $summary['sum'] += $price;
            $mwst = 0;
            if( $item->getVAT() == 7){
                $mwst  =  round($price * 0.07,2);
                $summary['mwst7'] += $mwst;
            }elseif( $item->getVAT() == 19){
                $mwst  = round($price * 0.19,2);
                $summary['mwst19'] += $mwst;
            }
            $summary['netto'] += $price - $mwst;


        }

        return $this->render('BSCheckoutBundle:Default:receipt.html.twig', array('basket' => $currentBasket,'bontext'=>$bontext, 'summary'=> $summary,'info'=> $cashbox->getBonafter()));

    }

    /**
     * @Route("/checkout/payoff",name="BSCheckout_payoff")
     * @Method({ "GET"})
     * @Template()
     */
    public function payoffAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        return $this->render('BSCheckoutBundle:Default:payoff.html.twig');
    }


    private function buildOrderForm(){
        return  $this->createFormBuilder()

            ->add('customerno', 'hidden',array('required'=>false))

            ->add('lastname','text',array('label'=>'Nachname'))
            ->add('firstname', 'text',array('label'=>'Vorname','required'=>false))
            ->add('company', 'text',array('label'=>'Firma','required'=>false))
            ->add('street', 'text',array('label'=>'Strasse','required'=>false))
            ->add('HouseNo', 'text',array('label'=>'Hausnummer','required'=>false))
            ->add('city', 'text',array('label'=>'Stadt','required'=>false))
            ->add('zip', 'text',array('label'=>'PLZ','required'=>false))
            ->add('country', 'country',array('label'=>'Land',
                'preferred_choices' => array('DE','AT','CH'),))
            ->add('email', 'email',array('label'=>'Email','required'=>false))
            ->getForm();


    }




}
