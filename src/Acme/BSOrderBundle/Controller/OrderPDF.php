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

    private $position = 0;
    public $title;
    private $CarePage = 0;

    function __construct($title) {
        parent::__construct();
        $this->title = $title;
    }





    function Header()
    {
        $this->SetFont('Arial','',8);
        $this->Text(10,10,$this->title);
        // Line break
        $this->Ln(2);
    }


    function OrderHeader(Orders $Order,array $aInfo){

        $this->SetFont('Arial','B',24);
        $this->SetTextColor(0,0,0);
        $this->Text(160,13,$Order->getOrderID());
        // TODO: Florian hier Firmen Name einsetzen und Prüfen
        $this->Text(10,10,utf8_decode($Order->getCompany()));
        $this->Text(10,17,utf8_decode($Order->getLastname().','.$Order->getFirstname()));
        $this->Text(10,27,utf8_decode($Order->getZIP().' '.$Order->getCity()));
        $this->Text(10,35,$Order->getTelephone());

        $this->Ln(10);
        $this->Code39(160,16,$Order->getOrderID(),1,12);
        //$this->EAN13(160,18,$Order->getOrderID(),10);
        $this->Ln(13);
        $this->Cell(150,10,'',0,0,'r');
        $this->Cell(40,10,'Packetnummer',1,0,'r');
        $this->SetFont('Arial','B',16);
        $this->Text(10,40,"INFO:");
        $this->Ln(5);
        $i= 0 ;
        $this->SetFont('Arial','B',14);
        foreach($aInfo as $info){
            $this->MultiCell(150,5,utf8_decode($info->getText()));
            $i++;
        }
    }

    function CareListHeader(Orders $Order){
        $this->CarePage = 1;
        $this->SetFont('Arial','B',18);
        $this->SetTextColor(0,0,0);
        //$this->Text(140,10,'Bestellung');

        $this->Text(140,10,$Order->getOrderID());
        $this->Text(165,10,utf8_decode($Order->getLastname()));
        $this->SetFont('Arial','',14);
        $this->SetTextColor(0,0,0);
        $this->Text(140,20,'Seite');
        $this->Text(165,20,$this->CarePage);
        $this->Image('images/bslogo.jpg',50,10,85,25,'JPEG');
        $this->ln(20);
        $this->SetFont('Arial','B',24);
        $this->SetTextColor(0,0,0);
        $this->Cell(160,15,"Pflegehinweise & Verwendung" ,0,1,'C');
        $this->SetFont('Arial','B',16);
        $this->MultiCell(180,8,utf8_decode("Damit Ihre Pflanzen sicherer gedeihen, haben wir hier noch einige Hiweise für sie zum Aufheben zusammengestellt.") );
        $this->ln(5);
    }

    function CareListBody(Product $Product,Orders $Order){
        if($this->getY() > 200){
            $this->addPage();
             $this->CarePage++;
            $this->SetFont('Arial','B',18);
            $this->SetTextColor(0,0,0);
            $this->Text(140,10,$Order->getOrderID());
            $this->Text(165,10,utf8_decode($Order->getLastname()));
            $this->SetFont('Arial','',14);
            $this->SetTextColor(0,0,0);
            $this->Text(140,20,'Seite');
            $this->Text(165,20,$this->CarePage);
            $this->Image('images/bslogo.jpg',50,10,85,25,'JPEG');
            $this->ln(20);
            $this->SetFont('Arial','B',24);
            $this->SetTextColor(0,0,0);
            $this->Cell(160,15,"Pflegehinweise & Verwendung" ,0,1,'C');
            $this->SetFont('Arial','B',12);
            $this->Cell(160,8,"Fortsetzung" ,0,1,'C');
            $this->ln(5);

        }

        $this->SetFont('Arial','B',12);
        $this->SetTextColor(0,0,0);
        $this->Cell(180,12,utf8_decode($Product->getName()).' ('.$Product->getArticleNo().')' ,'T',1,'L');
        $this->SetFont('Arial','',12);
        $this->MultiCell(180,5,utf8_decode($Product->getLabelText()));
        $this->ln(5);
        //        $this->Cell(180,2,' ' ,'B',1,'L');

    }



    function ItemsHeader($Stock,$cellHight){
        $this->Ln(10);
        $this->SetFont('Arial','B',12);
        $this->Cell(180,$cellHight,utf8_decode($Stock),'B',1,'L');
        $this->SetFont('Arial','',10);

        /*$this->SetTextColor(0,0,0);
        $this->Cell(10,$cellHight,'Menge','B',0,'C');
        $this->Cell(25,$cellHight,'Code','B',0,'L');
        $this->Cell(130,$cellHight,'Artikel','B',0,'L');
        //$this->Cell(20,$cellHight,'Update','B',0,'L');
        //$this->Cell(20,$cellHight,'Lager','B',0,'L');
        $this->Cell(15,$cellHight,'Preis','B',0,'L');
        $this->Cell(5,$cellHight,'OK','B',1,'L');
        */


    }

    function ItemsBody(Product $Product,\Acme\BSDataBundle\Entity\OrdersItem $item,$cellHight){
        $this->SetFont('Arial','',12);
        $this->Cell(5,$cellHight,' ' ,1,0,'L');
        $this->SetFont('Arial','B',14);
        $this->Cell(10,$cellHight,$item->getQuantity(),'',0,'C');
        $this->SetFont('Arial','',12);
        if($Product){
            //$this->Cell(20,$cellHight,($Product->getStockground()?$Product->getStockground():''),'B',0,'L'); // TODO: florian Lagerort finden
            $this->Cell(25,$cellHight,utf8_decode($Product->getArticleNo()),'',0,'L');
        } else{
            //$this->Cell(20,$cellHight,'','B',0,'L'); // TODO: florian Lagerort finden
            $this->Cell(25,$cellHight,'','',0,'L');
        }
        $this->SetFont('Arial','',12);
        $this->Cell(130,$cellHight,substr(utf8_decode($Product->getName2()." ".$item->getItemText()),0,63),'',0,'L');
        //$this->Cell(20,$cellHight,(isset($oItem->LastUpdate)?$oItem->LastUpdate:''),'B',0,'L');
        $this->SetFont('Arial','',12);
        $this->Cell(20,$cellHight,sprintf("%01.2f " , $item->getPrice()).EURO,'',1,'L');


    }


    function PicklistHeader( $Order,$Picklistname){

        $this->AddPage('');
        $this->SetFont('Arial','B',24);
        $this->Cell(180,10,"Sammelpack ".$Picklistname,'B',1,'L');
        $this->SetFont('Arial','',10);
        $this->SetTextColor(0,0,0);
        $this->Cell(25,8,utf8_decode("für die Aufträge:"),'',1,'L');
       foreach($Order as $o){
           $this->SetFont('Arial','',10);
           $this->Cell(15,8,$o->getOrderID(),'B',0,'L');
           $this->Cell(50,8,utf8_decode($o->getLastname().' '.$o->getFirstname()),'B',0,'L');
           $this->Cell(50,8,utf8_decode($o->getZIP().' '.$o->getCity()),'B',0,'L');
           $this->Cell(50,8,$o->getTelephone(),'B',0,'L');
           $this->SetFont('Arial','B',10);
           $this->Cell(25,8,($o->getCountryID()!= 1?'AUSLAND '.$o->getCountryID():' '),'B',1,'L');
       }
        $this->AddPage('L');

    }


    function ItemsPickHeader($Stock,$cellHight){
        $this->Ln(10);
        $this->SetFont('Arial','B',14);
        $this->Cell(100,$cellHight,utf8_decode($Stock),0,1,'L');
        $this->SetFont('Arial','',8);
        $this->SetTextColor(0,0,0);
        /*
        $this->Cell(10,$cellHight,'Menge','B',0,'C');
        $this->Cell(25,$cellHight,'Code','B',0,'C');
        $this->Cell(80,$cellHight,'Latein','B',0,'L');
        $this->Cell(80,$cellHight,'Deutsch','B',0,'L');
        $this->Cell(20,$cellHight,'Preis','B',0,'L');
        $this->Cell(50,$cellHight,'Bestellung','B',1,'L');
        */



    }

    function ItemsPickBody( $item ,$cellHight){
       //$item['quantity'],$item['product'],$item['item']
        //array $orders, $Quantity, $Product,\Acme\BSDataBundle\Entity\OrdersItem $item

        $this->SetFont('Arial','',12);
        $this->Cell(5,$cellHight,' ' ,1,0,'L');
        $this->SetFont('Arial','B',14);
        $this->Cell(10,$cellHight,$item['quantity'],'T',0,'C');
        $this->SetFont('Arial','',12);
        if($item['product']){
            $this->Cell(25,$cellHight,utf8_decode($item['product']->getArticleNo()),'T',0,'L');
        } else{
            $this->Cell(25,$cellHight,'','',0,'L');
        }
        $this->Cell(80,$cellHight,substr(utf8_decode($item['product']->getName2()),0,65),'T',0,'L');
        $this->Cell(80,$cellHight,substr(utf8_decode($item['item']->getItemText()),0,35),'T',0,'L');
        $this->Cell(20,$cellHight,sprintf("%01.2f " , $item['item']->getPrice()).EURO,'T',0,'L');
        $STROrder = '';

        foreach($item['orders'] as $order){
            $STROrder .= $order['Quantity'].' x '.$order['OrderID'].' '.$order['Name']."\n";
        }
        $this->MultiCell(0,$cellHight,$STROrder ,'T','J');
        //$this->Cell(100,$cellHight,substr(utf8_decode($item['item']->getItemText()),0,65),'',0,'L');


    }




    function OrderFooder(\Acme\BSDataBundle\Entity\Orders $Order,$quantity){
        $hdelta = 220;

        $this->SetFont('Arial','',24);
        $this->Cell(10, 20," Gesamt: ".$quantity);
        $this->line(0,  $hdelta,220 ,  $hdelta);

        $this->SetFont('Arial','B',48);
        $this->SetTextColor(0,0,0);
        $this->Text(10, $hdelta + 20,$Order->getOrderID());
        $this->Text(10, $hdelta + 50,utf8_decode($Order->getLastname()));
        $this->SetFont('Arial','',24);
        $this->Text(80, $hdelta + 20,utf8_decode($Order->getZIP().' '.$Order->getCity()));



    }




    function EAN13($x, $y, $barcode, $h=16, $w=.35)
    {
        $this->Barcode($x,$y,$barcode,$h,$w,13);
    }

    function UPC_A($x, $y, $barcode, $h=16, $w=.35)
    {
        $this->Barcode($x,$y,$barcode,$h,$w,12);
    }

    function GetCheckDigit($barcode)
    {
        //Compute the check digit
        $sum=0;
        for($i=1;$i<=11;$i+=2)
            $sum+=3*$barcode[$i];
        for($i=0;$i<=10;$i+=2)
            $sum+=$barcode[$i];
        $r=$sum%10;
        if($r>0)
            $r=10-$r;
        return $r;
    }

    function TestCheckDigit($barcode)
    {
        //Test validity of check digit
        $sum=0;
        for($i=1;$i<=11;$i+=2)
            $sum+=3*$barcode[$i];
        for($i=0;$i<=10;$i+=2)
            $sum+=$barcode[$i];
        return ($sum+$barcode[12])%10==0;
    }

    function Barcode($x, $y, $barcode, $h, $w, $len)
    {
        //Padding
        $barcode=str_pad($barcode,$len-1,'0',STR_PAD_LEFT);
        if($len==12)
            $barcode='0'.$barcode;
        //Add or control the check digit
        if(strlen($barcode)==12)
            $barcode.=$this->GetCheckDigit($barcode);
        elseif(!$this->TestCheckDigit($barcode))
            $this->Error('Incorrect check digit');
        //Convert digits to bars
        $codes=array(
            'A'=>array(
                '0'=>'0001101','1'=>'0011001','2'=>'0010011','3'=>'0111101','4'=>'0100011',
                '5'=>'0110001','6'=>'0101111','7'=>'0111011','8'=>'0110111','9'=>'0001011'),
            'B'=>array(
                '0'=>'0100111','1'=>'0110011','2'=>'0011011','3'=>'0100001','4'=>'0011101',
                '5'=>'0111001','6'=>'0000101','7'=>'0010001','8'=>'0001001','9'=>'0010111'),
            'C'=>array(
                '0'=>'1110010','1'=>'1100110','2'=>'1101100','3'=>'1000010','4'=>'1011100',
                '5'=>'1001110','6'=>'1010000','7'=>'1000100','8'=>'1001000','9'=>'1110100')
        );
        $parities=array(
            '0'=>array('A','A','A','A','A','A'),
            '1'=>array('A','A','B','A','B','B'),
            '2'=>array('A','A','B','B','A','B'),
            '3'=>array('A','A','B','B','B','A'),
            '4'=>array('A','B','A','A','B','B'),
            '5'=>array('A','B','B','A','A','B'),
            '6'=>array('A','B','B','B','A','A'),
            '7'=>array('A','B','A','B','A','B'),
            '8'=>array('A','B','A','B','B','A'),
            '9'=>array('A','B','B','A','B','A')
        );
        $code='101';
        $p=$parities[$barcode[0]];
        for($i=1;$i<=6;$i++)
            $code.=$codes[$p[$i-1]][$barcode[$i]];
        $code.='01010';
        for($i=7;$i<=12;$i++)
            $code.=$codes['C'][$barcode[$i]];
        $code.='101';
        //Draw bars
        for($i=0;$i<strlen($code);$i++)
        {
            if($code[$i]=='1')
                $this->Rect($x+$i*$w,$y,$w,$h,'F');
        }
        //Print text uder barcode
        $this->SetFont('Arial','',12);
        $this->Text($x,$y+$h+11/$this->k,substr($barcode,-$len));
    }

    function Code39($xpos, $ypos, $code, $baseline=0.5, $height=5){

        $wide = $baseline;
        $narrow = $baseline / 3 ;
        $gap = $narrow;

        $barChar['0'] = 'nnnwwnwnn';
        $barChar['1'] = 'wnnwnnnnw';
        $barChar['2'] = 'nnwwnnnnw';
        $barChar['3'] = 'wnwwnnnnn';
        $barChar['4'] = 'nnnwwnnnw';
        $barChar['5'] = 'wnnwwnnnn';
        $barChar['6'] = 'nnwwwnnnn';
        $barChar['7'] = 'nnnwnnwnw';
        $barChar['8'] = 'wnnwnnwnn';
        $barChar['9'] = 'nnwwnnwnn';
        $barChar['A'] = 'wnnnnwnnw';
        $barChar['B'] = 'nnwnnwnnw';
        $barChar['C'] = 'wnwnnwnnn';
        $barChar['D'] = 'nnnnwwnnw';
        $barChar['E'] = 'wnnnwwnnn';
        $barChar['F'] = 'nnwnwwnnn';
        $barChar['G'] = 'nnnnnwwnw';
        $barChar['H'] = 'wnnnnwwnn';
        $barChar['I'] = 'nnwnnwwnn';
        $barChar['J'] = 'nnnnwwwnn';
        $barChar['K'] = 'wnnnnnnww';
        $barChar['L'] = 'nnwnnnnww';
        $barChar['M'] = 'wnwnnnnwn';
        $barChar['N'] = 'nnnnwnnww';
        $barChar['O'] = 'wnnnwnnwn';
        $barChar['P'] = 'nnwnwnnwn';
        $barChar['Q'] = 'nnnnnnwww';
        $barChar['R'] = 'wnnnnnwwn';
        $barChar['S'] = 'nnwnnnwwn';
        $barChar['T'] = 'nnnnwnwwn';
        $barChar['U'] = 'wwnnnnnnw';
        $barChar['V'] = 'nwwnnnnnw';
        $barChar['W'] = 'wwwnnnnnn';
        $barChar['X'] = 'nwnnwnnnw';
        $barChar['Y'] = 'wwnnwnnnn';
        $barChar['Z'] = 'nwwnwnnnn';
        $barChar['-'] = 'nwnnnnwnw';
        $barChar['.'] = 'wwnnnnwnn';
        $barChar[' '] = 'nwwnnnwnn';
        $barChar['*'] = 'nwnnwnwnn';
        $barChar['$'] = 'nwnwnwnnn';
        $barChar['/'] = 'nwnwnnnwn';
        $barChar['+'] = 'nwnnnwnwn';
        $barChar['%'] = 'nnnwnwnwn';

        $this->SetFont('Arial','',10);
        $this->Text($xpos, $ypos + $height + 4, $code);
        $this->SetFillColor(0);

        $code = '*'.strtoupper($code).'*';
        for($i=0; $i<strlen($code); $i++){
            $char = $code[$i];
            if(!isset($barChar[$char])){
                $this->Error('Invalid character in barcode: '.$char);
            }
            $seq = $barChar[$char];
            for($bar=0; $bar<9; $bar++){
                if($seq[$bar] == 'n'){
                    $lineWidth = $narrow;
                }else{
                    $lineWidth = $wide;
                }
                if($bar % 2 == 0){
                    $this->Rect($xpos, $ypos, $lineWidth, $height, 'F');
                }
                $xpos += $lineWidth;
            }
            $xpos += $gap;
        }
    }
}
