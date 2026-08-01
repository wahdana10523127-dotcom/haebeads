<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
if(isset($_SESSION['updated'])){
    unset($_SESSION['updated']);
    echo "
    <script>
        if(window.history.replaceState){
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
    ";
}
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
$totalPemakaian = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM(jumlah) AS total
FROM stok_keluar
"));
$jenisBahan = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(DISTINCT id_bahan) AS total
FROM stok_keluar
"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Stok Keluar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
<div class="container-fluid mt-4">
<div class="welcome-box mb-4">
<div class="row align-items-center">
<div class="col-md-8">
<h2>📤 Welcome to Stock Out Center</h2>
<p>
Kelola seluruh pemakaian bahan baku Haebeads.
</p>
</div>
<div class="col-md-4 text-end">
<i class="bi bi-arrow-up-circle-fill display-1 text-white"></i>
</div>
</div>
</div>
<div class="row mb-4">
<div class="col-md-6">
<div class="stat-card">
<i class="bi bi-arrow-up-circle"></i>
<h5>Total Pemakaian</h5>
<h2><?= $totalPemakaian['total'] ?? 0; ?></h2>
</div>
</div>
<div class="col-md-6">
<div class="stat-card">
<i class="bi bi-box-seam"></i>
<h5>Jenis Bahan Dipakai</h5>
<h2><?= $jenisBahan['total']; ?></h2>
</div>
</div>
</div>
<div class="d-flex justify-content-between align-items-center mb-3">
<h3>
<i class="bi bi-arrow-up-circle-fill text-danger"></i>
Data Stok Keluar
</h3>
<a href="tambah.php" class="btn btn-pink">
<i class="bi bi-plus-circle"></i>
Tambah Pemakaian
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
<th>Nama Produksi</th>
<th>Nama Bahan</th>
<th>Jumlah Dipakai</th>
<th>Aksi</th>
</tr>

</thead>

<tbody>

<?php
$no=1;
$data=mysqli_query($conn,"
SELECT stok_keluar.*, bahan_baku.nama_bahan, bahan_baku.satuan
FROM stok_keluar
JOIN bahan_baku
ON stok_keluar.id_bahan=bahan_baku.id_bahan
ORDER BY tanggal DESC
");
while($d=mysqli_fetch_assoc($data)){
?>

<tr>

<td><?= $no++; ?></td>
<td><?= date('d-m-Y',strtotime($d['tanggal'])); ?></td>
<td><?= $d['nama_produksi']; ?></td>
<td><?= $d['nama_bahan']; ?></td>
<td><?= $d['jumlah']." ".$d['satuan']; ?></td>
<td>

<a
href="edit.php?id=<?= $d['id_keluar']; ?>"
class="btn btn-warning btn-sm">
<i class="bi bi-pencil"></i>
</a>

<a
href="hapus.php?id=<?= $d['id_keluar']; ?>"
onclick="return confirm('Yakin?')"
class="btn btn-danger btn-sm">
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