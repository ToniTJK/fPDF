<?php

$conexio = new mysqli("localhost", "root","root", "empresa");
$conexio->query("SET NAMES 'utf80");

$codi = $_GET['codi'];
$lasql = "SELECT * FROM empleats 
INNER JOIN departaments ON empleats.ndepartament = departaments.codi 
WHERE departaments.codi = $codi";

$consulta = $conexio->query($lasql);

require('../fpdf/fpdf.php');

class MiPDF extends FPDF {
function Header(){
	$this->SetFont('Arial','B', 12);
	$this->SetFillColor(255,255,255);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(0.5);
	$this->Ln(2);
	$this->SetTextColor(10,120,180);
	$this->Cell(205,8,"Llistat d'empleats segons el codi departament",0,2,'C',false);
	$this->Image('../webbing.png', 0, -10,-150);

	$this->Ln(5);
}

function Footer(){
	$this->Ln(20);
	$this->SetTextColor(0,0,0);
	$this->SetFont("Arial","I", 7);
	$this->Cell(0,5,"Pagina ".$this->PageNo(),'T',1,'C',0);
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
$pdf->setX(126);
$pdf->Cell(40,5,utf8_decode('Funció'),1,0,'C',1);
$pdf->setX(168);
$pdf->Cell(40,5,'Contracte',1,0,'C',1);
$pdf->SetTextColor(30,30,30);

while ($registro = $consulta->fetch_array()) {
	$pdf->Ln(7);
	$pdf->setX(20);
	$pdf->Cell(20,5,$registro['codi'],0,0,'R');
	$pdf->setX(42);
	$pdf->Cell(40,5,$registro['nom']);
	$pdf->setX(84);	
	$pdf->Cell(40,5,$registro['ciutat']);
	$pdf->setX(126);	
	$pdf->Cell(40,5,$registro['funcio']);	
	$pdf->setX(170);	
	$pdf->Cell(40,5,$registro['datacontracte']);
	
}

$pdf->SetAuthor('Toni Torres');
$pdf->Output('departaments', 'I'); 

?>