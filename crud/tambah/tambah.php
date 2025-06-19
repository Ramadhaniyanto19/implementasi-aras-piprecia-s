<?php
session_start();
if (isset($_SESSION['username'])) {
?>

	<?php include('../../koneksi/koneksi.php'); ?>
	<!DOCTYPE html>
	<html>

	<head>
		<title>FORM INPUT DATA</title>
		<link rel="icon" href="../../img/logo.png">
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="../../css/bootstrap.min.css">
		<!-- animate CSS -->
		<link rel="stylesheet" href="../../css/animate.css">
		<!-- owl carousel CSS -->
		<link rel="stylesheet" href="../../css/owl.carousel.min.css">
		<!-- themify CSS -->
		<link rel="stylesheet" href="../../css/themify-icons.css">
		<!-- flaticon CSS -->
		<link rel="stylesheet" href="../../css/flaticon.css">
		<!-- font awesome CSS -->
		<link rel="stylesheet" href="../../css/magnific-popup.css">
		<!-- swiper CSS -->
		<link rel="stylesheet" href="../../css/slick.css">
		<!-- style CSS -->
		<link rel="stylesheet" href="../../css/style.css">
	</head>

	<body>

		<!--::header part start::-->
		<header class="main_menu home_menu">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-lg-12">
						<nav class="navbar navbar-expand-lg navbar-light">
							<a class="navbar-brand" href="../../index.php"> <img src="../../img/logo.png" alt="logo" width="100" height="100"> </a>
							<button class="navbar-toggler" type="button" data-toggle="collapse"
								data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
								aria-expanded="false" aria-label="Toggle navigation">
								<span class="navbar-toggler-icon"></span>
							</button>

							<div class="collapse navbar-collapse main-menu-item justify-content-end"
								id="navbarSupportedContent">
								<ul class="navbar-nav align-items-center">
									<li class="nav-item active">
										<a class="nav-link" href="../../index.php">Beranda</a>
									</li>
									<li class="nav-item active">
										<a class="nav-link" href="../tampil/tampil.php">Data Alternatif</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="../../hitung/hitung.php">Perhitungan ARAS & PIPRECIA-S</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="../../logout/logout.php">Logout</a>
									</li>
								</ul>
							</div>
						</nav>
					</div>
				</div>
			</div>
		</header>
		<!-- Header part end-->

		<div class="container" style="margin-top:200px">
			<h2>Input Data Alternatif &ensp; | &ensp;
				<a href="../tampil/tampil.php" class="btn btn-warning">BATAL</a>
			</h2>

			<hr>

			<?php
			if (isset($_POST['submit'])) {
				$alternatif = $_POST['alternatif'];
				$tinggi_badan = $_POST['tinggi_badan'];
				$berat_badan = $_POST['berat_badan'];
				$berpenampilan_menarik = $_POST['berpenampilan_menarik'];
				$menguasai_panggung = $_POST['menguasai_panggung'];

				if ($tinggi_badan <= 150) {
					$tinggi_badann = 1;
				} else if ($tinggi_badan < 160) {
					$tinggi_badann = 2;
				} else if ($tinggi_badan < 170) {
					$tinggi_badann = 3;
				} else if ($tinggi_badan < 180) {
					$tinggi_badann = 4;
				} else {
					$tinggi_badann = 5;
				}

				if ($berat_badan < 45) {
					$berat_badann = 1;
				} else if ($berat_badan <= 54) {
					$berat_badann = 2;
				} else if ($berat_badan <= 64) {
					$berat_badann = 3;
				} else if ($berat_badan <= 74) {
					$berat_badann = 4;
				} else if ($berat_badan <= 84) {
					$berat_badann = 5;
				} else {
					$berat_badann = 1;
				}

				if ($berpenampilan_menarik == 'Tidak Menarik') {
					$berpenampilan_menarikn = 1;
				} else if ($berpenampilan_menarik == 'Kurang Menarik') {
					$berpenampilan_menarikn = 2;
				} else if ($berpenampilan_menarik == 'Cukup') {
					$berpenampilan_menarikn = 3;
				} else if ($berpenampilan_menarik == 'Menarik') {
					$berpenampilan_menarikn = 4;
				} else if ($berpenampilan_menarik == 'Sangat Menarik') {
					$berpenampilan_menarikn = 5;
				}

				if ($menguasai_panggung == 'Tidak Baik') {
					$menguasai_panggungn = 1;
				} else if ($menguasai_panggung == 'Kurang Baik') {
					$menguasai_panggungn = 2;
				} else if ($menguasai_panggung == 'Cukup') {
					$menguasai_panggungn = 3;
				} else if ($menguasai_panggung == 'Baik') {
					$menguasai_panggungn = 4;
				} else if ($menguasai_panggung == 'Sangat Baik') {
					$menguasai_panggungn = 5;
				}

				$sql = mysqli_query($koneksi, "INSERT INTO data_primer(id, alternatif, tinggi_badan, berat_badan, berpenampilan_menarik, menguasai_panggung) VALUES('', '$alternatif', '$tinggi_badan', '$berat_badan','$berpenampilan_menarik','$menguasai_panggung')") or die(mysqli_error($koneksi));

				$sql = mysqli_query($koneksi, "INSERT INTO data_konversi(id, alternatif, tinggi_badan, berat_badan, berpenampilan_menarik, menguasai_panggung) VALUES('', '$alternatif', '$tinggi_badann', '$berat_badann','$berpenampilan_menarikn','$menguasai_panggungn')") or die(mysqli_error($koneksi));

				if ($sql) {
					echo '<script>alert("Berhasil menambahkan data."); document.location="../tampil/tampil.php";</script>';
				} else {
					echo '<div class="alert alert-warning">Gagal melakukan proses tambah data.</div>';
				}
			} else {
			}

			?>

			<form action="tambah.php" method="post">
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">ALTERNATIF</label>
					<div class="col-sm-10">
						<input type="text" name="alternatif" class="form-control" required>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">TINGGI BADAN (cm)</label>
					<div class="col-sm-10">
						<input type="text" name="tinggi_badan" class="form-control" required>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">BERAT BADAN (kg)</label>
					<div class="col-sm-10">
						<input type="text" name="berat_badan" class="form-control" required>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">BERPENAMPILAN MENARIK</label>
					<div class="col-sm-10">
						<select name="berpenampilan_menarik" class="form-control" required>
							<option value="">-PILIH-</option>
							<option value="Tidak Menarik">Tidak Menarik</option>
							<option value="Kurang Menarik">Kurang Menarik</option>
							<option value="Cukup">Cukup</option>
							<option value="Menarik">Menarik</option>
							<option value="Sangat Menarik">Sangat Menarik</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Menguasai Panggung</label>
					<div class="col-sm-10">
						<select name="menguasai_panggung" class="form-control" required>
							<option value="">-PILIH-</option>
							<option value="Tidak Baik">Tidak Baik</option>
							<option value="Kurang Baik">Kurang Baik</option>
							<option value="Cukup">Cukup</option>
							<option value="Baik">Baik</option>
							<option value="Sangat Baik">Sangat Baik</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">&nbsp;</label>
					<div class="col-sm-10">
						<input type="submit" name="submit" class="btn btn-primary" value="SIMPAN">
					</div>
				</div>
			</form>
		</div>
	</body>

	</html>

<?php
} else {
	echo "<script language=\"javascript\">alert(\"Silahkan Login Terlebih Dahulu\");document.location.href='../login/index.php';</script>";
}
?>