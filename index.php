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
    <title>PIPARAS-DSS (PIPRECIA-ARAS Decision Support System)</title>
    <link rel="icon" href="img/logo.png">

    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        :root {
            --primary: #4e73df;
            --primary-dark: #2e59d9;
            --secondary: #1cc88a;
            --dark: #5a5c69;
            --light: #f8f9fc;
            --gray: #dddfeb;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
            color: #5a5c69;
            overflow-x: hidden;
        }

        /* Navbar Custom */
        .navbar {
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            background: white;
            padding: 0.5rem 0;
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 0.2rem 0;
            box-shadow: 0 0.3rem 1rem rgba(0, 0, 0, 0.1);
        }

        .navbar-brand img {
            transition: all 0.3s ease;
            height: 60px;
        }

        .navbar-brand:hover img {
            transform: scale(1.05) rotate(-5deg);
        }

        .nav-link {
            font-weight: 600;
            padding: 0.5rem 1rem;
            position: relative;
            color: var(--dark);
            transition: all 0.3s ease;
        }

        .nav-link:hover,
        .nav-item.active .nav-link {
            color: var(--primary);
            transform: translateY(-2px);
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
            background: linear-gradient(135deg, #f8f9fc 0%, #e9ecef 100%);
            padding: 6rem 0 5rem;
            margin-top: 80px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            bottom: -50px;
            right: -50px;
            width: 200px;
            height: 200px;
            background-color: var(--primary);
            opacity: 0.1;
            border-radius: 50%;
            z-index: 1;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            top: -100px;
            left: -100px;
            width: 300px;
            height: 300px;
            background-color: var(--secondary);
            opacity: 0.1;
            border-radius: 50%;
            z-index: 1;
        }

        .hero-title {
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 1.5rem;
            line-height: 1.3;
            position: relative;
            z-index: 2;
        }

        .hero-title span {
            color: var(--primary);
            position: relative;
        }

        .hero-title span::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 100%;
            height: 10px;
            background-color: rgba(78, 115, 223, 0.2);
            z-index: -1;
            transform: skew(-15deg);
        }

        .hero-description {
            font-size: 1.2rem;
            line-height: 1.8;
            color: #6c757d;
            margin-bottom: 2.5rem;
            position: relative;
            z-index: 2;
        }

        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            padding: 0.8rem 2rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.3);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(78, 115, 223, 0.4);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
            z-index: -1;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
            font-weight: 600;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.1);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(78, 115, 223, 0.2);
        }

        .hero-img-container {
            position: relative;
            z-index: 2;
        }

        .hero-img {
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            max-height: 400px;
            object-fit: cover;
            transform: perspective(1000px) rotateY(-10deg);
            transition: all 0.5s ease;
            border: 10px solid white;
        }

        .hero-img:hover {
            transform: perspective(1000px) rotateY(0deg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .hero-img-container::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100%;
            height: 100%;
            border: 2px dashed var(--primary);
            border-radius: 15px;
            z-index: -1;
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Features Section */
        .features-section {
            padding: 5rem 0;
            background-color: white;
        }

        .section-title {
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 3rem;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50px;
            height: 4px;
            background-color: var(--primary);
            border-radius: 2px;
        }

        .feature-card {
            background-color: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border-left: 4px solid var(--primary);
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1.5rem;
        }

        .feature-title {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1rem;
        }

        .feature-text {
            color: #6c757d;
            line-height: 1.7;
        }

        /* Footer */
        .footer {
            background-color: white;
            padding: 2rem 0;
            box-shadow: 0 -0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
            position: relative;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
        }

        .footer-text {
            font-weight: 600;
            margin: 0;
        }

        /* Animations */
        .animate-delay-1 {
            animation-delay: 0.2s;
        }

        .animate-delay-2 {
            animation-delay: 0.4s;
        }

        .animate-delay-3 {
            animation-delay: 0.6s;
        }

        @media (max-width: 768px) {
            .hero-section {
                text-align: center;
                padding: 4rem 0;
            }

            .hero-img {
                margin-top: 3rem;
                max-height: 300px;
                transform: perspective(1000px) rotateY(0deg);
            }

            .hero-img:hover {
                transform: perspective(1000px) rotateY(0deg);
            }

            .hero-img-container::before {
                display: none;
            }

            .btn-group {
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top" id="mainNav">
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
                    <h1 class="hero-title animate__animated animate__fadeInDown">Sistem Pendukung Keputusan <span>Pemilihan Pelayanan Expedisi Terbaik</span></h1>
                    <p class="hero-description animate__animated animate__fadeIn animate__delay-1s">
                        Menggunakan metode ARAS dan PIPRECIA-S untuk membantu dalam memilih jasa pengiriman terbaik
                        untuk memudahkan dan mempercepat proses pengiriman barang.
                    </p>
                    <div class="d-flex flex-wrap animate__animated animate__fadeInUp animate__delay-2s">
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
                    <div class="hero-img-container animate__animated animate__fadeInRight animate__delay-1s">
                        <img src="img/expedition.jpg" alt="Fashion Show" class="img-fluid hero-img">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <h2 class="section-title text-center animate__animated animate__fadeIn">Fitur Unggulan Sistem</h2>
            <div class="row">
                <div class="col-md-4 animate__animated animate__fadeInUp animate-delay-1">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="feature-title">Analisis Multi-Kriteria</h3>
                        <p class="feature-text">Menggunakan metode ARAS dan PIPRECIA-S untuk analisis komprehensif dengan berbagai parameter penilaian.</p>
                    </div>
                </div>
                <div class="col-md-4 animate__animated animate__fadeInUp animate-delay-2">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-balance-scale"></i>
                        </div>
                        <h3 class="feature-title">Penilaian Objektif</h3>
                        <p class="feature-text">Sistem yang adil dan transparan tanpa bias dalam proses seleksi model fashion show.</p>
                    </div>
                </div>
                <div class="col-md-4 animate__animated animate__fadeInUp animate-delay-3">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <h3 class="feature-title">Proses Cepat</h3>
                        <p class="feature-text">Pengolahan data yang efisien dengan hasil yang dapat dipercaya dalam waktu singkat.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container text-center">
            <p class="footer-text">© 2025 PIPARAS-DSS | (PIPRECIA-ARAS Decision Support System)</p>
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

            // Navbar scroll effect
            $(window).scroll(function() {
                if ($(this).scrollTop() > 50) {
                    $('#mainNav').addClass('scrolled');
                } else {
                    $('#mainNav').removeClass('scrolled');
                }
            });

            // Animation on scroll
            function animateOnScroll() {
                $('.animate__animated').each(function() {
                    var position = $(this).offset().top;
                    var scroll = $(window).scrollTop();
                    var windowHeight = $(window).height();

                    if (scroll + windowHeight > position) {
                        var animationClass = $(this).attr('class').split('animate__animated ')[1].split(' ')[0];
                        $(this).addClass('animate__' + animationClass);
                    }
                });
            }

            // Run once on page load
            animateOnScroll();

            // Run on scroll
            $(window).scroll(function() {
                animateOnScroll();
            });
        });
    </script>
</body>

</html>