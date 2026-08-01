<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}
include "../config/koneksi.php";
// Statistik
$totalTransaksi = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) AS total FROM stok_masuk"));
$totalBarang = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT SUM(jumlah) AS total FROM stok_masuk"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Stok Masuk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
<div class="container-fluid mt-4">
    <!-- Welcome Box -->
<div class="welcome-box mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2>📥 Welcome to Stock In Center</h2>
            <p>
                Kelola seluruh data stok masuk bahan baku Haebeads dengan mudah.
            </p>
        </div>
        <div class="col-md-4 text-end">
            <i class="bi bi-arrow-down-circle-fill display-1 text-white"></i>
        </div>
    </div>
</div>
<!-- Statistik -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="stat-card">
            <i class="bi bi-box-arrow-in-down"></i>
            <h5>Total Transaksi</h5>
            <h2><?= $totalTransaksi['total']; ?></h2>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <i class="bi bi-box-seam"></i>
            <h5>Total Barang Masuk</h5>
            <h2><?= $totalBarang['total'] ?? 0; ?></h2>
        </div>
    </div>
</div>
<!-- Header -->

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>
        <i class="bi bi-arrow-down-circle-fill text-success"></i>
        Data Stok Masuk
    </h3>
    <a href="tambah.php" class="btn btn-pink">
        <i class="bi bi-plus-circle"></i>
        Tambah Stok
    </a>
</div>
<!-- Tabel -->
<div class="card shadow rounded-4">
<div class="card-body">
<div class="table-responsive">
<table class="table table-hover align-middle">
<thead class="table-light">
<tr>
<th>No</th>
<th>Tanggal</th>
<th>Nama Bahan</th>
<th>Jumlah Masuk</th>
<th width="120">Aksi</th>
</tr>
</thead>
<tbody>
<?php
$no = 1;
$data = mysqli_query($conn,"
SELECT stok_masuk.*, bahan_baku.nama_bahan
FROM stok_masuk
INNER JOIN bahan_baku
ON stok_masuk.id_bahan = bahan_baku.id_bahan
ORDER BY tanggal DESC
");
while($d = mysqli_fetch_assoc($data)){
?>
<tr>
<td><?= $no++; ?></td>
<td><?= date('d-m-Y', strtotime($d['tanggal'])); ?></td>
<td><?= $d['nama_bahan']; ?></td>
<td><?= $d['jumlah']; ?></td>
<td>
<a href="edit.php?id=<?= $d['id_masuk']; ?>"
class="btn btn-warning btn-sm">
<i class="bi bi-pencil"></i>
</a>
<a href="hapus.php?id=<?= $d['id_masuk']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Yakin ingin menghapus data?')">
<i class="bi bi-trash"></i>
</a>
</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</body>
</html>
