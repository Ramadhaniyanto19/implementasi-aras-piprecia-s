<?php
session_start();
if (isset($_SESSION['username'])) {
	include('../koneksi/koneksi.php');
	include('../includes/header.php');
?>

	<div class="container-fluid">
		<div class="row">
			<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<h1 class="h2">Hasil Perankingan Gabungan</h1>
					<div class="btn-toolbar mb-2 mb-md-0">
						<a href="../skema_hitung/skema_hitung.php" class="btn btn-warning me-2">
							<i class="fas fa-project-diagram"></i> View Skema
						</a>
						<a href="../laporan/laporan.php" class="btn btn-success">
							<i class="fas fa-file-pdf"></i> Cetak Laporan
						</a>
					</div>
				</div>

				<!-- Combined Results Table -->
				<div class="card shadow-sm mb-4">
					<div class="card-header bg-primary text-white">
						<h4 class="mb-0">Hasil Gabungan ARAS dan PIPRECIA-S</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover">
								<thead class="table-dark">
									<tr>
										<th>Ranking</th>
										<th>Alternatif</th>
										<th>Skor Gabungan</th>
										<th>Skor ARAS</th>
										<th>Skor PIPRECIA-S</th>
										<th>Detail</th>
									</tr>
								</thead>
								<tbody>
									<?php
									// 1. Ambil bobot kriteria dari database
									$bobot_kriteria = [];
									$bobot_query = mysqli_query($koneksi, "SELECT kriteria, bobot_piprecia FROM bobot_kriteria");
									while ($row = mysqli_fetch_assoc($bobot_query)) {
										$bobot_kriteria[$row['kriteria']] = $row['bobot_piprecia'];
									}

									// 2. Hitung ulang ARAS dengan bobot PIPRECIA-S untuk hasil yang lebih valid
									$aras_scores = [];
									$matrik_query = mysqli_query($koneksi, "SELECT * FROM data_matrik WHERE alternatif != '-'");

									// Cari nilai maksimal setiap kriteria
									$max_values = [
										'tinggi_badan' => 0,
										'berat_badan' => 0,
										'berpenampilan_menarik' => 0,
										'menguasai_panggung' => 0
									];

									$matrik_data = [];
									while ($row = mysqli_fetch_assoc($matrik_query)) {
										$matrik_data[$row['alternatif']] = [
											'tinggi_badan' => $row['tinggi_badan'],
											'berat_badan' => $row['berat_badan'],
											'berpenampilan_menarik' => $row['berpenampilan_menarik'],
											'menguasai_panggung' => $row['menguasai_panggung']
										];

										// Cari nilai maksimal
										foreach ($max_values as $key => $value) {
											if ($row[$key] > $value) {
												$max_values[$key] = $row[$key];
											}
										}
									}

									// Hitung nilai ARAS dengan bobot PIPRECIA-S
									foreach ($matrik_data as $alt => $data) {
										$normalized = [
											'tinggi_badan' => $data['tinggi_badan'] / $max_values['tinggi_badan'],
											'berat_badan' => $max_values['berat_badan'] / $data['berat_badan'], // Cost criteria
											'berpenampilan_menarik' => $data['berpenampilan_menarik'] / $max_values['berpenampilan_menarik'],
											'menguasai_panggung' => $data['menguasai_panggung'] / $max_values['menguasai_panggung']
										];

										$weighted_sum = 0;
										foreach ($normalized as $key => $value) {
											$weighted_sum += $value * $bobot_kriteria[$key];
										}

										$aras_scores[$alt] = $weighted_sum;
									}

									// 3. Ambil hasil PIPRECIA-S dari database
									$piprecia_scores = [];
									$piprecia_query = mysqli_query($koneksi, "SELECT alternatif, nilai_akhir FROM hasil_piprecia");
									while ($row = mysqli_fetch_assoc($piprecia_query)) {
										$piprecia_scores[$row['alternatif']] = $row['nilai_akhir'];
									}

									// 4. Normalisasi skor ke range 0-1
									$max_aras = max($aras_scores);
									$max_piprecia = max($piprecia_scores);

									foreach ($aras_scores as $alt => $score) {
										$aras_scores[$alt] = $score / $max_aras;
									}

									foreach ($piprecia_scores as $alt => $score) {
										$piprecia_scores[$alt] = $score / $max_piprecia;
									}

									// 5. Hitung skor gabungan dengan bobot (bisa disesuaikan)
									$combined_scores = [];
									foreach ($aras_scores as $alt => $score) {
										if (isset($piprecia_scores[$alt])) {
											$combined_scores[$alt] = [
												'combined' => (0.6 * $score) + (0.4 * $piprecia_scores[$alt]), // Bobot bisa diubah
												'aras' => $score,
												'piprecia' => $piprecia_scores[$alt],
												'details' => $matrik_data[$alt]
											];
										}
									}

									// 6. Urutkan berdasarkan skor gabungan
									arsort($combined_scores);

									// 7. Tampilkan hasil
									$rank = 1;
									foreach ($combined_scores as $alt => $scores) {
										echo '
                                    <tr>
                                        <td>' . $rank++ . '</td>
                                        <td>' . htmlspecialchars($alt) . '</td>
                                        <td>' . number_format($scores['combined'], 4) . '</td>
                                        <td>' . number_format($scores['aras'], 4) . '</td>
                                        <td>' . number_format($scores['piprecia'], 4) . '</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal' . $rank . '">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    ';
									}
									?>
								</tbody>
							</table>
						</div>

						<div class="alert alert-info mt-3">
							<strong>Keterangan:</strong>
							<ul>
								<li>Skor gabungan merupakan kombinasi tertimbang (60% ARAS + 40% PIPRECIA-S)</li>
								<li>ARAS dihitung ulang menggunakan bobot dari PIPRECIA-S untuk konsistensi</li>
								<li>Klik tombol detail untuk melihat nilai kriteria</li>
							</ul>
						</div>
					</div>
				</div>

				<!-- Modal for Details -->
				<?php
				$rank = 1;
				foreach ($combined_scores as $alt => $scores) {
					echo '
                <div class="modal fade" id="detailModal' . $rank . '" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detail Nilai: ' . htmlspecialchars($alt) . '</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Kriteria</th>
                                        <th>Nilai</th>
                                        <th>Normalisasi</th>
                                        <th>Bobot</th>
                                    </tr>
                                    <tr>
                                        <td>Tinggi Badan</td>
                                        <td>' . $scores['details']['tinggi_badan'] . '</td>
                                        <td>' . number_format($scores['details']['tinggi_badan'] / $max_values['tinggi_badan'], 4) . '</td>
                                        <td>' . number_format($bobot_kriteria['tinggi_badan'], 4) . '</td>
                                    </tr>
                                    <tr>
                                        <td>Berat Badan</td>
                                        <td>' . $scores['details']['berat_badan'] . '</td>
                                        <td>' . number_format($max_values['berat_badan'] / $scores['details']['berat_badan'], 4) . '</td>
                                        <td>' . number_format($bobot_kriteria['berat_badan'], 4) . '</td>
                                    </tr>
                                    <tr>
                                        <td>Berpenampilan Menarik</td>
                                        <td>' . $scores['details']['berpenampilan_menarik'] . '</td>
                                        <td>' . number_format($scores['details']['berpenampilan_menarik'] / $max_values['berpenampilan_menarik'], 4) . '</td>
                                        <td>' . number_format($bobot_kriteria['berpenampilan_menarik'], 4) . '</td>
                                    </tr>
                                    <tr>
                                        <td>Menguasai Panggung</td>
                                        <td>' . $scores['details']['menguasai_panggung'] . '</td>
                                        <td>' . number_format($scores['details']['menguasai_panggung'] / $max_values['menguasai_panggung'], 4) . '</td>
                                        <td>' . number_format($bobot_kriteria['menguasai_panggung'], 4) . '</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                ';
					$rank++;
				}
				?>

			</main>
		</div>
	</div>

<?php
	include('../includes/footer.php');
} else {
	echo "<script>alert('Silahkan Login Terlebih Dahulu');document.location.href='../login/index.php';</script>";
}
?>