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

if(isset($_POST['simpan'])){

$nama   = $_POST['nama_bahan'];
$satuan = $_POST['satuan'];
$harga  = $_POST['harga_satuan'];
$rop    = $_POST['rop'];

   mysqli_query($conn,"INSERT INTO bahan_baku
(nama_bahan,satuan,stok,harga_satuan,rop)
VALUES
('$nama','$satuan','0','$harga','$rop')
");

echo "
<script>
alert('Data berhasil disimpan');
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
<title>Tambah Bahan Baku</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
<div class="container mt-5">
    <div class="card shadow rounded-4">
<div class="card-header bg-pink text-white">
<h3>
<i class="bi bi-gem"></i>
Tambah Bahan Baku
</h3>
</div>
<div class="card-body">
<form method="POST">
    <div class="mb-3">

<label>Nama Bahan</label>
<input type="text"
class="form-control"
name="nama_bahan"
required>
</div>
<div class="mb-3">
<label>Satuan</label>
<input type="text"
class="form-control"
name="satuan"
required>
</div>
<div class="mb-3">
<label>Harga / Satuan</label>
<input type="number"
class="form-control"
name="harga_satuan"
required>
</div>
<div class="mb-3">
<label>ROP</label>
<input type="number"
class="form-control"
name="rop"
required>
</div>
<div class="mt-4">
<button
type="submit"
name="simpan"
class="btn btn-pink">
<i class="bi bi-save"></i>
Simpan
</button>
<button
type="button"
class="btn btn-secondary"
onclick="history.back();">

Kembali

</button>
</div>
</form>
</div>
</div>
</div>
</body>
</html>