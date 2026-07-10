<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../../index.php");
    exit;
}

require_once '../../models/OrderModel.php';
$orderModel = new OrderModel();
$orders = $orderModel->getAllOrdersAdmin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - NexTech Store</title>
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
            <h4 class="fw-bold"><i class="bi bi-cart-check me-2"></i>Kelola Pesanan</h4>
            <a href="../dashboard/admin.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
        </div>
        
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID Order</th>
                                <th>Pelanggan</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Ubah Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Belum ada pesanan masuk.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($orders as $o): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold text-primary">#ORD-<?= str_pad($o['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                    <td>
                                        <div class="fw-semibold"><?= htmlspecialchars($o['nama_penerima']) ?></div>
                                        <small class="text-muted"><i class="bi bi-person me-1"></i><?= htmlspecialchars($o['nama_user']) ?></small>
                                    </td>
                                    <td><?= date('d M Y, H:i', strtotime($o['created_at'])) ?></td>
                                    <td class="fw-bold">Rp <?= number_format($o['total'], 0, ',', '.') ?></td>
                                    <td>
                                        <?php if ($o['status'] === 'pending'): ?>
                                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                                        <?php elseif ($o['status'] === 'diproses'): ?>
                                            <span class="badge bg-info text-dark"><i class="bi bi-arrow-repeat me-1"></i>Diproses</span>
                                        <?php elseif ($o['status'] === 'selesai'): ?>
                                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Selesai</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Dibatalkan</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <form action="../../controllers/OrderController.php?action=adminUpdateStatus" method="POST" class="d-inline-block">
                                            <input type="hidden" name="order_id" value="<?= $o['id'] ?>">
                                            <select name="status" class="form-select form-select-sm d-inline-block w-auto" onchange="this.form.submit()">
                                                <option value="pending" <?= $o['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                                <option value="diproses" <?= $o['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                                <option value="selesai" <?= $o['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                                <option value="dibatalkan" <?= $o['status'] == 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
