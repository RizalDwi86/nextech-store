<?php

session_start();

if (!isset($_SESSION['id'])) {

    header("Location: ../../../index.php");
    exit;

}

?>

<!DOCTYPE html>

<html>

<head>

<title>Dashboard Customer</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="container mt-5">

<h2>Dashboard Customer</h2>

<hr>

<p>Selamat datang <b><?php echo $_SESSION['nama']; ?></b></p>

<p>Email : <?php echo $_SESSION['email']; ?></p>

<p>Role : <?php echo $_SESSION['role']; ?></p>

<a href="../../../logout.php" class="btn btn-danger">

Logout

</a>

</body>

</html>