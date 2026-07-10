<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../../../index.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: history.php');
    exit;
}

require_once '../../controllers/OrderController.php';
$orderController = new OrderController();

// We need to fetch the order summary from DB to check if it belongs to the user
// Since getOrdersByUser gives all, we can just find it, or we add getOrderById
// To keep it simple, we'll fetch from history and filter it.
$orders = $orderController->history()['orders'];
$currentOrder = null;
foreach ($orders as $o) {
    if ($o['id'] == $_GET['id']) {
        $currentOrder = $o;
        break;
    }
}

if (!$currentOrder) {
    $_SESSION['error'] = 'Pesanan tidak ditemukan atau bukan milik Anda.';
    header('Location: history.php');
    exit;
}

$orderDetails = $orderController->detail($_GET['id']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #<?= str_pad($currentOrder['id'], 5, '0', STR_PAD_LEFT) ?> - NexTech Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .product-img { width: 60px; height: 60px; object-fit: contain; border-radius: 8px; border: 1px solid #dee2e6; background: #fff; padding: 5px; }
        .placeholder-img { width: 60px; height: 60px; border-radius: 8px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center; color: #6c757d; font-size: 1.5rem; border: 1px solid #dee2e6; }
    </style>
</head>
<body>

    <?php require_once "../layout/navbar.php"; ?>

    <div class="container py-5">
        
        <div class="mb-4">
            <a href="history.php" class="text-decoration-none text-muted fw-semibold">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Riwayat
            </a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                    <h5 class="fw-bold mb-0">Detail Pesanan #<?= str_pad($currentOrder['id'], 5, '0', STR_PAD_LEFT) ?></h5>
                    <?php 
                    $badgeClass = 'bg-secondary';
                    if ($currentOrder['status'] == 'pending') $badgeClass = 'bg-warning text-dark';
                    if ($currentOrder['status'] == 'diproses') $badgeClass = 'bg-info text-dark';
                    if ($currentOrder['status'] == 'selesai') $badgeClass = 'bg-success';
                    if ($currentOrder['status'] == 'dibatalkan') $badgeClass = 'bg-danger';
                    ?>
                    <span class="badge <?= $badgeClass ?> fs-6 px-3 py-2 text-uppercase"><?= $currentOrder['status'] ?></span>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6 border-end">
                        <h6 class="fw-bold text-muted mb-3">Informasi Pengiriman</h6>
                        <p class="mb-1"><strong>Penerima:</strong> <?= htmlspecialchars($currentOrder['nama_penerima']) ?></p>
                        <p class="mb-1"><strong>No. HP:</strong> <?= htmlspecialchars($currentOrder['no_hp']) ?></p>
                        <p class="mb-0"><strong>Alamat:</strong> <?= nl2br(htmlspecialchars($currentOrder['alamat'])) ?></p>
                    </div>
                    <div class="col-md-6 ps-md-4 mt-4 mt-md-0">
                        <h6 class="fw-bold text-muted mb-3">Informasi Pesanan</h6>
                        <p class="mb-1"><strong>Tanggal:</strong> <?= date('d M Y, H:i', strtotime($currentOrder['created_at'])) ?></p>
                        <p class="mb-1"><strong>Metode Pembayaran:</strong> COD / Transfer Bank</p>
                    </div>
                </div>

                <h6 class="fw-bold text-muted mb-3">Daftar Produk</h6>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Harga Satuan</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orderDetails as $detail): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($detail['gambar']) && file_exists(__DIR__ . '/../../../public/uploads/' . $detail['gambar'])): ?>
                                                <img src="../../../public/uploads/<?= htmlspecialchars($detail['gambar']) ?>" alt="Img" class="product-img me-3 zoomable-img">
                                            <?php else: ?>
                                                <div class="placeholder-img me-3"><i class="bi bi-image"></i></div>
                                            <?php endif; ?>
                                            <span class="fw-semibold"><?= htmlspecialchars($detail['nama_produk']) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">Rp <?= number_format($detail['harga_satuan'], 0, ',', '.') ?></td>
                                    <td class="text-center"><?= $detail['qty'] ?></td>
                                    <td class="text-end fw-bold">Rp <?= number_format($detail['harga_satuan'] * $detail['qty'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold fs-5">Total Pembayaran</td>
                                <td class="text-end fw-bold fs-5 text-danger">Rp <?= number_format($currentOrder['total'], 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
