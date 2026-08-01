<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$id = $_GET['id'];

// Ambil data transaksi
$data = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM stok_keluar
WHERE id_keluar='$id'
"));

$id_bahan = $data['id_bahan'];
$jumlah = $data['jumlah'];

// Kembalikan stok bahan
mysqli_query($conn,"
UPDATE bahan_baku
SET stok = stok + $jumlah
WHERE id_bahan='$id_bahan'
");

// Hapus transaksi
mysqli_query($conn,"
DELETE FROM stok_keluar
WHERE id_keluar='$id'
");

echo "
<script>
alert('Data berhasil dihapus');
history.go(-1);
</script>
";
exit;
?>