<?php

session_start();

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['id'])) {
    header("Location: ../../../index.php");
    exit;
}

if ($_SESSION['role'] != "admin") {
    header("Location: customer.php");
    exit;
}

?>

<!DOCTYPE html>

<html>

<head>

<title>Dashboard Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<?php require_once "../layout/navbar.php"; ?>

<div class="container mt-5">

<div class="card shadow">

<div class="card-body">

<h2>Dashboard Admin</h2>

<hr>

<p>Selamat datang <b><?php echo $_SESSION['nama']; ?></b></p>

<p>Email : <?php echo $_SESSION['email']; ?></p>

<p>Role : <?php echo $_SESSION['role']; ?></p>

</div>

</div>

</div>

</body>

</html>