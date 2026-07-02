<?php

session_start();

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['id'])) {
    header("Location: ../../../index.php");
    exit;
}

require_once '../../core/Database.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $password_lama = $_POST['password_lama'];
    $password_baru = $_POST['password_baru'];
    $konfirmasi = $_POST['konfirmasi_password'];

    $query = $conn->prepare("SELECT password FROM users WHERE id=?");
    $query->execute([$_SESSION['id']]);

    $user = $query->fetch(PDO::FETCH_ASSOC);

    if (!password_verify($password_lama, $user['password'])) {

        echo "<script>
                alert('Password lama salah!');
                window.location='change_password.php';
              </script>";
        exit;
    }

    if ($password_baru != $konfirmasi) {

        echo "<script>
                alert('Konfirmasi password tidak sama!');
                window.location='change_password.php';
              </script>";
        exit;
    }

    $passwordHash = password_hash($password_baru, PASSWORD_DEFAULT);

    $update = $conn->prepare("UPDATE users SET password=? WHERE id=?");
    $update->execute([$passwordHash, $_SESSION['id']]);

    echo "<script>
            alert('Password berhasil diubah!');
            window.location='profile.php';
          </script>";

    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Ganti Password</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<?php require_once "../layout/navbar.php"; ?>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header">

<h3>Ganti Password</h3>

</div>

<div class="card-body">

<form method="POST">

<div class="mb-3">

<label>Password Lama</label>

<input
type="password"
name="password_lama"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Password Baru</label>

<input
type="password"
name="password_baru"
class="form-control"
required>

</div>

<div class="mb-3">

<label>Konfirmasi Password</label>

<input
type="password"
name="konfirmasi_password"
class="form-control"
required>

</div>

<button class="btn btn-success">

Simpan Password

</button>

<a href="profile.php" class="btn btn-secondary">

Kembali

</a>

</form>

</div>

</div>

</div>

</body>

</html>