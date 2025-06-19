<?php
// Pindahkan session_start ke paling atas file, sebelum DOCTYPE
session_start();
?>
<!doctype html>
<html lang="id">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SPK-ARAS | Sistem Pendukung Keputusan</title>
    <link rel="icon" href="img/logo.png">

    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        :root {
            --primary: #4e73df;
            --secondary: #1cc88a;
            --dark: #5a5c69;
            --light: #f8f9fc;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
            color: #5a5c69;
        }

        /* Navbar Custom */
        .navbar {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            background: white;
            padding: 0.5rem 0;
        }

        .navbar-brand img {
            transition: transform 0.3s;
            height: 60px;
        }

        .navbar-brand:hover img {
            transform: scale(1.05);
        }

        .nav-link {
            font-weight: 600;
            padding: 0.5rem 1rem;
            position: relative;
            color: var(--dark);
        }

        .nav-link:hover,
        .nav-item.active .nav-link {
            color: var(--primary);
        }

        .nav-link:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s;
        }

        .nav-link:hover:after,
        .nav-item.active .nav-link:after {
            width: 70%;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(to right, #f8f9fc 0%, #e9ecef 100%);
            padding: 5rem 0;
            margin-top: 80px;
        }

        .hero-title {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1.5rem;
        }

        .hero-description {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #6c757d;
            margin-bottom: 2rem;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            padding: 0.5rem 1.5rem;
            font-weight: 600;
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
            font-weight: 600;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary);
            color: white;
        }

        .hero-img {
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            max-height: 400px;
            object-fit: cover;
        }

        /* Footer */
        .footer {
            background-color: white;
            padding: 1.5rem 0;
            box-shadow: 0 -0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            margin-top: 3rem;
        }

        @media (max-width: 768px) {
            .hero-section {
                text-align: center;
                padding: 3rem 0;
            }

            .hero-img {
                margin-top: 2rem;
                max-height: 300px;
            }

            .btn-group {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="img/logo.png" alt="SPK-ARAS Logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>
                    <?php if (isset($_SESSION['username'])) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="crud/tampil/tampil.php">Data Alternatif</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="hitung/hitung.php">Perhitungan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout/logout.php">Logout</a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login/index.php">Login</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="hero-title">Sistem Pendukung Keputusan Pemilihan Model</h1>
                    <p class="hero-description">
                        Menggunakan metode ARAS dan PIPRECIA-S untuk membantu BOESA Management dalam seleksi model terbaik
                        untuk berbagai kontes fashion show dengan penilaian yang objektif dan terukur.
                    </p>
                    <div class="d-flex flex-wrap">
                        <?php if (!isset($_SESSION['username'])) { ?>
                            <a href="login/index.php" class="btn btn-primary mr-3 mb-3">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login Sekarang
                            </a>
                            <a href="login/index.php" class="btn btn-outline-primary mb-3">
                                Pelajari Lebih Lanjut <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="img/expedition.jpg" alt="Fashion Show" class="img-fluid hero-img">
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <p class="mb-0 font-weight-bold">© 2025 SPK-ARAS | Sistem Pendukung Keputusan</p>
        </div>
    </footer>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Add active class to current nav item
        $(document).ready(function() {
            var url = window.location.pathname;
            var filename = url.substring(url.lastIndexOf('/') + 1);

            $('.navbar-nav a').each(function() {
                if ($(this).attr('href') === filename) {
                    $(this).parent().addClass('active');
                }
            });
        });
    </script>
</body>

</html>