<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

if(isset($_POST['simpan'])){

    $tanggal        = $_POST['tanggal'];
    $nama_produksi  = mysqli_real_escape_string($conn,$_POST['nama_produksi']);
    $id_bahan       = $_POST['id_bahan'];
    $jumlah         = $_POST['jumlah'];

    // Ambil data bahan
    $cek = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT *
        FROM bahan_baku
        WHERE id_bahan='$id_bahan'
    "));

    if($jumlah <= 0){

        echo "
        <script>
        alert('Jumlah harus lebih dari 0');
        </script>";

    }

  elseif($jumlah > $cek['stok']){

    echo "
    <script>
    alert('Stok ".$cek['nama_bahan']." tidak mencukupi!');
    window.location='tambah.php';
    </script>";

    }

    else{

        // Simpan transaksi stok keluar
        mysqli_query($conn,"
            INSERT INTO stok_keluar
            (tanggal,nama_produksi,id_bahan,jumlah)

            VALUES

            ('$tanggal','$nama_produksi','$id_bahan','$jumlah')
        ");

        // Kurangi stok bahan
        mysqli_query($conn,"
            UPDATE bahan_baku
            SET stok = stok-$jumlah
            WHERE id_bahan='$id_bahan'
        ");

echo "
<script>
alert('Data berhasil disimpan');
history.go(-2);
</script>
";
exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tambah Pemakaian Bahan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
<div class="container mt-5">
<div class="card shadow rounded-4">
<div class="card-body p-4">
<h3 class="mb-4">
<i class="bi bi-plus-circle-fill text-danger"></i>
Tambah Pemakaian Bahan
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
<label>Nama Produksi</label>
<select
name="nama_produksi"
class="form-select"
required>
<option value="">-- Pilih Produk --</option>
<?php
$produk=mysqli_query($conn,"
SELECT *
FROM produk
ORDER BY nama_produk ASC
");
while($p=mysqli_fetch_assoc($produk)){
?>
<option value="<?= $p['nama_produk']; ?>">
<?= $p['nama_produk']; ?>
</option>
<?php } ?>
</select>
</div>
<div class="mb-3">
<label>Nama Bahan</label>
<select
name="id_bahan"
class="form-select"
required>
<option value="">-- Pilih Bahan --</option>
<?php
$bahan=mysqli_query($conn,"
SELECT *
FROM bahan_baku
ORDER BY nama_bahan ASC
");
while($b=mysqli_fetch_assoc($bahan)){
?>
<option value="<?= $b['id_bahan']; ?>">
<?= $b['nama_bahan']; ?>
(<?= $b['stok']; ?> <?= $b['satuan']; ?>)
</option>
<?php } ?>
</select>
</div>
<div class="mb-3">
<label>Jumlah Dipakai</label>
<input
type="number"
step="0.01"
min="0.01"
name="jumlah"
class="form-control"
placeholder="Contoh : 35 cm / 20 gram"
required>
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