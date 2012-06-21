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



class CheckCommand extends ContainerAwareCommand
{
protected function configure()
{
    $this
        ->setName('BSOrder:check')
        ->setDescription('Checks Accouning Data')
        ->addArgument('file', InputArgument::REQUIRED, 'Accounting data')
        ->addArgument('daysback', InputArgument::OPTIONAL, 'how many Days?');
       // ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')

}

protected function execute(InputInterface $input, OutputInterface $output)
    {

        //$oPlentySoapClient	=	new PlentySoapClient($this,$this->getContainer()->get('doctrine'));
        $aIN = array();
        $aOUT = array();
        //$oPlentySoapClient->doGetMethodOfPayments();

        if (($handle = fopen($input->getArgument('file'), "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

                $aIN[] = $data;
            }
            fclose($handle);

        }
        else{
            $output->writeln( "Ein fehler ist Aufgetreten");
        }
        $em = $this->getContainer()->get('doctrine')->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'o')
            ->add('from', 'BSDataBundle:Orders o');

        $qb  ->add('where',$qb->expr()->andX(
            $qb->expr()->not(
                $qb->expr()->like('o.OrderType','?1')
                ),
            $qb->expr()->orX(
                $qb->expr()->eq('o.OrderStatus','7'),
                $qb->expr()->eq('o.OrderStatus','11'))
        ))->setParameter('1', 'delivery');

        $orders = $qb->getQuery()->getResult();
        $ExportFail = 0;
        $BookingFail = 0;
        $Accounting = array("50000","50001","50002","50003","1801","1800","1802","1803");

        foreach($orders as $order){

            //$output->writeln( "Check ".$order->getOrderID() );
            //$key = array_search(strval($order->getOrderID()),$aIN);
            $konto = array();
            $delta = 0;
            foreach($aIN as $row){
                if(!empty($row[0])){
                $r =  strstr($row[0],strval($order->getOrderID()) );
                //foreach($Accounting as $k)    $konto[] = array_search($k,$row);

                if($r){
                    //$output->writeln( "*     ".implode($row, ';')."\r\n");
                    $result[$order->getOrderID()][] = $row;


                    if($row[2] >= 50000)
                        $delta += $row[1] ;

                     if($row[3] >= 50000)
                         $delta -= $row[1] ;

                    }




                    }
                }

            if($delta != 0) {
                $output->writeln( "\n".$order->getOrderID()." Buchung noch nicht aufgelöst (".$delta.")");

                foreach($result[$order->getOrderID()] as $row){
                    $output->writeln( "      ".implode($row, "\t"));
                }
                $BookingFail++;

            }


            if(!isset($result[$order->getOrderID()])) {
                $output->writeln( "\n".$order->getOrderID()." Keine Daten gefunden" );
                $order->setExportDate(null);
                $em->persist($order);

                $ExportFail ++;
            }





            }





       $em->flush();
        $output->writeln( " Ergebniss  ");
        $output->writeln( " Export : ".$ExportFail );
        $output->writeln( " Buchung: ".$BookingFail );


    }


}

