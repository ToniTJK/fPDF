<?php

$conexio = new mysqli("localhost", "root","root", "empresa");
$conexio->query("SET NAMES 'utf80");

$lasql = "select * from departaments";
$consulta = $conexio->query($lasql);

require('../fpdf/fpdf.php');

class MiPDF extends FPDF {
function Header(){
	$this->SetFont('Arial','B', 12);
//Cambio color de fondo
	$this->SetFillColor(255,255,255);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(0.5);
	$this->Ln(2);
	$this->SetTextColor(10,120,180);
	$this->Cell(205,8,"Llistat d'empleats",0,2,'C',false);
	$this->Image('../webbing.png', 0, -10,-150);
	$this->Ln(5);
}

function Footer(){
	$this->Ln(20);
	$this->SetTextColor(0,0,0);
	$this->SetFont("Arial","I", 7);

	$this->Cell(0,5,utf8_decode("Pagina: ").$this->PageNo(),'T',1,'C',0);
}

function AcceptPageBreak(){
	return $this->AutoPageBreak;
}
}


$pdf=new MiPDF('p', 'mm', 'A4');

$pdf->AddPage();


$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','', 10);

$pdf->Ln(5);
$pdf -> SetFillColor(10,120,180);
$pdf -> SetDrawColor(10,120,180);

$pdf->setX(20);
$pdf->Cell(20,5,'Codi',1,0,'C',1);
$pdf->setX(42);
$pdf->Cell(40,5,'Nom',1,0,'C',1);
$pdf->setX(84);
$pdf->Cell(40,5,'Ciutat',1,0,'C',1);
//$pdf->setX(126);
//$pdf->Cell(30,5,'Sou',1,0,'C',1);
$pdf->SetTextColor(30,30,30);

while ($registro = $consulta->fetch_array()) {
	$pdf->Ln(7);
	$pdf->setX(20);
	$pdf->Cell(20,5,utf8_decode($registro['codi'],0,0,'R'));
	$pdf->setX(42);
	$pdf->Cell(40,5,utf8_decode($registro['nom']));
	$pdf->setX(84);	
	$pdf->Cell(40,5,utf8_decode($registro['ciutat']));
	//$pdf->setX(126);	
	//$pdf->Cell(30,5,$registro['sou']." ".chr(128),0,0,'R');	
}

$pdf->SetAuthor('Toni Torres');
$pdf->Output('departaments', 'I'); 

?>