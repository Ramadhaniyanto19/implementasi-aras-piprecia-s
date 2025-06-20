<?php
// Ambil nama file aktif
$currentFile = basename($_SERVER['PHP_SELF']);

// Tentukan apakah harus pakai ../ di depan path
$useBackPath = in_array($currentFile, ['tampil.php', 'tambah.php', 'edit.php', 'kriteria.php']) ? '../../' : '../';
?>

<div class="col-md-3 col-lg-2 d-md-block sidebar collapse" id="sidebar" style="background: linear-gradient(135deg, #2c3e50, #3498db);">
    <div class="position-sticky pt-3 h-100 d-flex flex-column">
        <!-- Mobile Toggle Button (visible only on small screens) -->
        <button class="d-md-none btn btn-dark w-100 text-left mb-3 position-fixed" type="button" data-toggle="collapse" data-target="#sidebar" aria-expanded="false" aria-controls="sidebar">
            <i class="fas fa-bars mr-2"></i> Menu
        </button>

        <div class="text-center mb-4 mt-md-5 pt-md-3 w-100 px-2">
            <img src="<?php echo $useBackPath; ?>img/logo.png" alt="Logo" width="80" class="rounded-circle img-fluid" style="max-width: 100px;">
            <h5 class="text-white mt-2 d-none d-md-block">PIPARAS-DSSPIPARAS-DSS</h5>
            <h6 class="text-white mt-2 d-md-none">SPK Model</h6>
            <hr class="bg-white w-75 d-none d-md-block">
        </div>

        <ul class="nav flex-column flex-grow-1">
            <li class="nav-item mt-1 mt-md-3">
                <a class="nav-link text-white hover-effect" href="<?php echo $useBackPath; ?>index.php">
                    <i class="fas fa-home mr-2"></i> <span class="d-none d-md-inline">Beranda</span>
                    <span class="d-md-none">Beranda</span>
                </a>
            </li>
            <li class="nav-item mt-1 mt-md-3">
                <a class="nav-link text-white hover-effect" href="<?php echo $useBackPath; ?>crud/kriteria/kriteria.php">
                    <i class="fas fa-list mr-2"></i> <span class="d-none d-md-inline">Data Kriteria</span>
                    <span class="d-md-none">Kriteria</span>
                </a>
            </li>
            <li class="nav-item mt-1 mt-md-3">
                <a class="nav-link text-white hover-effect" href="<?php echo $useBackPath; ?>crud/tampil/tampil.php">
                    <i class="fas fa-users mr-2"></i> <span class="d-none d-md-inline">Data Alternatif</span>
                    <span class="d-md-none">Alternatif</span>
                </a>
            </li>
            <li class="nav-item mt-1 mt-md-3">
                <a class="nav-link text-white hover-effect" href="<?php echo $useBackPath; ?>hitung/hitung.php">
                    <i class="fas fa-calculator mr-2"></i> <span class="d-none d-md-inline">Perhitungan</span>
                    <span class="d-md-none">Hitung</span>
                </a>
            </li>
            <li class="nav-item mt-1 mt-md-3">
                <a class="nav-link text-white hover-effect" href="<?php echo $useBackPath; ?>laporan/laporan.php">
                    <i class="fas fa-file-alt mr-2"></i> <span class="d-none d-md-inline">Laporan</span>
                    <span class="d-md-none">Laporan</span>
                </a>
            </li>
            <li class="nav-item mt-1 mt-md-3 d-md-none">
                <a class="nav-link text-white hover-effect" href="#" data-toggle="collapse" data-target="#sidebar">
                    <i class="fas fa-times mr-2"></i> Tutup Menu
                </a>
            </li>
        </ul>

        <!-- Logout Button - Stays at bottom -->
        <div class="mt-auto">
            <div class="w-100 px-3 pb-3 d-none d-md-block">
                <a class="nav-link text-white font-weight-bold hover-effect" href="<?php echo $useBackPath; ?>logout/logout.php">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>

            <!-- Mobile Logout Button -->
            <div class="w-100 px-3 pb-3 d-md-none bg-dark">
                <a class="nav-link text-white font-weight-bold hover-effect" href="<?php echo $useBackPath; ?>logout/logout.php">
                    <i class="fas fa-sign-out-alt mr-2"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Styles for Responsive Sidebar */
    .sidebar {
        position: fixed;
        height: 100vh;
        overflow-y: auto;
        transition: all 0.3s ease;
        z-index: 1000;
    }

    .hover-effect {
        transition: all 0.2s ease;
        border-radius: 4px;
        padding: 8px 15px;
    }

    .hover-effect:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
    }

    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.2);
        font-weight: bold;
    }

    /* Better mobile experience */
    @media (max-width: 767.98px) {
        .sidebar {
            width: 250px;
            transform: translateX(-100%);
        }

        .sidebar.collapse.show {
            transform: translateX(0);
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.2);
        }
    }

    /* Ensure main content doesn't get hidden behind sidebar */
    main {
        margin-left: 8%;
        /* Match sidebar width */
    }

    @media (max-width: 767.98px) {
        main {
            margin-left: 0;
        }
    }
</style>

<script>
    // Automatically close mobile sidebar when clicking a link
    $(document).ready(function() {
        $('.nav-link').on('click', function() {
            if ($(window).width() < 768) {
                $('#sidebar').collapse('hide');
            }
        });

        // Add active class to current page link
        const currentUrl = window.location.pathname.split('/').pop();
        $('.nav-link').each(function() {
            const linkUrl = $(this).attr('href').split('/').pop();
            if (currentUrl === linkUrl ||
                (currentUrl === '' && linkUrl === 'index.php') ||
                (currentUrl === 'tampil.php' && linkUrl === 'tampil.php') ||
                (currentUrl === 'tambah.php' && linkUrl === 'tampil.php') ||
                (currentUrl === 'kriteria.php' && linkUrl === 'kriteria.php') ||
                (currentUrl === 'edit.php' && linkUrl === 'tampil.php')) {
                $(this).addClass('active');
            }
        });
    });
</script>