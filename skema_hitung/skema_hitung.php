<?php
session_start();
if (isset($_SESSION['username'])) {
	include('../koneksi/koneksi.php');
	include('../includes/header.php');
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
							echo '
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Alternatif</th>
                                        <th>Tinggi Badan (C1)</th>
                                        <th>Berat Badan (C2)</th>
                                        <th>Berpenampilan Menarik (C3)</th>
                                        <th>Menguasai Panggung (C4)</th>
                                    </tr>
                                </thead>
                                <tbody>';
							while ($data = mysqli_fetch_assoc($sql)) {
								echo '
                                    <tr>
                                        <td>' . $data['alternatif'] . '</td>
                                        <td>' . $data['tinggi_badan'] . ' cm</td>
                                        <td>' . $data['berat_badan'] . ' kg</td>
                                        <td>' . $data['berpenampilan_menarik'] . '</td>
                                        <td>' . $data['menguasai_panggung'] . '</td>
                                    </tr>';
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
							echo '
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Alternatif</th>
                                        <th>Tinggi Badan (C1)</th>
                                        <th>Berat Badan (C2)</th>
                                        <th>Berpenampilan Menarik (C3)</th>
                                        <th>Menguasai Panggung (C4)</th>
                                    </tr>
                                </thead>
                                <tbody>';
							while ($data = mysqli_fetch_assoc($sql)) {
								$aras_matrix[$data['alternatif']] = [
									'tinggi_badan' => $data['tinggi_badan'],
									'berat_badan' => $data['berat_badan'],
									'berpenampilan_menarik' => $data['berpenampilan_menarik'],
									'menguasai_panggung' => $data['menguasai_panggung']
								];
								echo '
                                    <tr>
                                        <td>' . $data['alternatif'] . '</td>
                                        <td>' . $data['tinggi_badan'] . '</td>
                                        <td>' . $data['berat_badan'] . '</td>
                                        <td>' . $data['berpenampilan_menarik'] . '</td>
                                        <td>' . $data['menguasai_panggung'] . '</td>
                                    </tr>';
							}
							echo '</tbody></table></div>';

							// Find max values for each criterion
							$max_values = [
								'tinggi_badan' => max(array_column($aras_matrix, 'tinggi_badan')),
								'berat_badan' => max(array_column($aras_matrix, 'berat_badan')),
								'berpenampilan_menarik' => max(array_column($aras_matrix, 'berpenampilan_menarik')),
								'menguasai_panggung' => max(array_column($aras_matrix, 'menguasai_panggung'))
							];
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
						<p>Dimana:</p>
						<ul>
							<li>C1 (Tinggi Badan): Benefit</li>
							<li>C2 (Berat Badan): Cost</li>
							<li>C3 (Berpenampilan Menarik): Benefit</li>
							<li>C4 (Menguasai Panggung): Benefit</li>
						</ul>

						<?php
						if (!empty($aras_matrix) && !empty($max_values)) {
							echo '
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Alternatif</th>
                                        <th>Tinggi Badan (C1)</th>
                                        <th>Berat Badan (C2)</th>
                                        <th>Berpenampilan Menarik (C3)</th>
                                        <th>Menguasai Panggung (C4)</th>
                                    </tr>
                                </thead>
                                <tbody>';

							foreach ($aras_matrix as $alt => $values) {
								// Calculate normalized values
								$normalized = [
									'tinggi_badan' => $values['tinggi_badan'] / $max_values['tinggi_badan'],
									'berat_badan' => $max_values['berat_badan'] / $values['berat_badan'],
									'berpenampilan_menarik' => $values['berpenampilan_menarik'] / $max_values['berpenampilan_menarik'],
									'menguasai_panggung' => $values['menguasai_panggung'] / $max_values['menguasai_panggung']
								];

								echo '
                                    <tr>
                                        <td>' . $alt . '</td>
                                        <td>' . number_format($normalized['tinggi_badan'], 4) . '</td>
                                        <td>' . number_format($normalized['berat_badan'], 4) . '</td>
                                        <td>' . number_format($normalized['berpenampilan_menarik'], 4) . '</td>
                                        <td>' . number_format($normalized['menguasai_panggung'], 4) . '</td>
                                    </tr>';
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
						// Get weights from PIPRECIA-S
						$weights = [];
						$sql = mysqli_query($koneksi, "SELECT kriteria, bobot_piprecia FROM bobot_kriteria");
						while ($row = mysqli_fetch_assoc($sql)) {
							$weights[$row['kriteria']] = $row['bobot_piprecia'];
						}

						if (!empty($aras_matrix) && !empty($max_values) && !empty($weights)) {
							echo '
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Alternatif</th>
                                        <th>Tinggi Badan (C1)<br>Bobot: ' . number_format($weights['tinggi_badan'], 4) . '</th>
                                        <th>Berat Badan (C2)<br>Bobot: ' . number_format($weights['berat_badan'], 4) . '</th>
                                        <th>Berpenampilan Menarik (C3)<br>Bobot: ' . number_format($weights['berpenampilan_menarik'], 4) . '</th>
                                        <th>Menguasai Panggung (C4)<br>Bobot: ' . number_format($weights['menguasai_panggung'], 4) . '</th>
                                    </tr>
                                </thead>
                                <tbody>';

							foreach ($aras_matrix as $alt => $values) {
								// Calculate weighted normalized values
								$weighted = [
									'tinggi_badan' => ($values['tinggi_badan'] / $max_values['tinggi_badan']) * $weights['tinggi_badan'],
									'berat_badan' => ($max_values['berat_badan'] / $values['berat_badan']) * $weights['berat_badan'],
									'berpenampilan_menarik' => ($values['berpenampilan_menarik'] / $max_values['berpenampilan_menarik']) * $weights['berpenampilan_menarik'],
									'menguasai_panggung' => ($values['menguasai_panggung'] / $max_values['menguasai_panggung']) * $weights['menguasai_panggung']
								];

								echo '
                                    <tr>
                                        <td>' . $alt . '</td>
                                        <td>' . number_format($weighted['tinggi_badan'], 4) . '</td>
                                        <td>' . number_format($weighted['berat_badan'], 4) . '</td>
                                        <td>' . number_format($weighted['berpenampilan_menarik'], 4) . '</td>
                                        <td>' . number_format($weighted['menguasai_panggung'], 4) . '</td>
                                    </tr>';
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
						if (!empty($aras_matrix) && !empty($max_values) && !empty($weights)) {
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
								// Calculate ARAS score
								$score =
									($values['tinggi_badan'] / $max_values['tinggi_badan']) * $weights['tinggi_badan'] +
									($max_values['berat_badan'] / $values['berat_badan']) * $weights['berat_badan'] +
									($values['berpenampilan_menarik'] / $max_values['berpenampilan_menarik']) * $weights['berpenampilan_menarik'] +
									($values['menguasai_panggung'] / $max_values['menguasai_panggung']) * $weights['menguasai_panggung'];

								$aras_results[$alt] = $score;

								echo '
                                    <tr>
                                        <td>' . $alt . '</td>
                                        <td>
                                            (' . $values['tinggi_badan'] . '/' . $max_values['tinggi_badan'] . ')×' . $weights['tinggi_badan'] . ' + 
                                            (' . $max_values['berat_badan'] . '/' . $values['berat_badan'] . ')×' . $weights['berat_badan'] . ' + 
                                            (' . $values['berpenampilan_menarik'] . '/' . $max_values['berpenampilan_menarik'] . ')×' . $weights['berpenampilan_menarik'] . ' + 
                                            (' . $values['menguasai_panggung'] . '/' . $max_values['menguasai_panggung'] . ')×' . $weights['menguasai_panggung'] . '
                                        </td>
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
							echo '
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Alternatif</th>
                                        <th>Tinggi Badan (C1)</th>
                                        <th>Berat Badan (C2)</th>
                                        <th>Berpenampilan Menarik (C3)</th>
                                        <th>Menguasai Panggung (C4)</th>
                                    </tr>
                                </thead>
                                <tbody>';
							while ($data = mysqli_fetch_assoc($sql)) {
								$piprecia_matrix[$data['alternatif']] = [
									'tinggi_badan' => $data['tinggi_badan'],
									'berat_badan' => $data['berat_badan'],
									'berpenampilan_menarik' => $data['berpenampilan_menarik'],
									'menguasai_panggung' => $data['menguasai_panggung']
								];
								echo '
                                    <tr>
                                        <td>' . $data['alternatif'] . '</td>
                                        <td>' . number_format($data['tinggi_badan'], 4) . '</td>
                                        <td>' . number_format($data['berat_badan'], 4) . '</td>
                                        <td>' . number_format($data['berpenampilan_menarik'], 4) . '</td>
                                        <td>' . number_format($data['menguasai_panggung'], 4) . '</td>
                                    </tr>';
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
                                        <td>' . $data['alternatif'] . '</td>
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
								$normalized_aras[$alt] = $score / $max_aras;
							}

							foreach ($piprecia_results as $alt => $score) {
								$normalized_piprecia[$alt] = $score / $max_piprecia;
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
                                        <td>' . $alt . '</td>
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