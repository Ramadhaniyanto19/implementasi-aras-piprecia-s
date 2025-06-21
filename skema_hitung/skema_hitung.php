<?php
session_start();
if (isset($_SESSION['username'])) {
	include('../koneksi/koneksi.php');
	include('../includes/header.php');

	// Ambil semua kriteria dari database dengan penanganan error
	$kriteria_list = [];
	$kriteria_query = mysqli_query($koneksi, "SELECT * FROM bobot_kriteria ORDER BY id ASC");
	if ($kriteria_query) {
		while ($row = mysqli_fetch_assoc($kriteria_query)) {
			$col_name = strtolower(str_replace(' ', '_', $row['kriteria']));
			$kriteria_list[$col_name] = [
				'nama' => $row['kriteria'],
				'bobot' => $row['bobot_piprecia'],
				'jenis' => $row['jenis']
			];
		}
	}
?>

	<div class="container-fluid">
		<div class="row">
			<main class="col-md-9 ms-sm-auto col-lg-11 px-md-4 py-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<h1 class="h2">Hasil Perhitungan ARAS dengan Pembobotan PIPRECIA-S</h1>
				</div>

				<!-- Step 1: Data Konversi -->
				<div class="card mb-4">
					<div class="card-header bg-primary text-white">
						<h4>1. Data Konversi Awal</h4>
					</div>
					<div class="card-body">
						<div class="alert alert-info">
							<strong>Keterangan Konversi:</strong>
							<ul>
								<li>Kriteria kualitatif dikonversi ke skala numerik:
									<br>"Tidak Baik"=1, "Kurang Baik"=2, "Cukup"=3, "Baik"=4, "Sangat Baik"=5
								</li>
								<li>Kriteria kuantitatif menggunakan nilai asli</li>
							</ul>
						</div>

						<?php
						$sql = mysqli_query($koneksi, "SELECT * FROM data_konversi");
						if (mysqli_num_rows($sql) > 0) {
							echo '
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Alternatif</th>';

							foreach ($kriteria_list as $col_name => $data) {
								echo '<th>' . htmlspecialchars($data['nama']) . '</th>';
							}

							echo '</tr></thead><tbody>';

							while ($data = mysqli_fetch_assoc($sql)) {
								echo '<tr><td>' . htmlspecialchars($data['alternatif']) . '</td>';

								foreach ($kriteria_list as $col_name => $data_kriteria) {
									$value = isset($data[$col_name]) ? $data[$col_name] : 0;
									echo '<td>' . htmlspecialchars($value) . '</td>';
								}

								echo '</tr>';
							}

							echo '</tbody></table></div>';
						}
						?>
					</div>
				</div>

				<!-- Step 2: Matriks Normalisasi ARAS -->
				<div class="card mb-4">
					<div class="card-header bg-primary text-white">
						<h4>2. Matriks Normalisasi ARAS</h4>
					</div>
					<div class="card-body">
						<div class="alert alert-info">
							<strong>Rumus Normalisasi:</strong>
							<ul>
								<li>Untuk kriteria benefit (semakin besar semakin baik):
									<br><code>r<sub>ij</sub> = X<sub>ij</sub> / X<sub>max</sub></code>
								</li>
								<li>Untuk kriteria cost (semakin kecil semakin baik):
									<br><code>r<sub>ij</sub> = X<sub>min</sub> / X<sub>ij</sub></code>
								</li>
							</ul>
							<strong>Keterangan Jenis Kriteria:</strong>
							<ul>
								<?php foreach ($kriteria_list as $col_name => $data): ?>
									<li><?= htmlspecialchars($data['nama']) ?>: <?= ucfirst($data['jenis']) ?></li>
								<?php endforeach; ?>
							</ul>
						</div>

						<?php
						// Hitung matriks normalisasi ARAS
						$aras_matrix = [];
						$max_min_values = [];

						// Inisialisasi nilai max/min
						foreach ($kriteria_list as $col_name => $data) {
							$max_min_values[$col_name] = [
								'max' => 0,
								'min' => PHP_INT_MAX,
								'jenis' => $data['jenis'],
								'nama' => $data['nama']
							];
						}

						// Ambil data dan hitung max/min
						$sql = mysqli_query($koneksi, "SELECT * FROM data_matrik");
						while ($data = mysqli_fetch_assoc($sql)) {
							foreach ($kriteria_list as $col_name => $data_kriteria) {
								$value = isset($data[$col_name]) ? $data[$col_name] : 0;

								if ($value > $max_min_values[$col_name]['max']) {
									$max_min_values[$col_name]['max'] = $value;
								}
								if ($value < $max_min_values[$col_name]['min']) {
									$max_min_values[$col_name]['min'] = $value;
								}
							}
						}

						// Hitung normalisasi ARAS
						$sql = mysqli_query($koneksi, "SELECT * FROM data_matrik");
						if (mysqli_num_rows($sql) > 0) {
							echo '
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Alternatif</th>';

							foreach ($kriteria_list as $col_name => $data) {
								echo '<th>' . htmlspecialchars($data['nama']) . '</th>';
							}

							echo '</tr></thead><tbody>';

							while ($data = mysqli_fetch_assoc($sql)) {
								echo '<tr><td>' . htmlspecialchars($data['alternatif']) . '</td>';

								foreach ($kriteria_list as $col_name => $data_kriteria) {
									$value = isset($data[$col_name]) ? $data[$col_name] : 0;
									$max = $max_min_values[$col_name]['max'];
									$min = $max_min_values[$col_name]['min'];
									$jenis = $max_min_values[$col_name]['jenis'];

									if ($jenis == 'benefit') {
										$normalized = ($max == 0) ? 0 : $value / $max;
									} else {
										$normalized = ($value == 0) ? 0 : $min / $value;
									}

									$aras_matrix[$data['alternatif']][$col_name] = $normalized;
									echo '<td>' . number_format($normalized, 4) . '</td>';
								}

								echo '</tr>';
							}

							echo '</tbody></table></div>';

							// Tampilkan nilai max/min
							echo '<div class="mt-3"><strong>Nilai Maksimum/Minimum:</strong><ul>';
							foreach ($max_min_values as $col_name => $data) {
								echo '<li>' . htmlspecialchars($data['nama']) .
									': Max=' . $data['max'] .
									', Min=' . $data['min'] .
									' (' . ucfirst($data['jenis']) . ')</li>';
							}
							echo '</ul></div>';
						}
						?>
					</div>
				</div>

				<!-- Step 3: Matriks Terbobot ARAS -->
				<div class="card mb-4">
					<div class="card-header bg-primary text-white">
						<h4>3. Matriks Terbobot ARAS (Hasil Akhir)</h4>
					</div>
					<div class="card-body">
						<div class="alert alert-info">
							<strong>Rumus Pembobotan:</strong>
							<ul>
								<li><code>v<sub>ij</sub> = w<sub>j</sub> × r<sub>ij</sub></code></li>
								<li>Dimana:
									<ul>
										<li>w<sub>j</sub> = bobot kriteria dari PIPRECIA-S</li>
										<li>r<sub>ij</sub> = nilai normalisasi</li>
										<li>v<sub>ij</sub> = nilai terbobot</li>
									</ul>
								</li>
							</ul>
						</div>

						<?php
						if (!empty($aras_matrix)) {
							echo '
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Alternatif</th>';

							foreach ($kriteria_list as $col_name => $data) {
								echo '<th>' . htmlspecialchars($data['nama']) . '<br>Bobot: ' . number_format($data['bobot'], 4) . '</th>';
							}

							echo '</tr></thead><tbody>';

							foreach ($aras_matrix as $alt => $values) {
								echo '<tr><td>' . htmlspecialchars($alt) . '</td>';

								foreach ($kriteria_list as $col_name => $data) {
									$normalized_value = isset($values[$col_name]) ? $values[$col_name] : 0;
									$weighted = $normalized_value * $data['bobot'];
									echo '<td>' . number_format($weighted, 4) . '</td>';
								}

								echo '</tr>';
							}

							echo '</tbody></table></div>';
						}
						?>
					</div>
				</div>

				<!-- Step 4: Hasil Akhir ARAS -->
				<div class="card mb-4">
					<div class="card-header bg-primary text-white">
						<h4>4. Hasil Akhir ARAS dengan Pembobotan PIPRECIA-S</h4>
					</div>
					<div class="card-body">
						<div class="alert alert-info">
							<strong>Rumus Nilai Akhir:</strong>
							<ul>
								<li><code>S<sub>i</sub> = Σ v<sub>ij</sub></code> (jumlah semua nilai terbobot per alternatif)</li>
							</ul>
							<strong>Klasifikasi:</strong>
							<ul>
								<li>0.8 - 1.0: Sangat Baik</li>
								<li>0.6 - 0.79: Baik</li>
								<li>0.4 - 0.59: Cukup</li>
								<li>&lt; 0.4: Kurang</li>
							</ul>
						</div>

						<?php
						$aras_results = [];

						if (!empty($aras_matrix)) {
							echo '
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Ranking</th>
                                        <th>Alternatif</th>
                                        <th>Nilai Akhir</th>
                                        <th>Keterangan</th>
                                        <th>Detail Perhitungan</th>
                                    </tr>
                                </thead>
                                <tbody>';

							foreach ($aras_matrix as $alt => $values) {
								$score = 0;
								$detail = [];

								foreach ($kriteria_list as $col_name => $data) {
									$normalized_value = isset($values[$col_name]) ? $values[$col_name] : 0;
									$weighted = $normalized_value * $data['bobot'];
									$score += $weighted;
									$detail[] = number_format($weighted, 4) . ' (' . $data['nama'] . ')';
								}

								$aras_results[$alt] = $score;
							}

							// Urutkan dari nilai tertinggi
							arsort($aras_results);

							$rank = 1;
							foreach ($aras_results as $alt => $score) {
								$keterangan = '';
								if ($score >= 0.8) {
									$keterangan = 'Sangat Baik';
								} elseif ($score >= 0.6) {
									$keterangan = 'Baik';
								} elseif ($score >= 0.4) {
									$keterangan = 'Cukup';
								} else {
									$keterangan = 'Kurang';
								}

								// Hitung ulang detail untuk alternatif ini
								$detail = [];
								foreach ($kriteria_list as $col_name => $data) {
									$normalized_value = isset($aras_matrix[$alt][$col_name]) ? $aras_matrix[$alt][$col_name] : 0;
									$weighted = $normalized_value * $data['bobot'];
									$detail[] = number_format($weighted, 4) . ' (' . $data['nama'] . ')';
								}

								echo '
                            <tr>
                                <td>' . $rank . '</td>
                                <td>' . htmlspecialchars($alt) . '</td>
                                <td>' . number_format($score, 4) . '</td>
                                <td>' . htmlspecialchars($keterangan) . '</td>
                                <td>' . implode(' + ', $detail) . ' = ' . number_format($score, 4) . '</td>
                            </tr>';
								$rank++;
							}

							echo '</tbody></table></div>';
						}
						?>
					</div>
				</div>

			</main>
		</div>
	</div>

<?php
	include('../includes/footer.php');
} else {
	echo "<script>alert('Silahkan Login Terlebih Dahulu');document.location.href='../login/index.php';</script>";
}
?>