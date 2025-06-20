<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../login/index.php");
    exit();
}

include('../../koneksi/koneksi.php');
include('../../includes/header.php');

// Error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Pastikan PHPExcel sudah terinclude dengan benar
$phpExcelPath = '../../includes/PHPExcel/Classes/PHPExcel.php';
if (!file_exists($phpExcelPath)) {
    die("File PHPExcel tidak ditemukan di: $phpExcelPath");
}
require_once $phpExcelPath;

// Get all criteria for validation
$kriteria_list = [];
$kriteria_query = mysqli_query($koneksi, "SELECT * FROM bobot_kriteria ORDER BY id ASC");
if (!$kriteria_query) {
    die("Error query kriteria: " . mysqli_error($koneksi));
}
while ($row = mysqli_fetch_assoc($kriteria_query)) {
    $kriteria_list[] = $row['kriteria'];
}

// Process form submission
if (isset($_POST['import'])) {
    // Validasi file upload
    if (!isset($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK) {
        echo "<script>alert('Error upload file!'); window.history.back();</script>";
        exit();
    }

    $file = $_FILES['file']['tmp_name'];
    $file_name = $_FILES['file']['name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);

    // Validasi ekstensi file
    $allowed_ext = ['xlsx', 'xls'];
    if (!in_array(strtolower($file_ext), $allowed_ext)) {
        echo "<script>alert('Hanya file Excel (.xlsx, .xls) yang diizinkan!'); window.history.back();</script>";
        exit();
    }

    try {
        // Load file Excel
        $inputFileType = PHPExcel_IOFactory::identify($file);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($file);

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Validasi header
        $header = [];
        for ($col = 'A'; $col <= $highestColumn; $col++) {
            $header[] = trim($sheet->getCell($col . '1')->getValue());
        }

        // Debug: Tampilkan header yang dibaca
        echo "<!-- Header yang dibaca: " . print_r($header, true) . " -->";

        // Check kolom wajib
        if (!in_array('Alternatif', $header)) {
            echo "<script>alert('Kolom \"Alternatif\" tidak ditemukan dalam file Excel!'); window.history.back();</script>";
            exit();
        }

        // Check kriteria
        $missing_columns = array_diff($kriteria_list, $header);
        if (!empty($missing_columns)) {
            echo "<script>alert('Kolom berikut tidak ditemukan: " . implode(', ', $missing_columns) . "'); window.history.back();</script>";
            exit();
        }

        // Mulai transaction
        mysqli_begin_transaction($koneksi);

        $success_count = 0;
        $error_count = 0;
        $error_messages = [];

        for ($row = 2; $row <= $highestRow; $row++) {
            $rowData = [];
            $colIndex = 0;

            for ($col = 'A'; $col <= $highestColumn; $col++) {
                $cellValue = $sheet->getCell($col . $row)->getValue();
                $headerName = $header[$colIndex];

                if ($headerName != 'No') { // Skip kolom No jika ada
                    $rowData[$headerName] = $cellValue;
                }
                $colIndex++;
            }

            // Skip baris kosong
            if (empty($rowData['Alternatif'])) {
                continue;
            }

            // Siapkan data untuk insert
            $alternatif = mysqli_real_escape_string($koneksi, $rowData['Alternatif']);

            // Bangun query dinamis
            $columns = ['alternatif'];
            $values = ["'$alternatif'"];

            foreach ($kriteria_list as $kriteria) {
                $columns[] = $kriteria;
                $value = isset($rowData[$kriteria]) ? $rowData[$kriteria] : '';
                $values[] = "'" . mysqli_real_escape_string($koneksi, $value) . "'";
            }

            $columns_str = implode(', ', $columns);
            $values_str = implode(', ', $values);

            // Query insert
            $query = "INSERT INTO data_primer ($columns_str) VALUES ($values_str)";
            if (mysqli_query($koneksi, $query)) {
                $success_count++;
            } else {
                $error_count++;
                $error_messages[] = "Baris $row: " . mysqli_error($koneksi);
            }
        }

        // Commit atau rollback
        if ($error_count == 0) {
            mysqli_commit($koneksi);
            $message = "Import berhasil! $success_count data berhasil diimpor.";
        } else {
            mysqli_rollback($koneksi);
            $message = "Import selesai dengan $error_count error. $success_count data berhasil diimpor.<br>";
            $message .= "Error detail: <br>" . implode("<br>", $error_messages);
        }

        echo "<script>alert('" . addslashes($message) . "'); window.location.href='tampil.php';</script>";
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "'); window.history.back();</script>";
        exit();
    }
}
?>

<!-- Bagian HTML tetap sama seperti sebelumnya -->

<div class="container-fluid w-full">
    <div class="row">
        <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Import Data Alternatif</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <a href="tampil.php" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Petunjuk Import:</strong>
                        <ul>
                            <li>Unduh template terlebih dahulu untuk memastikan format file yang benar</li>
                            <li>File harus dalam format Excel (.xlsx atau .xls)</li>
                            <li>Kolom harus sesuai dengan struktur database</li>
                            <li>Pastikan tidak ada data yang kosong pada kolom wajib</li>
                        </ul>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="file" class="form-label">Pilih File Excel</label>
                                    <input type="file" class="form-control" id="file" name="file" required accept=".xlsx,.xls">
                                </div>
                                <div class="mb-3">
                                    <button type="submit" name="import" class="btn btn-primary">
                                        <i class="fas fa-file-import"></i> Import Data
                                    </button>
                                    <a href="export.php" class="btn btn-success">
                                        <i class="fas fa-file-download"></i> Unduh Template
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include('../../includes/footer.php'); ?>