<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIPARAS-DSS (PIPRECIA-ARAS Decision Support System)</title>

    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">


    <!-- Bootstrap 4 JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Chart.js for visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom CSS -->
    <style>
        /* Main layout */
        body {
            overflow-x: hidden;
        }

        /* Navbar styling */
        .main-navbar {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            height: 60px;
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
            padding: 0 15px;
        }

        .navbar-brand {
            font-weight: 700;
            color: white !important;
            display: flex;
            align-items: center;
        }

        .navbar-brand img {
            margin-right: 10px;
            height: 30px;
        }

        /* User dropdown */
        .user-dropdown {
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }

        .user-dropdown:hover {
            opacity: 0.8;
        }

        .user-name {
            margin-right: 10px;
            color: white;
        }

        .user-avatar {
            font-size: 1.8rem;
            color: white;
        }

        /* Sidebar compatibility */
        .main-content {
            margin-top: 60px;
            margin-left: 250px;
            /* Match sidebar width */
            padding: 20px;
            transition: all 0.3s;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .main-content {
                margin-left: 0;
            }

            .user-name {
                display: none;
            }
        }

        /* Dropdown menu */
        .dropdown-menu {
            min-width: 180px;
            border: none;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
        }

        .dropdown-item {
            padding: 8px 15px;
            transition: all 0.2s;
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #3498db;
        }

        .dropdown-divider {
            margin: 0.25rem 0;
        }
    </style>

    <?php if ($_SERVER['PHP_SELF'] == '/hitung.php') { ?>
        <link rel="icon" href="../../img/logo.png">
    <?php } else { ?>
        <link rel="icon" href="../../img/logo.png">
    <?php } ?>
</head>

<body>
    <!-- Fixed Top Navbar -->
    <nav class="main-navbar navbar navbar-expand navbar-dark">
        <button class="btn btn-link text-white d-lg-none" type="button" data-toggle="collapse" data-target="#sidebar">
            <i class="fas fa-bars"></i>
        </button>

        <a class="navbar-brand ml-2" href="#">
            PIPARAS-DSS
        </a>

        <div class="ml-auto">
            <div class="dropdown">
                <div class="user-dropdown" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="user-name"><?php echo $_SESSION['username']; ?></span>
                    <i class="fas fa-user-circle user-avatar"></i>
                </div>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#"><i class="fas fa-user"></i> Profile</a>
                    <a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Settings</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="../../logout/logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-3 d-none d-lg-block">
            </div>
            <?php include('sidebar.php'); ?>

            <!-- Main Content -->
            <main role="main" class="main-content col-9">
                <!-- Your page content goes here -->
                <div class="w-full">
                    <!-- Content will start below the fixed navbar -->