<?php
include '../../koneksi/koneksi.php';

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../login/index.php");
    exit();
}

// Function to calculate PIPRECIA weights
// Modify the calculatePIPRECIAWeights function to this:

function calculatePIPRECIAWeights($koneksi)
{
    // Reset semua nilai perhitungan sebelumnya
    mysqli_query($koneksi, "UPDATE bobot_kriteria SET 
        rank_piprecia = NULL,
        sj = NULL,
        kj = NULL,
        qj = NULL,
        bobot_piprecia = NULL");

    // Step 1: Dapatkan kriteria dan set ranking
    $result = mysqli_query($koneksi, "SELECT id FROM bobot_kriteria ORDER BY id ASC");
    $ids = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $ids[] = $row['id'];
    }

    // Step 2: Set ranking (1 = paling penting)
    foreach ($ids as $index => $id) {
        $rank = $index + 1;
        mysqli_query($koneksi, "UPDATE bobot_kriteria SET rank_piprecia = $rank WHERE id = $id");
    }

    // Step 3: Hitung sj (importance relative)
    $result = mysqli_query($koneksi, "SELECT id, rank_piprecia FROM bobot_kriteria ORDER BY rank_piprecia ASC");
    while ($row = mysqli_fetch_assoc($result)) {
        $sj = ($row['rank_piprecia'] == 1) ? 0 : 1 + (($row['rank_piprecia'] - 1) * 0.2);
        mysqli_query($koneksi, "UPDATE bobot_kriteria SET sj = $sj WHERE id = {$row['id']}");
    }

    // Step 4: Hitung kj
    mysqli_query($koneksi, "UPDATE bobot_kriteria SET kj = IF(rank_piprecia = 1, 1, sj + 1)");

    // Step 5: Hitung qj
    $qj_previous = 1;
    $result = mysqli_query($koneksi, "SELECT id, kj FROM bobot_kriteria ORDER BY rank_piprecia ASC");
    while ($row = mysqli_fetch_assoc($result)) {
        $qj = ($row['kj'] == 1) ? 1 : $qj_previous / $row['kj'];
        mysqli_query($koneksi, "UPDATE bobot_kriteria SET qj = $qj WHERE id = {$row['id']}");
        $qj_previous = $qj;
    }

    // Step 6: Hitung bobot akhir (wj)
    $sum_qj = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(qj) as total FROM bobot_kriteria"))['total'];
    mysqli_query($koneksi, "UPDATE bobot_kriteria SET bobot_piprecia = qj / $sum_qj");

    // Validasi bobot
    $check = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(bobot_piprecia) as total FROM bobot_kriteria"));
    if (abs($check['total'] - 1) > 0.01) {
        // Jika total bobot tidak 1, lakukan normalisasi ulang
        mysqli_query($koneksi, "UPDATE bobot_kriteria SET 
            bobot_piprecia = bobot_piprecia / {$check['total']}");
    }
}


// Create
if (isset($_POST['tambah'])) {
    $kriteria = $_POST['kriteria'];
    $jenis = $_POST['jenis']; // benefit/cost

    $query = "INSERT INTO bobot_kriteria (kriteria, jenis) VALUES ('$kriteria', '$jenis')";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        // After adding new criterion, recalculate weights
        calculatePIPRECIAWeights($koneksi);
        header("Location: kriteria.php?success=1");
    } else {
        echo "Gagal menambahkan data: " . mysqli_error($koneksi);
    }
}

// Read
$query = "SELECT * FROM bobot_kriteria ORDER BY rank_piprecia ASC";
$result = mysqli_query($koneksi, $query);
$kriteria = [];
while ($row = mysqli_fetch_assoc($result)) {
    $kriteria[] = $row;
}

// Update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $kriteria = $_POST['kriteria'];
    $jenis = $_POST['jenis'];

    $query = "UPDATE bobot_kriteria SET kriteria='$kriteria', jenis='$jenis' WHERE id='$id'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        // After updating, recalculate weights
        calculatePIPRECIAWeights($koneksi);
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
        // After deleting, recalculate weights
        calculatePIPRECIAWeights($koneksi);
        header("Location: kriteria.php?success=3");
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
}

// Calculate weights on initial load if not calculated yet
$check_weights = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM bobot_kriteria WHERE bobot_piprecia IS NULL");
if (mysqli_fetch_assoc($check_weights)['count'] > 0) {
    calculatePIPRECIAWeights($koneksi);
}
?>

<!-- Rest of your HTML remains the same, but remove the weight input field -->
<?php include '../../includes/header.php'; ?>
<div class="container-fluid mt-4 w-full">
    <div class="row justify-content-center w-full">
        <div class="col-12 col-sm-11 col-md-10 col-lg-9">

            <h2>Data Bobot Kriteria</h2>

            <div class="alert alert-info mb-4">
                <strong>Metode PIPRECIA:</strong>
                <p>Bobot kriteria dihitung otomatis menggunakan metode PIPRECIA. Sistem akan:</p>
                <ol>
                    <li>Menentukan ranking kriteria berdasarkan urutan input</li>
                    <li>Menghitung nilai kepentingan relatif (sj)</li>
                    <li>Menghitung nilai kj dan qj</li>
                    <li>Menghitung bobot akhir (wj) yang dinormalisasi</li>
                </ol>
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

            <!-- Form Tambah Data - Remove weight input -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Tambah Kriteria Baru</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="form-row">
                            <div class="form-group col-md-8">
                                <label>Nama Kriteria</label>
                                <input type="text" name="kriteria" class="form-control" required>
                            </div>
                            <div class="form-group col-md-4">
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
                                    <th>Ranking</th>
                                    <th>Bobot PIPRECIA</th>
                                    <th>Jenis</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($kriteria)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($kriteria as $index => $item): ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= $item['kriteria'] ?></td>
                                            <td><?= $item['rank_piprecia'] ?></td>
                                            <td><?= number_format($item['bobot_piprecia'], 4) ?></td>
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

                                                <!-- Modal Edit - Remove weight input -->
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
                                                                    <div class="form-group">
                                                                        <label>Jenis Kriteria</label>
                                                                        <select name="jenis" class="form-control" required>
                                                                            <option value="benefit" <?= $item['jenis'] == 'benefit' ? 'selected' : '' ?>>Benefit</option>
                                                                            <option value="cost" <?= $item['jenis'] == 'cost' ? 'selected' : '' ?>>Cost</option>
                                                                        </select>
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