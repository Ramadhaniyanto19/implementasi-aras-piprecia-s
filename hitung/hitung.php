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
					<h1 class="h2">Hasil Perankingan ARAS dengan Bobot PIPRECIA-S</h1>
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
					$col_name = strtolower(str_replace(' ', '_', $row['kriteria']));
					$kriteria_list[$col_name] = [
						'nama' => $row['kriteria'],
						'bobot' => $row['bobot_piprecia'],
						'jenis' => $row['jenis']
					];
				}

				// 2. Ambil data alternatif dari data_matrik
				$alternatif_data = [];
				$matrik_query = mysqli_query($koneksi, "SELECT * FROM data_matrik WHERE alternatif != '-'");

				// 3. Hitung nilai maks/min untuk normalisasi
				$max_min_values = [];
				foreach ($kriteria_list as $col_name => $data) {
					$max_min_values[$col_name] = [
						'max' => 0,
						'min' => PHP_INT_MAX,
						'jenis' => $data['jenis']
					];
				}

				// Simpan data dan cari max/min
				while ($row = mysqli_fetch_assoc($matrik_query)) {
					$alt_name = $row['alternatif'];
					$alternatif_data[$alt_name] = [];

					foreach ($kriteria_list as $col_name => $data) {
						$value = $row[$col_name];
						$alternatif_data[$alt_name][$col_name] = $value;

						// Update max/min
						if ($value > $max_min_values[$col_name]['max']) {
							$max_min_values[$col_name]['max'] = $value;
						}
						if ($value < $max_min_values[$col_name]['min']) {
							$max_min_values[$col_name]['min'] = $value;
						}
					}
				}

				// 4. Hitung nilai ARAS dengan bobot PIPRECIA-S
				$aras_results = [];
				foreach ($alternatif_data as $alt_name => $kriteria_values) {
					$total_score = 0;
					$detail_values = [];

					foreach ($kriteria_values as $col_name => $value) {
						$kriteria = $kriteria_list[$col_name];
						$max = $max_min_values[$col_name]['max'];
						$min = $max_min_values[$col_name]['min'];

						// Normalisasi sesuai jenis kriteria
						if ($kriteria['jenis'] == 'benefit') {
							$normalized = ($max == 0) ? 0 : $value / $max;
						} else {
							$normalized = ($value == 0) ? 0 : $min / $value;
						}

						// Hitung nilai terbobot
						$weighted = $normalized * $kriteria['bobot'];
						$total_score += $weighted;

						// Simpan detail untuk ditampilkan
						$detail_values[$col_name] = [
							'nilai' => $value,
							'normalized' => $normalized,
							'weighted' => $weighted
						];
					}

					$aras_results[$alt_name] = [
						'total_score' => $total_score,
						'details' => $detail_values
					];
				}

				// 5. Urutkan berdasarkan nilai tertinggi
				arsort($aras_results);

				// Siapkan data untuk chart
				$chart_labels = [];
				$chart_scores = [];
				$rank = 1;

				foreach ($aras_results as $alt_name => $data) {
					$chart_labels[] = $alt_name;
					$chart_scores[] = $data['total_score'];
					$rank++;
				}
				?>

				<!-- Hasil Perankingan -->
				<div class="card mb-4">
					<div class="card-header bg-primary text-white">
						<h4 class="mb-0">Hasil Perankingan ARAS dengan Bobot PIPRECIA-S</h4>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover">
								<thead class="thead-dark">
									<tr>
										<th>Ranking</th>
										<th>Alternatif</th>
										<th>Nilai ARAS</th>
										<th>Detail</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$rank = 1;
									foreach ($aras_results as $alt_name => $data) {
										$modal_id = 'detailModal_' . md5($alt_name);
										echo '
                                    <tr>
                                        <td>' . $rank . '</td>
                                        <td>' . htmlspecialchars($alt_name) . '</td>
                                        <td>' . number_format($data['total_score'], 4) . '</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#' . $modal_id . '">
                                                <i class="fas fa-info-circle"></i> Detail
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
					</div>
				</div>

				<!-- Chart Hasil ARAS -->
				<div class="card mb-4">
					<div class="card-header bg-success text-white">
						<h5 class="mb-0">Grafik Hasil Perankingan ARAS</h5>
					</div>
					<div class="card-body">
						<canvas id="arasChart" height="300"></canvas>
					</div>
				</div>

				<!-- Chart Bobot Kriteria -->
				<div class="card">
					<div class="card-header bg-info text-white">
						<h5 class="mb-0">Distribusi Bobot Kriteria PIPRECIA-S</h5>
					</div>
					<div class="card-body">
						<canvas id="criteriaChart" height="150"></canvas>
					</div>
				</div>

				<!-- Modal untuk Detail Nilai -->
				<?php
				foreach ($aras_results as $alt_name => $data) {
					$modal_id = 'detailModal_' . md5($alt_name);
					echo '
                <div class="modal fade" id="' . $modal_id . '" tabindex="-1" role="dialog" aria-labelledby="' . $modal_id . 'Label" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="' . $modal_id . 'Label">Detail Nilai: ' . htmlspecialchars($alt_name) . '</h5>
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
                                                <th>Nilai Awal</th>
                                                <th>Normalisasi</th>
                                                <th>Bobot</th>
                                                <th>Nilai Terbobot</th>
                                            </tr>
                                        </thead>
                                        <tbody>';

					foreach ($data['details'] as $col_name => $detail) {
						$kriteria = $kriteria_list[$col_name];
						echo '
                                            <tr>
                                                <td>' . htmlspecialchars($kriteria['nama']) . '</td>
                                                <td>' . $detail['nilai'] . '</td>
                                                <td>' . number_format($detail['normalized'], 4) . '</td>
                                                <td>' . number_format($kriteria['bobot'], 4) . '</td>
                                                <td>' . number_format($detail['weighted'], 4) . '</td>
                                            </tr>';
					}

					echo '
                                        </tbody>
                                        <tfoot class="font-weight-bold">
                                            <tr>
                                                <td colspan="4" class="text-right">Total Nilai ARAS:</td>
                                                <td>' . number_format($data['total_score'], 4) . '</td>
                                            </tr>
                                        </tfoot>
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
				}
				?>

				<!-- Chart Scripts -->
				<script>
					// Chart Hasil ARAS
					const arasCtx = document.getElementById('arasChart').getContext('2d');
					const arasChart = new Chart(arasCtx, {
						type: 'bar',
						data: {
							labels: <?php echo json_encode($chart_labels); ?>,
							datasets: [{
								label: 'Nilai ARAS',
								data: <?php echo json_encode($chart_scores); ?>,
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
										text: 'Nilai ARAS'
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
											return 'Nilai: ' + context.raw.toFixed(4);
										}
									}
								}
							}
						}
					});

					// Chart Bobot Kriteria
					const criteriaCtx = document.getElementById('criteriaChart').getContext('2d');
					const criteriaChart = new Chart(criteriaCtx, {
						type: 'pie',
						data: {
							labels: <?php echo json_encode(array_column($kriteria_list, 'nama')); ?>,
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
											return label + ': ' + value.toFixed(4);
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