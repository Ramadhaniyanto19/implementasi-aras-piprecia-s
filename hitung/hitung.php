<?php
session_start();
if (isset($_SESSION['username'])) {
?>

	<?php
	//memasukkan file config.php
	include('../koneksi/koneksi.php');
	?>

	<?php include('../includes/header.php'); ?>

	<div class="mt-5 ml-4">
		<h2>Hasil Perankingan Alternatif <i class="fas fa-1x fa-calculator mr-2"></i> </h2>
		<hr>

		<!-- ARAS Method Calculation -->
		<!-- Langkah 1 -->
		<!-- Normalisasi Matriks per Kriteria -->
		<!-- Mendapatkan Nilai Max per Kriteria -->
		<?php
		$query_mysql = mysqli_query($koneksi, "SELECT MAX(tinggi_badan) AS maximum FROM data_konversi") or die(mysqli_error());
		$row = mysqli_fetch_assoc($query_mysql);
		$maxk1 = $row['maximum'];
		?>
		<?php
		$query_mysql = mysqli_query($koneksi, "SELECT MAX(berat_badan) AS maximum FROM data_konversi") or die(mysqli_error());
		$row = mysqli_fetch_assoc($query_mysql);
		$maxk2 = $row['maximum'];
		?>
		<?php
		$query_mysql = mysqli_query($koneksi, "SELECT MAX(berpenampilan_menarik) AS maximum FROM data_konversi") or die(mysqli_error());
		$row = mysqli_fetch_assoc($query_mysql);
		$maxk3 = $row['maximum'];
		?>
		<?php
		$query_mysql = mysqli_query($koneksi, "SELECT MAX(menguasai_panggung) AS maximum FROM data_konversi") or die(mysqli_error());
		$row = mysqli_fetch_assoc($query_mysql);
		$maxk4 = $row['maximum'];
		?>

		<!-- Langkah 1 -->
		<!-- Matriks Keputusan -->
		<?php
		include('../koneksi/koneksi.php');
		mysqli_query($koneksi, "TRUNCATE TABLE data_matrik") or die(mysqli_error());
		mysqli_query($koneksi, "INSERT INTO data_matrik VALUES('','-','$maxk1','$maxk2','$maxk3','$maxk4')");
		$query_mysql = mysqli_query($koneksi, "SELECT * FROM data_konversi") or die(mysqli_error());
		$nomor = 1;
		$sumk1 = 0;
		$sumk2 = 0;
		$sumk3 = 0;
		$sumk4 = 0;
		while ($data = mysqli_fetch_array($query_mysql)) { ?>
			<?php
			$nama = $data['alternatif'];
			$k1 = $data['tinggi_badan'];
			$k2 = $data['berat_badan'];
			$k3 = $data['berpenampilan_menarik'];
			$k4 = $data['menguasai_panggung'];

			mysqli_query($koneksi, "INSERT INTO data_matrik VALUES('','$nama','$k1','$k2','$k3','$k4')");
			?>
		<?php } ?>

		<!-- Langkah 2 -->
		<!-- Normalisasi Matriks -->
		<!-- Normalisasi Matriks per Kriteria -->
		<?php
		include('../koneksi/koneksi.php');
		mysqli_query($koneksi, "TRUNCATE TABLE normalisasi") or die(mysqli_error());
		$query_mysql = mysqli_query($koneksi, "SELECT * FROM data_matrik") or die(mysqli_error());
		$nomor = 1;
		$sumk1 = 0;
		$sumk2 = 0;
		$sumk3 = 0;
		$sumk4 = 0;
		while ($data = mysqli_fetch_array($query_mysql)) { ?>
			<?php
			$nama = $data['alternatif'];
			$k1 = $data['tinggi_badan'];
			$k2 = $data['berat_badan'];
			$k3 = $data['berpenampilan_menarik'];
			$k4 = $data['menguasai_panggung'];
			$sumk1 = $sumk1 + $k1;
			$sumk2 = $sumk2 + $k2;
			$sumk3 = $sumk3 + $k3;
			$sumk4 = $sumk4 + $k4;
			?>
		<?php } ?>


		<?php
		include('../koneksi/koneksi.php');
		$query_mysql = mysqli_query($koneksi, "TRUNCATE TABLE normalisasi") or die(mysqli_error());
		?>
		<br>

		<?php
		include('../koneksi/koneksi.php');
		$query_mysql = mysqli_query($koneksi, "SELECT * FROM data_matrik") or die(mysqli_error());
		$nomor = 1;
		while ($data = mysqli_fetch_array($query_mysql)) { ?>
			<?php
			$nama = $data['alternatif'];
			$k1 = $data['tinggi_badan'];
			$k2 = $data['berat_badan'];
			$k3 = $data['berpenampilan_menarik'];
			$k4 = $data['menguasai_panggung'];
			$kn1 = $k1 / $sumk1;
			$kn1 = round($kn1, 4);
			$kn2 = $k2 / $sumk2;
			$kn2 = round($kn2, 4);
			$kn3 = $k3 / $sumk3;
			$kn3 = round($kn3, 4);
			$kn4 = $k4 / $sumk4;
			$kn4 = round($kn4, 4);
			mysqli_query($koneksi, "INSERT INTO normalisasi VALUES('','$nama','$kn1','$kn2','$kn3','$kn4')");
			?>
		<?php } ?>

		<!-- Langkah 3 -->
		<!-- Menghitung Nilai Normalisasi * Bobot -->
		<?php
		include('../koneksi/koneksi.php');
		$query_mysql = mysqli_query($koneksi, "TRUNCATE TABLE normalisasi_terbobot") or die(mysqli_error());
		?>
		<br>

		<?php
		include('../koneksi/koneksi.php');
		$query_mysql = mysqli_query($koneksi, "SELECT * FROM normalisasi") or die(mysqli_error());
		$nomor = 1;
		while ($data = mysqli_fetch_array($query_mysql)) { ?>
			<?php
			$nama = $data['alternatif'];
			$k1 = $data['tinggi_badan'];
			$k2 = $data['berat_badan'];
			$k3 = $data['berpenampilan_menarik'];
			$k4 = $data['menguasai_panggung'];
			$nil1 = $k1 * 0.4;
			$nil1 = round($nil1, 4);
			$nil2 = $k2 * 0.3;
			$nil2 = round($nil2, 4);
			$nil3 = $k3 * 0.2;
			$nil3 = round($nil3, 4);
			$nil4 = $k4 * 0.1;
			$nil4 = round($nil4, 4);

			mysqli_query($koneksi, "INSERT INTO normalisasi_terbobot VALUES('','$nama','$nil1','$nil2','$nil3','$nil4')");
			?>
		<?php } ?>

		<!-- Langkah 4 -->
		<!-- Menentukan Nilai Dari Fungsi Optimum -->
		<?php
		include('../koneksi/koneksi.php');
		$query_mysql = mysqli_query($koneksi, "TRUNCATE TABLE hasil") or die(mysqli_error());
		?>
		<br>

		<?php
		include('../koneksi/koneksi.php');
		$query_mysql = mysqli_query($koneksi, "SELECT * FROM normalisasi_terbobot") or die(mysqli_error());
		$nomor = 1;
		while ($data = mysqli_fetch_array($query_mysql)) { ?>
			<?php
			$nama = $data['alternatif'];
			$k1 = $data['tinggi_badan'];
			$k2 = $data['berat_badan'];
			$k3 = $data['berpenampilan_menarik'];
			$k4 = $data['menguasai_panggung'];
			$nilop = $k1 + $k2 + $k3 + $k4;

			mysqli_query($koneksi, "INSERT INTO hasil VALUES('','$nama','$nilop')");
			?>
		<?php } ?>

		<!-- Langkah 4 -->
		<!-- Perangkingan -->
		<?php
		$query_mysql = mysqli_query($koneksi, "SELECT MAX(nilai_optimum) AS maximum FROM hasil") or die(mysqli_error());
		$row = mysqli_fetch_assoc($query_mysql);
		$maxoptimum = $row['maximum'];
		?>

		<?php
		include('../koneksi/koneksi.php');
		$query_mysql = mysqli_query($koneksi, "TRUNCATE TABLE hasil2") or die(mysqli_error());
		?>
		<br>

		<?php
		include('../koneksi/koneksi.php');
		$query_mysql = mysqli_query($koneksi, "SELECT * FROM hasil") or die(mysqli_error());
		$nomor = 1;
		while ($data = mysqli_fetch_array($query_mysql)) { ?>
			<?php
			$nama = $data['alternatif'];
			$k1 = $data['nilai_optimum'];
			$rang = $k1 / $maxoptimum;
			$rang = round($rang, 4);

			mysqli_query($koneksi, "INSERT INTO hasil2 VALUES('','$nama','$rang')");
			?>
		<?php }

		$del = mysqli_query($koneksi, "DELETE FROM hasil2 WHERE no='1'") or die(mysqli_error($koneksi));
		?>

		<h3 style="margin-top: -80px;">Metode ARAS</h3>

		<table class="table table-striped table-hover table-sm table-bordered">
			<thead class="thead-dark">
				<tr>
					<th>No</th>
					<th>Nama</th>
					<th>Nilai</th>
					<th>Ranking</th>
				</tr>
			</thead>
			<tbody>
				<?php
				//query ke database SELECT tabel mahasiswa urut berdasarkan id yang paling besar
				$sql = mysqli_query($koneksi, "SELECT * FROM hasil2 ORDER by nilai_akhir DESC") or die(mysqli_error($koneksi));
				//jika query diatas menghasilkan nilai > 0 maka menjalankan script di bawah if...
				if (mysqli_num_rows($sql) > 0) {
					//membuat variabel $no untuk menyimpan nomor urut
					$no = 1;
					$rank = 1;
					//melakukan perulangan while dengan dari dari query $sql
					while ($data = mysqli_fetch_assoc($sql)) {
						//menampilkan data perulangan

						echo '
						<tr>
							<td>' . $no . '</td>
							<td>' . $data['alternatif'] . '</td>
							<td>' . $data['nilai_akhir'] . '</td>
							<td>Ranking ke ' . $rank . '</td>
						</tr>
						';
						$no++;
						$rank++;
					}
					//jika query menghasilkan nilai 0
				} else {
					echo '
					<tr>
						<td colspan="6">Tidak ada data.</td>
					</tr>
					';
				}
				?>
			<tbody>
		</table>

		<hr>

		<!-- PIPRECIA-S Method Calculation -->
		<h3>Metode PIPRECIA-S</h3>
		<?php
		// Step 1: Normalize the decision matrix
		mysqli_query($koneksi, "TRUNCATE TABLE normalisasi_piprecia") or die(mysqli_error());

		// Get max values for each criterion
		$query_max_k1 = mysqli_query($koneksi, "SELECT MAX(tinggi_badan) AS max_k1 FROM data_konversi") or die(mysqli_error());
		$max_k1 = mysqli_fetch_assoc($query_max_k1)['max_k1'];

		$query_max_k2 = mysqli_query($koneksi, "SELECT MAX(berat_badan) AS max_k2 FROM data_konversi") or die(mysqli_error());
		$max_k2 = mysqli_fetch_assoc($query_max_k2)['max_k2'];

		$query_max_k3 = mysqli_query($koneksi, "SELECT MAX(berpenampilan_menarik) AS max_k3 FROM data_konversi") or die(mysqli_error());
		$max_k3 = mysqli_fetch_assoc($query_max_k3)['max_k3'];

		$query_max_k4 = mysqli_query($koneksi, "SELECT MAX(menguasai_panggung) AS max_k4 FROM data_konversi") or die(mysqli_error());
		$max_k4 = mysqli_fetch_assoc($query_max_k4)['max_k4'];

		// Get min values for each criterion (for cost criteria if any, but in this case all are benefit)
		$query_min_k2 = mysqli_query($koneksi, "SELECT MIN(berat_badan) AS min_k2 FROM data_konversi") or die(mysqli_error());
		$min_k2 = mysqli_fetch_assoc($query_min_k2)['min_k2'];

		// Normalize the data
		$query_data = mysqli_query($koneksi, "SELECT * FROM data_konversi") or die(mysqli_error());
		while ($data = mysqli_fetch_array($query_data)) {
			$id = $data['id'];
			$nama = $data['alternatif'];

			// Normalize each criterion (all are benefit criteria)
			$norm_k1 = $data['tinggi_badan'] / $max_k1;
			$norm_k2 = $data['berat_badan'] / $max_k2;
			$norm_k3 = $data['berpenampilan_menarik'] / $max_k3;
			$norm_k4 = $data['menguasai_panggung'] / $max_k4;

			// Round to 4 decimal places
			$norm_k1 = round($norm_k1, 4);
			$norm_k2 = round($norm_k2, 4);
			$norm_k3 = round($norm_k3, 4);
			$norm_k4 = round($norm_k4, 4);

			// Insert into normalisasi_piprecia table
			mysqli_query($koneksi, "INSERT INTO normalisasi_piprecia VALUES('$id', '$nama', '$norm_k1', '$norm_k2', '$norm_k3', '$norm_k4')") or die(mysqli_error());
		}

		// Step 2: Calculate weights using PIPRECIA method
		// Since we have 4 criteria, we'll calculate weights for them

		// Step 2.1: Calculate sj values
		$sj = array();

		// For K1 (compare with K2, K3, K4)
		$sum_k1 = 0;
		$query_k1 = mysqli_query($koneksi, "SELECT tinggi_badan FROM data_konversi") or die(mysqli_error());
		while ($row = mysqli_fetch_assoc($query_k1)) {
			$sum_k1 += $row['tinggi_badan'];
		}

		$query_k2 = mysqli_query($koneksi, "SELECT berat_badan FROM data_konversi") or die(mysqli_error());
		$sum_k2 = 0;
		while ($row = mysqli_fetch_assoc($query_k2)) {
			$sum_k2 += $row['berat_badan'];
		}

		$query_k3 = mysqli_query($koneksi, "SELECT berpenampilan_menarik FROM data_konversi") or die(mysqli_error());
		$sum_k3 = 0;
		while ($row = mysqli_fetch_assoc($query_k3)) {
			$sum_k3 += $row['berpenampilan_menarik'];
		}

		$query_k4 = mysqli_query($koneksi, "SELECT menguasai_panggung FROM data_konversi") or die(mysqli_error());
		$sum_k4 = 0;
		while ($row = mysqli_fetch_assoc($query_k4)) {
			$sum_k4 += $row['menguasai_panggung'];
		}

		// Calculate sj values (relative importance)
		$sj[1] = ($sum_k1 > $sum_k2) ? ($sum_k2 / $sum_k1) : ($sum_k1 / $sum_k2);
		$sj[2] = ($sum_k1 > $sum_k3) ? ($sum_k3 / $sum_k1) : ($sum_k1 / $sum_k3);
		$sj[3] = ($sum_k1 > $sum_k4) ? ($sum_k4 / $sum_k1) : ($sum_k1 / $sum_k4);
		$sj[4] = ($sum_k2 > $sum_k3) ? ($sum_k3 / $sum_k2) : ($sum_k2 / $sum_k3);
		$sj[5] = ($sum_k2 > $sum_k4) ? ($sum_k4 / $sum_k2) : ($sum_k2 / $sum_k4);
		$sj[6] = ($sum_k3 > $sum_k4) ? ($sum_k4 / $sum_k3) : ($sum_k3 / $sum_k4);

		// Step 2.2: Calculate kj values
		$kj = array();
		$kj[1] = 1; // First criterion

		for ($i = 2; $i <= 4; $i++) {
			$kj[$i] = 1 + (($sj[$i - 1] + $sj[$i]) / 2);
		}

		// Step 2.3: Calculate qj values
		$qj = array();
		$qj[1] = 1; // First criterion

		for ($i = 2; $i <= 4; $i++) {
			$qj[$i] = $qj[$i - 1] / $kj[$i];
		}

		// Step 2.4: Calculate weights (wj)
		$sum_qj = array_sum($qj);
		$wj = array();

		for ($i = 1; $i <= 4; $i++) {
			$wj[$i] = $qj[$i] / $sum_qj;
		}

		// Assign weights to criteria (based on your original weights)
		$weight_k1 = $wj[1]; // tinggi badan
		$weight_k2 = $wj[2]; // berat badan
		$weight_k3 = $wj[3]; // berpenampilan menarik
		$weight_k4 = $wj[4]; // menguasai panggung

		// Step 3: Calculate weighted normalized matrix
		// We'll use the same table structure as ARAS for simplicity
		mysqli_query($koneksi, "TRUNCATE TABLE hasil_piprecia") or die(mysqli_error());

		$query_normalized = mysqli_query($koneksi, "SELECT * FROM normalisasi_piprecia") or die(mysqli_error());
		while ($data = mysqli_fetch_array($query_normalized)) {
			$id = $data['id'];
			$nama = $data['alternatif'];

			// Get normalized values
			$norm_k1 = $data['tinggi_badan'];
			$norm_k2 = $data['berat_badan'];
			$norm_k3 = $data['berpenampilan_menarik'];
			$norm_k4 = $data['menguasai_panggung'];

			// Calculate weighted values
			$weighted_k1 = $norm_k1 * $weight_k1;
			$weighted_k2 = $norm_k2 * $weight_k2;
			$weighted_k3 = $norm_k3 * $weight_k3;
			$weighted_k4 = $norm_k4 * $weight_k4;

			// Calculate final score (sum of weighted values)
			$final_score = $weighted_k1 + $weighted_k2 + $weighted_k3 + $weighted_k4;
			$final_score = round($final_score, 4);

			// Insert into hasil_piprecia table
			mysqli_query($koneksi, "INSERT INTO hasil_piprecia VALUES('$id', '$nama', '$final_score')") or die(mysqli_error());
		}
		?>

		<!-- Display PIPRECIA-S Results -->
		<table class="table table-striped table-hover table-sm table-bordered">
			<thead class="thead-dark">
				<tr>
					<th>No</th>
					<th>Nama</th>
					<th>Nilai</th>
					<th>Ranking</th>
				</tr>
			</thead>
			<tbody>
				<?php
				//query ke database SELECT tabel mahasiswa urut berdasarkan id yang paling besar
				$sql = mysqli_query($koneksi, "SELECT * FROM hasil_piprecia ORDER by nilai_akhir DESC") or die(mysqli_error($koneksi));
				//jika query diatas menghasilkan nilai > 0 maka menjalankan script di bawah if...
				if (mysqli_num_rows($sql) > 0) {
					//membuat variabel $no untuk menyimpan nomor urut
					$no = 1;
					$rank = 1;
					//melakukan perulangan while dengan dari dari query $sql
					while ($data = mysqli_fetch_assoc($sql)) {
						//menampilkan data perulangan

						echo '
						<tr>
							<td>' . $no . '</td>
							<td>' . $data['alternatif'] . '</td>
							<td>' . $data['nilai_akhir'] . '</td>
							<td>Ranking ke ' . $rank . '</td>
						</tr>
						';
						$no++;
						$rank++;
					}
					//jika query menghasilkan nilai 0
				} else {
					echo '
					<tr>
						<td colspan="6">Tidak ada data.</td>
					</tr>
					';
				}
				?>
			<tbody>
		</table>

		<p align="center">
			<a href="../skema_hitung/skema_hitung.php" class="btn btn-warning">View Skema</a>
			<a href="../laporan/laporan.php" class="btn btn-warning">Cetak Laporan</a>
		</p>
		<hr>
	</div>

<?php
} else {
	echo "<script language=\"javascript\">alert(\"Silahkan Login Terlebih Dahulu\");document.location.href='../login/index.php';</script>";
}
?>