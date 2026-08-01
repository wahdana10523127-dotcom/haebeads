<?php
session_start();

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$id = $_GET['id'];

// Ambil data penjualan
$penjualan = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM penjualan
WHERE id_penjualan='$id'
"));

if(!$penjualan){
    echo "
    <script>
    alert('Data tidak ditemukan');
    window.location='index.php';
    </script>
    ";
    exit;
}

// Ambil detail penjualan
$detail = mysqli_query($conn,"
SELECT detail_penjualan.*, produk.nama_produk
FROM detail_penjualan
JOIN produk
ON detail_penjualan.id_produk=produk.id_produk
WHERE id_penjualan='$id'
");

if(isset($_POST['update'])){
    mysqli_begin_transaction($conn);

    $tanggal    = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];

    // ==========================
    // KEMBALIKAN STOK LAMA
    // ==========================

    $lama = mysqli_query($conn,"
    SELECT *
    FROM detail_penjualan
    WHERE id_penjualan='$id'
    ");

    while($l=mysqli_fetch_assoc($lama)){

        mysqli_query($conn,"
        UPDATE produk
        SET stok_produk = stok_produk + ".$l['qty']."
        WHERE id_produk='".$l['id_produk']."'
        ");

    }

    // ==========================
    // HAPUS DETAIL LAMA
    // ==========================

    mysqli_query($conn,"
    DELETE FROM detail_penjualan
    WHERE id_penjualan='$id'
    ");

    $grandTotal=0;

    foreach($_POST['id_produk'] as $i=>$id_produk){

        $qty=$_POST['qty'][$i];

        $produk=mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT *
        FROM produk
        WHERE id_produk='$id_produk'
        "));

        // VALIDASI STOK

      if($qty > $produk['stok_produk']){

    mysqli_rollback($conn);

    echo "
    <script>
    alert('Stok ".$produk['nama_produk']." tidak mencukupi!');
    window.history.back();
    </script>
    ";

    exit;

}

        $harga=$produk['harga_jual'];

        $subtotal=$harga*$qty;

        $grandTotal += $subtotal;

        mysqli_query($conn,"
        INSERT INTO detail_penjualan
        (id_penjualan,id_produk,harga,qty,subtotal)

        VALUES

        ('$id','$id_produk','$harga','$qty','$subtotal')
        ");

        mysqli_query($conn,"
        UPDATE produk
        SET stok_produk = stok_produk-$qty
        WHERE id_produk='$id_produk'
        ");

    }

   mysqli_query($conn,"
UPDATE penjualan
SET
    tanggal='$tanggal',
    keterangan='$keterangan',
    total='$grandTotal'
WHERE id_penjualan='$id'
");

mysqli_commit($conn);

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

<title>Edit Penjualan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>
<body>

<div class="container mt-5">

<div class="card shadow rounded-4">

<div class="card-body">

<h3 class="mb-4">
<i class="bi bi-pencil-square text-warning"></i>
Edit Penjualan
</h3>

<form method="POST">

<div class="mb-3">

<label>Tanggal</label>

<input
type="date"
name="tanggal"
class="form-control"
value="<?= $penjualan['tanggal']; ?>"
required>

</div>

<div class="mb-3">

<label>Keterangan</label>

<select
name="keterangan"
class="form-select"
required>

<option <?=($penjualan['keterangan']=="Shopee")?"selected":"";?>>
Shopee
</option>

<option <?=($penjualan['keterangan']=="TikTok Shop")?"selected":"";?>>
TikTok Shop
</option>

<option <?=($penjualan['keterangan']=="WhatsApp")?"selected":"";?>>
WhatsApp
</option>

<option <?=($penjualan['keterangan']=="Offline")?"selected":"";?>>
Offline
</option>

</select>

</div>

<hr>

<h5>Produk</h5>

<div id="produk-wrapper">

<?php

mysqli_data_seek($detail,0);

while($d=mysqli_fetch_assoc($detail)){

?>

<div class="row produk-item mb-3">

<div class="col-md-5">

<label>Produk</label>

<select
name="id_produk[]"
class="form-select"
required>

<?php

$produk=mysqli_query($conn,"
SELECT *
FROM produk
ORDER BY nama_produk
");

while($p=mysqli_fetch_assoc($produk)){

?>

<option

value="<?= $p['id_produk']; ?>"

data-harga="<?= $p['harga_jual']; ?>"

<?=($d['id_produk']==$p['id_produk'])?"selected":"";?>>

<?= $p['nama_produk']; ?>

(Stock :
<?= $p['stok_produk']; ?>)

</option>

<?php } ?>

</select>

</div>

<div class="col-md-2">

<label>Qty</label>

<input

type="number"

name="qty[]"

class="form-control"

value="<?= $d['qty']; ?>"

min="1"

required>

</div>

<div class="col-md-3">

<label>Subtotal</label>

<input

type="text"

class="form-control subtotal"

readonly

value="<?= number_format($d['subtotal'],0,',','.'); ?>">

</div>

<div class="col-md-2 d-flex align-items-end">

<button

type="button"

class="btn btn-danger hapus">

<i class="bi bi-trash"></i>

</button>

</div>

</div>

<?php } ?>

</div>

<button

type="button"

id="tambahProduk"

class="btn btn-info mb-3">

<i class="bi bi-plus-circle"></i>

Tambah Produk

</button>

<hr>

<div class="mb-3">

<label>Grand Total</label>

<input

type="text"

id="grandTotal"

class="form-control"

readonly>

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
<script>

function hitungTotal(){

    let grand = 0;

    document.querySelectorAll(".produk-item").forEach(function(item){

        let produk = item.querySelector("select");

        let qty = item.querySelector("input[name='qty[]']");

        let subtotal = item.querySelector(".subtotal");

        let harga = 0;

        if(produk.selectedOptions.length > 0){

            harga = parseFloat(
                produk.selectedOptions[0].getAttribute("data-harga") || 0
            );

        }

        let total = harga * parseFloat(qty.value || 0);

        subtotal.value = total.toLocaleString("id-ID");

        grand += total;

    });

    document.getElementById("grandTotal").value =
    grand.toLocaleString("id-ID");

}

// Hitung saat produk / qty berubah
document.addEventListener("change",function(e){

    if(
        e.target.matches("select") ||
        e.target.matches("input[name='qty[]']")
    ){

        hitungTotal();

    }

});

// Tambah produk
document.getElementById("tambahProduk").onclick=function(){

    let wrapper=document.getElementById("produk-wrapper");

    let item=document.querySelector(".produk-item");

    let clone=item.cloneNode(true);

    clone.querySelector("select").selectedIndex=0;

    clone.querySelector("input[name='qty[]']").value=1;

    clone.querySelector(".subtotal").value=0;

    wrapper.appendChild(clone);

    hitungTotal();

};

// Hapus produk
document.addEventListener("click",function(e){

    if(e.target.closest(".hapus")){

        let items=document.querySelectorAll(".produk-item");

        if(items.length>1){

            e.target.closest(".produk-item").remove();

            hitungTotal();

        }

    }

});

// Hitung saat halaman pertama dibuka
hitungTotal();

</script>

</body>
</html>