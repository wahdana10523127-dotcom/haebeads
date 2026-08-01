<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}
include "../config/koneksi.php";
$id = $_GET['id'];
$data = mysqli_query($conn,"
SELECT stok_masuk.*, bahan_baku.nama_bahan
FROM stok_masuk
JOIN bahan_baku
ON stok_masuk.id_bahan = bahan_baku.id_bahan
WHERE id_masuk='$id'
");
$d = mysqli_fetch_assoc($data);
if(isset($_POST['update'])){
    $id_bahan_baru = $_POST['id_bahan'];
    $tanggal       = $_POST['tanggal'];
    $jumlah_baru   = $_POST['jumlah'];
    // Ambil data transaksi lama
    $lama = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT * FROM stok_masuk WHERE id_masuk='$id'"));
    $id_bahan_lama = $lama['id_bahan'];
    $jumlah_lama   = $lama['jumlah'];
    // Kurangi stok lama
    mysqli_query($conn,"
    UPDATE bahan_baku
    SET stok = stok - $jumlah_lama
    WHERE id_bahan='$id_bahan_lama'
    ");
    // Tambahkan stok baru
    mysqli_query($conn,"
    UPDATE bahan_baku
    SET stok = stok + $jumlah_baru
    WHERE id_bahan='$id_bahan_baru'
    ");
    // Update transaksi
    mysqli_query($conn,"
    UPDATE stok_masuk
    SET
        id_bahan='$id_bahan_baru',
        tanggal='$tanggal',
        jumlah='$jumlah_baru'
    WHERE id_masuk='$id'
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
<title>Edit Stok Masuk</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<h2 class="mb-4">Edit Stok Masuk</h2>
<form method="POST">
<div class="mb-3">
<label>Nama Bahan</label>
<select name="id_bahan" class="form-control">
<?php
$bahan = mysqli_query($conn,"SELECT * FROM bahan_baku");
while($b = mysqli_fetch_assoc($bahan)){
$selected = "";
if($b['id_bahan']==$d['id_bahan']){
    $selected="selected";
}
?>
<option value="<?= $b['id_bahan']; ?>" <?= $selected; ?>>
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
        value="<?= $d['tanggal']; ?>">
</div>
<div class="mb-3">
    <label>Jumlah Masuk</label>
    <input
        type="number"
        name="jumlah"
        class="form-control"
        value="<?= $d['jumlah']; ?>">
</div>
<button type="submit" name="update" class="btn btn-primary">
    Simpan Perubahan
</button>
<button
type="button"
class="btn btn-secondary"
onclick="history.back();">

Kembali

</button>
</form>
</div>
</body>
</html>
