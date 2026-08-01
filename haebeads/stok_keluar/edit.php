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

// Ambil data transaksi
$data = mysqli_query($conn,"
SELECT stok_keluar.*, bahan_baku.nama_bahan
FROM stok_keluar
JOIN bahan_baku
ON stok_keluar.id_bahan = bahan_baku.id_bahan
WHERE stok_keluar.id_keluar='$id'
");

$d = mysqli_fetch_assoc($data);

if(!$d){
    echo "
    <script>
    alert('Data tidak ditemukan');
    window.location='index.php';
    </script>
    ";
    exit;
}

if(isset($_POST['update'])){

    $tanggal       = $_POST['tanggal'];
    $nama_produksi = mysqli_real_escape_string($conn,$_POST['nama_produksi']);
    $id_bahan_baru = $_POST['id_bahan'];
    $jumlah_baru   = $_POST['jumlah'];

    // Data lama
    $lama = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT *
    FROM stok_keluar
    WHERE id_keluar='$id'
    "));

    $id_bahan_lama = $lama['id_bahan'];
    $jumlah_lama   = $lama['jumlah'];

    // Kembalikan stok lama
    mysqli_query($conn,"
    UPDATE bahan_baku
    SET stok = stok + $jumlah_lama
    WHERE id_bahan='$id_bahan_lama'
    ");

    // Cek stok baru
    $cek = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT *
    FROM bahan_baku
    WHERE id_bahan='$id_bahan_baru'
    "));

    if($jumlah_baru <= 0){

        mysqli_query($conn,"
        UPDATE bahan_baku
        SET stok = stok - $jumlah_lama
        WHERE id_bahan='$id_bahan_lama'
        ");

        echo "
        <script>
        alert('Jumlah harus lebih dari 0');
        window.location='edit.php?id=$id';
        </script>
        ";
        exit;
    }

    if($jumlah_baru > $cek['stok']){

        mysqli_query($conn,"
        UPDATE bahan_baku
        SET stok = stok - $jumlah_lama
        WHERE id_bahan='$id_bahan_lama'
        ");

        echo "
        <script>
        alert('Stok ".$cek['nama_bahan']." tidak mencukupi!');
        window.location='edit.php?id=$id';
        </script>
        ";
        exit;
    }

    // Kurangi stok baru
    mysqli_query($conn,"
    UPDATE bahan_baku
    SET stok = stok - $jumlah_baru
    WHERE id_bahan='$id_bahan_baru'
    ");

    // Update transaksi
    mysqli_query($conn,"
    UPDATE stok_keluar
    SET
        tanggal='$tanggal',
        nama_produksi='$nama_produksi',
        id_bahan='$id_bahan_baru',
        jumlah='$jumlah_baru'
    WHERE id_keluar='$id'
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

<title>Edit Pemakaian Bahan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>
<body>

<div class="container mt-5">

<div class="card shadow rounded-4">

<div class="card-body p-4">

<h3 class="mb-4">
<i class="bi bi-pencil-square text-warning"></i>
Edit Pemakaian Bahan
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

<option
value="<?= $p['nama_produk']; ?>"
<?= ($d['nama_produksi']==$p['nama_produk']) ? 'selected' : ''; ?>>

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

<option
value="<?= $b['id_bahan']; ?>"
<?= ($d['id_bahan']==$b['id_bahan']) ? 'selected' : ''; ?>>

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
value="<?= $d['jumlah']; ?>"
required>

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