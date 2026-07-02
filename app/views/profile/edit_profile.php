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

    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $alamat = trim($_POST['alamat']);

    // Cek apakah email sudah digunakan user lain
    $cek = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $cek->execute([$email, $_SESSION['id']]);

    if ($cek->rowCount() > 0) {

        echo "<script>
                alert('Email sudah digunakan oleh pengguna lain!');
                window.location='edit_profile.php';
              </script>";
        exit;
    }

    // Update data
    $query = $conn->prepare("UPDATE users SET nama=?, email=?, alamat=? WHERE id=?");
    $query->execute([
        $nama,
        $email,
        $alamat,
        $_SESSION['id']
    ]);

    // Update session
    $_SESSION['nama'] = $nama;
    $_SESSION['email'] = $email;

    echo "<script>
            alert('Profil berhasil diperbarui!');
            window.location='profile.php';
          </script>";

    exit;
}

$query = $conn->prepare("SELECT * FROM users WHERE id=?");
$query->execute([$_SESSION['id']]);

$user = $query->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Edit Profil</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<?php require_once "../layout/navbar.php"; ?>

<div class="container mt-5">

    <div class="card shadow">

        <div class="card-header">
            <h3>Edit Profil</h3>
        </div>

        <div class="card-body">

            <form method="POST">

                <div class="mb-3">

                    <label class="form-label">Nama</label>

                    <input
                        type="text"
                        class="form-control"
                        name="nama"
                        value="<?php echo htmlspecialchars($user['nama']); ?>"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">Email</label>

                    <input
                        type="email"
                        class="form-control"
                        name="email"
                        value="<?php echo htmlspecialchars($user['email']); ?>"
                        required>

                </div>

                <div class="mb-3">

                    <label class="form-label">Alamat</label>

                    <textarea
                        class="form-control"
                        name="alamat"
                        rows="3"
                        required><?php echo htmlspecialchars($user['alamat']); ?></textarea>

                </div>

                <button type="submit" class="btn btn-success">

                    Simpan Perubahan

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