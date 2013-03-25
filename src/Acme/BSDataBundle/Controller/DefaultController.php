<?php

namespace Acme\BSDataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\PlentyMarketsBundle\Controller\PlentySoapClient;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('BSDataBundle:Default:index.html.twig');
    }
    public function dataAction()
    {
        return $this->render('BSDataBundle:Default:data.html.twig');
    }


    public function customerSearchAction($string )
    {

        $oPlentySoapClient	=	new PlentySoapClient($this,$this->getDoctrine());


        $customers = $oPlentySoapClient->doGetCustomers(array('searchstring'=>$string));

        $response = new Response( json_encode($customers));
        $response->headers->set('Content-Type', 'application/json');

        return $response;


    }




}
