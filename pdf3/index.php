<?php

$conexio = new mysqli("localhost", "root","root", "empresa");
$conexio->query("SET NAMES 'utf80");

$lasql = "SELECT nom, comisio FROM `empleats` WHERE comisio > 0";
$query= $conexio->prepare($lasql);
$query->bind_result($nom, $comisio);
$query->execute();
$noms = Array();
$valors = Array();
$index = 0;
while ($query->fetch()){
	$noms[$index] = $nom;
	$valors[$index] = $comisio;
	$index++;
}

require('../jpgraph/src/jpgraph.php');
require('../jpgraph/src/jpgraph_bar.php');

$grafica = new Graph(600, 450, "auto");
$grafica->SetScale("textlin", 0, 1500);

$tema = new UniversalTheme;
$grafica->SetTheme($tema);    //Tema, estil

$grafica->img->SetAntiAliasing();  //3d 

$grafica->xaxis->SetTickLabels($noms);

$barres = new BarPlot($valors);

$barres->SetColor("white");
$barres->SetFillColor("#3333FF");
$barres->SetWidth(40);

$grafica->title->Set("Empleats que tenen comision");
$grafica->xaxis->SetTitle("Empleats");
$grafica->yaxis->SetTitle("Comisio");
$grafica->Add($barres);

$grafica->SetBox(false);
$grafica->ygrid->SetFill(false);

$grafica->Stroke("jgraph.png");

$lasql = "SELECT * FROM `empleats` WHERE comisio > 0";
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
	$this->Cell(205,8,"Llistat d'empleats amb comisio major a 0",0,2,'C',false);
	$this->Image('../webbing.png', 0, -10,-150);
	$this->Ln(5);
}

function Footer(){
	$this->Ln(100);
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

$pdf->setX(50);
$pdf->Cell(20,5,'Nom',1,0,'C',1);
$pdf->setX(75);
$pdf->Cell(40,5,'Comisio',1,0,'C',1);
$pdf->setX(120);
$pdf->Cell(40,5,'Sou',1,0,'C',1);
//$pdf->setX(126);
//$pdf->Cell(30,5,'Sou',1,0,'C',1);
$pdf->SetTextColor(30,30,30);

while ($registro = $consulta->fetch_array()) {
	$pdf->Ln(7);
	$pdf->setX(48);
	$pdf->Cell(20,5,$registro['nom'],0,0,'R');
	$pdf->setX(75);
	$pdf->Cell(40,5,$registro['comisio']);
	$pdf->setX(120);	
	$pdf->Cell(40,5,$registro['sou']);
	//$pdf->setX(126);	
	//$pdf->Cell(30,5,$registro['sou']." ".chr(128),0,0,'R');	
}
$pdf->Image('jgraph.png',60,80,90,0,'PNG');
//$pdf->Image('jgraph.png', 0, 0, 0);

$pdf->SetAuthor('Toni Torres');
$pdf->Output('departaments', 'I'); 

?>