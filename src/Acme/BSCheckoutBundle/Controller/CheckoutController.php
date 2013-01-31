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
     * @Route("/add/{code}",name="BSCheckout_add")
     * @Template()
     */
    public function addAction($code , $cashbox_id = 1)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->getCurrentBasket($cashbox_id);
        $em->getRepository('BSCheckoutBundle:checkoutItem')->addItem($currentBasket,$code);


       // return $this->render('BSCheckoutBundle:Default:index.html.twig', array('basket' => $currentBasket));
        $result = array();
        $index = 1;
        foreach($currentBasket->getCheckoutItems() as $product){
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



        return $this->render('BSCheckoutBundle:Default:index.html.twig', array('basket' => $currentBasket));

    }

    /**
     * @Route("/finish",name="BSCheckout_finish")
     * @Method({ "POST"})
     * @Template()
     */
    public function finishAction($cashbox_id = 1,$payment_id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $cb = $em->getRepository('BSCheckoutBundle:checkout')->clearCurrentBasket($cashbox_id);
            $sum = 0.0;
        foreach($cb->getCheckoutItems() as $product){

            $sum += $product->getQuantity() *  $product->getPrice();

        }

        $cb = new \Acme\BSCheckoutBundle\Entity\checkout();
        $cb->setPayment($payment_id);
        $cb->setBuydate(new \DateTime());
        $cb->setFinish(true);
        $cb->setSummary($sum);
        $em->persit($cb);
        $em->flush();


        return $this->render('BSCheckoutBundle:Default:index.html.twig', array('basket' => $currentBasket));

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




}
