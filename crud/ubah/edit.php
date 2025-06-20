<?php
session_start();
if (isset($_SESSION['username'])) {
	include('../../koneksi/koneksi.php');
	include('../../includes/header.php');

	// Function to convert values based on criteria type
	function convertValue($value, $type, $criteria_name)
	{
		if ($type == 'benefit') {
			// Handle numeric benefit criteria (like height, weight)
			if ($criteria_name == 'Tinggi Badan') {
				if ($value <= 150) return 1;
				elseif ($value < 160) return 2;
				elseif ($value < 170) return 3;
				elseif ($value < 180) return 4;
				else return 5;
			} elseif ($criteria_name == 'Berat Badan') {
				if ($value < 45) return 1;
				elseif ($value <= 54) return 2;
				elseif ($value <= 64) return 3;
				elseif ($value <= 74) return 4;
				elseif ($value <= 84) return 5;
				else return 1;
			}
		}

		// Handle qualitative criteria
		switch ($value) {
			case 'Tidak Menarik':
			case 'Tidak Baik':
				return 1;
			case 'Kurang Menarik':
			case 'Kurang Baik':
				return 2;
			case 'Cukup':
				return 3;
			case 'Menarik':
			case 'Baik':
				return 4;
			case 'Sangat Menarik':
			case 'Sangat Baik':
				return 5;
			default:
				return $value; // For already converted values
		}
	}

	// Get all criteria from database
	$criteria_query = mysqli_query($koneksi, "SELECT * FROM bobot_kriteria ORDER BY id ASC");
	$criteria = [];
	while ($row = mysqli_fetch_assoc($criteria_query)) {
		$criteria[] = $row;
	}
?>

	<div class="container-fluid min-vh-100">
		<div class="row">
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
					$alternatif = $_POST['alternatif'];
					$update_primer = "UPDATE data_primer SET alternatif='$alternatif'";
					$update_konversi = "UPDATE data_konversi SET alternatif='$alternatif'";

					// Process each criteria
					foreach ($criteria as $criterion) {
						$col_name = strtolower(str_replace(' ', '_', $criterion['kriteria']));
						$value = $_POST[$col_name];

						// Add to primer update
						$update_primer .= ", $col_name='$value'";

						// Convert value based on criteria type
						$converted_value = convertValue($value, $criterion['jenis'], $criterion['kriteria']);

						// Add to konversi update
						$update_konversi .= ", $col_name='$converted_value'";
					}

					$update_primer .= " WHERE id='$id'";
					$update_konversi .= " WHERE id='$id'";

					$sql_primer = mysqli_query($koneksi, $update_primer) or die(mysqli_error($koneksi));
					$sql_konversi = mysqli_query($koneksi, $update_konversi) or die(mysqli_error($koneksi));

					if ($sql_primer && $sql_konversi) {
						echo '<script>alert("Berhasil menyimpan data."); document.location="../../crud/tampil/tampil.php?id=' . $id . '";</script>';
					} else {
						echo '<div class="alert alert-warning">Gagal melakukan proses edit data.</div>';
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

							<?php foreach ($criteria as $criterion):
								$col_name = strtolower(str_replace(' ', '_', $criterion['kriteria']));
								$current_value = isset($data[$col_name]) ? $data[$col_name] : '';
							?>
								<div class="row mb-3">
									<label class="col-sm-3 col-form-label"><?php echo $criterion['kriteria']; ?></label>
									<div class="col-sm-9">
										<?php if ($criterion['jenis'] == 'benefit' && in_array($criterion['kriteria'], ['Tinggi Badan', 'Berat Badan'])): ?>
											<!-- Numeric input for measurable criteria -->
											<input type="number" name="<?php echo $col_name; ?>" class="form-control"
												value="<?php echo $current_value; ?>" required>
											<small class="text-muted">
												<?php
												if ($criterion['kriteria'] == 'Tinggi Badan') echo 'Dalam cm (contoh: 165)';
												elseif ($criterion['kriteria'] == 'Berat Badan') echo 'Dalam kg (contoh: 55)';
												?>
											</small>
										<?php else: ?>
											<!-- Select input for qualitative criteria -->
											<select name="<?php echo $col_name; ?>" class="form-select" required>
												<option value="">- Pilih -</option>
												<?php if (strpos($criterion['kriteria'], 'Menarik') !== false): ?>
													<option value="Tidak Menarik" <?= ($current_value == 'Tidak Menarik') ? 'selected' : '' ?>>Tidak Menarik</option>
													<option value="Kurang Menarik" <?= ($current_value == 'Kurang Menarik') ? 'selected' : '' ?>>Kurang Menarik</option>
													<option value="Cukup" <?= ($current_value == 'Cukup') ? 'selected' : '' ?>>Cukup</option>
													<option value="Menarik" <?= ($current_value == 'Menarik') ? 'selected' : '' ?>>Menarik</option>
													<option value="Sangat Menarik" <?= ($current_value == 'Sangat Menarik') ? 'selected' : '' ?>>Sangat Menarik</option>
												<?php else: ?>
													<option value="Tidak Baik" <?= ($current_value == 'Tidak Baik') ? 'selected' : '' ?>>Tidak Baik</option>
													<option value="Kurang Baik" <?= ($current_value == 'Kurang Baik') ? 'selected' : '' ?>>Kurang Baik</option>
													<option value="Cukup" <?= ($current_value == 'Cukup') ? 'selected' : '' ?>>Cukup</option>
													<option value="Baik" <?= ($current_value == 'Baik') ? 'selected' : '' ?>>Baik</option>
													<option value="Sangat Baik" <?= ($current_value == 'Sangat Baik') ? 'selected' : '' ?>>Sangat Baik</option>
												<?php endif; ?>
											</select>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>

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
	include('../../includes/footer.php');
} else {
	echo "<script>alert('Silahkan Login Terlebih Dahulu');document.location.href='../../login/index.php';</script>";
}
?>