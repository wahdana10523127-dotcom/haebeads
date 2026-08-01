<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
session_start();

if (isset($_SESSION['success_hpp'])) {
    echo "<script>alert('".$_SESSION['success_hpp']."');</script>";
    unset($_SESSION['success_hpp']);
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

/* ==========================
   STATISTIK
========================== */

$totalProduk = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM produk
"));

$sudahHpp = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(DISTINCT id_produk) AS total
FROM hpp
"));

$belumHpp = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM produk
WHERE id_produk NOT IN
(
SELECT id_produk FROM hpp
)
"));

/* ==========================
   SEARCH
========================== */

$cari="";

if(isset($_GET['cari'])){
    $cari=mysqli_real_escape_string($conn,$_GET['cari']);

    $produk=mysqli_query($conn,"
    SELECT p.*,
    h.id_hpp
    FROM produk p
    LEFT JOIN hpp h
    ON p.id_produk=h.id_produk
    WHERE p.nama_produk LIKE '%$cari%'
    GROUP BY p.id_produk
    ORDER BY p.nama_produk ASC
    ");

}else{

    $produk=mysqli_query($conn,"
    SELECT p.*,
    h.id_hpp
    FROM produk p
    LEFT JOIN hpp h
    ON p.id_produk=h.id_produk
    GROUP BY p.id_produk
    ORDER BY p.nama_produk ASC
    ");

}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Harga Pokok Produksi</title>

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
💎 Welcome to HPP Calculator
</h2>

<p>
Hitung Harga Pokok Produksi setiap produk Haebeads dengan mudah.
</p>

</div>

<div class="col-md-4 text-end">

<i class="bi bi-calculator display-1 text-white"></i>

</div>

</div>

</div>


<div class="row mb-4">

<div class="col-md-4">

<div class="stat-card">

<i class="bi bi-box-seam"></i>

<h5>Total Produk</h5>

<h2>
<?= $totalProduk['total']; ?>
</h2>

</div>

</div>

<div class="col-md-4">

<div class="stat-card">

<i class="bi bi-check-circle"></i>

<h5>Sudah Dihitung</h5>

<h2>
<?= $sudahHpp['total']; ?>
</h2>

</div>

</div>

<div class="col-md-4">

<div class="stat-card">

<i class="bi bi-hourglass-split"></i>

<h5>Belum Dihitung</h5>

<h2>
<?= $belumHpp['total']; ?>
</h2>

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
placeholder="Cari nama produk..."
value="<?= $cari; ?>">

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


<div class="card shadow rounded-4">

<div class="card-body">

<h3>

<i class="bi bi-calculator text-danger"></i>

Daftar Produk

</h3>

<table class="table table-hover align-middle">

<thead>

<tr>

<th>No</th>

<th>Nama Produk</th>

<th>Kategori</th>

<th>Harga Jual</th>

<th>Status HPP</th>

<th>Aksi</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

while($p=mysqli_fetch_assoc($produk)){

?>
<tr>

<td><?= $no++; ?></td>

<td><?= htmlspecialchars($p['nama_produk']); ?></td>

<td><?= htmlspecialchars($p['kategori']); ?></td>

<td>
Rp<?= number_format($p['harga_jual'],0,",","."); ?>
</td>

<td>

<?php if($p['id_hpp'] != NULL){ ?>

<span class="badge bg-success">
<i class="bi bi-check-circle"></i>
Sudah Dihitung
</span>

<?php }else{ ?>

<span class="badge bg-warning text-dark">
<i class="bi bi-hourglass-split"></i>
Belum Dihitung
</span>

<?php } ?>

</td>

<td>

<?php if($p['id_hpp']){ ?>

<a
href="hitung.php?id=<?= $p['id_produk']; ?>"
class="btn btn-warning btn-sm">

<i class="bi bi-arrow-repeat"></i>

Hitung Ulang

</a>

<?php }else{ ?>

<a
href="hitung.php?id=<?= $p['id_produk']; ?>"
class="btn btn-pink btn-sm">

<i class="bi bi-calculator"></i>

Hitung

</a>

<?php } ?>

</td>

</tr>

<?php
}
?>

</tbody>

</table>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
if (history.replaceState) {
    history.replaceState(null, null, location.href);
}
</script>

<script>
window.addEventListener("pageshow", function (event) {

    if (event.persisted) {
        location.reload();
    }

    const nav = performance.getEntriesByType("navigation");

    if (nav.length && nav[0].type === "back_forward") {
        location.reload();
    }

});
</script>

</body>

</html>