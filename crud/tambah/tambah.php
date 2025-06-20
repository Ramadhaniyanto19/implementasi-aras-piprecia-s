<?php
session_start();
if (isset($_SESSION['username'])) {
	include('../../koneksi/koneksi.php');
	include('../../includes/header.php'); // Assuming this includes your head section
?>

	<div class="container-fluid min-vh-100">
		<div class="row">
			<!-- Main Content -->
			<main class="col-md-9 ms-sm-auto col-lg-12 px-md-4 py-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<h1 class="h2">Input Data Alternatif <i class="fas fa-user-plus"></i></h1>
					<div class="btn-toolbar mb-2 mb-md-0">
						<a href="../tampil/tampil.php" class="btn btn-warning">
							<i class="fas fa-times"></i> Batal
						</a>
					</div>
				</div>

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

				<div class="card shadow-sm">
					<div class="card-body">
						<form action="tambah.php" method="post">
							<div class="row mb-3">
								<label class="col-sm-3 col-form-label">Alternatif</label>
								<div class="col-sm-9">
									<input type="text" name="alternatif" class="form-control" required>
								</div>
							</div>

							<div class="row mb-3">
								<label class="col-sm-3 col-form-label">Tinggi Badan (cm)</label>
								<div class="col-sm-9">
									<input type="number" name="tinggi_badan" class="form-control" required>
									<small class="text-muted">Contoh: 165</small>
								</div>
							</div>

							<div class="row mb-3">
								<label class="col-sm-3 col-form-label">Berat Badan (kg)</label>
								<div class="col-sm-9">
									<input type="number" name="berat_badan" class="form-control" required>
									<small class="text-muted">Contoh: 55</small>
								</div>
							</div>

							<div class="row mb-3">
								<label class="col-sm-3 col-form-label">Berpenampilan Menarik</label>
								<div class="col-sm-9">
									<select name="berpenampilan_menarik" class="form-select" required>
										<option value="">- Pilih -</option>
										<option value="Tidak Menarik">Tidak Menarik</option>
										<option value="Kurang Menarik">Kurang Menarik</option>
										<option value="Cukup">Cukup</option>
										<option value="Menarik">Menarik</option>
										<option value="Sangat Menarik">Sangat Menarik</option>
									</select>
								</div>
							</div>

							<div class="row mb-3">
								<label class="col-sm-3 col-form-label">Menguasai Panggung</label>
								<div class="col-sm-9">
									<select name="menguasai_panggung" class="form-select" required>
										<option value="">- Pilih -</option>
										<option value="Tidak Baik">Tidak Baik</option>
										<option value="Kurang Baik">Kurang Baik</option>
										<option value="Cukup">Cukup</option>
										<option value="Baik">Baik</option>
										<option value="Sangat Baik">Sangat Baik</option>
									</select>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-sm-9 offset-sm-3">
									<button type="submit" name="submit" class="btn btn-primary">
										<i class="fas fa-save"></i> Simpan Data
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</main>
		</div>
	</div>

<?php
	include('../../includes/footer.php'); // Assuming this includes your footer and JS
} else {
	echo "<script>alert('Silahkan Login Terlebih Dahulu');document.location.href='../../login/index.php';</script>";
}
?>