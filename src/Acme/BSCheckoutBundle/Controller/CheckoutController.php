<?php

namespace Acme\BSCheckoutBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

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

        return $this->render('BSCheckoutBundle:Default:index.html.twig', array('basket' => $currentBasket));
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
     * @Template()
     */
    public function clearAction($cashbox_id = 1)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $currentBasket = $em->getRepository('BSCheckoutBundle:checkout')->clearCurrentBasket($cashbox_id);



        return $this->render('BSCheckoutBundle:Default:index.html.twig', array('basket' => $currentBasket));

    }




}
