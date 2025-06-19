<?php
session_start();
if (isset($_SESSION['username'])) {
	include('../koneksi/koneksi.php');
	include('../includes/header.php');
?>

	<div class="container-fluid w-full ml-4" style="padding-right: 160px;">
		<div class="row w-full">
			<main class="col-md-9 w-full col-lg-12 px-md-4 py-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<h1 class="h2">Hasil Perankingan Gabungan</h1>
					<div class="btn-toolbar mb-2 mb-md-0">
						<a href="../skema_hitung/skema_hitung.php" class="btn btn-warning mr-2">
							<i class="fas fa-project-diagram"></i> View Skema
						</a>
						<a href="../laporan/laporan.php" class="btn btn-success">
							<i class="fas fa-file-pdf"></i> Cetak Laporan
						</a>
					</div>
				</div>

				<?php
				// 1. Ambil bobot kriteria dari database
				$bobot_kriteria = [];
				$bobot_query = mysqli_query($koneksi, "SELECT kriteria, bobot_piprecia FROM bobot_kriteria");
				while ($row = mysqli_fetch_assoc($bobot_query)) {
					$bobot_kriteria[$row['kriteria']] = $row['bobot_piprecia'];
				}

				// 2. Hitung ulang ARAS dengan bobot PIPRECIA-S
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

				// 5. Hitung skor gabungan dengan bobot
				$combined_scores = [];
				foreach ($aras_scores as $alt => $score) {
					if (isset($piprecia_scores[$alt])) {
						$combined_scores[$alt] = [
							'combined' => (0.6 * $score) + (0.4 * $piprecia_scores[$alt]),
							'aras' => $score,
							'piprecia' => $piprecia_scores[$alt],
							'details' => $matrik_data[$alt]
						];
					}
				}

				// 6. Urutkan berdasarkan skor gabungan
				arsort($combined_scores);

				// Prepare data for charts
				$chart_labels = [];
				$chart_combined = [];
				$chart_aras = [];
				$chart_piprecia = [];
				$rank = 1;
				foreach ($combined_scores as $alt => $scores) {
					$chart_labels[] = $alt;
					$chart_combined[] = $scores['combined'];
					$chart_aras[] = $scores['aras'];
					$chart_piprecia[] = $scores['piprecia'];
					$rank++;
				}
				?>


				<!-- Combined Results Table -->
				<div class="card mb-4">
					<div class="card-header bg-primary text-white">
						<h4 class="mb-0">Hasil Gabungan ARAS dan PIPRECIA-S</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover">
								<thead class="thead-dark">
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
									$rank = 1;
									foreach ($combined_scores as $alt => $scores) {
										$modal_id = 'detailModal_' . md5($alt); // Create unique ID for modal
										echo '
                                        <tr>
                                            <td>' . $rank . '</td>
                                            <td>' . htmlspecialchars($alt) . '</td>
                                            <td>' . number_format($scores['combined'], 4) . '</td>
                                            <td>' . number_format($scores['aras'], 4) . '</td>
                                            <td>' . number_format($scores['piprecia'], 4) . '</td>
                                            <td>
                                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#' . $modal_id . '">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        ';
										$rank++;
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

				<!-- Charts Section -->
				<div class="row mb-4">
					<!-- Combined Scores Chart -->
					<div class="col-md-6">
						<div class="card">
							<div class="card-header bg-primary text-white">
								<h5 class="mb-0">Perbandingan Skor Gabungan</h5>
							</div>
							<div class="card-body">
								<canvas id="combinedChart" height="300"></canvas>
							</div>
						</div>
					</div>

					<!-- Method Comparison Chart -->
					<div class="col-md-6">
						<div class="card">
							<div class="card-header bg-success text-white">
								<h5 class="mb-0">Perbandingan Metode ARAS vs PIPRECIA-S</h5>
							</div>
							<div class="card-body">
								<canvas id="methodComparisonChart" height="300"></canvas>
							</div>
						</div>
					</div>
				</div>

				<!-- Criteria Weights Chart -->
				<div class="row mb-4">
					<div class="col-md-12">
						<div class="card">
							<div class="card-header bg-info text-white">
								<h5 class="mb-0">Distribusi Bobot Kriteria</h5>
							</div>
							<div class="card-body">
								<canvas id="criteriaWeightsChart" height="150"></canvas>
							</div>
						</div>
					</div>
				</div>


				<!-- Modal for Details -->
				<?php
				$rank = 1;
				foreach ($combined_scores as $alt => $scores) {
					$modal_id = 'detailModal_' . md5($alt);
					echo '
                    <div class="modal fade" id="' . $modal_id . '" tabindex="-1" role="dialog" aria-labelledby="' . $modal_id . 'Label" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title" id="' . $modal_id . 'Label">Detail Nilai: ' . htmlspecialchars($alt) . '</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Kriteria</th>
                                                    <th>Nilai</th>
                                                    <th>Normalisasi</th>
                                                    <th>Bobot</th>
                                                </tr>
                                            </thead>
                                            <tbody>
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
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    ';
					$rank++;
				}
				?>

				<!-- Chart Scripts -->
				<script>
					// Combined Scores Chart (Bar Chart)
					const combinedCtx = document.getElementById('combinedChart').getContext('2d');
					const combinedChart = new Chart(combinedCtx, {
						type: 'bar',
						data: {
							labels: <?php echo json_encode($chart_labels); ?>,
							datasets: [{
								label: 'Skor Gabungan',
								data: <?php echo json_encode($chart_combined); ?>,
								backgroundColor: 'rgba(54, 162, 235, 0.7)',
								borderColor: 'rgba(54, 162, 235, 1)',
								borderWidth: 1
							}]
						},
						options: {
							responsive: true,
							scales: {
								y: {
									beginAtZero: true,
									title: {
										display: true,
										text: 'Nilai Skor'
									}
								},
								x: {
									title: {
										display: true,
										text: 'Alternatif'
									}
								}
							},
							plugins: {
								tooltip: {
									callbacks: {
										label: function(context) {
											return 'Skor: ' + context.raw.toFixed(4);
										}
									}
								}
							}
						}
					});

					// Method Comparison Chart (Line Chart)
					const comparisonCtx = document.getElementById('methodComparisonChart').getContext('2d');
					const comparisonChart = new Chart(comparisonCtx, {
						type: 'line',
						data: {
							labels: <?php echo json_encode($chart_labels); ?>,
							datasets: [{
									label: 'ARAS',
									data: <?php echo json_encode($chart_aras); ?>,
									borderColor: 'rgba(255, 99, 132, 1)',
									backgroundColor: 'rgba(255, 99, 132, 0.1)',
									borderWidth: 2,
									tension: 0.1,
									fill: true
								},
								{
									label: 'PIPRECIA-S',
									data: <?php echo json_encode($chart_piprecia); ?>,
									borderColor: 'rgba(75, 192, 192, 1)',
									backgroundColor: 'rgba(75, 192, 192, 0.1)',
									borderWidth: 2,
									tension: 0.1,
									fill: true
								}
							]
						},
						options: {
							responsive: true,
							scales: {
								y: {
									beginAtZero: true,
									title: {
										display: true,
										text: 'Nilai Skor'
									}
								},
								x: {
									title: {
										display: true,
										text: 'Alternatif'
									}
								}
							},
							plugins: {
								tooltip: {
									callbacks: {
										label: function(context) {
											return context.dataset.label + ': ' + context.raw.toFixed(4);
										}
									}
								}
							}
						}
					});

					// Criteria Weights Chart (Pie Chart)
					const criteriaCtx = document.getElementById('criteriaWeightsChart').getContext('2d');
					const criteriaChart = new Chart(criteriaCtx, {
						type: 'pie',
						data: {
							labels: <?php echo json_encode(array_keys($bobot_kriteria)); ?>,
							datasets: [{
								data: <?php echo json_encode(array_values($bobot_kriteria)); ?>,
								backgroundColor: [
									'rgba(255, 99, 132, 0.7)',
									'rgba(54, 162, 235, 0.7)',
									'rgba(255, 206, 86, 0.7)',
									'rgba(75, 192, 192, 0.7)'
								],
								borderColor: [
									'rgba(255, 99, 132, 1)',
									'rgba(54, 162, 235, 1)',
									'rgba(255, 206, 86, 1)',
									'rgba(75, 192, 192, 1)'
								],
								borderWidth: 1
							}]
						},
						options: {
							responsive: true,
							plugins: {
								legend: {
									position: 'right',
								},
								tooltip: {
									callbacks: {
										label: function(context) {
											const label = context.label || '';
											const value = context.raw || 0;
											const percentage = Math.round((value / <?php echo array_sum($bobot_kriteria); ?>) * 100);
											return `${label}: ${value.toFixed(4)} (${percentage}%)`;
										}
									}
								}
							}
						}
					});
				</script>

			</main>
		</div>
	</div>

<?php
	include('../includes/footer.php');
} else {
	echo "<script>alert('Silahkan Login Terlebih Dahulu');document.location.href='../login/index.php';</script>";
}
?>