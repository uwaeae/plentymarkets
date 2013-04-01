<?php

namespace Acme\BSCheckoutBundle\Controller;

use Acme\BSCheckoutBundle\Entity\checkoutItem;
use Acme\PlentyMarketsBundle\Controller\PMDeliveryAddress;
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

        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->getCurrentBasket($cashbox_id);
        $cashbox = $em->getRepository('BSCheckoutBundle:cashbox')->find($cashbox_id);
        $quickbuttons = $em->getRepository('BSCheckoutBundle:quickbutton')->getQuickbuttons($cashbox_id);


        /**
         * Es wird ein neuer Soap-Client angelegt.
         */
       /*
         $oPlentySoapClient	=	new PlentySoapClient($this,$this->getDoctrine() );


        $items = $oPlentySoapClient->doGetItemsByOptions(array('CategoriePath'=>"91",
        ));
        $STD_article = array();
        foreach($items as $item){
            $STD_article[$item->ItemNo] = $item->Texts->Name;

        }
       */



        return $this->render('BSCheckoutBundle:Default:index.html.twig', array(
                'basket' => $currentBasket,
                'cashbox' => $cashbox,
                'quickbuttons' => $quickbuttons,
                'form' => $this->buildOrderForm()->createView(),
            //    'StdArticle'=> $STD_article
            )
        );
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
        return $this->render('BSCheckoutBundle:Default:history.html.twig', array('Baskets' => $Baskets,'cashbox_id'=>$cashbox_id));
    }



    /**
     * @Route("/{cashbox_id}/checkout/add",name="BSCheckout_add")

     * @Template()
     */
    public function addAction($cashbox_id)
    {

        $code = $this->getRequest()->request->get('code');
        $price = $this->getRequest()->request->get('price');

        $price = floatval(str_replace(',','.',$price));

        $em = $this->getDoctrine()->getEntityManager();

        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->getCurrentBasket($cashbox_id);

        if(is_float($price) && $price > 0 ){
            $em->getRepository('BSCheckoutBundle:checkoutItem')->addItem($currentBasket,$code,$price);
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
     * @Route("/{cashbox_id}/checkout/clear",name="BSCheckout_clear")
     * @Method({ "POST"})
     * @Template()
     */
    public function clearAction($cashbox_id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->clearCurrentBasket($cashbox_id);

       //  return $this->render('BSCheckoutBundle:Default:index.html.twig', array('basket' => $currentBasket));
        return $this->redirect($this->generateUrl('BSCheckout_home',array('cashbox_id'=>$cashbox_id)));
    }

    /**
     * @Route("/{cashbox_id}/checkout/finish",name="BSCheckout_finish")
     * @Method({ "POST" })
     * @Template()
     */
    public function finishAction($cashbox_id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $payment_id = $this->getRequest()->request->get('payment_id');
        //$cashbox_id = $this->getRequest()->request->get('cashbox_id');

        if(!$cashbox_id || !$payment_id){
            throw $this->createNotFoundException("Keine Parameter übergeben");

        }

        $cb = $em->getRepository('BSCheckoutBundle:checkout')->getCurrentBasket($cashbox_id);
            $sum = 0.0;
        foreach($cb->getCheckoutItems() as $product){

            $sum += $product->getQuantity() *  $product->getPrice();

        }


        $cb->setPayment($payment_id);
        $cb->setBuydate(new \DateTime());
        $cb->setFinish(true);
        $cb->setSummary($sum);
        $em->persist($cb);
            $em->flush();


        return $this->redirect($this->generateUrl('BSCheckout_home',array('cashbox_id'=>$cashbox_id)));

    }




    /**
     * @Route("/{cashbox_id}/checkout/order",name="BSCheckout_order")
     * @Method({ "POST"})
     * @Template()
     */
    public function orderAction($cashbox_id )
    {
        $em = $this->getDoctrine()->getEntityManager();

        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->getCurrentBasket($cashbox_id);

        $oPlentySoapClient	=	new PlentySoapClient($this,$this->getDoctrine());

        $request = $this->getRequest();

        $form = $request->request->get('form');

        $OrderHead = new PMOrderHead();
        $OrderHead->OrderStatus = 12;
        $OrderHead->PaymentStatus = 1;
        $OrderHead->ExternalOrderID = ' ';
        $OrderHead->OrderID = null;
        $OrderHead->SalesAgentID = 13;
        if(!empty($form['customerno'])){
            $OrderHead->CustomerID = $form['customerno'];
        }
        //else todo Create New Customer


        $OrderDeliveryAddress = new PMDeliveryAddress();
        $OrderDeliveryAddress->CustomerID = $form['customerno'];
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
            //$OrderItem->ItemNo =  $item->getArticleCode();
            $OrderItem->Price = $item->getPrice();
            $OrderItem->Quantity = $item->getQuantity();


            $OrderItems[] = $OrderItem;
        }


        $pm_orders = new RequestAddOrders();
        $pm_orders->Orders[]['OrderHead'] = $OrderHead;
        $pm_orders->Orders[]['OrderDeliveryAddress'] = $OrderDeliveryAddress;
        $pm_orders->Orders[]['OrderItems'] = $OrderItems;


        //$pmorder->Orders->item->OrderItems->item = array();
        $response = $oPlentySoapClient->doAddOrders($pm_orders);


        $message  = explode(";",$response->Message);
        $OrderID = $message[1];
        $RequestAddOrderItems = new RequestAddOrdersItems();
        foreach($currentBasket->getCheckoutItems() as $item){
            //$item = new checkoutItem();
            $OrderItem = new PMOrderItem();
            $OrderItem->OrderID = $OrderID;
            $OrderItem->ItemID = $item->getArticleId();
            $OrderItem->ItemNo =  $item->getArticleCode();
            $OrderItem->Price = $item->getPrice();
            $OrderItem->Quantity = $item->getQuantity();
            $RequestAddOrderItems->OrderItems[] = $OrderItem;
        }
        $response = $oPlentySoapClient->doAddOrdersItems($RequestAddOrderItems);

        // TESTEN
        return $this->render('BSCheckoutBundle:Default:order.html.twig', array(
                'response' => $response,
                'cashbox_id' => $cashbox_id
            )
        );

    }



    /**
     * @Route("/{cashbox_id}/checkout/itemaction",name="BSCheckout_item")
     * @Method({ "POST"})
     * @Template()
     */
    public function itemAction($cashbox_id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $action = $this->getRequest()->request->get('action');
        $id = $this->getRequest()->request->get('id');

        $item = $em->getRepository('BSCheckoutBundle:checkoutItem')->find($id);
        if($item){
            switch($action){
                case 'plus':
                    $item->setQuantity($item->getQuantity()+ 1);
                    $em->persist($item);
                    break;
                case 'minus':
                    $quantity = $item->getQuantity() - 1;
                    if($quantity == 0){
                        $em->remove($item);
                    }else{
                        $item->setQuantity($quantity);
                        $em->persist($item);
                    }

                    break;
                case 'delete':
                    $em->remove($item);
                    break;



            }
            $em->flush();


        }

        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->getCurrentBasket($cashbox_id);

        return $this->createJSON($currentBasket);

    }

    /**
     * @Route("/{cashbox_id}/checkout/receipt/{id}",name="BSCheckout_receipt")

     * @Template()
     */
    public function receiptAction( $cashbox_id,$id )
    {

        $em = $this->getDoctrine()->getEntityManager();

        //$currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->getCurrentBasket($cashbox_id);
        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->find($id);


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
                $summary['mwst7'] = $mwst;
            }elseif( $item->getVAT() == 19){
                $mwst  = round($price * 0.19,2);
                $summary['mwst19'] += $mwst;
            }
            $summary['netto'] += $price - $mwst;


        }

        return $this->render('BSCheckoutBundle:Default:receipt.html.twig', array('basket' => $currentBasket, 'summary'=> $summary));

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
            ->add('prefix', 'choice', array(
                'choices'   => array('Herr' => 'Herr', 'Frau' => 'Frau', 'Firma' => 'Firma'),
                'label'=>'Anrede'
            ))
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
