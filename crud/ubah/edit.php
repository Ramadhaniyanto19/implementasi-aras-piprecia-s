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
					<h1 class="h2">Edit Data Alternatif</h1>
					<div class="btn-toolbar mb-2 mb-md-0">
						<a href="../../crud/tampil/tampil.php" class="btn btn-warning">
							<i class="fas fa-arrow-left"></i> Kembali
						</a>
					</div>
				</div>

				<?php
				if (isset($_GET['id'])) {
					$id = $_GET['id'];
					$select = mysqli_query($koneksi, "SELECT * FROM data_primer WHERE id='$id'") or die(mysqli_error($koneksi));

					if (mysqli_num_rows($select) == 0) {
						echo '<div class="alert alert-warning">ID tidak ditemukan dalam database.</div>';
						exit();
					} else {
						$data = mysqli_fetch_assoc($select);
					}
				}

				if (isset($_POST['submit'])) {
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


					if ($sql) {
						echo '<div class="alert alert-success">Berhasil menyimpan data.</div>';
						echo '<script>document.location="../../crud/tampil/tampil.php";</script>';
					} else {
						echo '<div class="alert alert-danger">Gagal melakukan proses edit data.</div>';
					}
				}
				?>

				<div class="card shadow-sm">
					<div class="card-body">
						<form action="edit.php?id=<?php echo $id; ?>" method="post">
							<div class="row mb-3">
								<label class="col-sm-3 col-form-label">Alternatif</label>
								<div class="col-sm-9">
									<input type="text" name="alternatif" class="form-control" value="<?php echo $data['alternatif']; ?>" required>
								</div>
							</div>

							<div class="row mb-3">
								<label class="col-sm-3 col-form-label">Tinggi Badan (cm)</label>
								<div class="col-sm-9">
									<input type="number" name="tinggi_badan" class="form-control" value="<?php echo $data['tinggi_badan']; ?>" required>
									<small class="text-muted">Contoh: 165</small>
								</div>
							</div>

							<div class="row mb-3">
								<label class="col-sm-3 col-form-label">Berat Badan (kg)</label>
								<div class="col-sm-9">
									<input type="number" name="berat_badan" class="form-control" value="<?php echo $data['berat_badan']; ?>" required>
									<small class="text-muted">Contoh: 55</small>
								</div>
							</div>

							<div class="row mb-3">
								<label class="col-sm-3 col-form-label">Berpenampilan Menarik</label>
								<div class="col-sm-9">
									<select name="berpenampilan_menarik" class="form-select" required>
										<option value="">- Pilih -</option>
										<option value="Tidak Menarik" <?= ($data['berpenampilan_menarik'] == 'Tidak Menarik') ? 'selected' : '' ?>>Tidak Menarik</option>
										<option value="Kurang Menarik" <?= ($data['berpenampilan_menarik'] == 'Kurang Menarik') ? 'selected' : '' ?>>Kurang Menarik</option>
										<option value="Cukup" <?= ($data['berpenampilan_menarik'] == 'Cukup') ? 'selected' : '' ?>>Cukup</option>
										<option value="Menarik" <?= ($data['berpenampilan_menarik'] == 'Menarik') ? 'selected' : '' ?>>Menarik</option>
										<option value="Sangat Menarik" <?= ($data['berpenampilan_menarik'] == 'Sangat Menarik') ? 'selected' : '' ?>>Sangat Menarik</option>
									</select>
								</div>
							</div>

							<div class="row mb-3">
								<label class="col-sm-3 col-form-label">Menguasai Panggung</label>
								<div class="col-sm-9">
									<select name="menguasai_panggung" class="form-select" required>
										<option value="">- Pilih -</option>
										<option value="Tidak Baik" <?= ($data['menguasai_panggung'] == 'Tidak Baik') ? 'selected' : '' ?>>Tidak Baik</option>
										<option value="Kurang Baik" <?= ($data['menguasai_panggung'] == 'Kurang Baik') ? 'selected' : '' ?>>Kurang Baik</option>
										<option value="Cukup" <?= ($data['menguasai_panggung'] == 'Cukup') ? 'selected' : '' ?>>Cukup</option>
										<option value="Baik" <?= ($data['menguasai_panggung'] == 'Baik') ? 'selected' : '' ?>>Baik</option>
										<option value="Sangat Baik" <?= ($data['menguasai_panggung'] == 'Sangat Baik') ? 'selected' : '' ?>>Sangat Baik</option>
									</select>
								</div>
							</div>

							<div class="row mb-3">
								<div class="col-sm-9 offset-sm-3">
									<button type="submit" name="submit" class="btn btn-primary">
										<i class="fas fa-save"></i> Simpan Perubahan
									</button>
									<a href="../../crud/tampil/tampil.php" class="btn btn-warning">
										<i class="fas fa-times"></i> Batal
									</a>
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