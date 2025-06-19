<?php
// Ambil nama file aktif
$currentFile = basename($_SERVER['PHP_SELF']);

// Tentukan apakah harus pakai ../ di depan path
$useBackPath = in_array($currentFile, ['tampil.php', 'tambah.php', 'edit.php']) ? '../../' : '../';
?>

<div class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse" id="sidebar">
    <div class="position-fixed pt-3">
        <div class="text-center mb-4 mt-5 pt-3">
            <img src="<?php echo $useBackPath; ?>img/logo.png" alt="Logo" width="100" class="rounded-circle">
            <h5 class="text-white mt-2">SPK Model APA AJA GITU DAH</h5>
            <hr class="bg-white w-full">
        </div>
        <ul class="nav flex-column mt-10">
            <li class="nav-item mt-3">
                <a class="nav-link text-white active" href="<?php echo $useBackPath; ?>index.php">
                    <i class="fas fa-home mr-2"></i> Beranda
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link text-white" href="<?php echo $useBackPath; ?>crud/tampil/tampil.php">
                    <i class="fas fa-users mr-2"></i> Data Alternatif
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link text-white" href="<?php echo $useBackPath; ?>hitung/hitung.php">
                    <i class="fas fa-calculator mr-2"></i> Perhitungan ARAS & PIPRECIA-S
                </a>
            </li>
            <li class="nav-item mt-3">
                <a class="nav-link text-white" href="<?php echo $useBackPath; ?>laporan/laporan.php">
                    <i class="fas fa-file-alt mr-2"></i> Laporan
                </a>
            </li>
            <li class="nav-item -mt-5 fixed-bottom">
                <a class="nav-link text-danger" href="<?php echo $useBackPath; ?>logout/logout.php">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>