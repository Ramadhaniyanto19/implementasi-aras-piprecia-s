<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../login/index.php");
    exit();
}

include('../../koneksi/koneksi.php');

// Include PHPExcel library
require_once '../../includes/PHPExcel/Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("SPK PIPRECIA-ARAS")
    ->setLastModifiedBy("SPK PIPRECIA-ARAS")
    ->setTitle("Data Alternatif")
    ->setSubject("Data Alternatif")
    ->setDescription("Dokumen berisi data alternatif untuk SPK PIPRECIA-ARAS")
    ->setKeywords("spk alternatif piprecia aras")
    ->setCategory("Data Alternatif");

// Get all criteria
$kriteria_list = [];
$kriteria_query = mysqli_query($koneksi, "SELECT * FROM bobot_kriteria ORDER BY id ASC");
while ($row = mysqli_fetch_assoc($kriteria_query)) {
    $kriteria_list[] = $row['kriteria'];
}

// Add header row
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', 'No')
    ->setCellValue('B1', 'Alternatif');

$column = 'C';
foreach ($kriteria_list as $kriteria) {
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($column . '1', $kriteria);
    $column++;
}

// Get data from database
$sql = mysqli_query($koneksi, "SELECT * FROM data_primer");
$rowNumber = 2;
$no = 1;

while ($row = mysqli_fetch_assoc($sql)) {
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $rowNumber, $no)
        ->setCellValue('B' . $rowNumber, $row['alternatif']);

    $dataColumn = 'C';
    foreach ($kriteria_list as $kriteria) {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue($dataColumn . $rowNumber, $row[$kriteria]);
        $dataColumn++;
    }

    $rowNumber++;
    $no++;
}

// Set active sheet index to the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Set headers for download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data_alternatif.xlsx"');
header('Cache-Control: max-age=0');

// Save Excel 2007 file
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
