<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../index.php");
    exit;
}

require_once '../../controllers/UserController.php';
$userController = new UserController();
$users = $userController->index();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - NexTech Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
    </style>
</head>
<body>
    <?php include '../layout/navbar.php'; ?>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold"><i class="bi bi-people me-2"></i>Kelola Pengguna</h4>
            <a href="../dashboard/admin.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        </div>
        
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th>Role</th>
                                <th>Tanggal Daftar</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                            <tr>
                                <td class="ps-4 text-muted">#<?= $u['id'] ?></td>
                                <td class="fw-semibold"><?= htmlspecialchars($u['nama']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><small class="text-muted"><?= htmlspecialchars(substr($u['alamat'], 0, 30)) ?>...</small></td>
                                <td>
                                    <?php if ($u['role'] === 'admin'): ?>
                                        <span class="badge bg-danger">Admin</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Customer</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d M Y, H:i', strtotime($u['created_at'])) ?></td>
                                <td class="text-end pe-4">
                                    <form action="../../controllers/UserController.php?action=updateRole" method="POST" class="d-inline-block">
                                        <input type="hidden" name="id" value="<?= $u['id'] ?>">
                                        <select name="role" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                            <option value="customer" <?= $u['role'] == 'customer' ? 'selected' : '' ?>>Customer</option>
                                            <option value="admin" <?= $u['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                                        </select>
                                    </form>
                                    <?php if ($u['id'] != $_SESSION['id']): ?>
                                        <a href="../../controllers/UserController.php?action=delete&id=<?= $u['id'] ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Yakin ingin menghapus pengguna ini?')"><i class="bi bi-trash"></i></a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
