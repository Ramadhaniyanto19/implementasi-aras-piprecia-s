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
					<h1 class="h2">Input Data Alternatif <i><i class="fas fa-plus"></i></i></h1>
					<div class="btn-toolbar mb-2 mb-md-0">
						<a href="../tampil/tampil.php" class="btn btn-warning">
							<i class="fas fa-times"></i> Batal
						</a>
					</div>
				</div>

				<?php
				if (isset($_POST['submit'])) {
					// Your existing PHP processing code here
					// ...

					if ($sql) {
						echo '<div class="alert alert-success">Berhasil menambahkan data.</div>';
						echo '<script>document.location="../tampil/tampil.php";</script>';
					} else {
						echo '<div class="alert alert-danger">Gagal melakukan proses tambah data.</div>';
					}
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