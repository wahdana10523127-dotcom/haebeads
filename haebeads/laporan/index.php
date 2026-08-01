<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Laporan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

<div class="container-fluid mt-4">

<div class="welcome-box mb-4">

<div class="row align-items-center">

<div class="col-md-8">

<h2>📊 Report Center</h2>

<p>

Lihat seluruh laporan Haebeads dalam satu halaman.

</p>

</div>

<div class="col-md-4 text-end">

<i class="bi bi-file-earmark-bar-graph-fill display-1 text-white"></i>

</div>

</div>

</div>

<div class="card shadow rounded-4">

<div class="card-body">

<h3 class="mb-4">

<i class="bi bi-table text-success"></i>

Laporan Produk

</h3>

<div class="table-responsive">

<table class="table table-hover">

<thead class="table-light">

<tr>

<th>No</th>

<th>Nama Produk</th>

<th>Harga Jual</th>

<th>Stok</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

$data=mysqli_query($conn,"
SELECT *
FROM produk
ORDER BY nama_produk ASC
");

while($d=mysqli_fetch_assoc($data)){
?>

<tr>

<td><?= $no++; ?></td>

<td><?= $d['nama_produk']; ?></td>

<td>Rp<?= number_format($d['harga_jual'],0,',','.'); ?></td>

<td><?= $d['stok_produk']; ?></td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<br>

<div class="card shadow rounded-4">

<div class="card-body">

<h3 class="mb-4">

<i class="bi bi-cart-fill text-primary"></i>

Laporan Penjualan

</h3>

<div class="table-responsive">

<table class="table table-hover">

<thead class="table-light">

<tr>

<th>No</th>

<th>Tanggal</th>

<th>Keterangan</th>

<th>Total</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

$jual=mysqli_query($conn,"
SELECT *
FROM penjualan
ORDER BY tanggal DESC
");

while($j=mysqli_fetch_assoc($jual)){
?>

<tr>

<td><?= $no++; ?></td>

<td><?= date('d-m-Y',strtotime($j['tanggal'])); ?></td>

<td><?= $j['keterangan']; ?></td>

<td>

Rp<?= number_format($j['total'],0,',','.'); ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<br>

<div class="card shadow rounded-4">

<div class="card-body">

<h3 class="mb-4">

<i class="bi bi-wallet-fill text-danger"></i>

Laporan Pengeluaran

</h3>

<div class="table-responsive">

<table class="table table-hover">

<thead class="table-light">

<tr>

<th>No</th>

<th>Tanggal</th>

<th>Kategori</th>

<th>Nominal</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

$keluar=mysqli_query($conn,"
SELECT *
FROM pengeluaran
ORDER BY tanggal DESC
");

while($k=mysqli_fetch_assoc($keluar)){
?>

<tr>

<td><?= $no++; ?></td>

<td><?= date('d-m-Y',strtotime($k['tanggal'])); ?></td>

<td><?= $k['kategori']; ?></td>

<td>

Rp<?= number_format($k['nominal'],0,',','.'); ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<br>

<div class="card shadow rounded-4">

<div class="card-body">

<h3 class="mb-4">

<i class="bi bi-calculator-fill text-success"></i>

Laporan HPP

</h3>

<div class="table-responsive">

<table class="table table-hover">

<thead class="table-light">

<tr>

<th>No</th>

<th>Produk</th>

<th>Total HPP</th>

<th>Tanggal Hitung</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

$hpp=mysqli_query($conn,"
SELECT
hpp.*,
produk.nama_produk

FROM hpp

INNER JOIN produk
ON hpp.id_produk=produk.id_produk

ORDER BY tanggal_hitung DESC
");

while($h=mysqli_fetch_assoc($hpp)){
?>

<tr>

<td><?= $no++; ?></td>

<td><?= $h['nama_produk']; ?></td>

<td>

Rp<?= number_format($h['total_hpp'],0,',','.'); ?>

</td>

<td>

<?= date('d-m-Y',strtotime($h['tanggal_hitung'])); ?>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<?php

$totalPenjualan=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(total) AS total
FROM penjualan
"));

$totalPengeluaran=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(nominal) AS total
FROM pengeluaran
"));

?>

<br>

<div class="row">

<div class="col-md-6">

<div class="stat-card">

<i class="bi bi-cart-fill"></i>

<h5>Total Penjualan</h5>

<h2>

Rp<?= number_format($totalPenjualan['total'] ?? 0,0,',','.'); ?>

</h2>

</div>

</div>

<div class="col-md-6">

<div class="stat-card">

<i class="bi bi-wallet-fill"></i>

<h5>Total Pengeluaran</h5>

<h2>

Rp<?= number_format($totalPengeluaran['total'] ?? 0,0,',','.'); ?>

</h2>

</div>

</div>

</div>

</div>

</body>

</html>