<?php
/**
 * Created by JetBrains PhpStorm.
 * User: florianengler
 * Date: 10.02.12
 * Time: 16:52
 * To change this template use File | Settings | File Templates.
 */


namespace Acme\BSOrderBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\BSDataBundle\Entity\Product;
use Acme\BSDataBundle\Form\ProductType;
//use Acme\BSOrderBundle\Controller\OrderPDF;
use Acme\PlentyMarketsBundle\Controller\PlentySoapClient;

//define('EURO',chr(128));

/**
 * Order controller.
 *
 * @Route("/order")
 */

class OrderController extends Controller
{


    /**
     * @Route("/orders/print")
     * @Template(orders.html.twig)
     */
    public function indexAction()
    {
        /**
        $oPlentySoapClient	=	new PlentySoapClient($this);

        $orders = $oPlentySoapClient->doGetOrdersWithState(array('OrderStatus'=> null));
        $time = $oPlentySoapClient->doGetServerTime();

        $OrderArray = $orders->item;


        return $this->render('BSOrderBundle:Order:orders.html.twig', array('time' => $time,'orders'=>$OrderArray));
         *
         */
    }

    /**
     * @Route("/orders/state")
     * @Template()
     */

    /**
     * @Route("/orders/print")
     * @Template()
     */

    public function testAction(Request $request){

        return "<html><body> das ist ein TEST</body></html>";
    }

 /*   public function printAction(Request $request)
    {
        $pdf = new OrderPDF();

       $oPlentySoapClient	=	new PlentySoapClient($this);
        $aOrders = array();
        //$orders = $oPlentySoapClient->doGetOrdersWithState(array('OrderStatus'=> doubleval(6)));
        //$OrderArray = $orders->item;
        $oRequest = array();

        foreach($request->request->all() as $Order) {
            $oRequest[] = strval($Order);
        }


        $pdf = new OrderPDF();



        $data = array();

        foreach($oRequest as $rOrder){

            $pdf->AddPage();
            $oOrder = $oPlentySoapClient->doGetOrder($rOrder);
            $aOrders[] = $oOrder;
            $oCustomer = $oPlentySoapClient->doGetCustomer(array('CustomerID'=> $oOrder->OrderHead->CustomerID));
            $aWarehouseList = $oPlentySoapClient->doGetWarehouseList();
            $pdf->OrderHeader($oOrder->OrderHead->OrderID,$oCustomer->FirstName,$oCustomer->Surname);

            $cellHight = 8;
            $pdf->SetFont('Arial','B',10);
            $pdf->SetTextColor(0,0,0);
            $pdf->Cell(20,$cellHight,'Lagerort','B',0,'L');
            $pdf->Cell(30,$cellHight,'ArtikelID','B',0,'L');
            $pdf->Cell(15,$cellHight,'Menge','B',0,'L');
            $pdf->Cell(60,$cellHight,'Artikelname','B',0,'L');
            $pdf->Cell(10,$cellHight,'Update','B',1,'L');
            $pdf->Cell(10,$cellHight,'Lager','B',1,'L');
            $pdf->Cell(10,$cellHight,'Preis','B',1,'L');
            $pdf->SetFont('Arial','',10);
            foreach($oOrder->OrderItems->item as $item){
                $SKU = explode("-", $item->SKU);
                $oItem = $oPlentySoapClient->doGetItemBase(array('ItemNo'=> $SKU[0] ));
                $oItemStock = $oPlentySoapClient->doGetItemsStock(array('SKU' => $item->SKU ));


                $pdf->Cell(20,$cellHight,$item->WarehouseID,'B',0,'L');
                $pdf->Cell(30,$cellHight,$item->SKU,'B',0,'L');
                $pdf->Cell(15,$cellHight,$item->Quantity,'B',0,'L');
                $pdf->Cell(60,$cellHight,$item->ItemText,'B',0,'L');
                $pdf->Cell(60,$cellHight,$oItem->LastUpdate,'B',0,'L');
                $pdf->Cell(60,$cellHight,$oItemStock->Quantity,'B',0,'L');

                $pdf->Cell(10,$cellHight,sprintf("%01.2f " , $item->Price).EURO,'B',1,'L');

            }

        }








        return $pdf->Output();


    }

*/


}
