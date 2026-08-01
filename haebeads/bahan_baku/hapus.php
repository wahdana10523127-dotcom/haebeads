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

mysqli_query($conn,"DELETE FROM bahan_baku WHERE id_bahan='$id'");

echo "
<script>
alert('Data berhasil dihapus');
history.go(-1);
</script>
";
exit;

?>