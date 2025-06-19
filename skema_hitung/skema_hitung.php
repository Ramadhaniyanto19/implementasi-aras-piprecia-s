<?php
session_start();
if (isset($_SESSION['username'])) {
?>

	<?php
	//memasukkan file config.php
	include('../koneksi/koneksi.php');
	?>

	<?php include('../includes/header.php'); ?>

	<h1 class="ml-4 pt-5">Skema Perhitungan ARAS & PIPRECIA-S</h1>
	<div class="container-fluid px-0">
		<div class="row no-gutters">
			<div class="col-12 p-4">
				<h2>KONVERSI DATA ALTERNATIF</h2>
				<hr>
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm table-bordered w-100 m-0">
						<thead class="thead-dark">
							<tr>
								<th>NO</th>
								<th>ALTERNATIF</th>
								<th>KRITERIA (C1)</th>
								<th>KRITERIA (C2)</th>
								<th>KRITERIA (C3)</th>
								<th>KRITERIA (C4)</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = mysqli_query($koneksi, "SELECT * FROM data_konversi") or die(mysqli_error($koneksi));
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
                                </tr>
                                ';
									$no++;
								}
							} else {
								echo '<tr><td colspan="6">Tidak ada data.</td></tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="row no-gutters mt-4">
			<div class="col-12 p-4">
				<h2>MATRIK KEPUTUSAN (ARAS)</h2>
				<hr>
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm table-bordered w-100 m-0">
						<thead class="thead-dark">
							<tr>
								<th>NO</th>
								<th>ALTERNATIF</th>
								<th>KRITERIA (C1)</th>
								<th>KRITERIA (C2)</th>
								<th>KRITERIA (C3)</th>
								<th>KRITERIA (C4)</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = mysqli_query($koneksi, "SELECT * FROM data_matrik") or die(mysqli_error($koneksi));
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
                                </tr>
                                ';
									$no++;
								}
							} else {
								echo '<tr><td colspan="6">Tidak ada data.</td></tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="row no-gutters mt-4">
			<div class="col-12 p-4">
				<h2>MATRIKS HASIL NORMALISASI (ARAS)</h2>
				<hr>
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm table-bordered w-100 m-0">
						<thead class="thead-dark">
							<tr>
								<th>NO</th>
								<th>ALTERNATIF</th>
								<th>KRITERIA (C1)</th>
								<th>KRITERIA (C2)</th>
								<th>KRITERIA (C3)</th>
								<th>KRITERIA (C4)</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = mysqli_query($koneksi, "SELECT * FROM normalisasi") or die(mysqli_error($koneksi));
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
                                </tr>
                                ';
									$no++;
								}
							} else {
								echo '<tr><td colspan="6">Tidak ada data.</td></tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="row no-gutters mt-4">
			<div class="col-12 p-4">
				<h2>MATRIKS HASIL NORMALISASI TERBOBOT (ARAS)</h2>
				<hr>
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm table-bordered w-100 m-0">
						<thead class="thead-dark">
							<tr>
								<th>NO</th>
								<th>ALTERNATIF</th>
								<th>KRITERIA (C1)</th>
								<th>KRITERIA (C2)</th>
								<th>KRITERIA (C3)</th>
								<th>KRITERIA (C4)</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = mysqli_query($koneksi, "SELECT * FROM normalisasi_terbobot") or die(mysqli_error($koneksi));
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
                                </tr>
                                ';
									$no++;
								}
							} else {
								echo '<tr><td colspan="6">Tidak ada data.</td></tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="row no-gutters mt-4">
			<div class="col-12 p-4">
				<h2>NILAI DARI FUNGSI OPTIMUM (ARAS)</h2>
				<hr>
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm table-bordered w-100 m-0">
						<thead class="thead-dark">
							<tr>
								<th>NO</th>
								<th>ALTERNATIF</th>
								<th>Nilai Optimum</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = mysqli_query($koneksi, "SELECT * FROM hasil") or die(mysqli_error($koneksi));
							if (mysqli_num_rows($sql) > 0) {
								$no = 1;
								while ($data = mysqli_fetch_assoc($sql)) {
									echo '
                                <tr>
                                    <td>' . $no . '</td>
                                    <td>' . $data['alternatif'] . '</td>
                                    <td>' . $data['nilai_optimum'] . '</td>
                                </tr>
                                ';
									$no++;
								}
							} else {
								echo '<tr><td colspan="3">Tidak ada data.</td></tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="row no-gutters mt-4">
			<div class="col-12 p-4">
				<h2>Matriks Hasil (ARAS)</h2>
				<hr>
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm table-bordered w-100 m-0">
						<thead class="thead-dark">
							<tr>
								<th>NO</th>
								<th>ALTERNATIF</th>
								<th>NILAI</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = mysqli_query($koneksi, "SELECT * FROM hasil2") or die(mysqli_error($koneksi));
							if (mysqli_num_rows($sql) > 0) {
								$no = 1;
								while ($data = mysqli_fetch_assoc($sql)) {
									echo '
                                <tr>
                                    <td>' . $no . '</td>
                                    <td>' . $data['alternatif'] . '</td>
                                    <td>' . $data['nilai_akhir'] . '</td>
                                </tr>
                                ';
									$no++;
								}
							} else {
								echo '<tr><td colspan="3">Tidak ada data.</td></tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="row no-gutters mt-4">
			<div class="col-12 p-4">
				<h2>MATRIKS NORMALISASI (PIPRECIA-S)</h2>
				<hr>
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm table-bordered w-100 m-0">
						<thead class="thead-dark">
							<tr>
								<th>NO</th>
								<th>ALTERNATIF</th>
								<th>KRITERIA (C1)</th>
								<th>KRITERIA (C2)</th>
								<th>KRITERIA (C3)</th>
								<th>KRITERIA (C4)</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = mysqli_query($koneksi, "SELECT * FROM normalisasi_piprecia") or die(mysqli_error($koneksi));
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
                                </tr>
                                ';
									$no++;
								}
							} else {
								echo '<tr><td colspan="6">Tidak ada data.</td></tr>';
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="row no-gutters mt-4 mb-5">
			<div class="col-12 p-4">
				<h2>HASIL PERANGKINGAN (PIPRECIA-S)</h2>
				<hr>
				<div class="table-responsive">
					<table class="table table-striped table-hover table-sm table-bordered w-100 m-0">
						<thead class="thead-dark">
							<tr>
								<th>NO</th>
								<th>ALTERNATIF</th>
								<th>NILAI</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = mysqli_query($koneksi, "SELECT * FROM hasil_piprecia ORDER BY nilai_akhir DESC") or die(mysqli_error($koneksi));
							if (mysqli_num_rows($sql) > 0) {
								$no = 1;
								while ($data = mysqli_fetch_assoc($sql)) {
									echo '
                                <tr>
                                    <td>' . $no . '</td>
                                    <td>' . $data['alternatif'] . '</td>
                                    <td>' . $data['nilai_akhir'] . '</td>
                                </tr>
                                ';
									$no++;
								}
							} else {
								echo '<tr><td colspan="3">Tidak ada data.</td></tr>';
							}
							?>
						</tbody>
					</table>
				</div>
				<div class="text-center mt-4">
					<a href="../hitung/hitung.php" class="btn btn-warning">Kembali ke Perankingan</a>
				</div>
			</div>
		</div>
	</div>

	<?php include('../includes/footer.php'); ?>

<?php
} else {
	echo "<script language=\"javascript\">alert(\"Silahkan Login Terlebih Dahulu\");document.location.href='../login/index.php';</script>";
}
?>