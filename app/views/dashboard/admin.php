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
    <title>Dashboard Admin</title>
</head>

<body>

    <h1>Dashboard Admin</h1>

    <p>Selamat datang,
        <b><?php echo $_SESSION['nama']; ?></b>
    </p>

    <p>Email :
        <?php echo $_SESSION['email']; ?>
    </p>

    <p>Role :
        <?php echo $_SESSION['role']; ?>
    </p>

    <br>

    <a href="../../../logout.php">Logout</a>

</body>

</html>