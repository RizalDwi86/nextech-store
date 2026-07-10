<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../../../index.php');
    exit;
}

require_once '../../controllers/OrderController.php';
$orderController = new OrderController();
$viewData = $orderController->history();
$orders = $viewData['orders'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - NexTech Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
    </style>
</head>
<body>

    <?php require_once "../layout/navbar.php"; ?>

    <div class="container py-5">
        
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold">Riwayat Pesanan</h3>
                <p class="text-muted mb-0">Daftar semua transaksi yang pernah Anda lakukan.</p>
            </div>
            <a href="../customer/catalog.php" class="btn btn-dark"><i class="bi bi-cart-plus me-2"></i>Belanja Lagi</a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i><?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <?php if (empty($orders)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-receipt display-1 text-muted mb-3 d-block"></i>
                        <h5 class="text-muted mb-3">Anda belum memiliki riwayat pesanan.</h5>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">No. Order</th>
                                    <th>Tanggal</th>
                                    <th>Penerima</th>
                                    <th>Total Pembayaran</th>
                                    <th>Status</th>
                                    <th class="pe-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold">#<?= str_pad($order['id'], 5, '0', STR_PAD_LEFT) ?></td>
                                        <td><?= date('d M Y, H:i', strtotime($order['created_at'])) ?></td>
                                        <td><?= htmlspecialchars($order['nama_penerima']) ?></td>
                                        <td class="fw-bold text-dark">Rp <?= number_format($order['total'], 0, ',', '.') ?></td>
                                        <td>
                                            <?php 
                                            $badgeClass = 'bg-secondary';
                                            if ($order['status'] == 'pending') $badgeClass = 'bg-warning text-dark';
                                            if ($order['status'] == 'diproses') $badgeClass = 'bg-info text-dark';
                                            if ($order['status'] == 'selesai') $badgeClass = 'bg-success';
                                            if ($order['status'] == 'dibatalkan') $badgeClass = 'bg-danger';
                                            ?>
                                            <span class="badge <?= $badgeClass ?> text-uppercase"><?= $order['status'] ?></span>
                                        </td>
                                        <td class="pe-4 text-center">
                                            <a href="detail.php?id=<?= $order['id'] ?>" class="btn btn-outline-dark btn-sm">Detail</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
