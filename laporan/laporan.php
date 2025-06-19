<?php
include('../koneksi/koneksi.php');
include('../fpdf/fpdf.php');

$tgl = date('d-M-Y');
$pdf = new FPDF();
$pdf->addPage();
$pdf->setAutoPageBreak(false);
$pdf->setFont('Arial', 'B', 11);

// Header
$image1 = "../image/boesa.jpg";
$pdf->Image($image1, 40, 15, 20, 20);
$pdf->text(85, 20, 'BOESA MANAGEMENT');
$pdf->text(73, 27, 'Model Utusan Kontes Fashion Show');
$pdf->text(95, 34, 'Tahun 2020');

$yi = 50;
$ya = 50;
$row = 6;

// Judul Laporan
$pdf->setFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'HASIL PERANGKINGAN GABUNGAN (60% ARAS + 40% PIPRECIA-S)', 0, 1, 'C');
$pdf->Ln(5);

// Header Tabel
$pdf->setFont('Arial', 'B', 11);
$pdf->setFillColor(222, 222, 222);
$pdf->setXY(20, $ya);
$pdf->CELL(10, 6, 'NO', 1, 0, 'C', 1);
$pdf->CELL(50, 6, 'ALTERNATIF', 1, 0, 'C', 1);
$pdf->CELL(30, 6, 'ARAS', 1, 0, 'C', 1);
$pdf->CELL(30, 6, 'PIPRECIA-S', 1, 0, 'C', 1);
$pdf->CELL(30, 6, 'GABUNGAN', 1, 0, 'C', 1);
$pdf->CELL(25, 6, 'RANKING', 1, 0, 'C', 1);
$ya = $yi + $row;

// Query untuk mengambil data gabungan
$sql = mysqli_query($koneksi, "
    SELECT 
        a.alternatif,
        a.nilai_akhir AS skor_aras,
        p.nilai_akhir AS skor_piprecia,
        (0.6 * a.nilai_akhir) + (0.4 * p.nilai_akhir) AS skor_gabungan
    FROM 
        hasil2 a
    JOIN 
        hasil_piprecia p ON a.alternatif = p.alternatif
    ORDER BY 
        skor_gabungan DESC
") or die(mysqli_error($koneksi));

$no = 1;
$rank = 1;
$prev_score = null;
$actual_rank = 1;

while ($data = mysqli_fetch_array($sql)) {
    // Handle ranking yang sama
    if ($prev_score !== null && $data['skor_gabungan'] == $prev_score) {
        $display_rank = $actual_rank - 1;
    } else {
        $display_rank = $actual_rank;
    }

    $pdf->setXY(20, $ya);
    $pdf->setFont('arial', '', 9);
    $pdf->setFillColor(255, 255, 255);

    $pdf->cell(10, 6, $no, 1, 0, 'C', 1);
    $pdf->cell(50, 6, $data['alternatif'], 1, 0, 'L', 1);
    $pdf->cell(30, 6, number_format($data['skor_aras'], 4), 1, 0, 'C', 1);
    $pdf->cell(30, 6, number_format($data['skor_piprecia'], 4), 1, 0, 'C', 1);
    $pdf->cell(30, 6, number_format($data['skor_gabungan'], 4), 1, 0, 'C', 1);
    $pdf->cell(25, 6, $display_rank, 1, 0, 'C', 1);

    $ya = $ya + $row;
    $no++;
    $prev_score = $data['skor_gabungan'];
    $actual_rank++;

    // Handle page break
    if ($ya > 260) {
        $pdf->AddPage();
        $ya = 30;
        // Header tabel di halaman baru
        $pdf->setXY(20, $ya);
        $pdf->setFont('Arial', 'B', 11);
        $pdf->setFillColor(222, 222, 222);
        $pdf->CELL(10, 6, 'NO', 1, 0, 'C', 1);
        $pdf->CELL(50, 6, 'ALTERNATIF', 1, 0, 'C', 1);
        $pdf->CELL(30, 6, 'ARAS', 1, 0, 'C', 1);
        $pdf->CELL(30, 6, 'PIPRECIA-S', 1, 0, 'C', 1);
        $pdf->CELL(30, 6, 'GABUNGAN', 1, 0, 'C', 1);
        $pdf->CELL(25, 6, 'RANKING', 1, 0, 'C', 1);
        $ya = $ya + $row;
    }
}

// Footer
$pdf->text(142, $ya + 10, "MEDAN, " . $tgl);
$pdf->text(130, $ya + 30, "Manager Boesa Management");

$pdf->output();
