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


class exportPDF extends \FPDF
{

    private $position = 0;
    public $title;
    private $cellHight;
    function __construct($title,$cellHight) {
        parent::__construct();
        $this->title = $title;
        $this->cellHight = $cellHight;

    }





    function Header()
    {
        $cellHight = $this->cellHight;
        $this->SetFont('Arial','',8);
        $this->Cell(80,10,"Blumenschule BSIntern zu Lexware Export");
        $this->Cell(30,10,$this->title);
        $this->Cell(20,10,'Seite '.$this->PageNo(),0,0,'R');
        // Line break
        $this->Ln(10);
        $this->SetTextColor(0,0,0);
        $this->SetFont('Arial','',8);
        $this->Cell(20  ,$cellHight,'Belegnummer','B',0,'L');
        $this->Cell(40  ,$cellHight,'Buchungstext','B',0,'L');
        $this->Cell(15  ,$cellHight,'Betrag','B',0,'L');
        $this->Cell(10  ,$cellHight,'MwSt','B',0,'L');
        $this->Cell(20  ,$cellHight,'Sollkonto','B',0,'L');
        $this->Cell(20  ,$cellHight,'Habenkonto','B',0,'L');
        $this->Cell(20  ,$cellHight,'Belegdatum','B',0,'L');
        $this->Cell(20  ,$cellHight, utf8_decode('Währung'),'B',0,'L');
        //$this->Cell(10  ,$cellHight,'Kostenstelle','B',0,'L');
        $this->Cell(30  ,$cellHight,'Re_Nr','B',1,'L');


    }


    function Body($row,$cellHight){
        $this->SetFont('Arial','',12);
        $this->Cell(20  ,$cellHight,$row['Belegnummer']     ,'B',0,'L');
        $this->Cell(40  ,$cellHight,utf8_decode( $row['Buchungstext'] )   ,'B',0,'L');
        $this->Cell(15  ,$cellHight,sprintf('%01.2f',$row['Buchungsbetrag'] ) ,'B',0,'R');
        $this->Cell(10  ,$cellHight,$row['MwSt']            ,'B',0,'R');
        $this->Cell(20  ,$cellHight,$row['Sollkonto']       ,'B',0,'L');
        $this->Cell(20  ,$cellHight,$row['Habenkonto']      ,'B',0,'L');
        $this->Cell(20  ,$cellHight,$row['Belegdatum']      ,'B',0,'L');
        $this->Cell(20  ,$cellHight,$row['Währung']         ,'B',0,'L');
       // $this->Cell(10  ,$cellHight,$row['Kostenstelle']    ,'B',0,'L');
        $this->Cell(30  ,$cellHight,$row['Re_Nr']           ,'B',1,'L');


    }



    function exportFooder($data,$cellHight){
        $this->Ln(20);
        $this->SetFont('Arial','',12);
        $this->Cell(60  ,$cellHight,utf8_decode("Summen für die Konten"   )  ,'B',1,'L');
        foreach($data as $k => $d){

            $this->Cell(20  ,$cellHight,$k     ,'B',0,'L');
            $this->Cell(20  ,$cellHight,sprintf('%01.2f',$d   ),'B',1,'L');


        }

    function Footer(){

        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Page number
        $this->Cell(0,10,'Seite '.$this->PageNo().'/{nb}',0,0,'R');


        }


    }





}
