<?php
session_start();
if (isset($_SESSION['username'])) {
?>

	<?php
	//memasukkan file config.php
	include('../koneksi/koneksi.php');
	?>
	<!DOCTYPE html>
	<html>

	<head>
		<title>Tampilan Skema Perhitungan</title>
		<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
		<link rel="icon" href="../img/logo.png">
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="../css/bootstrap.min.css">
		<!-- animate CSS -->
		<link rel="stylesheet" href="../css/animate.css">
		<!-- owl carousel CSS -->
		<link rel="stylesheet" href="../css/owl.carousel.min.css">
		<!-- themify CSS -->
		<link rel="stylesheet" href="../css/themify-icons.css">
		<!-- flaticon CSS -->
		<link rel="stylesheet" href="../css/flaticon.css">
		<!-- font awesome CSS -->
		<link rel="stylesheet" href="../css/magnific-popup.css">
		<!-- swiper CSS -->
		<link rel="stylesheet" href="../css/slick.css">
		<!-- style CSS -->
		<link rel="stylesheet" href="../css/style.css">
	</head>

	<body>
		<!--::header part start::-->
		<header class="main_menu home_menu">
			<div class="container">
				<div class="row align-items-center">
					<div class="col-lg-12">
						<nav class="navbar navbar-expand-lg navbar-light">
							<a class="navbar-brand" href="../index.php"> <img src="../img/logo.png" alt="logo" width="100" height="100"> </a>
							<button class="navbar-toggler" type="button" data-toggle="collapse"
								data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
								aria-expanded="false" aria-label="Toggle navigation">
								<span class="navbar-toggler-icon"></span>
							</button>

							<div class="collapse navbar-collapse main-menu-item justify-content-end"
								id="navbarSupportedContent">
								<ul class="navbar-nav align-items-center">
									<li class="nav-item active">
										<a class="nav-link" href="../index.php">Beranda</a>
									</li>
									<li class="nav-item active">
										<a class="nav-link" href="../crud/tampil/tampil.php">Data Alternatif</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="../hitung/hitung.php">Perhitungan ARAS & PIPRECIA-S</a>
									</li>
									<li class="nav-item">
										<a class="nav-link" href="../logout/logout.php">Logout</a>
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
			<h2>KONVERSI DATA ALTERNATIF</h2>
			<hr>
			<table class="table table-striped table-hover table-sm table-bordered">
				<thead class="thead-dark">
					<tr>
						<th>NO</th>
						<th>ALTERNATIF</th>
						<th>KRITERIA (C1)</th>
						<th>KRITERIA (C2)</th>
						<th>KRITERIA (C3)</th>
						<th>KRITERIA (C4)</th>
					</tr>
				</thead>
				<tbody>
					<?php
					//query ke database SELECT tabel mahasiswa urut berdasarkan id yang paling besar
					$sql = mysqli_query($koneksi, "SELECT * FROM data_konversi") or die(mysqli_error($koneksi));
					//jika query diatas menghasilkan nilai > 0 maka menjalankan script di bawah if...
					if (mysqli_num_rows($sql) > 0) {
						//membuat variabel $no untuk menyimpan nomor urut
						$no = 1;
						//melakukan perulangan while dengan dari dari query $sql
						while ($data = mysqli_fetch_assoc($sql)) {
							//menampilkan data perulangan
							echo '
                        <tr>
                            <td>' . $no . '</td>
                            <td>' . $data['alternatif'] . '</td>
                            <td>' . $data['tinggi_badan'] . '</td>
                            <td>' . $data['berat_badan'] . '</td>
                            <td>' . $data['berpenampilan_menarik'] . '</td>
                            <td>' . $data['menguasai_panggung'] . '</td>
                        </tr>
                        ';
							$no++;
						}
						//jika query menghasilkan nilai 0
					} else {
						echo '
                    <tr>
                        <td colspan="6">Tidak ada data.</td>
                    </tr>
                    ';
					}
					?>
				<tbody>
			</table>
		</div>


		<div class="container" style="margin-top:40px">
			<h2>MATRIK KEPUTUSAN (ARAS)</h2>
			<hr>
			<table class="table table-striped table-hover table-sm table-bordered">
				<thead class="thead-dark">
					<tr>
						<th>NO</th>
						<th>ALTERNATIF</th>
						<th>KRITERIA (C1)</th>
						<th>KRITERIA (C2)</th>
						<th>KRITERIA (C3)</th>
						<th>KRITERIA (C4)</th>
					</tr>
				</thead>
				<tbody>
					<?php
					//query ke database SELECT tabel mahasiswa urut berdasarkan id yang paling besar
					$sql = mysqli_query($koneksi, "SELECT * FROM data_matrik") or die(mysqli_error($koneksi));
					//jika query diatas menghasilkan nilai > 0 maka menjalankan script di bawah if...
					if (mysqli_num_rows($sql) > 0) {
						//membuat variabel $no untuk menyimpan nomor urut
						$no = 1;
						//melakukan perulangan while dengan dari dari query $sql
						while ($data = mysqli_fetch_assoc($sql)) {
							//menampilkan data perulangan
							echo '
                        <tr>
                            <td>' . $no . '</td>
                            <td>' . $data['alternatif'] . '</td>
                            <td>' . $data['tinggi_badan'] . '</td>
                            <td>' . $data['berat_badan'] . '</td>
                            <td>' . $data['berpenampilan_menarik'] . '</td>
                            <td>' . $data['menguasai_panggung'] . '</td>
                        </tr>
                        ';
							$no++;
						}
						//jika query menghasilkan nilai 0
					} else {
						echo '
                    <tr>
                        <td colspan="6">Tidak ada data.</td>
                    </tr>
                    ';
					}
					?>
				<tbody>
			</table>
		</div>

		<div class="container" style="margin-top:40px">
			<h2>MATRIKS HASIL NORMALISASI (ARAS)</h2>
			<hr>
			<table class="table table-striped table-hover table-sm table-bordered">
				<thead class="thead-dark">
					<tr>
						<th>NO</th>
						<th>ALTERNATIF</th>
						<th>KRITERIA (C1)</th>
						<th>KRITERIA (C2)</th>
						<th>KRITERIA (C3)</th>
						<th>KRITERIA (C4)</th>
					</tr>
				</thead>
				<tbody>
					<?php
					//query ke database SELECT tabel mahasiswa urut berdasarkan id yang paling besar
					$sql = mysqli_query($koneksi, "SELECT * FROM normalisasi") or die(mysqli_error($koneksi));
					//jika query diatas menghasilkan nilai > 0 maka menjalankan script di bawah if...
					if (mysqli_num_rows($sql) > 0) {
						//membuat variabel $no untuk menyimpan nomor urut
						$no = 1;
						//melakukan perulangan while dengan dari dari query $sql
						while ($data = mysqli_fetch_assoc($sql)) {
							//menampilkan data perulangan
							echo '
                        <tr>
                            <td>' . $no . '</td>
                            <td>' . $data['alternatif'] . '</td>
                            <td>' . $data['tinggi_badan'] . '</td>
                            <td>' . $data['berat_badan'] . '</td>
                            <td>' . $data['berpenampilan_menarik'] . '</td>
                            <td>' . $data['menguasai_panggung'] . '</td>
                        </tr>
                        ';
							$no++;
						}
						//jika query menghasilkan nilai 0
					} else {
						echo '
                    <tr>
                        <td colspan="6">Tidak ada data.</td>
                    </tr>
                    ';
					}
					?>
				<tbody>
			</table>
		</div>


		<div class="container" style="margin-top:40px">
			<h2>MATRIKS HASIL NORMALISASI TERBOBOT (ARAS)</h2>
			<hr>
			<table class="table table-striped table-hover table-sm table-bordered">
				<thead class="thead-dark">
					<tr>
						<th>NO</th>
						<th>ALTERNATIF</th>
						<th>KRITERIA (C1)</th>
						<th>KRITERIA (C2)</th>
						<th>KRITERIA (C3)</th>
						<th>KRITERIA (C4)</th>
					</tr>
				</thead>
				<tbody>
					<?php
					//query ke database SELECT tabel mahasiswa urut berdasarkan id yang paling besar
					$sql = mysqli_query($koneksi, "SELECT * FROM normalisasi_terbobot") or die(mysqli_error($koneksi));
					//jika query diatas menghasilkan nilai > 0 maka menjalankan script di bawah if...
					if (mysqli_num_rows($sql) > 0) {
						//membuat variabel $no untuk menyimpan nomor urut
						$no = 1;
						//melakukan perulangan while dengan dari dari query $sql
						while ($data = mysqli_fetch_assoc($sql)) {
							//menampilkan data perulangan
							echo '
                        <tr>
                            <td>' . $no . '</td>
                            <td>' . $data['alternatif'] . '</td>
                            <td>' . $data['tinggi_badan'] . '</td>
                            <td>' . $data['berat_badan'] . '</td>
                            <td>' . $data['berpenampilan_menarik'] . '</td>
                            <td>' . $data['menguasai_panggung'] . '</td>
                        </tr>
                        ';
							$no++;
						}
						//jika query menghasilkan nilai 0
					} else {
						echo '
                    <tr>
                        <td colspan="6">Tidak ada data.</td>
                    </tr>
                    ';
					}
					?>
				<tbody>
			</table>
		</div>

		<div class="container" style="margin-top:40px">
			<h2>NILAI DARI FUNGSI OPTIMUM (ARAS)</h2>
			<hr>
			<table class="table table-striped table-hover table-sm table-bordered">
				<thead class="thead-dark">
					<tr>
						<th>NO</th>
						<th>ALTERNATIF</th>
						<th>Nilai Optimum</th>
					</tr>
				</thead>
				<tbody>
					<?php
					//query ke database SELECT tabel mahasiswa urut berdasarkan id yang paling besar
					$sql = mysqli_query($koneksi, "SELECT * FROM hasil") or die(mysqli_error($koneksi));
					//jika query diatas menghasilkan nilai > 0 maka menjalankan script di bawah if...
					if (mysqli_num_rows($sql) > 0) {
						//membuat variabel $no untuk menyimpan nomor urut
						$no = 1;
						//melakukan perulangan while dengan dari dari query $sql
						while ($data = mysqli_fetch_assoc($sql)) {
							//menampilkan data perulangan
							echo '
                        <tr>
                            <td>' . $no . '</td>
                            <td>' . $data['alternatif'] . '</td>
                            <td>' . $data['nilai_optimum'] . '</td>
                        </tr>
                        ';
							$no++;
						}
						//jika query menghasilkan nilai 0
					} else {
						echo '
                    <tr>
                        <td colspan="6">Tidak ada data.</td>
                    </tr>
                    ';
					}
					?>
				<tbody>
			</table>
		</div>

		<div class="container" style="margin-top:40px">
			<h2>Matriks Hasil (ARAS)</h2>
			<hr>
			<!-- Tampilan Hasil -->
			<table class="table table-striped table-hover table-sm table-bordered">
				<thead class="thead-dark">
					<tr>
						<th>NO</th>
						<th>ALTERNATIF</th>
						<th>NILAI</th>
					</tr>
				</thead>
				<tbody>
					<?php
					//query ke database SELECT tabel mahasiswa urut berdasarkan id yang paling besar
					$sql = mysqli_query($koneksi, "SELECT * FROM hasil2") or die(mysqli_error($koneksi));
					//jika query diatas menghasilkan nilai > 0 maka menjalankan script di bawah if...
					if (mysqli_num_rows($sql) > 0) {
						//membuat variabel $no untuk menyimpan nomor urut
						$no = 1;
						//melakukan perulangan while dengan dari dari query $sql
						while ($data = mysqli_fetch_assoc($sql)) {
							//menampilkan data perulangan
							echo '
                        <tr>
                            <td>' . $no . '</td>
                            <td>' . $data['alternatif'] . '</td>
                            <td>' . $data['nilai_akhir'] . '</td>
                        </tr>
                        ';
							$no++;
						}
						//jika query menghasilkan nilai 0
					} else {
						echo '
                    <tr>
                        <td colspan="6">Tidak ada data.</td>
                    </tr>
                    ';
					}
					?>
				<tbody>
			</table>
		</div>

		<!-- PIPRECIA-S Method Tables -->
		<div class="container" style="margin-top:40px">
			<h2>MATRIKS NORMALISASI (PIPRECIA-S)</h2>
			<hr>
			<table class="table table-striped table-hover table-sm table-bordered">
				<thead class="thead-dark">
					<tr>
						<th>NO</th>
						<th>ALTERNATIF</th>
						<th>KRITERIA (C1)</th>
						<th>KRITERIA (C2)</th>
						<th>KRITERIA (C3)</th>
						<th>KRITERIA (C4)</th>
					</tr>
				</thead>
				<tbody>
					<?php
					//query ke database SELECT tabel mahasiswa urut berdasarkan id yang paling besar
					$sql = mysqli_query($koneksi, "SELECT * FROM normalisasi_piprecia") or die(mysqli_error($koneksi));
					//jika query diatas menghasilkan nilai > 0 maka menjalankan script di bawah if...
					if (mysqli_num_rows($sql) > 0) {
						//membuat variabel $no untuk menyimpan nomor urut
						$no = 1;
						//melakukan perulangan while dengan dari dari query $sql
						while ($data = mysqli_fetch_assoc($sql)) {
							//menampilkan data perulangan
							echo '
                        <tr>
                            <td>' . $no . '</td>
                            <td>' . $data['alternatif'] . '</td>
                            <td>' . $data['tinggi_badan'] . '</td>
                            <td>' . $data['berat_badan'] . '</td>
                            <td>' . $data['berpenampilan_menarik'] . '</td>
                            <td>' . $data['menguasai_panggung'] . '</td>
                        </tr>
                        ';
							$no++;
						}
						//jika query menghasilkan nilai 0
					} else {
						echo '
                    <tr>
                        <td colspan="6">Tidak ada data.</td>
                    </tr>
                    ';
					}
					?>
				<tbody>
			</table>
		</div>

		<div class="container" style="margin-top:40px">
			<h2>HASIL PERANGKINGAN (PIPRECIA-S)</h2>
			<hr>
			<table class="table table-striped table-hover table-sm table-bordered">
				<thead class="thead-dark">
					<tr>
						<th>NO</th>
						<th>ALTERNATIF</th>
						<th>NILAI</th>
					</tr>
				</thead>
				<tbody>
					<?php
					//query ke database SELECT tabel mahasiswa urut berdasarkan id yang paling besar
					$sql = mysqli_query($koneksi, "SELECT * FROM hasil_piprecia ORDER BY nilai_akhir DESC") or die(mysqli_error($koneksi));
					//jika query diatas menghasilkan nilai > 0 maka menjalankan script di bawah if...
					if (mysqli_num_rows($sql) > 0) {
						//membuat variabel $no untuk menyimpan nomor urut
						$no = 1;
						//melakukan perulangan while dengan dari dari query $sql
						while ($data = mysqli_fetch_assoc($sql)) {
							//menampilkan data perulangan
							echo '
                        <tr>
                            <td>' . $no . '</td>
                            <td>' . $data['alternatif'] . '</td>
                            <td>' . $data['nilai_akhir'] . '</td>
                        </tr>
                        ';
							$no++;
						}
						//jika query menghasilkan nilai 0
					} else {
						echo '
                    <tr>
                        <td colspan="6">Tidak ada data.</td>
                    </tr>
                    ';
					}
					?>
				<tbody>
			</table>
			<p align="center">
				<a href="../hitung/hitung.php" class="btn btn-warning">Kembali ke Perankingan</a>
			</p>
			<hr>
		</div>

	</body>

	</html>

<?php
} else {
	echo "<script language=\"javascript\">alert(\"Silahkan Login Terlebih Dahulu\");document.location.href='../login/index.php';</script>";
}
?>