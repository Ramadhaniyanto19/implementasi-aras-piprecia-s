<?php
include('../koneksi/koneksi.php');
include ('../fpdf/fpdf.php');
$tgl = date('d-M-Y');
$pdf = new FPDF();
$pdf->addPage();
$pdf->setAutoPageBreak(false);
$pdf->setFont('Arial','B',11);
$image1 = "../image/boesa.jpg";
$pdf->Image($image1,40,15,20,20);
$pdf->text(85,20,'BOESA MANAGEMENT');
$pdf->text(73,27,'Model Utusan Kontes Fashion Show');
$pdf->text(95,34,'Tahun 2020');
$yi = 50;
$ya = 50;
$row = 6;
$pdf->setFont('Arial','B',11);
$pdf->setFillColor(222,222,222);
$pdf->setXY(40,$ya);
$pdf->CELL(8,6,'NO',1,0,'C',1);
$pdf->CELL(55,6,'ALternatif',1,0,'C',1);
$pdf->CELL(30,6,'NILAI',1,0,'C',1);
$pdf->CELL(35,6,'RANKING',1,0,'C',1);
$ya = $yi + $row;
$sql = mysqli_query($koneksi, "SELECT * FROM hasil2 ORDER by nilai_akhir DESC") or die(mysqli_error($koneksi));
$i = 1;
$no = 1;
$max = 31;
$row = 6;
$rank = 1;
while($data = mysqli_fetch_array($sql)){
$pdf->setXY(40,$ya);
$pdf->setFont('arial','',9);
$pdf->setFillColor(255,255,255);
$pdf->cell(8,6,$no,1,0,'C',1);
$pdf->cell(55,6,$data['alternatif'],1,0,'L',1);
$pdf->cell(30,6,$data['nilai_akhir'],1,0,'C',1);
$pdf->CELL(35,6,'Ranking ke '.$rank,1,0,'C',1);
$ya = $ya+$row;
$no++;
$i++;
$rank++;
}
//page footer

$pdf->text(142,$ya+10,"MEDAN , ".$tgl);
$pdf->text(130,$ya+30,"Manager Boesa Management");
$pdf->output();
?>