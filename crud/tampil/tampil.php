<?php
session_start();
if (isset($_SESSION['username'])) {
	include('../../koneksi/koneksi.php');
	include('../../includes/header.php');

	// Get all criteria from database
	$kriteria_list = [];
	$kriteria_query = mysqli_query($koneksi, "SELECT * FROM bobot_kriteria ORDER BY id ASC");
	while ($row = mysqli_fetch_assoc($kriteria_query)) {
		// Convert criteria name to match database column naming convention
		$col_name = strtolower(str_replace(' ', '_', $row['kriteria']));
		$kriteria_list[$col_name] = [
			'display_name' => $row['kriteria'],
			'bobot' => $row['bobot_piprecia'],
			'jenis' => $row['jenis']
		];
	}
?>
	<div class="container-fluid w-full">
		<div class="row">
			<!-- Main Content -->
			<main class="col-md-9 ms-sm-auto col-lg-12 px-md-4 py-4">
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<h1 class="h2">Daftar Alternatif</h1>
					<div class="btn-toolbar mb-2 mb-md-0">
						<div class="btn-group me-2">
							<a href="../tambah/tambah.php" class="btn btn-sm btn-success">
								<i class="fas fa-plus"></i> Tambah Data
							</a>
							<a href="../hapus/delete_all.php" class="btn btn-sm btn-danger ml-2" onclick="return confirm('Yakin ingin menghapus semua data?')">
								<i class="fas fa-trash-alt"></i> Hapus Semua
							</a>
							<a href="import.php" class="btn btn-sm btn-primary ml-2">
								<i class="fas fa-file-import"></i> Import Excel
							</a>
							<a href="export.php" class="btn btn-sm btn-secondary ml-2">
								<i class="fas fa-file-export"></i> Export Excel
							</a>
						</div>
					</div>
				</div>

				<!-- Explanation Section -->
				<div class="alert alert-info mb-4">
					<strong>Penjelasan Alternatif:</strong>
					<p>Alternatif adalah opsi-opsi yang akan dinilai dalam sistem pendukung keputusan PIPRECIA-ARAS. Setiap alternatif memiliki:</p>
					<ul>
						<li><strong>Nama Alternatif</strong> - Identifikasi unik untuk setiap opsi</li>
						<li><strong>Nilai Kriteria</strong> - Nilai untuk setiap kriteria yang telah ditentukan</li>
					</ul>
					<p>Contoh alternatif bisa berupa produk, vendor, lokasi, atau opsi lain yang sedang dievaluasi. Data alternatif ini akan digunakan dalam proses perhitungan untuk menentukan ranking terbaik.</p>
				</div>

				<div class="card shadow-sm">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover table-striped">
								<thead class="table-dark">
									<tr>
										<th width="5%">No</th>
										<th>Alternatif</th>
										<?php foreach ($kriteria_list as $col_name => $data): ?>
											<th><?= htmlspecialchars($data['display_name']) ?></th>
										<?php endforeach; ?>
										<th width="15%">Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql = mysqli_query($koneksi, "SELECT * FROM data_matrik") or die(mysqli_error($koneksi));
									if (mysqli_num_rows($sql) > 0) {
										$no = 1;
										while ($row = mysqli_fetch_assoc($sql)) {
											echo '
                                        <tr>
                                            <td>' . $no . '</td>
                                            <td>' . htmlspecialchars($row['alternatif']) . '</td>';

											// Display each criteria value
											foreach ($kriteria_list as $kriteria => $data) {
												echo '<td>' . htmlspecialchars($row[$kriteria]) . '</td>';
											}

											echo '
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="../../crud/ubah/edit.php?id=' . $row['id'] . '" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="../../crud/hapus/delete.php?id=' . $row['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin ingin menghapus data ini?\')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        ';
											$no++;
										}
									} else {
										$colspan = count($kriteria_list) + 3; // No + Alternatif + Action + criteria columns
										echo '
                                    <tr>
                                        <td colspan="' . $colspan . '" class="text-center">Tidak ada data.</td>
                                    </tr>
                                    ';
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</main>
		</div>
	</div>

<?php
} else {
	echo "<script>alert('Silahkan Login Terlebih Dahulu');document.location.href='../../login/index.php';</script>";
}
?>