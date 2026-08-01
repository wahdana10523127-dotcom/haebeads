<?php

include "../config/koneksi.php";

$id=$_GET['id'];

mysqli_query($conn,"DELETE FROM produk WHERE id_produk='$id'");

echo "
<script>
alert('Produk berhasil dihapus');
history.go(-1);
</script>
";
exit;

?>