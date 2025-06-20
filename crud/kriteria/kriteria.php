<?php
include '../../koneksi/koneksi.php';

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../login/index.php");
    exit();
}

// Create
if (isset($_POST['tambah'])) {
    $kriteria = $_POST['kriteria'];
    $bobot_piprecia = $_POST['bobot_piprecia'];
    $jenis = $_POST['jenis']; // benefit/cost

    $query = "INSERT INTO bobot_kriteria (kriteria, bobot_piprecia, jenis) VALUES ('$kriteria', '$bobot_piprecia', '$jenis')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: kriteria.php?success=1");
    } else {
        echo "Gagal menambahkan data: " . mysqli_error($koneksi);
    }
}

// Read
$query = "SELECT * FROM bobot_kriteria ORDER BY id ASC";
$result = mysqli_query($koneksi, $query);
$kriteria = [];
while ($row = mysqli_fetch_assoc($result)) {
    $kriteria[] = $row;
}

// Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $kriteria = $_POST['kriteria'];
    $bobot_piprecia = $_POST['bobot_piprecia'];
    $jenis = $_POST['jenis'];

    $query = "UPDATE bobot_kriteria SET kriteria='$kriteria', bobot_piprecia='$bobot_piprecia', jenis='$jenis' WHERE id='$id'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: kriteria.php?success=2");
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($koneksi);
    }
}

// Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $query = "DELETE FROM bobot_kriteria WHERE id='$id'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: kriteria.php?success=3");
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
}
?>

<?php include '../../includes/header.php'; ?>
<div class="container-fluid mt-4 w-full">
    <div class="row justify-content-center w-full">
        <div class="col-12 col-sm-11 col-md-10 col-lg-9">

            <h2>Data Bobot Kriteria</h2>

            <!-- Explanation about Criteria -->
            <div class="alert alert-info mb-4">
                <strong>Penjelasan Kriteria:</strong>
                <p>Kriteria adalah faktor-faktor penilaian yang digunakan dalam sistem pendukung keputusan PIPRECIA-ARAS. Setiap kriteria memiliki:</p>
                <ul>
                    <li><strong>Nama Kriteria</strong> - Menjelaskan aspek yang dinilai</li>
                    <li><strong>Bobot PIPRECIA</strong> - Nilai penting kriteria (0-1) yang dihitung menggunakan metode PIPRECIA</li>
                    <li><strong>Jenis Kriteria</strong> - Benefit (semakin besar semakin baik) atau Cost (semakin kecil semakin baik)</li>
                </ul>
                <p>Contoh kriteria benefit: Kualitas Produk, Pelayanan. Contoh kriteria cost: Harga, Waktu Pengiriman.</p>
            </div>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <?php
                    if ($_GET['success'] == 1) echo "Data berhasil ditambahkan!";
                    if ($_GET['success'] == 2) echo "Data berhasil diupdate!";
                    if ($_GET['success'] == 3) echo "Data berhasil dihapus!";
                    ?>
                </div>
            <?php endif; ?>

            <!-- Rest of your existing code remains the same -->
            <!-- Form Tambah Data -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Tambah Kriteria Baru</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Nama Kriteria</label>
                                <input type="text" name="kriteria" class="form-control" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Bobot PIPRECIA</label>
                                <input type="number" step="0.01" name="bobot_piprecia" class="form-control" required>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Jenis Kriteria</label>
                                <select name="jenis" class="form-control" required>
                                    <option value="benefit">Benefit</option>
                                    <option value="cost">Cost</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" name="tambah" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>

            <!-- Tabel Data Kriteria -->
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Kriteria</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kriteria</th>
                                    <th>Bobot PIPRECIA</th>
                                    <th>Jenis</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($kriteria)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($kriteria as $index => $item): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= $item['kriteria'] ?></td>
                                            <td><?= $item['bobot_piprecia'] ?></td>
                                            <td>
                                                <span class="badge badge-<?= $item['jenis'] == 'benefit' ? 'success' : 'danger' ?>">
                                                    <?= ucfirst($item['jenis']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <!-- Tombol Edit -->
                                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal<?= $item['id'] ?>">
                                                    Edit
                                                </button>

                                                <!-- Modal Edit -->
                                                <div class="modal fade" id="editModal<?= $item['id'] ?>" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="editModalLabel">Edit Kriteria</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <form method="POST" action="">
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                                                    <div class="form-group">
                                                                        <label>Nama Kriteria</label>
                                                                        <input type="text" name="kriteria" class="form-control" value="<?= $item['kriteria'] ?>" required>
                                                                    </div>
                                                                    <div class="form-row">
                                                                        <div class="form-group col-md-6">
                                                                            <label>Bobot PIPRECIA</label>
                                                                            <input type="number" step="0.01" name="bobot_piprecia" class="form-control" value="<?= $item['bobot_piprecia'] ?>" required>
                                                                        </div>
                                                                        <div class="form-group col-md-6">
                                                                            <label>Jenis Kriteria</label>
                                                                            <select name="jenis" class="form-control" required>
                                                                                <option value="benefit" <?= $item['jenis'] == 'benefit' ? 'selected' : '' ?>>Benefit</option>
                                                                                <option value="cost" <?= $item['jenis'] == 'cost' ? 'selected' : '' ?>>Cost</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                                    <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Tombol Hapus -->
                                                <a href="kriteria.php?delete=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Script -->
<script src="../../js/jquery.min.js"></script>
<script src="../../bootstrap/js/bootstrap.min.js"></script>