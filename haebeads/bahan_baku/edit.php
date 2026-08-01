<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$id = $_GET['id'];

$data = mysqli_query($conn,"SELECT * FROM bahan_baku WHERE id_bahan='$id'");
$d = mysqli_fetch_assoc($data);

if(isset($_POST['update'])){

    $nama = $_POST['nama_bahan'];
    $satuan = $_POST['satuan'];
    $harga = $_POST['harga_satuan'];
    $rop = $_POST['rop'];

    mysqli_query($conn,"UPDATE bahan_baku SET
    nama_bahan='$nama',
    satuan='$satuan',
    harga_satuan='$harga',
    rop='$rop'
    WHERE id_bahan='$id'
");

echo "
<script>
alert('Data berhasil diupdate');

// kembali ke halaman sebelumnya
history.go(-2);

</script>
";
exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Edit Bahan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
<div class="container mt-5">
<div class="card shadow rounded-4">
<div class="card-body">
<h3>Edit Bahan Baku</h3>
<form method="POST">
<div class="mb-3">
<label>Nama Bahan</label>
<input
type="text"
name="nama_bahan"
class="form-control"
value="<?= $d['nama_bahan']; ?>"
required>
</div>
<div class="mb-3">
<label>Satuan</label>
<input
type="text"
name="satuan"
class="form-control"
value="<?= $d['satuan']; ?>"
required>
</div>
<div class="mb-3">
<label>Stok Saat Ini</label>
<input
type="text"
class="form-control"
value="<?= $d['stok']; ?>"
readonly>
<small class="text-muted">
Stok hanya dapat diubah melalui menu Stok Masuk dan Stok Keluar.
</small>
</div>
<div class="mb-3">
<label>Harga / Satuan</label>
<input
type="number"
name="harga_satuan"
class="form-control"
value="<?= $d['harga_satuan']; ?>"
required>
</div>
<div class="mb-3">
<label>ROP</label>
<input
type="number"
name="rop"
class="form-control"
value="<?= $d['rop']; ?>"
required>
</div>
<button
type="submit"
name="update"
class="btn btn-pink">
Update
<button
type="button"
class="btn btn-secondary"
onclick="history.back();">

Kembali

</button>
</form>
</div>
</div>
</div>
</body>
</html>