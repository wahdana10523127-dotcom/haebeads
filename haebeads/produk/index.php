<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
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
/* =====================
   STATISTIK
===================== */
$total = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM produk"));
$bracelet = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM produk WHERE kategori='Bracelet'"));
$keychain = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM produk WHERE kategori='Keychain'"));
$stok = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(stok_produk) as total FROM produk"));
/* =====================
   SEARCH
===================== */
$cari = "";
if(isset($_GET['cari'])){
    $cari = mysqli_real_escape_string($conn,$_GET['cari']);
    $query = mysqli_query($conn,"
        SELECT * FROM produk
        WHERE nama_produk LIKE '%$cari%'
        OR kategori LIKE '%$cari%'
        ORDER BY id_produk DESC
    ");
}else{
    $query = mysqli_query($conn,"
        SELECT * FROM produk
        ORDER BY id_produk DESC
    ");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Data Produk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
<div class="container-fluid p-4">

<!-- ===========================
    WELCOME
============================ -->
<div class="welcome-box mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <h2>🌸 Welcome to Haebeads Product</h2>
            <p class="mt-3">
                Kelola seluruh koleksi handmade accessories dengan lebih mudah,
                cepat, dan terorganisir.
            </p>
        </div>
        <div class="col-md-4 text-end">
            <i class="bi bi-bag-heart-fill"
            style="font-size:90px;color:white;"></i>
        </div>
    </div>
</div>
<!-- ===========================
    CARD STATISTIK
============================ -->
<div class="row g-4 mb-4">
    <div class="col-lg-3">
        <div class="stat-card">
            <i class="bi bi-box-seam"></i>
            <h6>Total Produk</h6>
            <h2><?= $total['total'] ?? 0 ?></h2>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="stat-card">
            <i class="bi bi-gem"></i>
            <h6>Bracelet</h6>
            <h2><?= $bracelet['total'] ?? 0 ?></h2>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="stat-card">
            <i class="bi bi-stars"></i>
            <h6>Keychain</h6>
            <h2><?= $keychain['total'] ?? 0 ?></h2>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="stat-card">
            <i class="bi bi-box-fill"></i>
            <h6>Total Stok</h6>
            <h2><?= $stok['total'] ?? 0 ?></h2>
        </div>
    </div>
</div>
<!-- ===========================
    SEARCH
============================ -->
<div class="card shadow border-0 rounded-4 mb-4">
<div class="card-body">
<form method="GET">
<div class="row">
<div class="col-md-10">
<input
type="text"
class="form-control"
name="cari"
placeholder="Cari nama produk atau kategori..."
value="<?= $cari ?>">
</div>
<div class="col-md-2 d-grid">
<button class="btn btn-outline-danger">
<i class="bi bi-search"></i>
Cari
</button>
</div>
</div>
</form>
</div>
</div>
    <!-- ===========================
    HEADER
============================ -->

<div class="d-flex justify-content-between align-items-center mb-3">
<h2>
<i class="bi bi-box-seam-fill text-danger"></i>
Data Produk
</h2>
<a href="tambah.php" class="btn btn-pink">
<i class="bi bi-plus-circle"></i>
Tambah Produk
</a>
</div>
<!-- ===========================
    TABEL PRODUK
============================ -->
<div class="card shadow rounded-4">
<div class="card-header bg-white">
</div>
<div class="card-body">
<div class="table-responsive">
<table class="table table-hover align-middle">
<thead class="table-light">
<tr>
<th width="60">No</th>
<th>Nama Produk</th>
<th>Kategori</th>
<th>Harga</th>
<th>Stok</th>
<th width="140" class="text-center">Aksi</th>
</tr>
</thead>
<tbody>
<?php
$no = 1;
if(mysqli_num_rows($query) > 0){
while($d = mysqli_fetch_assoc($query)){
?>
<tr>
<td><?= $no++ ?></td>
<td>
<strong><?= $d['nama_produk']; ?></strong>
</td>
<td>
<?php
if($d['kategori']=="Bracelet"){
echo '<span class="badge bg-primary">Bracelet</span>';
}else{
echo '<span class="badge bg-success">'.$d['kategori'].'</span>';
}
?>
</td>
<td>
Rp <?= number_format($d['harga_jual'],0,",","."); ?>
</td>
<td>
<span class="badge bg-warning text-dark">
<?= $d['stok_produk']; ?>
</span>
</td>
<td class="text-center">
<a
href="edit.php?id=<?= $d['id_produk']; ?>"
class="btn btn-warning btn-sm">
<i class="bi bi-pencil-square"></i>
</a>
<a
href="hapus.php?id=<?= $d['id_produk']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Yakin ingin menghapus produk ini?')">
<i class="bi bi-trash"></i>
</a>
</td>
</tr>
<?php
}
}else{
?>
<tr>
<td colspan="6" class="text-center p-5">
<i class="bi bi-box2-heart"
style="font-size:70px;color:#ff7aa8;"></i>
<h4 class="mt-3">
Belum ada produk
</h4>
<p class="text-muted">
Silakan tambahkan produk pertama Haebeads.
</p>
<a
href="tambah.php"
class="btn btn-pink">
<i class="bi bi-plus-circle"></i>
Tambah Produk
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
const searchInput = document.querySelector("input[name='cari']");
if(searchInput){

    searchInput.addEventListener("keyup",function(e){

        if(e.key==="Enter"){

            this.form.submit();

        }

    });
}
</script>
</body>
</html>
