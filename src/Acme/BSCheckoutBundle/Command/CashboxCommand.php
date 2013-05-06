<?php

namespace Acme\BSCheckoutBundle\Command;

use Acme\BSCheckoutBundle\Entity\checkoutItem;
use Acme\PlentyMarketsBundle\Controller\PMOrder;
use Acme\PlentyMarketsBundle\Controller\PMOrderHead;
use Acme\PlentyMarketsBundle\Controller\PMOrderItem;
use Acme\PlentyMarketsBundle\Controller\RequestAddOrders;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Acme\BSDataBundle\Entity\Product;
use Acme\BSDataBundle\Entity\Orders;
use Acme\PlentyMarketsBundle\Controller\PlentySoapClient;



class CashboxCommand extends ContainerAwareCommand
{
protected function configure()
{
    $this
        ->setName('BSCheckout:finish')
        ->setDescription('Uploads CheckoutData to Plentymarkets ')
    ;
        //->addArgument('daysback', InputArgument::OPTIONAL, 'how many Days?');
       // ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')

}

protected function execute(InputInterface $input, OutputInterface $output)
    {

        $output->writeln(' *** Kassenabschluss '.date('d.m.Y'));
        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        $Baskets = $em->getRepository('BSCheckoutBundle:checkout')->getHistory(1,'now');

        $summary= array();
        $article = array();
        $zahlungsart = array();
        foreach($Baskets as $basket){

            $payment = $basket->getPayment();
            $basket->setExported(date('U'));
            $em->persist($basket);
            foreach($basket->getCheckoutItems() as $item){
                //$item = new checkoutItem();
                $zahlungsart[$payment][] = $item;
                if(!isset($summary[$payment])) $summary[$payment] = 0;
                $summary[$payment] += $item->getPrice() * $item->getQuantity();

            }

        }



        $oPlentySoapClient	=	new PlentySoapClient($this,$this->getContainer()->get('doctrine'));
        $pm_orders = new RequestAddOrders();
        $pm_orders->Orders = array();

        foreach($zahlungsart as $key => $article){
            $OrderHead = new PMOrderHead();
            $OrderHead->OrderStatus = 7.3;
            $OrderHead->PaymentStatus = 1;
            $OrderHead->MultishopID = 0;
            $OrderHead->ReferrerID = 9;
            $OrderHead->PaidTimestamp = date('U');
            $OrderHead->MethodOfPaymentID = 11;
            $OrderHead->ResponsibleID = 13;
            $OrderHead->ExternalOrderID = 'KA_'.$key.date('_d_m_Y');
            $OrderHead->OrderID = null;
            $OrderHead->SalesAgentID = 13;
            $OrderHead->TotalBrutto = $summary[$key];
            $OrderHead->ShippingMethodID = 4;
            $OrderHead->ShippingProfileID = 6;
            $OrderHead->CustomerID = -1;




            $OrderItems = array();
            foreach($article as $item){

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
            $pm_order->OrderDeliveryAddress = null;
            $pm_order->OrderItems = $OrderItems;

            $pm_orders->Orders[] = $pm_order;




        }

        $response = $oPlentySoapClient->doAddOrders($pm_orders);
        $em->flush();
        $output->writeln('Aufträge angelegt');
        foreach($response as $m){
            //$message  = explode(";",$m->Message);
            $output->writeln($m->Message);
        }



    }


}

