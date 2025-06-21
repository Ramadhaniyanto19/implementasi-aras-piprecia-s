<?php
include('../koneksi/koneksi.php');
include('../fpdf/fpdf.php');

// Fungsi untuk mendapatkan data valid dari database
function getValidData($koneksi)
{
    $query = "
        SELECT 
            a.alternatif,
            a.nilai_akhir AS skor_aras,
            p.nilai_akhir AS skor_piprecia
        FROM 
            (SELECT alternatif, nilai_akhir FROM hasil_aras) a
        JOIN 
            (SELECT alternatif, nilai_akhir FROM hasil_piprecia) p 
        ON a.alternatif = p.alternatif
        WHERE 
            a.nilai_akhir IS NOT NULL AND 
            p.nilai_akhir IS NOT NULL
        ORDER BY 
            a.nilai_akhir DESC, p.nilai_akhir DESC
    ";

    $result = mysqli_query($koneksi, $query);
    $data = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }
    }

    return $data;
}

// Generate PDF
$tgl = date('d-M-Y');
$pdf = new FPDF();
$pdf->addPage();
$pdf->setAutoPageBreak(false);
$pdf->setFont('Arial', 'B', 11);

// Header
$image1 = "../image/boesa.png";
$pdf->Image($image1, 40, 15, 20, 20);
$pdf->text(85, 20, 'PIPARAS-DSS');
$pdf->text(73, 27, 'Layanan Expedisi Terbaik menurut PIPARAS-DSS');
$pdf->text(95, 34, 'Tahun 2024');

$yi = 50;
$ya = 50;
$row = 6;

// Judul Laporan
$pdf->setFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'HASIL PERANGKINGAN ARAS & PIPRECIA-S', 0, 1, 'C');
$pdf->Ln(5);

// Header Tabel
$pdf->setFont('Arial', 'B', 11);
$pdf->setFillColor(222, 222, 222);
$pdf->setXY(20, $ya);
$pdf->CELL(10, 6, 'NO', 1, 0, 'C', 1);
$pdf->CELL(60, 6, 'ALTERNATIF', 1, 0, 'C', 1);
$pdf->CELL(40, 6, 'NILAI ARAS', 1, 0, 'C', 1);
$pdf->CELL(40, 6, 'NILAI PIPRECIA-S', 1, 0, 'C', 1);
$pdf->CELL(25, 6, 'RANKING', 1, 0, 'C', 1);
$ya = $yi + $row;

// Ambil data valid dari database
$data = getValidData($koneksi);

$no = 1;
$rank = 1;
$prev_aras = null;
$prev_piprecia = null;

foreach ($data as $row) {
    // Handle ranking yang sama
    if ($prev_aras !== null && $row['skor_aras'] == $prev_aras && $row['skor_piprecia'] == $prev_piprecia) {
        $display_rank = $rank - 1;
    } else {
        $display_rank = $rank;
    }

    $pdf->setXY(20, $ya);
    $pdf->setFont('arial', '', 9);
    $pdf->setFillColor(255, 255, 255);

    $pdf->cell(10, 6, $no, 1, 0, 'C', 1);
    $pdf->cell(60, 6, $row['alternatif'], 1, 0, 'L', 1);
    $pdf->cell(40, 6, number_format($row['skor_aras'], 4), 1, 0, 'C', 1);
    $pdf->cell(40, 6, number_format($row['skor_piprecia'], 4), 1, 0, 'C', 1);
    $pdf->cell(25, 6, $display_rank, 1, 0, 'C', 1);

    $ya = $ya + $row;
    $no++;
    $prev_aras = $row['skor_aras'];
    $prev_piprecia = $row['skor_piprecia'];
    $rank++;

    // Handle page break
    if ($ya > 260) {
        $pdf->AddPage();
        $ya = 30;
        // Header tabel di halaman baru
        $pdf->setXY(20, $ya);
        $pdf->setFont('Arial', 'B', 11);
        $pdf->setFillColor(222, 222, 222);
        $pdf->CELL(10, 6, 'NO', 1, 0, 'C', 1);
        $pdf->CELL(60, 6, 'ALTERNATIF', 1, 0, 'C', 1);
        $pdf->CELL(40, 6, 'NILAI ARAS', 1, 0, 'C', 1);
        $pdf->CELL(40, 6, 'NILAI PIPRECIA-S', 1, 0, 'C', 1);
        $pdf->CELL(25, 6, 'RANKING', 1, 0, 'C', 1);
        $ya = $ya + $row;
    }
}

// Footer
$pdf->text(142, $ya + 10, "BANDUNG, " . $tgl);
$pdf->text(130, $ya + 30, "PIPARAS-DSS");

$pdf->output();
