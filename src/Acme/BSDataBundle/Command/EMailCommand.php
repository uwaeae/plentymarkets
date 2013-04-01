<?php

namespace Acme\BSDataBundle\Command;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Acme\BSDataBundle\Entity\Product;
use Acme\BSDataBundle\Entity\Orders;
use Acme\PlentyMarketsBundle\Controller\PlentySoapClient;



class EMailCommand extends ContainerAwareCommand
{
protected function configure()
{
    $this
        ->setName('BSData:email')
        ->setDescription('Checks Accouning Data')
        ->addArgument('file', InputArgument::REQUIRED, 'EMail')
    ;
        //->addArgument('daysback', InputArgument::OPTIONAL, 'how many Days?');
       // ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')

}

protected function execute(InputInterface $input, OutputInterface $output)
    {

        //$oPlentySoapClient	=	new PlentySoapClient($this,$this->getContainer()->get('doctrine'));
        $aIN = array();


        if (($handle = fopen($input->getArgument('file'), "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

                $aIN[] = $data;
            }
            fclose($handle);

        }
        else{
            $output->writeln( "Ein fehler ist Aufgetreten");
        }

        $message = \Swift_Message::newInstance();
        $message->setSubject('Sammelpack')
            ->setFrom('noreply@blumenschule.de')
            ->setTo('florian.engler@gmx.de')
            ->setBody("DAS IST EIN TEST")
        ;
        $this->get('mailer')->send($message);




    }


}

