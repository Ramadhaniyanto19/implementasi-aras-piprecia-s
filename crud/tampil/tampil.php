<?php
session_start();
if (isset($_SESSION['username'])) {
	include('../../koneksi/koneksi.php');
	include('../../includes/header.php'); // Assuming this includes your sidebar and head section
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
						</div>
					</div>
				</div>

				<div class="card shadow-sm">
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-hover table-striped">
								<thead class="table-dark">
									<tr>
										<th width="5%">No</th>
										<th>Alternatif</th>
										<th>Tinggi Badan (cm)</th>
										<th>Berat Badan (kg)</th>
										<th>Berpenampilan Menarik</th>
										<th>Menguasai Panggung</th>
										<th width="15%">Aksi</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$sql = mysqli_query($koneksi, "SELECT * FROM data_primer") or die(mysqli_error($koneksi));
									if (mysqli_num_rows($sql) > 0) {
										$no = 1;
										while ($data = mysqli_fetch_assoc($sql)) {
											echo '
                                        <tr>
                                            <td>' . $no . '</td>
                                            <td>' . $data['alternatif'] . '</td>
                                            <td>' . $data['tinggi_badan'] . '</td>
                                            <td>' . $data['berat_badan'] . '</td>
                                            <td>' . $data['berpenampilan_menarik'] . '</td>
                                            <td>' . $data['menguasai_panggung'] . '</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="../../crud/ubah/edit.php?id=' . $data['id'] . '" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="../../crud/hapus/delete.php?id=' . $data['id'] . '" class="btn btn-sm btn-danger" onclick="return confirm(\'Yakin ingin menghapus data ini?\')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        ';
											$no++;
										}
									} else {
										echo '
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data.</td>
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

	<footer class="footer mt-auto py-3 bg-light">
		<div class="container text-center">
			<span class="text-muted">© 2023 E-Assessment</span>
		</div>
	</footer>

<?php
} else {
	echo "<script>alert('Silahkan Login Terlebih Dahulu');document.location.href='../../login/index.php';</script>";
}
?>