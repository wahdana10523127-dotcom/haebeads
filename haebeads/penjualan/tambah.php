
<?php
session_start();

include "../config/koneksi.php";

// Simpan transaksi
if(isset($_POST['simpan'])){

    $tanggal     = $_POST['tanggal'];
    $keterangan  = $_POST['keterangan'];

    // Simpan header penjualan
    mysqli_query($conn,"
    INSERT INTO penjualan
    (tanggal,keterangan,total)

    VALUES

    ('$tanggal','$keterangan',0)
    ");

    // Ambil ID penjualan terakhir
    $id_penjualan = mysqli_insert_id($conn);

    $grandTotal = 0;

    // Loop semua produk yang dipilih
    foreach($_POST['id_produk'] as $i => $id_produk){

        $qty = $_POST['qty'][$i];

        // Ambil data produk
        $produk = mysqli_fetch_assoc(mysqli_query($conn,"
        SELECT *
        FROM produk
        WHERE id_produk='$id_produk'
        "));

        // Validasi stok
if($qty > $produk['stok_produk']){

mysqli_query($conn,"
DELETE FROM penjualan
WHERE id_penjualan='$id_penjualan'
");

    echo "
    <script>
    alert('Stok ".$produk['nama_produk']." tidak mencukupi!');
    window.history.back();
    </script>
    ";

    exit;
}

        $harga = $produk['harga_jual'];

        $subtotal = $harga * $qty;

        $grandTotal += $subtotal;

        // Simpan detail
        mysqli_query($conn,"
        INSERT INTO detail_penjualan
        (id_penjualan,id_produk,harga,qty,subtotal)

        VALUES

        ('$id_penjualan','$id_produk','$harga','$qty','$subtotal')
        ");

        // Kurangi stok produk
        mysqli_query($conn,"
        UPDATE produk
        SET stok_produk = stok_produk-$qty
        WHERE id_produk='$id_produk'
        ");

    }

    // Update total
    mysqli_query($conn,"
    UPDATE penjualan
    SET total='$grandTotal'
    WHERE id_penjualan='$id_penjualan'
    ");

 echo "
<script>
alert('Data berhasil disimpan');
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

<title>Tambah Penjualan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

<div class="container mt-5">

<div class="card shadow rounded-4">

<div class="card-body">

<h3 class="mb-4">
<i class="bi bi-cart-plus-fill text-success"></i>
Tambah Penjualan
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

<label>Keterangan</label>

<select
name="keterangan"
class="form-select"
required>

<option value="">-- Pilih --</option>
<option>Shopee</option>
<option>TikTok Shop</option>
<option>WhatsApp</option>
<option>Offline</option>

</select>
</div>

<hr>
<h5>Produk</h5>

<div id="produk-wrapper">

<div class="row produk-item mb-3">

<div class="col-md-5">

<label>Produk</label>

<select
name="id_produk[]"
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
value="<?= $p['id_produk']; ?>"

data-harga="<?= $p['harga_jual']; ?>">

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
min="1"
value="1"
required>

</div>

<div class="col-md-3">

<label>Subtotal</label>

<input
type="text"
class="form-control subtotal"
readonly
value="0">

</div>

<div class="col-md-2 d-flex align-items-end">

<button
type="button"
class="btn btn-danger hapus">

<i class="bi bi-trash"></i>

</button>

</div>
</div>
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
readonly
value="0">

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

<script>

function hitungTotal(){

let grand=0;

document.querySelectorAll(".produk-item").forEach(function(item){

let produk=item.querySelector("select");
let qty=item.querySelector("input[name='qty[]']");
let subtotal=item.querySelector(".subtotal");

let harga=0;

if(produk.selectedOptions.length>0){

harga=parseFloat(
produk.selectedOptions[0].getAttribute("data-harga") || 0
);

}

let total=harga*parseFloat(qty.value || 0);

subtotal.value=total.toLocaleString("id-ID");

grand+=total;

});

document.getElementById("grandTotal").value=
grand.toLocaleString("id-ID");

}

document.addEventListener("change",function(e){

if(
e.target.matches("select") ||
e.target.matches("input[name='qty[]']")
){

hitungTotal();

}

});

document.getElementById("tambahProduk").onclick=function(){

let wrapper=document.getElementById("produk-wrapper");

let item=document.querySelector(".produk-item");

let clone=item.cloneNode(true);

clone.querySelector("select").selectedIndex=0;

clone.querySelector("input[name='qty[]']").value=1;

clone.querySelector(".subtotal").value=0;

wrapper.appendChild(clone);

};

document.addEventListener("click",function(e){

if(e.target.closest(".hapus")){

let items=document.querySelectorAll(".produk-item");

if(items.length>1){

e.target.closest(".produk-item").remove();

hitungTotal();

}

}

});

hitungTotal();

</script>

</body>
</html>