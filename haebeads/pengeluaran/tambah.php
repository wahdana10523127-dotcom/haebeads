<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

if(isset($_POST['simpan'])){

    $tanggal     = $_POST['tanggal'];
    $kategori    = $_POST['kategori'];
    $nominal     = $_POST['nominal'];
    $keterangan  = $_POST['keterangan'];

    mysqli_query($conn,"
    INSERT INTO pengeluaran
    (tanggal,kategori,nominal,keterangan)

    VALUES

    ('$tanggal','$kategori','$nominal','$keterangan')
    ");

echo "
<script>
alert('Data pengeluaran berhasil ditambahkan');
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

<meta name="viewport"
content="width=device-width, initial-scale=1">

<title>Tambah Pengeluaran</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

<div class="container mt-5">

<div class="card shadow rounded-4">

<div class="card-body">

<h3 class="mb-4">

<i class="bi bi-wallet2 text-danger"></i>

Tambah Pengeluaran

</h3>

<form method="POST">

<div class="mb-3">

<label>Tanggal</label>

<input
type="date"
name="tanggal"
class="form-control"
value="<?= date('Y-m-d'); ?>"
required>

</div>

<div class="mb-3">

<label>Kategori</label>

<select
name="kategori"
class="form-select"
required>

<option value="">-- Pilih Kategori --</option>

<option>Bahan Baku</option>

<option>Operasional</option>

<option>Transportasi</option>

<option>Packaging</option>

<option>Listrik</option>

<option>Internet</option>

<option>Lainnya</option>

</select>

</div>

<div class="mb-3">

<label>Nominal</label>

<input
type="number"
name="nominal"
class="form-control"
min="1"
placeholder="Masukkan nominal"
required>

</div>

<div class="mb-3">

<label>Keterangan</label>

<textarea
name="keterangan"
class="form-control"
rows="4"
placeholder="Masukkan keterangan (opsional)"></textarea>

</div>

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

</form>

</div>

</div>

</div>

</body>

</html>