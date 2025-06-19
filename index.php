<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SPK-ARAS</title>
    <link rel="icon" href="img/logo.png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- animate CSS -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- owl carousel CSS -->
    <link rel="stylesheet" href="css/owl.carousel.min.css">
    <!-- themify CSS -->
    <link rel="stylesheet" href="css/themify-icons.css">
    <!-- flaticon CSS -->
    <link rel="stylesheet" href="css/flaticon.css">
    <!-- font awesome CSS -->
    <link rel="stylesheet" href="css/magnific-popup.css">
    <!-- swiper CSS -->
    <link rel="stylesheet" href="css/slick.css">
    <!-- style CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">

    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .hero-section {
            padding: 80px 0;
            background-color: #f8f9fa;
        }

        .hero-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .hero-text {
            flex: 1;
            padding-right: 30px;
        }

        .hero-image {
            flex: 1;
            text-align: center;
        }

        .hero-image img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .service-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: #343a40;
        }

        .service-description {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #6c757d;
            margin-bottom: 30px;
        }

        @media (max-width: 768px) {
            .hero-content {
                flex-direction: column;
            }

            .hero-text {
                padding-right: 0;
                margin-bottom: 30px;
            }
        }
    </style>
</head>

<body class="overflow-hidden">
    <!--::header part start::-->
    <header class="main_menu home_menu">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg navbar-light">
                        <a class="navbar-brand" href="index.php">
                            <img src="img/logo.png" alt="logo" width="100" height="100">
                        </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                        <div class="collapse navbar-collapse main-menu-item justify-content-end"
                            id="navbarSupportedContent">
                            <ul class="navbar-nav align-items-center">
                                <li class="nav-item active">
                                    <a class="nav-link" href="index.php">Beranda</a>
                                </li>
                                <?php
                                session_start();
                                if (isset($_SESSION['username'])) {
                                ?>
                                    <li class="nav-item active">
                                        <a class="nav-link" href="crud/tampil/tampil.php">Data Alternatif</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="hitung/hitung.php">Perhitungan ARAS & PIPRECIA-S</a>
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
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- Header part end-->

    <!-- Hero Section -->
    <section class="hero-section mb-5 pt-5">
        <div class="container min-vh-100" style="margin-top: 100px;">
            <div class="hero-content pt-5 mt-5">
                <div class="hero-text">
                    <h1 class="service-title">Layanan On-Demand</h1>
                    <p class="service-description">
                        Sistem Pendukung Keputusan (SPK) untuk menilai kelayakan model fashion show menggunakan metode ARAS dan PIPRECIA-S.
                        Layanan kami membantu BOESA Management dalam seleksi model terbaik untuk berbagai kontes fashion show.
                    </p>
                    <div class="d-flex">
                        <?php if (!isset($_SESSION['username'])) { ?>
                            <a href="login/index.php" class="btn btn-primary mr-3">Login</a>
                            <a href="login/index.php" class="btn btn-outline-secondary">Login Disini <i class="fas fa-arrow-right"></i> </a>
                        <?php } ?>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="img/expedition.jpg" width="300" height="600" alt="Expedition Fashion Show" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="copyright" style="margin-top:-350px;margin-bottom:50px;">
            <h6 style="text-align:center;font-family:arial;font-weight: bold;">© 2025 E-Assessment</h6>
        </div>
    </footer>

    <!-- jquery plugins here-->
    <!-- jquery -->
    <script src="js/jquery-1.12.1.min.js"></script>
    <!-- popper js -->
    <script src="js/popper.min.js"></script>
    <!-- bootstrap js -->
    <script src="js/bootstrap.min.js"></script>
    <!-- easing js -->
    <script src="js/jquery.magnific-popup.js"></script>
    <!-- swiper js -->
    <script src="js/swiper.min.js"></script>
    <!-- swiper js -->
    <script src="js/masonry.pkgd.js"></script>
    <!-- particles js -->
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.nice-select.min.js"></script>
    <!-- swiper js -->
    <script src="js/slick.min.js"></script>
    <script src="js/jquery.counterup.min.js"></script>
    <script src="js/waypoints.min.js"></script>
    <!-- custom js -->
    <script src="js/custom.js"></script>
</body>

</html>