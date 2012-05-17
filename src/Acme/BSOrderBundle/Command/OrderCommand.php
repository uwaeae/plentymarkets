<?php

namespace Acme\BSOrderBundle\Command;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Acme\BSDataBundle\Entity\Orders;
use Acme\PlentyMarketsBundle\Controller\PlentySoapClient;



class OrderCommand extends ContainerAwareCommand
{
protected function configure()
{
    $this
        ->setName('BSOrder:import')
        ->setDescription('Import Invoice Data from Plenty Markets')
        ->addArgument('state', InputArgument::OPTIONAL, 'Order with State?')
       // ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;
}

protected function execute(InputInterface $input, OutputInterface $output)
    {

        $oPlentySoapClient	=	new PlentySoapClient($this,$this->getContainer()->get('doctrine'));

        //$oPlentySoapClient->doGetMethodOfPayments();



        $id =  $input->getArgument('state');

        $orders = $oPlentySoapClient->doGetOrdersWithState( ( $id ? $id : 7 ) );


        $output->writeln('Syncronisiert '.count($orders));
       /*
        foreach($orders as $order){

            $output->writeln($order['head']->getOrderId().' '.$order['head']->getTotalBrutto().' '.$order['head']->getPaymentMethods()->getId().' '.$order['head']->getInvoiceNumber());

        }
       */
      /*
        $name = $input->getArgument('name');
        if ($name) {
        $text = 'Hello '.$name;
        } else {
        $text = 'Hello';
        }

        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }
*/

    }
}

