<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

if(isset($_POST['simpan'])){

    $nama = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga_jual'];
    $stok = $_POST['stok_produk'];

    mysqli_query($conn,"INSERT INTO produk(nama_produk,kategori,harga_jual,stok_produk)
    VALUES('$nama','$kategori','$harga','$stok')");

 echo "
<script>
alert('Produk berhasil ditambahkan');
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

<title>Tambah Produk</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body style="background:#f8f9fd;">

<div class="container mt-5">

<div class="card shadow-lg border-0 rounded-4">

<div class="card-header bg-pink text-white">

<h3>Tambah Produk</h3>

</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label>Nama Produk</label>

<input type="text"
name="nama_produk"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Kategori</label>

<input type="text"
name="kategori"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Harga</label>

<input type="number"
name="harga_jual"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Stok</label>

<input type="number"
name="stok_produk"
class="form-control"
required>

</div>

<button
type="submit"
name="simpan"
class="btn btn-pink">

Simpan

</button>

<button
type="button"
class="btn btn-secondary"
onclick="history.back();">

Kembali

</button>
</a>

</form>

</div>

</div>

</div>

</body>
</html>