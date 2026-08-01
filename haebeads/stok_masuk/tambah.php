<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}
include "../config/koneksi.php";
if(isset($_POST['simpan'])){

    $id_bahan = $_POST['id_bahan'];
    $tanggal  = $_POST['tanggal'];
    $jumlah   = $_POST['jumlah'];

    // Simpan ke tabel stok_masuk
    mysqli_query($conn,"
    INSERT INTO stok_masuk(id_bahan,tanggal,jumlah)
    VALUES('$id_bahan','$tanggal','$jumlah')
    ");

    // Update stok bahan baku
    mysqli_query($conn,"
    UPDATE bahan_baku
    SET stok = stok + '$jumlah'
    WHERE id_bahan='$id_bahan'
    ");

echo "
<script>
alert('Stok berhasil ditambahkan');
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
<title>Tambah Stok Masuk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
<div class="container mt-5">
<div class="card shadow rounded-4">
<div class="card-body p-4">
<h3 class="mb-4">
<i class="bi bi-plus-circle-fill text-success"></i>
Tambah Stok Masuk
</h3>
<form method="POST">
<div class="mb-3">
<label>Nama Bahan</label>
<select name="id_bahan" class="form-select" required>
<option value="">-- Pilih Bahan --</option>
<?php
$data = mysqli_query($conn,"SELECT * FROM bahan_baku ORDER BY nama_bahan ASC");
while($b = mysqli_fetch_assoc($data)){
?>
<option value="<?= $b['id_bahan']; ?>">
<?= $b['nama_bahan']; ?>
</option>
<?php } ?>
</select>
</div>
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
<label>Jumlah Masuk</label>
<input
type="number"
name="jumlah"
class="form-control"
required>
</div>
<button class="btn btn-pink" name="simpan">
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