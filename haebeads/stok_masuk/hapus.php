<?php
session_start();
if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}
include "../config/koneksi.php";
$id = $_GET['id'];
// Ambil data transaksi stok masuk
$data = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM stok_masuk
WHERE id_masuk='$id'
"));
$id_bahan = $data['id_bahan'];
$jumlah   = $data['jumlah'];
mysqli_query($conn,"
UPDATE bahan_baku
SET stok = stok - $jumlah
WHERE id_bahan='$id_bahan'
");
mysqli_query($conn,"
DELETE FROM stok_masuk
WHERE id_masuk='$id'
");
echo "
<script>
alert('Data berhasil dihapus');
history.go(-1);
</script>
";
exit;