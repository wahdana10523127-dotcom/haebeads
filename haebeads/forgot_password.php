<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Forgot Password | Haebeads</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#ffe6ef;
    font-family:'Poppins',sans-serif;
}

.card-login{
    max-width:1100px;
    width:100%;
    border:none;
    border-radius:30px;
    overflow:hidden;
    box-shadow:0 15px 35px rgba(0,0,0,.15);
}

.left{
    background:#fff;
    padding:60px;
}

.right{
    background:#f45b98;
    color:white;
    text-align:center;
    padding:50px;
}

.right img{
    width:320px;
    border-radius:25px;
}

.title{
    color:#f45b98;
    font-size:55px;
    font-weight:700;
    margin-bottom:20px;
}

.desc{
    color:#666;
    font-size:22px;
    line-height:1.7;
    margin-bottom:40px;
}

.btn-pink{
    background:#f45b98;
    color:white;
    border:none;
    padding:12px;
    border-radius:12px;
    font-size:18px;
    font-weight:600;
}

.btn-pink:hover{
    background:#ec3f83;
    color:white;
}

.brand{
    font-size:48px;
    font-weight:bold;
    margin-top:25px;
}

.tagline{
    font-size:28px;
    font-weight:600;
}

.sub{
    font-size:20px;
}

</style>

</head>

<body>

<div class="container vh-100 d-flex justify-content-center align-items-center">

<div class="card-login row">

<div class="col-md-6 left">

<h1 class="title">
Forgot Password?
</h1>

<p class="desc">

Jika lupa password, silakan hubungi Administrator
untuk melakukan reset password akun Haebeads.

</p>

<button
type="button"
class="btn btn-pink w-100"
onclick="window.location.replace('login.php')">

Kembali ke Login

</button>

</div>

<div class="col-md-6 right d-flex flex-column justify-content-center align-items-center">

<img src="assets/img/haebeads.jpeg">

<div class="brand">
HAEBEADS
</div>

<div class="tagline">
For Your Habbits!
</div>

<div class="sub mt-3">
Handmade Accessories Management System
</div>

</div>

</div>

</div>

</body>

</html>