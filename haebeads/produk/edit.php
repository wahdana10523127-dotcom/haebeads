<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$id = $_GET['id'];

$data = mysqli_query($conn,"SELECT * FROM produk WHERE id_produk='$id'");
$d = mysqli_fetch_array($data);

if(isset($_POST['update'])){

    $nama = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga_jual'];
    $stok = $_POST['stok_produk'];

    mysqli_query($conn,"UPDATE produk SET

    nama_produk='$nama',
    kategori='$kategori',
    harga_jual='$harga',
    stok_produk='$stok'

    WHERE id_produk='$id'");

 echo "
<script>
alert('Produk berhasil diupdate');

// kembali ke halaman sebelumnya
history.go(-2);

</script>
";
exit;
}
?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Produk</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body style="background:#f8f9fd;">

<div class="container mt-5">

<div class="card shadow-lg">

<div class="card-header bg-pink text-white">

<h3>Edit Produk</h3>

</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label>Nama Produk</label>

<input
type="text"
name="nama_produk"
class="form-control"
value="<?= $d['nama_produk'] ?>"
required>

</div>

<div class="mb-3">

<label>Kategori</label>

<input
type="text"
name="kategori"
class="form-control"
value="<?= $d['kategori'] ?>"
required>

</div>

<div class="mb-3">

<label>Harga</label>

<input
type="number"
name="harga_jual"
class="form-control"
value="<?= $d['harga_jual'] ?>"
required>

</div>

<div class="mb-3">

<label>Stok</label>

<input
type="number"
name="stok_produk"
class="form-control"
value="<?= $d['stok_produk'] ?>"
required>

</div>

<button
type="submit"
name="update"
class="btn btn-pink">

Update

</button>

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