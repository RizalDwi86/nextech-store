<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../../../index.php');
    exit;
}

require_once '../../controllers/OrderController.php';
$orderController = new OrderController();
$viewData = $orderController->checkout();
$cartItems = $viewData['cartItems'];
$total = $viewData['total'];
$errors = $viewData['errors'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - NexTech Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
    </style>
</head>
<body>

    <?php require_once "../layout/navbar.php"; ?>

    <div class="container py-5">
        
        <div class="mb-4">
            <h3 class="fw-bold">Checkout Pesanan</h3>
            <p class="text-muted">Lengkapi data pengiriman untuk memproses pesanan Anda.</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h5 class="fw-bold border-bottom pb-3 mb-4">Informasi Pengiriman</h5>
                        
                        <form action="checkout.php" method="POST" id="checkoutForm">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nama Penerima</label>
                                <input type="text" name="nama_penerima" class="form-control" value="<?= htmlspecialchars($_SESSION['nama'] ?? '') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nomor HP / WhatsApp</label>
                                <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 08123456789" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-semibold">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control" rows="4" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos" required><?= htmlspecialchars($_SESSION['alamat'] ?? '') ?></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold border-bottom pb-3 mb-4">Ringkasan Pesanan</h5>
                        
                        <div class="mb-4" style="max-height: 250px; overflow-y: auto;">
                            <?php foreach ($cartItems as $item): ?>
                                <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                                    <div>
                                        <h6 class="mb-1 fw-bold"><?= htmlspecialchars($item['product']['nama']) ?></h6>
                                        <small class="text-muted"><?= $item['qty'] ?>x Rp <?= number_format($item['product']['harga'], 0, ',', '.') ?></small>
                                    </div>
                                    <div class="fw-bold text-dark">
                                        Rp <?= number_format($item['product']['harga'] * $item['qty'], 0, ',', '.') ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4 pt-2">
                            <span class="fs-5 fw-bold">Total Pembayaran</span>
                            <h3 class="fw-bold text-danger mb-0">Rp <?= number_format($total, 0, ',', '.') ?></h3>
                        </div>

                        <button type="submit" form="checkoutForm" class="btn btn-success w-100 py-3 fw-bold rounded-3 shadow-sm">
                            Selesaikan Pesanan <i class="bi bi-check-circle ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
