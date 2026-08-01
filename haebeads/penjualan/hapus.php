<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$id = $_GET['id'];

// Ambil semua detail penjualan
$detail = mysqli_query($conn,"
SELECT *
FROM detail_penjualan
WHERE id_penjualan='$id'
");

// Kembalikan stok produk
while($d = mysqli_fetch_assoc($detail)){

    mysqli_query($conn,"
    UPDATE produk
    SET stok_produk = stok_produk + ".$d['qty']."
    WHERE id_produk='".$d['id_produk']."'
    ");

}

// Hapus detail penjualan
mysqli_query($conn,"
DELETE FROM detail_penjualan
WHERE id_penjualan='$id'
");

// Hapus penjualan
mysqli_query($conn,"
DELETE FROM penjualan
WHERE id_penjualan='$id'
");

echo "
<script>
alert('Data penjualan berhasil dihapus');
history.go(-1);
</script>
";
?>