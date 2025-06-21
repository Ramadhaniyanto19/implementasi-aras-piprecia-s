<?php
session_start();
if (isset($_SESSION['username'])) {
	include('../../koneksi/koneksi.php');
	include('../../includes/header.php');

	// Fungsi untuk update struktur tabel
	function updateTablesStructure($koneksi)
	{
		// Get all criteria
		$query = "SELECT kriteria, jenis FROM bobot_kriteria";
		$result = mysqli_query($koneksi, $query);
		$columns = [];

		while ($row = mysqli_fetch_assoc($result)) {
			$col_name = strtolower(str_replace(' ', '_', $row['kriteria']));
			$columns[$col_name] = [
				'primer_type' => ($row['jenis'] == 'benefit') ? 'VARCHAR(50)' : 'DECIMAL(10,2)',
				'konversi_type' => 'DECIMAL(10,2)'
			];
		}

		// Update all tables
		$tables = [
			'data_primer' => 'primer_type',
			'data_matrik' => 'primer_type',
			'data_konversi' => 'konversi_type'
		];

		foreach ($tables as $table => $type_key) {
			// Check if table exists
			$table_check = mysqli_query($koneksi, "SHOW TABLES LIKE '$table'");
			if (mysqli_num_rows($table_check) == 0) {
				$create_query = "CREATE TABLE $table (
                    id INT(11) NOT NULL AUTO_INCREMENT,
                    alternatif VARCHAR(50) NOT NULL,
                    PRIMARY KEY (id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
				mysqli_query($koneksi, $create_query);
			}

			// Get existing columns
			$existing_columns = [];
			$result = mysqli_query($koneksi, "SHOW COLUMNS FROM $table");
			while ($row = mysqli_fetch_assoc($result)) {
				$existing_columns[] = $row['Field'];
			}

			// Add new columns
			foreach ($columns as $col_name => $col_data) {
				if (!in_array($col_name, $existing_columns)) {
					$col_type = $col_data[$type_key];
					$query = "ALTER TABLE $table ADD COLUMN `$col_name` $col_type NULL";
					mysqli_query($koneksi, $query);
				}
			}
		}
	}

	// Fungsi untuk mengkonversi nilai - VERSI DIPERBAIKI
	function convertValue($value, $criteria)
	{
		// Jika nilai sudah numerik, langsung return
		if (is_numeric($value)) {
			return [
				'value' => (float)$value,
				'text' => $value
			];
		}

		// Konversi berdasarkan teks kualitatif
		$value_trimmed = trim($value);
		$value_lower = strtolower($value_trimmed);

		// Mapping nilai kualitatif ke angka - DIPERBAIKI
		$mapping = [
			'tidak menarik' => ['value' => 1, 'text' => 'Tidak Menarik'],
			'tidak baik' => ['value' => 1, 'text' => 'Tidak Baik'],
			'sangat rendah' => ['value' => 1, 'text' => 'Sangat Rendah'],
			'kurang menarik' => ['value' => 2, 'text' => 'Kurang Menarik'],
			'kurang baik' => ['value' => 2, 'text' => 'Kurang Baik'],
			'rendah' => ['value' => 2, 'text' => 'Rendah'],
			'cukup' => ['value' => 3, 'text' => 'Cukup'],
			'sedang' => ['value' => 3, 'text' => 'Sedang'],
			'menarik' => ['value' => 4, 'text' => 'Menarik'],
			'baik' => ['value' => 4, 'text' => 'Baik'],
			'tinggi' => ['value' => 4, 'text' => 'Tinggi'],
			'sangat menarik' => ['value' => 5, 'text' => 'Sangat Menarik'],
			'sangat baik' => ['value' => 5, 'text' => 'Sangat Baik'],
			'sangat tinggi' => ['value' => 5, 'text' => 'Sangat Tinggi']
		];

		// Cek exact match terlebih dahulu - PERBAIKAN UTAMA
		if (array_key_exists($value_lower, $mapping)) {
			return $mapping[$value_lower];
		}

		// Cek partial match sebagai fallback
		foreach ($mapping as $key => $data) {
			if (strpos($value_lower, $key) !== false) {
				return $data;
			}
		}

		// Default return 0 jika tidak ada yang cocok
		return ['value' => 0, 'text' => $value];
	}

	// Update struktur tabel
	updateTablesStructure($koneksi);

	// Ambil semua kriteria beserta info konversi
	$criteria = [];
	$query = mysqli_query($koneksi, "SELECT * FROM bobot_kriteria ORDER BY id ASC");
	while ($row = mysqli_fetch_assoc($query)) {
		$criteria[] = $row;
	}

	// Proses form submission
	if (isset($_POST['submit'])) {
		$alternatif = mysqli_real_escape_string($koneksi, $_POST['alternatif']);

		// Mulai transaksi
		mysqli_begin_transaction($koneksi);

		try {
			// 1. Insert ke data_primer (menyimpan teks asli)
			$insert_primer = "INSERT INTO data_primer (alternatif";
			$values_primer = "VALUES ('$alternatif'";

			// 2. Insert ke data_konversi (menyimpan nilai angka)
			$insert_konversi = "INSERT INTO data_konversi (alternatif";
			$values_konversi = "VALUES ('$alternatif'";

			// 3. Insert ke data_matrik (menyimpan nilai angka untuk perhitungan)
			$insert_matrik = "INSERT INTO data_matrik (alternatif";
			$values_matrik = "VALUES ('$alternatif'";

			// Proses setiap kriteria
			foreach ($criteria as $criterion) {
				$col_name = strtolower(str_replace(' ', '_', $criterion['kriteria']));
				$value = $_POST[$col_name];
				$converted = convertValue($value, $criterion);

				// Untuk data_primer (simpan teks asli)
				$insert_primer .= ", `$col_name`";
				$values_primer .= ", '" . mysqli_real_escape_string($koneksi, $converted['text']) . "'";

				// Untuk data_konversi dan data_matrik (simpan nilai angka)
				$insert_konversi .= ", `$col_name`";
				$values_konversi .= ", " . $converted['value'];

				$insert_matrik .= ", `$col_name`";
				$values_matrik .= ", " . $converted['value'];
			}

			// Selesaikan query
			$insert_primer .= ") $values_primer)";
			$insert_konversi .= ") $values_konversi)";
			$insert_matrik .= ") $values_matrik)";

			// Eksekusi query
			$sql_primer = mysqli_query($koneksi, $insert_primer);
			if (!$sql_primer) throw new Exception("Gagal menyimpan data primer: " . mysqli_error($koneksi));

			$sql_konversi = mysqli_query($koneksi, $insert_konversi);
			if (!$sql_konversi) throw new Exception("Gagal menyimpan data konversi: " . mysqli_error($koneksi));

			$sql_matrik = mysqli_query($koneksi, $insert_matrik);
			if (!$sql_matrik) throw new Exception("Gagal menyimpan data matrik: " . mysqli_error($koneksi));

			// Commit transaksi jika semua berhasil
			mysqli_commit($koneksi);
			echo '<script>alert("Berhasil menambahkan data."); document.location="../tampil/tampil.php";</script>';
		} catch (Exception $e) {
			// Rollback jika ada error
			mysqli_rollback($koneksi);
			echo '<div class="alert alert-danger">Gagal menambahkan data: ' . $e->getMessage() . '</div>';
		}
	}
?>

	<div class="container-fluid min-vh-100">
		<div class="row">
			<main class="col-md-9 ms-sm-auto col-lg-12 px-md-4 py-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<h1 class="h2">Input Data Alternatif <i class="fas fa-user-plus"></i></h1>
					<div class="btn-toolbar mb-2 mb-md-0">
						<a href="../tampil/tampil.php" class="btn btn-warning">
							<i class="fas fa-times"></i> Batal
						</a>
					</div>
				</div>

				<div class="card shadow-sm">
					<div class="card-body">
						<form action="tambah.php" method="post">
							<div class="row mb-3">
								<label class="col-sm-3 col-form-label">Nama Alternatif</label>
								<div class="col-sm-9">
									<input type="text" name="alternatif" class="form-control" required>
									<small class="text-muted">Contoh: Nama Peserta</small>
								</div>
							</div>

							<?php foreach ($criteria as $criterion):
								$col_name = strtolower(str_replace(' ', '_', $criterion['kriteria']));
								$is_numeric = in_array($criterion['kriteria'], ['Tinggi Badan', 'Berat Badan']);
							?>
								<div class="row mb-3">
									<label class="col-sm-3 col-form-label">
										<?= htmlspecialchars($criterion['kriteria']) ?>
										<small class="text-muted">(<?= $criterion['jenis'] ?>)</small>
									</label>
									<div class="col-sm-9">
										<?php if ($is_numeric): ?>
											<!-- Input numerik untuk kriteria kuantitatif -->
											<input type="number" name="<?= $col_name ?>" class="form-control" step="0.01" required>
											<?php if ($criterion['kriteria'] == 'Tinggi Badan'): ?>
												<small class="text-muted">Contoh: 170 (dalam cm)</small>
											<?php elseif ($criterion['kriteria'] == 'Berat Badan'): ?>
												<small class="text-muted">Contoh: 65 (dalam kg)</small>
											<?php endif; ?>
										<?php else: ?>
											<!-- Select untuk kriteria kualitatif -->
											<select name="<?= $col_name ?>" class="form-select" required>
												<option value="">- Pilih -</option>
												<?php if (strpos($criterion['kriteria'], 'Menarik') !== false): ?>
													<option value="Tidak Menarik">Tidak Menarik (1)</option>
													<option value="Kurang Menarik">Kurang Menarik (2)</option>
													<option value="Cukup">Cukup (3)</option>
													<option value="Menarik">Menarik (4)</option>
													<option value="Sangat Menarik">Sangat Menarik (5)</option>
												<?php elseif (strpos($criterion['kriteria'], 'Baik') !== false): ?>
													<option value="Tidak Baik">Tidak Baik (1)</option>
													<option value="Kurang Baik">Kurang Baik (2)</option>
													<option value="Cukup">Cukup (3)</option>
													<option value="Baik">Baik (4)</option>
													<option value="Sangat Baik">Sangat Baik (5)</option>
												<?php else: ?>
													<option value="Sangat Rendah">Sangat Rendah (1)</option>
													<option value="Rendah">Rendah (2)</option>
													<option value="Sedang">Sedang (3)</option>
													<option value="Tinggi">Tinggi (4)</option>
													<option value="Sangat Tinggi">Sangat Tinggi (5)</option>
												<?php endif; ?>
											</select>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>

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
	include('../../includes/footer.php');
} else {
	echo "<script>alert('Silahkan Login Terlebih Dahulu');document.location.href='../../login/index.php';</script>";
}
?>