<?php
error_reporting(E_ALL);
ini_set('display_errors',1);
session_start();

if(!isset($_SESSION['login'])){
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$id_produk = (int)$_POST['id_produk'];
$biaya_tambahan = (float)$_POST['biaya_tambahan'];
$total_hpp = (float)$_POST['total_hpp'];

$detail = json_decode($_POST['detail_hpp'], true);

if(!$detail || count($detail)==0){
    die("Detail HPP kosong.");
}

/* =========================
   HAPUS HPP LAMA
========================= */

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

/* =========================
   SIMPAN HPP BARU
========================= */

mysqli_query($conn,"
INSERT INTO hpp
(
id_produk,
biaya_tambahan,
total_hpp,
tanggal_hitung
)
VALUES
(
'$id_produk',
'$biaya_tambahan',
'$total_hpp',
CURDATE()
)
");

$id_hpp = mysqli_insert_id($conn);

/* =========================
   SIMPAN DETAIL
========================= */

foreach($detail as $d){

    $id_bahan = (int)$d['id_bahan'];
    $harga = (float)$d['harga_satuan'];
    $jumlah = (float)$d['jumlah_pakai'];
    $subtotal = (float)$d['subtotal'];

    mysqli_query($conn,"
    INSERT INTO detail_hpp
    (
    id_hpp,
    id_bahan,
    harga_satuan,
    jumlah_pakai,
    subtotal
    )
    VALUES
    (
    '$id_hpp',
    '$id_bahan',
    '$harga',
    '$jumlah',
    '$subtotal'
    )
    ");

}

$_SESSION['success_hpp'] = "HPP berhasil disimpan";

header("Location: index.php");
exit;