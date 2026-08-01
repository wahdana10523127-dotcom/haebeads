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
$totalPengeluaran = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(nominal) AS total
FROM pengeluaran
"));
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Pengeluaran</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

<div class="container-fluid mt-4">

<div class="welcome-box mb-4">

<div class="row align-items-center">

<div class="col-md-8">

<h2>💸 Expense Center</h2>

<p>
Kelola seluruh data pengeluaran Haebeads dengan mudah.
</p>

</div>

<div class="col-md-4 text-end">

<i class="bi bi-wallet2 display-1 text-white"></i>

</div>

</div>

</div>

<div class="row mb-4">

<div class="col-md-12">

<div class="stat-card">

<i class="bi bi-cash-stack"></i>

<h5>Total Pengeluaran</h5>

<h2>

Rp<?= number_format($totalPengeluaran['total'] ?? 0,0,',','.'); ?>

</h2>

</div>

</div>

</div>

<div class="d-flex justify-content-between align-items-center mb-3">

<h3>

<i class="bi bi-wallet-fill text-danger"></i>

Data Pengeluaran

</h3>

<a
href="tambah.php"
class="btn btn-pink">

<i class="bi bi-plus-circle"></i>

Tambah Pengeluaran

</a>

</div>

<div class="card shadow rounded-4">

<div class="card-body">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-light">

<tr>

<th>No</th>
<th>Tanggal</th>
<th>Kategori</th>
<th>Nominal</th>
<th>Keterangan</th>
<th>Aksi</th>

</tr>

</thead>

<tbody>

<?php

$no=1;

$data=mysqli_query($conn,"
SELECT *
FROM pengeluaran
ORDER BY tanggal DESC
");

while($d=mysqli_fetch_assoc($data)){
?>

<tr>

<td><?= $no++; ?></td>

<td><?= date('d-m-Y',strtotime($d['tanggal'])); ?></td>

<td><?= $d['kategori']; ?></td>

<td>
Rp<?= number_format($d['nominal'],0,',','.'); ?>
</td>

<td><?= $d['keterangan']; ?></td>

<td>

<a
href="edit.php?id=<?= $d['id_pengeluaran']; ?>"
class="btn btn-warning btn-sm">

<i class="bi bi-pencil"></i>

</a>

<a
href="hapus.php?id=<?= $d['id_pengeluaran']; ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Yakin ingin menghapus data ini?');">

<i class="bi bi-trash"></i>

</a>

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

</div>

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