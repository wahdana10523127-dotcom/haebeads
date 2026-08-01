<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$id = $_GET['id'];

mysqli_query($conn,"
DELETE FROM pengeluaran
WHERE id_pengeluaran='$id'
");

echo "
<script>
alert('Data pengeluaran berhasil dihapus');
history.go(-1);
</script>
";
exit;
?>