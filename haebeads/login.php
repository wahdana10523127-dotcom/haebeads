<?php
session_start();
include "config/koneksi.php";
if(isset($_POST['login'])){
    $username = mysqli_real_escape_string($conn,$_POST['username']);
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    $query = mysqli_query($conn,"SELECT * FROM users WHERE username='$username' AND password='$password'");
    if(mysqli_num_rows($query)>0){
        $_SESSION['login']=true;
        header("Location: dashboard.php");
        exit;
    }else{
        $error="Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Haebeads</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="login-container">
<div class="login-card">
<div class="row g-0 h-100">
<!-- LEFT -->
<div class="col-lg-6 left-side">
<div class="login-form">
<h1>Welcome Back!</h1>
<p>Manage your Haebeads business quickly and efficiently.</p>
<?php
if(isset($error)){
?>
<div class="alert alert-danger">
<?= $error ?>
</div>
<?php
}
?>
<form method="POST">
<div class="mb-3">
<label>Username</label>
<input
type="text"
name="username"
class="form-control"
placeholder="Enter Username"
required>
</div>
<div class="mb-4">
<label>Password</label>
<input
type="password"
name="password"
class="form-control"
placeholder="Enter Password"
required>
</div>
<div class="d-flex justify-content-between align-items-center mb-4">
<div>
<input type="checkbox">
Remember me
</div>
<a href="forgot_password.php">
Forgot Password?
</a>
</div>
<button
type="submit"
name="login"
class="btn-login">
Login
</button>
</form>
</div>
</div>
<!-- RIGHT -->
<div class="col-lg-6 right-side">
<div class="right-content">
<img src="assets/img/haebeads.jpeg">
<h2>HAEBEADS</h2>
<h5>For Your Habbits!</h5>
<p>
Handmade Accessories Management System
</p>
</div>
</div>
</div>
</div>
</div>
</body>
</html>