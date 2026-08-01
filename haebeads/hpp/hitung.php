<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

if(!isset($_GET['id'])){
    header("Location:index.php");
    exit;
}

$id_produk = (int)$_GET['id'];

$produk = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM produk
WHERE id_produk='$id_produk'
"));

$hpp = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT *
FROM hpp
WHERE id_produk='$id_produk'
"));

$detailLama = [];

if($hpp){

    $q = mysqli_query($conn,"
    SELECT
    d.*,
    b.nama_bahan
    FROM detail_hpp d
    JOIN bahan_baku b
    ON d.id_bahan=b.id_bahan
    WHERE d.id_hpp='".$hpp['id_hpp']."'
    ");

    while($r=mysqli_fetch_assoc($q)){
        $detailLama[]=$r;
    }

}

if(!$produk){
    echo "Produk tidak ditemukan.";
    exit;
}

$bahan = mysqli_query($conn,"
SELECT *
FROM bahan_baku
ORDER BY nama_bahan ASC
");
if(isset($_POST['simpan'])){

    include "../config/koneksi.php";

    $id_produk = (int)$_POST['id_produk'];
    $biaya_tambahan = (float)$_POST['biaya_tambahan'];
    $total_hpp = (float)$_POST['total_hpp'];

    $detail = json_decode($_POST['detail_hpp'], true);

    if(!$detail || count($detail)==0){
        die("Detail HPP kosong.");
    }

    // Hapus HPP lama
    $cek = mysqli_query($conn,"
    SELECT id_hpp
    FROM hpp
    WHERE id_produk='$id_produk'
    ");

    if(mysqli_num_rows($cek)>0){

        $old = mysqli_fetch_assoc($cek);

        mysqli_query($conn,"
        DELETE FROM detail_hpp
        WHERE id_hpp='".$old['id_hpp']."'
        ");

        mysqli_query($conn,"
        DELETE FROM hpp
        WHERE id_hpp='".$old['id_hpp']."'
        ");
    }

    // Simpan HPP baru
    mysqli_query($conn,"
    INSERT INTO hpp
    (id_produk,biaya_tambahan,total_hpp,tanggal_hitung)
    VALUES
    ('$id_produk','$biaya_tambahan','$total_hpp',CURDATE())
    ");

    $id_hpp = mysqli_insert_id($conn);

    foreach($detail as $d){

        $id_bahan = (int)$d['id_bahan'];
        $harga = (float)$d['harga_satuan'];
        $jumlah = (float)$d['jumlah_pakai'];
        $subtotal = (float)$d['subtotal'];

        mysqli_query($conn,"
        INSERT INTO detail_hpp
        (id_hpp,id_bahan,harga_satuan,jumlah_pakai,subtotal)
        VALUES
        ('$id_hpp','$id_bahan','$harga','$jumlah','$subtotal')
        ");
    }

    echo "
    <script>
    alert('HPP berhasil disimpan');
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

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Hitung Harga Pokok Produksi</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<body>

<div class="container-fluid mt-4">

<div class="welcome-box mb-4">

<div class="row align-items-center">

<div class="col-md-8">

<h2>
💎 Hitung Harga Pokok Produksi
</h2>

<p>
Gunakan kalkulator HPP untuk menghitung biaya produksi produk Haebeads.
</p>

</div>

<div class="col-md-4 text-end">

<i class="bi bi-calculator display-1 text-white"></i>

</div>

</div>

</div>

<div class="card shadow rounded-4 mb-4">

<div class="card-body">

<div class="row">

<div class="col-md-4">

<label class="fw-bold">
Nama Produk
</label>

<input
type="text"
class="form-control"
value="<?= $produk['nama_produk']; ?>"
readonly>

</div>

<div class="col-md-4">

<label class="fw-bold">
Kategori
</label>

<input
type="text"
class="form-control"
value="<?= $produk['kategori']; ?>"
readonly>

</div>

<div class="col-md-4">

<label class="fw-bold">
Harga Jual
</label>

<input
type="text"
class="form-control"
id="hargaJual"
value="<?= $produk['harga_jual']; ?>"
readonly>

</div>

</div>

</div>

</div>

<form method="POST" id="formHPP">
<input
type="hidden"
name="id_produk"
value="<?= $produk['id_produk']; ?>">

<div class="card shadow rounded-4 mb-4">

<div class="card-body">

<h4 class="mb-3">

<i class="bi bi-plus-circle text-danger"></i>

Tambah Bahan

</h4>

<div class="row">

<div class="col-md-6">

<label>Bahan Baku</label>

<select
id="bahan"
class="form-select">

<option value="">
-- Pilih Bahan --
</option>

<?php while($b=mysqli_fetch_assoc($bahan)){ ?>

<option
value="<?= $b['id_bahan']; ?>"
data-nama="<?= $b['nama_bahan']; ?>"
data-harga="<?= $b['harga_satuan']; ?>">

<?= $b['nama_bahan']; ?>

</option>

<?php } ?>

</select>

</div>

<div class="col-md-4">

<label>Jumlah Dipakai</label>

<input
type="number"
step="0.01"
id="jumlah"
class="form-control"
placeholder="Contoh : 0.25">

</div>

<div class="col-md-2 d-grid">

<label>&nbsp;</label>

<button
type="button"
class="btn btn-pink"
id="btnTambah">

<i class="bi bi-plus-circle"></i>

Tambah

</button>

</div>

</div>

</div>

</div>
<div class="card shadow rounded-4">

<div class="card-body">

<h4 class="mb-3">

<i class="bi bi-table text-danger"></i>

Daftar Perhitungan HPP

</h4>

<div class="table-responsive">

<table class="table table-bordered align-middle" id="tabelHPP">

<thead class="table-light">

<tr>

<th width="5%">No</th>

<th>Nama Bahan</th>

<th width="15%">Harga</th>

<th width="15%">Dipakai</th>

<th width="18%">Subtotal</th>

<th width="10%">Aksi</th>

</tr>

</thead>

<tbody>

<?php

$no=1;
$totalAwal=0;

foreach($detailLama as $d){

$totalAwal+=$d['subtotal'];

?>

<tr>

<td><?= $no++; ?></td>

<td><?= $d['nama_bahan']; ?></td>

<td>

Rp<?= number_format($d['harga_satuan'],0,",","."); ?>

</td>

<td>

<?= $d['jumlah_pakai']; ?>

</td>

<td>

Rp<?= number_format($d['subtotal'],0,",","."); ?>

</td>

<td>

<button
type="button"
class="btn btn-danger btn-sm">

<i class="bi bi-trash"></i>

</button>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

<hr>

<div class="row">

<div class="col-md-6">

<label class="fw-bold">

Biaya Tambahan

</label>

<input
type="number"
class="form-control"
id="biayaTambahan"
name="biaya_tambahan"
value="<?= $hpp ? $hpp['biaya_tambahan'] : 0; ?>>

</div>

<div class="col-md-6">

<div class="mb-2">

<label class="fw-bold">

Total Biaya Bahan

</label>

<input
type="text"
id="totalBahan"
class="form-control"
value="Rp <?= number_format($totalAwal,0,",","."); ?>"
readonly>

</div>

<div class="mb-2">

<label class="fw-bold">

Total HPP

</label>

<input
type="text"
id="totalHPP"
class="form-control"
value="Rp <?= number_format($totalAwal+($hpp['biaya_tambahan']??0),0,",","."); ?>"
readonly>

</div>

<div>

<label class="fw-bold">

Estimasi Laba

</label>

<input
type="text"
id="laba"
class="form-control"
value="Rp <?= number_format($produk['harga_jual']-($totalAwal+($hpp['biaya_tambahan']??0)),0,",","."); ?>"
readonly>

</div>

</div>

</div>

<input
type="hidden"
name="detail_hpp"
id="detailHPP">

<input
type="hidden"
name="total_hpp"
id="inputTotalHPP">

<div class="d-flex justify-content-between mt-4">

<button
type="button"
class="btn btn-secondary"
onclick="history.back();">

<i class="bi bi-arrow-left"></i>
Kembali

</button>

<button
type="submit"
name="simpan"
class="btn btn-pink">

<i class="bi bi-save"></i>

Simpan HPP

</button>

</div>

</div>

</div>

</form>
<script>

let no = 1;
let totalBahan = 0;
let detail = [];

const btnTambah = document.getElementById("btnTambah");
const tbody = document.querySelector("#tabelHPP tbody");

btnTambah.addEventListener("click",function(){

    let bahan = document.getElementById("bahan");
    let jumlah = document.getElementById("jumlah").value;

    if(bahan.value==""){
        alert("Pilih bahan baku.");
        return;
    }

    if(jumlah=="" || parseFloat(jumlah)<=0){
        alert("Masukkan jumlah pemakaian.");
        return;
    }

    let id = bahan.value;
    let nama = bahan.options[bahan.selectedIndex].dataset.nama;
    let harga = parseFloat(bahan.options[bahan.selectedIndex].dataset.harga);

    let subtotal = harga * parseFloat(jumlah);

    detail.push({
        id_bahan:id,
        harga_satuan:harga,
        jumlah_pakai:jumlah,
        subtotal:subtotal
    });

    totalBahan += subtotal;

    let tr = document.createElement("tr");

    tr.innerHTML = `
        <td>${no++}</td>

        <td>${nama}</td>

        <td>Rp ${harga.toLocaleString('id-ID')}</td>

        <td>${jumlah}</td>

        <td>Rp ${subtotal.toLocaleString('id-ID')}</td>

        <td>

        <button
        type="button"
        class="btn btn-danger btn-sm hapus">

        <i class="bi bi-trash"></i>

        </button>

        </td>
    `;

    tbody.appendChild(tr);

    bahan.options[bahan.selectedIndex].disabled=true;

    bahan.selectedIndex=0;

    document.getElementById("jumlah").value="";

    hitung();

    tr.querySelector(".hapus").addEventListener("click",function(){

        totalBahan -= subtotal;

        detail = detail.filter(function(item){

            return !(item.id_bahan==id &&
                     parseFloat(item.jumlah_pakai)==parseFloat(jumlah));

        });

        bahan.querySelector("option[value='"+id+"']").disabled=false;

        tr.remove();

        nomorUlang();

        hitung();

    });

});

function nomorUlang(){

    no=1;

    document.querySelectorAll("#tabelHPP tbody tr").forEach(function(row){

        row.cells[0].innerHTML=no++;

    });

}

document.getElementById("biayaTambahan").addEventListener("keyup",hitung);
document.getElementById("biayaTambahan").addEventListener("change",hitung);

function hitung(){

    let tambahan=parseFloat(document.getElementById("biayaTambahan").value)||0;

    let hpp=totalBahan+tambahan;

    let jual=parseFloat(document.getElementById("hargaJual").value);

    let laba=jual-hpp;

    document.getElementById("totalBahan").value=
    "Rp "+totalBahan.toLocaleString('id-ID');

    document.getElementById("totalHPP").value=
    "Rp "+hpp.toLocaleString('id-ID');

    document.getElementById("laba").value=
    "Rp "+laba.toLocaleString('id-ID');

    document.getElementById("inputTotalHPP").value=hpp;

    document.getElementById("detailHPP").value=
    JSON.stringify(detail);

}

document.getElementById("formHPP").addEventListener("submit",function(e){

    if(detail.length==0){

        e.preventDefault();

        alert("Tambahkan minimal satu bahan.");

    }

});

</script>

</body>
</html>