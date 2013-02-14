<?php

namespace Acme\BSCheckoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Acme\PlentyMarketsBundle\Controller\PlentySoapClient;


class CheckoutController extends Controller
{
    /**
     * @Route("/",name="BSCheckout_home")
     * @Template()
     */
    public function indexAction($cashbox_id = 1)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->getCurrentBasket($cashbox_id);

        $form = $this->createFormBuilder()
            ->add('prefix', 'choice', array(
            'choices'   => array('Herr' => 'Herr', 'Frau' => 'Frau', 'Firma' => 'Firma'),
            'label'=>'Anrede'
        ))
            ->add('firstname', 'text',array('label'=>'Vorname'))
            ->add('lastname','text',array('label'=>'Nachname'))
            ->add('company', 'text',array('label'=>'Firma'))
            ->add('street', 'text',array('label'=>'Strasse'))
            ->add('city', 'text',array('label'=>'Stadt'))
            ->add('country', 'country',array('label'=>'Land',
                'preferred_choices' => array('DE','AT','CH'),))
            ->add('email', 'email',array('label'=>'Email'))
            ->getForm();



        /**
         * Es wird ein neuer Soap-Client angelegt.
         */
        $oPlentySoapClient	=	new PlentySoapClient($this,$this->getDoctrine() );


        $items = $oPlentySoapClient->doGetItemsByOptions(array('CategoriePath'=>"91",
        ));
        $STD_article = array();
        foreach($items as $item){
            $STD_article[$item->ItemNo] = $item->Texts->Name;

        }




        return $this->render('BSCheckoutBundle:Default:index.html.twig', array('basket' => $currentBasket,'form' => $form->createView(),'StdArticle'=> $STD_article));
    }


    /**
     * @Route("/add",name="BSCheckout_add")

     * @Template()
     */
    public function addAction( $cashbox_id = 1)
    {

        $code = $this->getRequest()->request->get('code');
        $price = floatval(str_replace(',','.',$this->getRequest()->request->get('price')));

        $em = $this->getDoctrine()->getEntityManager();

        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->getCurrentBasket($cashbox_id);
        $em->getRepository('BSCheckoutBundle:checkoutItem')->addItem($currentBasket,$code,$price);


       // return $this->render('BSCheckoutBundle:Default:index.html.twig', array('basket' => $currentBasket));


        return $this->createJSON($currentBasket);
    }






    private function createJSON($currentBasket){

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
     * @Route("/clear",name="BSCheckout_clear")
     * @Method({ "POST"})
     * @Template()
     */
    public function clearAction($cashbox_id = 1)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->clearCurrentBasket($cashbox_id);

       //  return $this->render('BSCheckoutBundle:Default:index.html.twig', array('basket' => $currentBasket));
        return $this->redirect($this->generateUrl('BSCheckout_home'));
    }

    /**
     * @Route("/finish",name="BSCheckout_finish")
     * @Method({ "POST" })
     * @Template()
     */
    public function finishAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $payment_id = $this->getRequest()->request->get('payment_id');
        $cashbox_id = $this->getRequest()->request->get('cashbox_id');

        if(!$cashbox_id && !$payment_id){
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


        return $this->redirect($this->generateUrl('BSCheckout_home'));

    }

    /**
     * @Route("/order",name="BSCheckout_order")
     * @Method({ "POST"})
     * @Template()
     */
    public function orderAction($cashbox_id = 1)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->clearCurrentBasket($cashbox_id);



        return $this->render('BSCheckoutBundle:Default:index.html.twig', array('basket' => $currentBasket));

    }


    /**
     * @Route("/itemaction",name="BSCheckout_item")
     * @Method({ "POST"})
     * @Template()
     */
    public function itemAction($cashbox_id = 1)
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
     * @Route("/receipt",name="BSCheckout_receipt")

     * @Template()
     */
    public function receiptAction( $cashbox_id = 1)
    {

        $em = $this->getDoctrine()->getEntityManager();

        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->getCurrentBasket($cashbox_id);


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



}
