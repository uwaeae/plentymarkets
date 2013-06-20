<?php

namespace Acme\BSOrderBundle\Command;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Acme\BSDataBundle\Entity\Product;
use Acme\BSDataBundle\Entity\Orders;
use Acme\PlentyMarketsBundle\Controller\PlentySoapClient;



class OrderCommand extends ContainerAwareCommand
{
protected function configure()
{
    $this
        ->setName('BSOrder:import')
        ->setDescription('Import Invoice Data from Plenty Markets')
        ->addArgument('daysback', InputArgument::OPTIONAL, 'how many Days?')
       // ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;
}

protected function execute(InputInterface $input, OutputInterface $output)
    {

        $oPlentySoapClient	=	new PlentySoapClient($this,$this->getContainer()->get('doctrine'));

        //$oPlentySoapClient->doGetMethodOfPayments();



       // $state =  $input->getArgument('state');
        $back=  $input->getArgument('daysback');
        //$output->writeln(' *** Aufträge ***');
        //$this->syncOrders($oPlentySoapClient,7,$output,$back);
        //$output->writeln(' *** Stronierung ***');
        //$this->syncOrders($oPlentySoapClient,8,$output,$back);
        //$output->writeln(' *** Gutschriften ***');
        //$this->syncOrders($oPlentySoapClient,11,$output,$back);
        $output->writeln(' *** Artikel ***');
        $outputstring = sprintf("%10s | %6s | %10s | %30s","DATUM","ID",'CODE',"NAME");
       // $output->writeln($outputstring);
        $products =  $oPlentySoapClient->doGetItemsBase(date('U',mktime(0, 0, 0, date("m")  , date("d") - $back , date("Y"))),$output );
        $output->writeln('Artikel Syncronisiert '.count($products));

    }

private function syncOrders(PlentySoapClient $oPlentySoapClient,$state,$output, $back ){

    $orders = $oPlentySoapClient->doGetOrdersWithState( $state ,date('U',mktime(0, 0, 0, date("m")  , date("d") - $back , date("Y")))  );

    $output->writeln(' Syncronisiert '.count($orders).' Datum '.date('d.m.y',mktime(0, 0, 0, date("m")  , date("d") - $back , date("Y")) ));
    foreach($orders as $order){
            $output->writeln($order['head']->getOrderId()
                .'   '.($order['head']->getTotalBrutto()+$order['head']->getShippingCosts())
                .'   '.$order['head']->getPaymentMethods()->getName()
                .'   '.$order['head']->getInvoiceNumber());
        }

    }


}

