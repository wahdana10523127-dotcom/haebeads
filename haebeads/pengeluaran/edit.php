<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$id = $_GET['id'];

$data = mysqli_query($conn,"
SELECT *
FROM pengeluaran
WHERE id_pengeluaran='$id'
");

$d = mysqli_fetch_assoc($data);

if(isset($_POST['update'])){

    $tanggal     = $_POST['tanggal'];
    $kategori    = $_POST['kategori'];
    $nominal     = $_POST['nominal'];
    $keterangan  = $_POST['keterangan'];

    mysqli_query($conn,"
    UPDATE pengeluaran

    SET

    tanggal='$tanggal',
    kategori='$kategori',
    nominal='$nominal',
    keterangan='$keterangan'

    WHERE id_pengeluaran='$id'
    ");

   echo "
<script>

alert('Data berhasil diupdate');

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

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Edit Pengeluaran</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

<div class="container mt-5">

<div class="card shadow rounded-4">

<div class="card-body">

<h3 class="mb-4">

<i class="bi bi-pencil-square text-warning"></i>

Edit Pengeluaran

</h3>

<form method="POST">

<div class="mb-3">

<label>Tanggal</label>

<input
type="date"
name="tanggal"
class="form-control"
value="<?= $d['tanggal']; ?>"
required>

</div>

<div class="mb-3">

<label>Kategori</label>

<select
name="kategori"
class="form-select"
required>

<option value="">-- Pilih Kategori --</option>

<option value="Bahan Baku" <?= ($d['kategori']=="Bahan Baku") ? "selected" : ""; ?>>
Bahan Baku
</option>

<option value="Operasional" <?= ($d['kategori']=="Operasional") ? "selected" : ""; ?>>
Operasional
</option>

<option value="Transportasi" <?= ($d['kategori']=="Transportasi") ? "selected" : ""; ?>>
Transportasi
</option>

<option value="Packaging" <?= ($d['kategori']=="Packaging") ? "selected" : ""; ?>>
Packaging
</option>

<option value="Listrik" <?= ($d['kategori']=="Listrik") ? "selected" : ""; ?>>
Listrik
</option>

<option value="Internet" <?= ($d['kategori']=="Internet") ? "selected" : ""; ?>>
Internet
</option>

<option value="Lainnya" <?= ($d['kategori']=="Lainnya") ? "selected" : ""; ?>>
Lainnya
</option>

</select>

</div>

<div class="mb-3">

<label>Nominal</label>

<input
type="number"
name="nominal"
class="form-control"
min="1"
value="<?= $d['nominal']; ?>"
required>

</div>

<div class="mb-3">

<label>Keterangan</label>

<textarea
name="keterangan"
class="form-control"
rows="4"
placeholder="Masukkan keterangan"><?= $d['keterangan']; ?></textarea>

</div>

<button
type="submit"
name="update"
class="btn btn-pink">

<i class="bi bi-save"></i>

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