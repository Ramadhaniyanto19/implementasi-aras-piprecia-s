<?php
include "../koneksi/koneksi.php";

// Pindahkan session_start ke paling atas
if (isset($_POST['login'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];
	$cek = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM login WHERE username='$username' AND password='$password'"));
	$data = mysqli_fetch_array(mysqli_query($koneksi, "SELECT * FROM login WHERE username='$username' AND password='$password'"));

	if ($cek > 0) {
		session_start();
		$_SESSION['username'] = $data['username'];
		$_SESSION['nama'] = $data['nama'];
		header("Location: ../index.php");
		exit();
	} else {
		$error = true;
	}
}

session_start();
if (isset($_SESSION['username'])) {
	header("Location: ../index.php");
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Login - SPK Expedisi Terbaik</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="../img/logo.png">

	<!-- Bootstrap 4 -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

	<style>
		:root {
			--primary: #3498db;
			--secondary: #2ecc71;
			--dark: #34495e;
			--light: #ecf0f1;
		}

		body {
			font-family: 'Poppins', sans-serif;
			background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #7db9e8 100%);
			min-height: 100vh;
			display: flex;
			align-items: center;
		}

		.login-card {
			border-radius: 15px;
			overflow: hidden;
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
			transition: transform 0.3s;
			background: rgba(255, 255, 255, 0.95);
		}

		.login-card:hover {
			transform: translateY(-5px);
		}

		.card-header {
			background: linear-gradient(to right, #3498db, #2c3e50);
			color: white;
			text-align: center;
			padding: 1.5rem;
			position: relative;
		}

		.card-header:before {
			content: '';
			position: absolute;
			bottom: -15px;
			left: 50%;
			transform: translateX(-50%);
			width: 0;
			height: 0;
			border-left: 15px solid transparent;
			border-right: 15px solid transparent;
			border-top: 15px solid #2c3e50;
		}

		.logo-placeholder {
			width: 80px;
			height: 80px;
			margin: 0 auto 15px;
			background-color: white;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		}

		.logo-placeholder i {
			font-size: 2.5rem;
			color: #3498db;
		}

		.btn-login {
			background: linear-gradient(to right, #3498db, #2c3e50);
			border: none;
			padding: 10px;
			font-weight: 600;
			letter-spacing: 1px;
			transition: all 0.3s;
		}

		.btn-login:hover {
			background: linear-gradient(to right, #2980b9, #1a252f);
			transform: translateY(-2px);
		}

		.btn-cancel {
			background: linear-gradient(to right, #e74c3c, #c0392b);
			border: none;
			padding: 10px;
			font-weight: 600;
			letter-spacing: 1px;
			transition: all 0.3s;
		}

		.btn-cancel:hover {
			background: linear-gradient(to right, #c0392b, #a52714);
			transform: translateY(-2px);
		}

		.form-control {
			border-radius: 50px;
			padding: 15px 20px;
			margin-bottom: 20px;
			border: 1px solid #ddd;
			transition: all 0.3s;
		}

		.form-control:focus {
			border-color: var(--primary);
			box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
			transform: translateY(-2px);
		}

		.input-icon {
			position: relative;
		}

		.input-icon i {
			position: absolute;
			right: 20px;
			top: 15px;
			color: var(--primary);
		}

		.title-text {
			text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
		}
	</style>
</head>

<body>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6 col-lg-5">
				<div class="login-card">
					<div class="card-header">
						<div class="logo-placeholder">
							<i class="fas fa-truck"></i>
						</div>
						<h3 class="mb-0 title-text">SISTEM SPK</h3>
						<p class="mb-0">Penentuan Layanan Expedisi Terbaik</p>
					</div>
					<div class="card-body bg-white p-5">
						<?php if (isset($error)) { ?>
							<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<strong>Login gagal!</strong> Username atau password salah.
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
						<?php } ?>

						<form method="post">
							<div class="form-group input-icon">
								<input type="text" name="username" class="form-control" placeholder="Username" required>
								<i class="fas fa-user"></i>
							</div>

							<div class="form-group input-icon">
								<input type="password" name="password" class="form-control" placeholder="Password" required>
								<i class="fas fa-lock"></i>
							</div>

							<button type="submit" name="login" class="btn btn-login btn-block text-white mb-3">
								<i class="fas fa-sign-in-alt mr-2"></i> LOGIN
							</button>

							<a href="../index.php" class="btn btn-cancel btn-block text-white">
								<i class="fas fa-times mr-2"></i> BATAL
							</a>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- jQuery and Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>