<?php
session_start();
if (isset($_SESSION['username'])) {
?>

	<?php include('../../koneksi/koneksi.php'); ?>
	<!DOCTYPE html>
	<html>

	<head>
		<title>FORM UBAH DATA</title>
		<link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
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
										<a class="nav-link" href="../..//hitung/hitung.php">Perhitungan ARAS & PIPRECIA-S</a>
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
			<h2>Edit Mahasiswa</h2>

			<hr>

			<?php
			//jika sudah mendapatkan parameter GET id dari URL
			if (isset($_GET['id'])) {
				//membuat variabel $id untuk menyimpan id dari GET id di URL
				$id = $_GET['id'];

				//query ke database SELECT tabel mahasiswa berdasarkan id = $id
				$select = mysqli_query($koneksi, "SELECT * FROM data_primer WHERE id='$id'") or die(mysqli_error($koneksi));

				//jika hasil query = 0 maka muncul pesan error
				if (mysqli_num_rows($select) == 0) {
					echo '<div class="alert alert-warning">ID tidak Baik dalam database.</div>';
					exit();
					//jika hasil query > 0
				} else {
					//membuat variabel $data dan menyimpan data row dari query
					$data = mysqli_fetch_assoc($select);
				}
			}
			?>

			<?php
			//jika tombol simpan di tekan/klik
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

				if ($berpenampilan_menarik == 'Tidak Menarilk') {
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

				$sql = mysqli_query($koneksi, "UPDATE data_primer SET alternatif='$alternatif', tinggi_badan='$tinggi_badan', berat_badan='$berat_badan', berpenampilan_menarik='$berpenampilan_menarik', menguasai_panggung='$menguasai_panggung' WHERE id='$id'") or die(mysqli_error($koneksi));

				$sql = mysqli_query($koneksi, "UPDATE data_konversi SET alternatif='$alternatif', tinggi_badan='$tinggi_badann', berat_badan='$berat_badann', berpenampilan_menarik='$berpenampilan_menarikn', menguasai_panggung='$menguasai_panggungn' WHERE id='$id'") or die(mysqli_error($koneksi));

				if ($sql) {
					echo '<script>alert("Berhasil menyimpan data."); document.location="../../crud/tampil/tampil.php?id=' . $id . '";</script>';
				} else {
					echo '<div class="alert alert-warning">Gagal melakukan proses edit data.</div>';
				}
			}
			?>

			<form action="edit.php?id=<?php echo $id; ?>" method="post">
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">ALTERNATIF</label>
					<div class="col-sm-10">
						<input type="text" name="alternatif" class="form-control" value="<?php echo $data['alternatif']; ?>" required>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">TINGGI BADAN (cm)</label>
					<div class="col-sm-10">
						<input type="text" name="tinggi_badan" class="form-control" value="<?php echo $data['tinggi_badan']; ?>" required>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">BERAT BADAN (kg)</label>
					<div class="col-sm-10">
						<input type="text" name="berat_badan" class="form-control" value="<?php echo $data['berat_badan']; ?>" required>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">BERPENAMPILAN MENARIK</label>
					<div class="col-sm-10">
						<select name="berpenampilan_menarik" class="form-control" required>
							<option value="">-PILIH-</option>
							<option value="Tidak Menarik" <?php if ($data['berpenampilan_menarik'] == 'Tidak Menarik') {
																echo 'selected';
															} ?>>Tidak Menarik</option>
							<option value="Kurang Menarik" <?php if ($data['berpenampilan_menarik'] == 'Kurang Menarik') {
																echo 'selected';
															} ?>>Kurang Menarik</option>
							<option value="Cukup" <?php if ($data['berpenampilan_menarik'] == 'Cukup') {
														echo 'selected';
													} ?>>Cukup</option>
							<option value="Menarik" <?php if ($data['berpenampilan_menarik'] == 'Menarik') {
														echo 'selected';
													} ?>>Menarik</option>
							<option value="Sangat Menarik" <?php if ($data['berpenampilan_menarik'] == 'Sangat Menarik') {
																echo 'selected';
															} ?>>Sangat Menarik</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">MENGUASAI PANGGUNG</label>
					<div class="col-sm-10">
						<select name="menguasai_panggung" class="form-control" required>
							<option value="">-PILIH-</option>
							<option value="Tidak Baik" <?php if ($data['menguasai_panggung'] == 'Tidak Baik') {
															echo 'selected';
														} ?>>Tidak Baik</option>
							<option value="Kurang Baik" <?php if ($data['menguasai_panggung'] == 'Kurang Baik') {
															echo 'selected';
														} ?>>Kurang Baik</option>
							<option value="Cukup" <?php if ($data['menguasai_panggung'] == 'Cukup') {
														echo 'selected';
													} ?>>Cukup</option>
							<option value="Baik" <?php if ($data['menguasai_panggung'] == 'Baik') {
														echo 'selected';
													} ?>>Baik</option>
							<option value="Sangat Baik" <?php if ($data['menguasai_panggung'] == 'Sangat Baik') {
															echo 'selected';
														} ?>>Sangat Baik</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">&nbsp;</label>
					<div class="col-sm-10">
						<input type="submit" name="submit" class="btn btn-primary" value="UBAH">
						<a href="../../crud/tampil/tampil.php" class="btn btn-warning">KEMBALI</a>
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