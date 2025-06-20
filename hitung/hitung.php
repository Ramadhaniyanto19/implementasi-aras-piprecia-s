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
				// 1. Ambil semua kriteria dari database
				$kriteria_list = [];
				$kriteria_query = mysqli_query($koneksi, "SELECT * FROM bobot_kriteria");
				while ($row = mysqli_fetch_assoc($kriteria_query)) {
					$kriteria_list[$row['kriteria']] = [
						'bobot' => $row['bobot_piprecia'],
						'jenis' => $row['jenis']
					];
				}

				// 2. Hitung ulang ARAS dengan bobot dari database
				$aras_scores = [];
				$matrik_query = mysqli_query($koneksi, "SELECT * FROM data_matrik WHERE alternatif != '-'");

				// Cari nilai maksimal/minimal setiap kriteria
				$max_min_values = [];
				foreach ($kriteria_list as $kriteria => $data) {
					$max_min_values[$kriteria] = [
						'max' => 0,
						'min' => PHP_INT_MAX,
						'jenis' => $data['jenis']
					];
				}

				$matrik_data = [];
				while ($row = mysqli_fetch_assoc($matrik_query)) {
					$alt_data = [];
					foreach ($kriteria_list as $kriteria => $data) {
						$alt_data[$kriteria] = $row[$kriteria];

						// Update nilai maksimal/minimal
						if ($row[$kriteria] > $max_min_values[$kriteria]['max']) {
							$max_min_values[$kriteria]['max'] = $row[$kriteria];
						}
						if ($row[$kriteria] < $max_min_values[$kriteria]['min']) {
							$max_min_values[$kriteria]['min'] = $row[$kriteria];
						}
					}
					$matrik_data[$row['alternatif']] = $alt_data;
				}

				// Hitung nilai ARAS dengan bobot dari database
				foreach ($matrik_data as $alt => $data) {
					$weighted_sum = 0;

					foreach ($data as $kriteria => $value) {
						$jenis = $max_min_values[$kriteria]['jenis'];
						$max = $max_min_values[$kriteria]['max'];
						$min = $max_min_values[$kriteria]['min'];

						// Normalisasi berdasarkan jenis kriteria
						if ($jenis == 'benefit') {
							$normalized = ($max == 0) ? 0 : $value / $max;
						} else { // cost
							$normalized = ($value == 0) ? 0 : $min / $value;
						}

						$weighted_sum += $normalized * $kriteria_list[$kriteria]['bobot'];
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
					$aras_scores[$alt] = ($max_aras == 0) ? 0 : $score / $max_aras;
				}

				foreach ($piprecia_scores as $alt => $score) {
					$piprecia_scores[$alt] = ($max_piprecia == 0) ? 0 : $score / $max_piprecia;
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
										$modal_id = 'detailModal_' . md5($alt);
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
                                                <th>Jenis</th>
                                            </tr>
                                        </thead>
                                        <tbody>';

					foreach ($kriteria_list as $kriteria => $data) {
						$value = $scores['details'][$kriteria];
						$max = $max_min_values[$kriteria]['max'];
						$min = $max_min_values[$kriteria]['min'];
						$jenis = $data['jenis'];

						if ($jenis == 'benefit') {
							$normalized = ($max == 0) ? 0 : $value / $max;
						} else {
							$normalized = ($value == 0) ? 0 : $min / $value;
						}

						echo '
                                            <tr>
                                                <td>' . htmlspecialchars($kriteria) . '</td>
                                                <td>' . $value . '</td>
                                                <td>' . number_format($normalized, 4) . '</td>
                                                <td>' . number_format($data['bobot'], 4) . '</td>
                                                <td><span class="badge badge-' . ($jenis == 'benefit' ? 'success' : 'danger') . '">' . ucfirst($jenis) . '</span></td>
                                            </tr>';
					}

					echo '
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
							labels: <?php echo json_encode(array_keys($kriteria_list)); ?>,
							datasets: [{
								data: <?php echo json_encode(array_column($kriteria_list, 'bobot')); ?>,
								backgroundColor: [
									'rgba(255, 99, 132, 0.7)',
									'rgba(54, 162, 235, 0.7)',
									'rgba(255, 206, 86, 0.7)',
									'rgba(75, 192, 192, 0.7)',
									'rgba(153, 102, 255, 0.7)'
								],
								borderColor: [
									'rgba(255, 99, 132, 1)',
									'rgba(54, 162, 235, 1)',
									'rgba(255, 206, 86, 1)',
									'rgba(75, 192, 192, 1)',
									'rgba(153, 102, 255, 1)'
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
											const percentage = Math.round((value / <?php echo array_sum(array_column($kriteria_list, 'bobot')); ?>) * 100);
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