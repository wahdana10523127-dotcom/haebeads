<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

// Statistik
$totalTransaksi = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM penjualan
"));

$totalPenjualan = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(total) AS total
FROM penjualan
"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Data Penjualan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

<div class="container-fluid mt-4">

<div class="welcome-box mb-4">

<div class="row align-items-center">

<div class="col-md-8">

<h2>🛒 Welcome to Sales Center</h2>

<p>
Kelola seluruh transaksi penjualan Haebeads.
</p>

</div>

<div class="col-md-4 text-end">

<i class="bi bi-cart-check-fill display-1 text-white"></i>

</div>

</div>

</div>

<div class="row mb-4">

<div class="col-md-6">

<div class="stat-card">

<i class="bi bi-receipt"></i>

<h5>Total Transaksi</h5>

<h2><?= $totalTransaksi['total']; ?></h2>

</div>

</div>

<div class="col-md-6">

<div class="stat-card">

<i class="bi bi-cash-stack"></i>

<h5>Total Penjualan</h5>

<h2>

Rp<?= number_format($totalPenjualan['total'] ?? 0,0,',','.'); ?>

</h2>

</div>

</div>

</div>

<div class="d-flex justify-content-between align-items-center mb-3">

<h3>

<i class="bi bi-cart-fill text-success"></i>

Data Penjualan

</h3>

<a
href="tambah.php"
class="btn btn-pink">

<i class="bi bi-plus-circle"></i>

Tambah Penjualan

</a>

</div>

<div class="card shadow rounded-4">

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover">

<thead class="table-light">

<tr>

<th>No</th>

<th>Tanggal</th>

<th>Keterangan</th>

<th>Total</th>

<th>Jumlah Produk</th>

<th>Aksi</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

$data=mysqli_query($conn,"
SELECT *
FROM penjualan
ORDER BY tanggal DESC
");

while($d=mysqli_fetch_assoc($data)){

$jumlahProduk=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM detail_penjualan
WHERE id_penjualan='".$d['id_penjualan']."'
"));

?>

<tr>

<td><?= $no++; ?></td>

<td><?= date('d-m-Y',strtotime($d['tanggal'])); ?></td>

<td><?= $d['keterangan']; ?></td>

<td>

Rp<?= number_format($d['total'],0,',','.'); ?>

</td>

<td>

<?= $jumlahProduk['total']; ?> Produk

</td>

<td>

<a
href="edit.php?id=<?= $d['id_penjualan']; ?>"
class="btn btn-warning btn-sm">

<i class="bi bi-pencil"></i>

</a>

<a
href="hapus.php?id=<?= $d['id_penjualan']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Yakin ingin menghapus transaksi?');">

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

<script>
window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
        location.reload();
    }
});

if (performance.navigation.type === 2) {
    location.reload();
}
</script>

<script>
if (history.replaceState) {
    history.replaceState(null, null, location.href);
}

window.addEventListener("pageshow", function (event) {
    if (event.persisted) {
        location.reload();
    }
});
</script>

</body>

</html>