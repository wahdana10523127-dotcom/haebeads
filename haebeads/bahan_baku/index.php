<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}
include "../config/koneksi.php";
// Statistik
$totalBahan = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM bahan_baku"));
$totalStok = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(stok) AS total FROM bahan_baku"));
$totalNilai = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(stok * harga_satuan) AS total FROM bahan_baku"));
$totalROP = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM bahan_baku WHERE stok<=rop"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Bahan Baku</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
<div class="container-fluid mt-4">
    <div class="welcome-box mb-4">
<div class="row align-items-center">
<div class="col-md-8">
<h2>
💎 Welcome to Material Center
</h2>
<p>
Semua bahan baku Haebeads dapat dikelola dari halaman ini.
</p>
</div>
<div class="col-md-4 text-end">
<i class="bi bi-gem display-1 text-white"></i>
</div>
</div>
</div>
<div class="row mb-4">
<div class="col-md-3">
<div class="stat-card">
<i class="bi bi-box-seam"></i>
<h5>Total Bahan</h5>
<h2><?= $totalBahan['total']; ?></h2>
</div>
</div>
<div class="col-md-3">
<div class="stat-card">
<i class="bi bi-stack"></i>
<h5>Total Stok</h5>
<h2><?= $totalStok['total'] ?? 0; ?></h2>
</div>
</div>
<div class="col-md-3">
<div class="stat-card">
<i class="bi bi-cash-stack"></i>
<h5>Nilai Persediaan</h5>
<h2>
Rp<?= number_format($totalNilai['total'] ?? 0, 0, ",", "."); ?>
</h2>
</div>
</div>
<div class="col-md-3">
<div class="stat-card">
<i class="bi bi-exclamation-triangle"></i>
<h5>Stok Menipis</h5>
<h2><?= $totalROP['total']; ?></h2>
</div>
</div>
</div>
<div class="card shadow rounded-4 mb-4">
<div class="card-body">
<form method="GET">
<div class="row">
<div class="col-md-10">
<input
type="text"
name="cari"
class="form-control"
placeholder="Cari nama bahan...">
</div>
<div class="col-md-2">
<button class="btn btn-pink w-100">
<i class="bi bi-search"></i>
Cari
</button>
</div>
</div>
</form>
</div>
</div>
<div class="d-flex justify-content-between align-items-center mb-3">
<h3>
<i class="bi bi-gem text-danger"></i>
Daftar Bahan Baku
</h3>
<a href="tambah.php" class="btn btn-pink">
<i class="bi bi-plus-circle"></i>
Tambah Bahan
</a>
</div>
<div class="card shadow rounded-4">
<div class="card-body">
<table class="table table-hover">
<thead>
<tr>
<th>No</th>
<th>Nama</th>
<th>Satuan</th>
<th>Harga</th>
<th>Stok</th>
<th>ROP</th>
<th>Status</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
    <?php

$no=1;
if(isset($_GET['cari'])){
$cari=$_GET['cari'];
$data=mysqli_query($conn,
"SELECT * FROM bahan_baku
WHERE nama_bahan LIKE '%$cari%'");
}else{
$data=mysqli_query($conn,
"SELECT * FROM bahan_baku");
}
while($d=mysqli_fetch_array($data)){
?>
<tr>
<td><?= $no++; ?></td>
<td><?= $d['nama_bahan']; ?></td>
<td><?= $d['satuan']; ?></td>
<td>
Rp<?= number_format($d['harga_satuan'],0,",","."); ?>
</td>
<td><?= $d['stok']; ?></td>
<td><?= $d['rop']; ?></td>
<td>
<?php if($d['stok'] <= $d['rop']){ ?>
<span class="badge bg-danger">
<i class="bi bi-exclamation-circle"></i>
Menipis
</span>
<?php }else{ ?>
<span class="badge bg-success">
<i class="bi bi-check-circle"></i>
Aman
</span>
<?php } ?>
</td>
<td>
<a href="edit.php?id=<?= $d['id_bahan']; ?>" class="btn btn-warning btn-sm">
<i class="bi bi-pencil"></i>
</a>
<a href="hapus.php?id=<?= $d['id_bahan']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Yakin ingin menghapus?')">
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
</body>
</html>