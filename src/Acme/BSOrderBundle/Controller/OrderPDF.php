<?php
/**
 * Created by JetBrains PhpStorm.
 * User: florianengler
 * Date: 27.01.12
 * Time: 17:36
 * To change this template use File | Settings | File Templates.
 */
namespace Acme\BSOrderBundle\Controller;
include 'lib/fpdf/fpdf.php';
use FPDF;
use Acme\BSDataBundle\Entity\Product;
use Acme\BSDataBundle\Entity\Orders;
use \Acme\BSDataBundle\Entity\OrdersInfo;


class OrderPDF extends \FPDF
{


    function OrderHeader(Orders $Order,array $aInfo){

        $this->SetFont('Arial','B',16);
        $this->SetTextColor(0,0,0);
        $this->Text(10,10,$Order->getOrderID());
        $this->Text(40,10,$Order->getFirstname());
        $this->Text(80,10,$Order->getLastname());
        $this->SetFont('Arial','',12);
        $this->Text(120,10,$Order->getZIP().' '.$Order->getCity());

        $this->Text(120,15,$Order->getTelephone());
        $i= 0 ;
        foreach($aInfo as $info){
            $this->Text(120,25+($i*5),utf8_decode($info->getText()));
            $i++;
        }
        $this->SetFont('Arial','B',12);
        $this->Text(100,25,"INFO:");
        $this->SetFont('Arial','',6);
        $this->Text(10,15,'Bestellnummer');
        $this->Text(40,15,'Vorname');
        $this->Text(80,15,'Nachname');
        $this->Ln(10+($i*10));
    }

    function ItemsHeader($Stock,$cellHight){
        $this->SetFont('Arial','B',12);
        $this->Cell(180,$cellHight,$Stock,'B',1,'L');
        $this->SetFont('Arial','',10);
        $this->SetTextColor(0,0,0);
        $this->Cell(30,$cellHight,'ArtikelID','B',0,'L');
        $this->Cell(15,$cellHight,'Menge','B',0,'C');
        $this->Cell(100,$cellHight,'Artikelname','B',0,'L');
        //$this->Cell(20,$cellHight,'Update','B',0,'L');
        //$this->Cell(20,$cellHight,'Lager','B',0,'L');
        $this->Cell(20,$cellHight,'Preis','B',1,'L');

    }

    function ItemsBody( $Product,\Acme\BSDataBundle\Entity\OrdersItem $item,$cellHight){
        $this->SetFont('Arial','',12);
        if($Product){
            //$this->Cell(20,$cellHight,($Product->getStockground()?$Product->getStockground():''),'B',0,'L'); // TODO: florian Lagerort finden
            $this->Cell(30,$cellHight,utf8_decode($Product->getArticleNo()),'',0,'L');
        } else{
            //$this->Cell(20,$cellHight,'','B',0,'L'); // TODO: florian Lagerort finden
            $this->Cell(30,$cellHight,'','',0,'L');
        }

        $this->Cell(15,$cellHight,$item->getQuantity(),'',0,'C');
        $this->Cell(100,$cellHight,utf8_decode($item->getItemText()),'',0,'L');
        //$this->Cell(20,$cellHight,(isset($oItem->LastUpdate)?$oItem->LastUpdate:''),'B',0,'L');
        $this->Cell(20,$cellHight,sprintf("%01.2f " , $item->getPrice()).EURO,'',1,'L');


    }

    function OrderFooder(\Acme\BSDataBundle\Entity\Orders $Order){
        $hdelta = 220;

        $this->line(0,  $hdelta,220 ,  $hdelta);
        $this->SetFont('Arial','B',32);
        $this->SetTextColor(0,0,0);
        $this->Text(10, $hdelta + 20,$Order->getOrderID());
        $this->Text(10, $hdelta + 40,$Order->getLastname());
        $this->SetFont('Arial','',24);
        $this->Text(80, $hdelta + 20,$Order->getZIP().' '.$Order->getCity());



    }



    /*

    function OrderItemList($Item){

        $this->SetFont('Arial','B',16);
        $this->SetTextColor(0,0,0);
       // $this->Cell(40,10,$Order,0,0,'R');
       // $this->Cell(40,10,$FirstName,0,0,'R');
      //  $this->Cell(40,10,$Surname,0,0,'R');
        $this->Ln(10);
        $this->SetFont('Arial','',8);
        $this->Cell(40,10,'Bestellnummer',0,0,'R');
        $this->Cell(40,10,'Vorname',0,0,'R');
        $this->Cell(40,10,'Nachname',0,0,'R');
        $this->Ln(10);
    }

    function makeOrder($data){



        foreach($data as $Order){

            //$this->AddPage();
            //$this->OrderHeader(30000,'TEST1','TEST1');
            //$this->OrderHeader($Order['id'],$Order['FirstName'],$Order['Surname']);
            //$this->OrderHeader($oOrder->OrderHead->OrderID,$oCustomer->FirstName,$oCustomer->Surname);
            $this->SetFont('Arial','B',16);
            $this->SetTextColor(0,0,0);
            $this->Cell(40,10,$Order['id'],1,0,'C');
            $this->Cell(40,10,$Order['FirstName'],1,0,'C');
            $this->Cell(40,10,$Order['Surname'],1,0,'C');
            $this->Ln(10);
            $this->SetFont('Arial','',8);
            $this->Cell(40,10,'Bestellnummer',1,0,'C');
            $this->Cell(40,10,'Vorname',1,0,'C');
            $this->Cell(40,10,'Nachname',1,0,'C');
            $this->Ln(10);
        }

    }
    function test($string,$times){
        $this->SetFont('Arial','B',16);
        //$this->SetTextColor(255,255,255);
        $this->Cell(40,10," test",1,0);
        $this->Ln(10);
        foreach($times as $time){
            $this->Cell(40,10,$string.' '.$time,1,0,'C');
            $this->Cell(40,10,$string.' '.$time,1,0,'C');
            $this->Ln(10);
        }


    }
    */

}
