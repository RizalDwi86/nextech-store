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

$query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$query->execute([$_SESSION['id']]);

$user = $query->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Profil Saya</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<?php require_once "../layout/navbar.php"; ?>

<div class="container mt-5">

<div class="card shadow">

<div class="card-header">

<h3>Profil Saya</h3>

</div>

<div class="card-body">

<table class="table">

<tr>

<th width="200">Nama</th>

<td><?php echo $user['nama']; ?></td>

</tr>

<tr>

<th>Email</th>

<td><?php echo $user['email']; ?></td>

</tr>

<tr>

<th>Role</th>

<td><?php echo ucfirst($user['role']); ?></td>

</tr>

<tr>

<th>Alamat</th>

<td><?php echo $user['alamat']; ?></td>

</tr>

<tr>

<th>Tanggal Dibuat</th>

<td><?php echo $user['created_at']; ?></td>

</tr>

</table>

<a href="edit_profile.php" class="btn btn-primary">

Edit Profil

</a>

<a href="change_password.php" class="btn btn-warning">
    Ganti Password
</a>

</div>

</div>

</div>

</body>

</html>