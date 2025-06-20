<?php
session_start();
if (isset($_SESSION['username'])) {
	include('../koneksi/koneksi.php');
	include('../includes/header.php');

	// Ambil semua kriteria dari database
	$kriteria_list = [];
	$kriteria_query = mysqli_query($koneksi, "SELECT * FROM bobot_kriteria ORDER BY id ASC");
	while ($row = mysqli_fetch_assoc($kriteria_query)) {
		$kriteria_list[$row['kriteria']] = [
			'bobot' => $row['bobot_piprecia'],
			'jenis' => $row['jenis']
		];
	}
?>

	<div class="container-fluid">
		<div class="row">
			<main class="col-md-9 ms-sm-auto col-lg-12 px-md-4 py-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<h1 class="h2">Skema Perhitungan Lengkap ARAS & PIPRECIA-S</h1>
				</div>

				<!-- Step 1: Data Konversi -->
				<div class="card mb-4">
					<div class="card-header bg-primary text-white">
						<h4>1. Data Konversi Awal</h4>
					</div>
					<div class="card-body">
						<p>Data mentah yang diinputkan oleh user untuk setiap alternatif dan kriteria.</p>
						<?php
						$sql = mysqli_query($koneksi, "SELECT * FROM data_konversi");
						if (mysqli_num_rows($sql) > 0) {
							// Buat header tabel
							echo '
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Alternatif</th>';

							// Header kolom kriteria
							foreach ($kriteria_list as $kriteria => $data) {
								echo '<th>' . htmlspecialchars($kriteria) . '</th>';
							}

							echo '</tr></thead><tbody>';

							// Data baris
							while ($data = mysqli_fetch_assoc($sql)) {
								echo '<tr><td>' . htmlspecialchars($data['alternatif']) . '</td>';

								foreach ($kriteria_list as $kriteria => $data_kriteria) {
									echo '<td>' . htmlspecialchars($data[$kriteria]) . '</td>';
								}

								echo '</tr>';
							}

							echo '</tbody></table></div>';
						}
						?>
					</div>
				</div>

				<!-- Step 2: Matriks Keputusan ARAS -->
				<div class="card mb-4">
					<div class="card-header bg-primary text-white">
						<h4>2. Matriks Keputusan ARAS</h4>
					</div>
					<div class="card-body">
						<p>Matriks yang digunakan untuk perhitungan ARAS, dengan nilai yang sudah dinormalisasi.</p>
						<?php
						// Get data for ARAS matrix
						$aras_matrix = [];
						$sql = mysqli_query($koneksi, "SELECT * FROM data_matrik");
						if (mysqli_num_rows($sql) > 0) {
							// Buat header tabel
							echo '
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Alternatif</th>';

							// Header kolom kriteria
							foreach ($kriteria_list as $kriteria => $data) {
								echo '<th>' . htmlspecialchars($kriteria) . '</th>';
							}

							echo '</tr></thead><tbody>';

							// Data baris dan cari nilai maksimal/minimal
							$max_min_values = [];
							foreach ($kriteria_list as $kriteria => $data) {
								$max_min_values[$kriteria] = [
									'max' => 0,
									'min' => PHP_INT_MAX,
									'jenis' => $data['jenis']
								];
							}

							while ($data = mysqli_fetch_assoc($sql)) {
								$alt_data = [];
								echo '<tr><td>' . htmlspecialchars($data['alternatif']) . '</td>';

								foreach ($kriteria_list as $kriteria => $data_kriteria) {
									$value = $data[$kriteria];
									$alt_data[$kriteria] = $value;
									echo '<td>' . htmlspecialchars($value) . '</td>';

									// Update nilai maksimal/minimal
									if ($value > $max_min_values[$kriteria]['max']) {
										$max_min_values[$kriteria]['max'] = $value;
									}
									if ($value < $max_min_values[$kriteria]['min']) {
										$max_min_values[$kriteria]['min'] = $value;
									}
								}

								$aras_matrix[$data['alternatif']] = $alt_data;
								echo '</tr>';
							}

							echo '</tbody></table></div>';
						}
						?>
					</div>
				</div>

				<!-- Step 3: Normalisasi ARAS -->
				<div class="card mb-4">
					<div class="card-header bg-primary text-white">
						<h4>3. Normalisasi Matriks ARAS</h4>
					</div>
					<div class="card-body">
						<p>Proses normalisasi matriks ARAS dengan rumus:</p>
						<ul>
							<li>Untuk kriteria benefit: X<sub>ij</sub> / X<sub>max</sub></li>
							<li>Untuk kriteria cost: X<sub>min</sub> / X<sub>ij</sub></li>
						</ul>
						<p>Jenis Kriteria:</p>
						<ul>
							<?php foreach ($kriteria_list as $kriteria => $data): ?>
								<li><?= htmlspecialchars($kriteria) ?>: <?= ucfirst($data['jenis']) ?></li>
							<?php endforeach; ?>
						</ul>

						<?php
						if (!empty($aras_matrix) && !empty($max_min_values)) {
							// Buat header tabel
							echo '
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Alternatif</th>';

							// Header kolom kriteria
							foreach ($kriteria_list as $kriteria => $data) {
								echo '<th>' . htmlspecialchars($kriteria) . '</th>';
							}

							echo '</tr></thead><tbody>';

							// Data baris dengan nilai normalisasi
							foreach ($aras_matrix as $alt => $values) {
								echo '<tr><td>' . htmlspecialchars($alt) . '</td>';

								foreach ($kriteria_list as $kriteria => $data) {
									$value = $values[$kriteria];
									$max = $max_min_values[$kriteria]['max'];
									$min = $max_min_values[$kriteria]['min'];
									$jenis = $max_min_values[$kriteria]['jenis'];

									// Normalisasi berdasarkan jenis kriteria
									if ($jenis == 'benefit') {
										$normalized = ($max == 0) ? 0 : $value / $max;
									} else { // cost
										$normalized = ($value == 0) ? 0 : $min / $value;
									}

									echo '<td>' . number_format($normalized, 4) . '</td>';
								}

								echo '</tr>';
							}

							echo '</tbody></table></div>';
						}
						?>
					</div>
				</div>

				<!-- Step 4: Normalisasi Terbobot ARAS -->
				<div class="card mb-4">
					<div class="card-header bg-primary text-white">
						<h4>4. Normalisasi Terbobot ARAS</h4>
					</div>
					<div class="card-body">
						<p>Proses pembobotan matriks yang sudah dinormalisasi dengan bobot dari PIPRECIA-S.</p>
						<?php
						if (!empty($aras_matrix) && !empty($max_min_values) && !empty($kriteria_list)) {
							// Buat header tabel
							echo '
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Alternatif</th>';

							// Header kolom kriteria dengan bobot
							foreach ($kriteria_list as $kriteria => $data) {
								echo '<th>' . htmlspecialchars($kriteria) . '<br>Bobot: ' . number_format($data['bobot'], 4) . '</th>';
							}

							echo '</tr></thead><tbody>';

							// Data baris dengan nilai terbobot
							foreach ($aras_matrix as $alt => $values) {
								echo '<tr><td>' . htmlspecialchars($alt) . '</td>';

								foreach ($kriteria_list as $kriteria => $data) {
									$value = $values[$kriteria];
									$max = $max_min_values[$kriteria]['max'];
									$min = $max_min_values[$kriteria]['min'];
									$jenis = $max_min_values[$kriteria]['jenis'];
									$bobot = $data['bobot'];

									// Normalisasi dan pembobotan
									if ($jenis == 'benefit') {
										$weighted = ($max == 0) ? 0 : ($value / $max) * $bobot;
									} else { // cost
										$weighted = ($value == 0) ? 0 : ($min / $value) * $bobot;
									}

									echo '<td>' . number_format($weighted, 4) . '</td>';
								}

								echo '</tr>';
							}

							echo '</tbody></table></div>';
						}
						?>
					</div>
				</div>

				<!-- Step 5: Hasil ARAS -->
				<div class="card mb-4">
					<div class="card-header bg-primary text-white">
						<h4>5. Hasil Perhitungan ARAS</h4>
					</div>
					<div class="card-body">
						<p>Nilai optimum untuk setiap alternatif dihitung dengan menjumlahkan semua nilai kriteria yang sudah dinormalisasi dan terbobot.</p>
						<?php
						if (!empty($aras_matrix) && !empty($max_min_values) && !empty($kriteria_list)) {
							$aras_results = [];

							echo '
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Alternatif</th>
                                        <th>Perhitungan</th>
                                        <th>Nilai ARAS</th>
                                    </tr>
                                </thead>
                                <tbody>';

							foreach ($aras_matrix as $alt => $values) {
								$calculation_parts = [];
								$score = 0;

								foreach ($kriteria_list as $kriteria => $data) {
									$value = $values[$kriteria];
									$max = $max_min_values[$kriteria]['max'];
									$min = $max_min_values[$kriteria]['min'];
									$jenis = $max_min_values[$kriteria]['jenis'];
									$bobot = $data['bobot'];

									if ($jenis == 'benefit') {
										$part = ($max == 0) ? 0 : ($value / $max) * $bobot;
										$calculation_parts[] = '(' . $value . '/' . $max . ')×' . $bobot;
									} else { // cost
										$part = ($value == 0) ? 0 : ($min / $value) * $bobot;
										$calculation_parts[] = '(' . $min . '/' . $value . ')×' . $bobot;
									}

									$score += $part;
								}

								$aras_results[$alt] = $score;

								echo '
                                <tr>
                                    <td>' . htmlspecialchars($alt) . '</td>
                                    <td>' . implode(' + ', $calculation_parts) . '</td>
                                    <td>' . number_format($score, 4) . '</td>
                                </tr>';
							}

							echo '</tbody></table></div>';
						}
						?>
					</div>
				</div>

				<!-- Step 6: Normalisasi PIPRECIA-S -->
				<div class="card mb-4">
					<div class="card-header bg-primary text-white">
						<h4>6. Normalisasi PIPRECIA-S</h4>
					</div>
					<div class="card-body">
						<p>Matriks normalisasi untuk metode PIPRECIA-S.</p>
						<?php
						$piprecia_matrix = [];
						$sql = mysqli_query($koneksi, "SELECT * FROM normalisasi_piprecia");
						if (mysqli_num_rows($sql) > 0) {
							// Buat header tabel
							echo '
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Alternatif</th>';

							// Header kolom kriteria
							foreach ($kriteria_list as $kriteria => $data) {
								echo '<th>' . htmlspecialchars($kriteria) . '</th>';
							}

							echo '</tr></thead><tbody>';

							// Data baris
							while ($data = mysqli_fetch_assoc($sql)) {
								$alt_data = [];
								echo '<tr><td>' . htmlspecialchars($data['alternatif']) . '</td>';

								foreach ($kriteria_list as $kriteria => $data_kriteria) {
									$value = $data[$kriteria];
									$alt_data[$kriteria] = $value;
									echo '<td>' . number_format($value, 4) . '</td>';
								}

								$piprecia_matrix[$data['alternatif']] = $alt_data;
								echo '</tr>';
							}

							echo '</tbody></table></div>';
						}
						?>
					</div>
				</div>

				<!-- Step 7: Hasil PIPRECIA-S -->
				<div class="card mb-4">
					<div class="card-header bg-primary text-white">
						<h4>7. Hasil Perhitungan PIPRECIA-S</h4>
					</div>
					<div class="card-body">
						<p>Nilai akhir untuk setiap alternatif dari metode PIPRECIA-S.</p>
						<?php
						$piprecia_results = [];
						$sql = mysqli_query($koneksi, "SELECT * FROM hasil_piprecia ORDER BY nilai_akhir DESC");
						if (mysqli_num_rows($sql) > 0) {
							echo '
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Ranking</th>
                                        <th>Alternatif</th>
                                        <th>Nilai PIPRECIA-S</th>
                                    </tr>
                                </thead>
                                <tbody>';
							$rank = 1;
							while ($data = mysqli_fetch_assoc($sql)) {
								$piprecia_results[$data['alternatif']] = $data['nilai_akhir'];
								echo '
                                <tr>
                                    <td>' . $rank . '</td>
                                    <td>' . htmlspecialchars($data['alternatif']) . '</td>
                                    <td>' . number_format($data['nilai_akhir'], 4) . '</td>
                                </tr>';
								$rank++;
							}
							echo '</tbody></table></div>';
						}
						?>
					</div>
				</div>

				<!-- Step 8: Gabungan ARAS dan PIPRECIA-S -->
				<div class="card mb-4">
					<div class="card-header bg-primary text-white">
						<h4>8. Hasil Gabungan (60% ARAS + 40% PIPRECIA-S)</h4>
					</div>
					<div class="card-body">
						<p>Kombinasi hasil dari kedua metode dengan pembobotan 60% untuk ARAS dan 40% untuk PIPRECIA-S.</p>
						<?php
						if (!empty($aras_results) && !empty($piprecia_results)) {
							// Normalize scores to 0-1 range
							$max_aras = max($aras_results);
							$max_piprecia = max($piprecia_results);

							$normalized_aras = [];
							$normalized_piprecia = [];
							$combined_results = [];

							foreach ($aras_results as $alt => $score) {
								$normalized_aras[$alt] = ($max_aras == 0) ? 0 : $score / $max_aras;
							}

							foreach ($piprecia_results as $alt => $score) {
								$normalized_piprecia[$alt] = ($max_piprecia == 0) ? 0 : $score / $max_piprecia;
							}

							// Calculate combined scores
							foreach ($normalized_aras as $alt => $score) {
								if (isset($normalized_piprecia[$alt])) {
									$combined_results[$alt] = [
										'combined' => (0.6 * $score) + (0.4 * $normalized_piprecia[$alt]),
										'aras' => $score,
										'piprecia' => $normalized_piprecia[$alt]
									];
								}
							}

							// Sort by combined score
							arsort($combined_results);

							echo '
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Ranking</th>
                                        <th>Alternatif</th>
                                        <th>Skor Gabungan</th>
                                        <th>Skor ARAS (60%)</th>
                                        <th>Skor PIPRECIA-S (40%)</th>
                                    </tr>
                                </thead>
                                <tbody>';

							$rank = 1;
							foreach ($combined_results as $alt => $scores) {
								echo '
                                <tr>
                                    <td>' . $rank . '</td>
                                    <td>' . htmlspecialchars($alt) . '</td>
                                    <td>' . number_format($scores['combined'], 4) . '</td>
                                    <td>' . number_format($scores['aras'], 4) . '</td>
                                    <td>' . number_format($scores['piprecia'], 4) . '</td>
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