<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

if(!isset($_SESSION['login'])){
    header("Location: login.php");
    exit;
}

include "config/koneksi.php";

// Total Produk
$totalProduk = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) total FROM produk"));

// Total Bahan Baku
$totalBahan = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) total FROM bahan_baku"));

// Total Penjualan
$totalPenjualan = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT SUM(total) AS total FROM penjualan")
);

// Total Pengeluaran
$totalPengeluaran = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT SUM(nominal) AS total FROM pengeluaran")
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Haebeads</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/dashboard.css">
</head>
<body>
<div class="wrapper">
<!-- Sidebar -->
<div class="sidebar">
<h3>💖 Haebeads</h3>
<ul>
<li class="active"><i class="bi bi-house"></i> Dashboard</li>
<li>
    <a href="produk/index.php">
        <i class="bi bi-box-seam"></i>
        Produk
    </a>
</li>
<li>
    <a href="bahan_baku/index.php">
        <i class="bi bi-gem"></i>
        Bahan Baku
    </a>
</li>
<li>
    <a href="stok_masuk/index.php">
        <i class="bi bi-arrow-down-circle"></i>
        Stok Masuk
    </a>
</li>
<li>
    <a href="stok_keluar/index.php">
        <i class="bi bi-arrow-up-circle"></i>
        Stok Keluar
    </a>
</li>

<li>
    <a href="penjualan/index.php">
        <i class="bi bi-cart"></i>
        Penjualan
    </a>
</li>

<li>
    <a href="pengeluaran/index.php">
        <i class="bi bi-wallet2"></i>
        Pengeluaran
    </a>
</li>

<li>
    <a href="hpp/index.php">
        <i class="bi bi-calculator"></i>
        Hitung HPP
    </a>
</li>

<li>
    <a href="laporan/index.php">
        <i class="bi bi-file-earmark-bar-graph"></i>
        Laporan
    </a>
</li>
<li>
<a href="logout.php" class="logout">
<i class="bi bi-box-arrow-right"></i>
Logout
</a>
</li>
</ul>
</div>
<!-- Main -->
<div class="main">
<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h2 class="fw-bold">Dashboard</h2>
        <p class="text-muted mb-0" id="tanggal"></p>
    </div>

</div>
<div class="hero-dashboard">

    <div class="hero-text">
        <h2>🌸 Welcome Back, Admin!</h2>

        <p>
            Kelola seluruh aktivitas Haebeads mulai dari produk,
            bahan baku, penjualan, hingga laporan dalam satu dashboard.
        </p>

    </div>

    <div class="hero-image">
        <i class="bi bi-bag-heart-fill"></i>
    </div>

</div>
<div class="row mt-4">
<div class="col-md-3">
<div class="card-box">
<i class="bi bi-box-seam card-icon"></i>
<h5>Total Produk</h5>
<h2><?= $totalProduk['total']; ?></h2>
</div>
</div>
<div class="col-md-3">
<div class="card-box">
    <i class="bi bi-gem card-icon"></i>
    <h5>Bahan Baku</h5>
    <h2><?= $totalBahan['total']; ?></h2>
</div>
</div>
<div class="col-md-3">
<div class="card-box">
<i class="bi bi-cart-check card-icon"></i>
<h5>Penjualan</h5>
<h2>
Rp<?= number_format($totalPenjualan['total'] ?? 0,0,",","."); ?>
</h2>
</div>
</div>
<div class="col-md-3">
<div class="card-box">
<i class="bi bi-wallet2 card-icon"></i>
<h5>Pengeluaran</h5>
<h2>
Rp<?= number_format($totalPengeluaran['total'] ?? 0,0,",","."); ?>
</h2>
</div>
</div>
</div>
</div>
</div>
<script src="assets/js/script.js"></script>
<script>
window.history.pushState(null, "", window.location.href);

window.onpopstate = function () {
    window.history.pushState(null, "", window.location.href);
};
</script>
</body>
</html>