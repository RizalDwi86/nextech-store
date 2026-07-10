<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../../../index.php');
    exit;
}

require_once '../../controllers/CartController.php';
$cartController = new CartController();
$cartItems = $cartController->index();
$total = $cartController->getTotal();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - NexTech Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .cart-img { width: 80px; height: 80px; object-fit: contain; border-radius: 8px; border: 1px solid #dee2e6; background: #fff; padding: 5px; }
        .cart-img-placeholder { width: 80px; height: 80px; border-radius: 8px; background-color: #e9ecef; display: flex; align-items: center; justify-content: center; color: #6c757d; font-size: 2rem; border: 1px solid #dee2e6; }
        .qty-input { width: 60px; text-align: center; }
    </style>
</head>
<body>

    <?php require_once "../layout/navbar.php"; ?>

    <div class="container py-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">Keranjang Belanja</h3>
            <a href="../customer/catalog.php" class="btn btn-outline-dark"><i class="bi bi-arrow-left me-2"></i>Lanjut Belanja</a>
        </div>

        <!-- Notifikasi -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i><?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle me-2"></i><?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (empty($cartItems)): ?>
            <div class="card shadow-sm border-0 text-center py-5">
                <div class="card-body">
                    <i class="bi bi-cart-x display-1 text-muted mb-3 d-block"></i>
                    <h5 class="text-muted mb-4">Keranjang belanja Anda masih kosong.</h5>
                    <a href="../customer/catalog.php" class="btn btn-dark px-4 py-2">Mulai Belanja</a>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4">Produk</th>
                                            <th>Harga</th>
                                            <th class="text-center">Kuantitas</th>
                                            <th>Subtotal</th>
                                            <th class="pe-4 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cartItems as $id => $item): ?>
                                            <tr>
                                                <td class="ps-4 py-3">
                                                    <div class="d-flex align-items-center">
                                                        <?php if (!empty($item['product']['gambar']) && file_exists(__DIR__ . '/../../../public/uploads/' . $item['product']['gambar'])): ?>
                                                            <img src="../../../public/uploads/<?= htmlspecialchars($item['product']['gambar']) ?>" alt="Img" class="cart-img me-3 zoomable-img">
                                                        <?php else: ?>
                                                            <div class="cart-img-placeholder me-3"><i class="bi bi-image"></i></div>
                                                        <?php endif; ?>
                                                        <div>
                                                            <h6 class="fw-bold mb-1"><?= htmlspecialchars($item['product']['nama']) ?></h6>
                                                            <small class="text-muted">Stok: <?= $item['product']['stok'] ?></small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="fw-semibold text-muted">Rp <?= number_format($item['product']['harga'], 0, ',', '.') ?></td>
                                                <td>
                                                    <form action="update.php" method="POST" class="d-flex justify-content-center">
                                                        <input type="hidden" name="product_id" value="<?= $id ?>">
                                                        <div class="input-group input-group-sm" style="width: 120px;">
                                                            <button class="btn btn-outline-secondary" type="submit" name="qty" value="<?= $item['qty'] - 1 ?>">-</button>
                                                            <input type="number" class="form-control text-center bg-white" value="<?= $item['qty'] ?>" readonly>
                                                            <button class="btn btn-outline-secondary" type="submit" name="qty" value="<?= $item['qty'] + 1 ?>">+</button>
                                                        </div>
                                                    </form>
                                                </td>
                                                <td class="fw-bold text-dark">Rp <?= number_format($item['product']['harga'] * $item['qty'], 0, ',', '.') ?></td>
                                                <td class="pe-4 text-center">
                                                    <form action="remove.php" method="POST">
                                                        <input type="hidden" name="product_id" value="<?= $id ?>">
                                                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle shadow-sm" title="Hapus">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold border-bottom pb-3 mb-3">Ringkasan Belanja</h5>
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Barang</span>
                                <span class="fw-semibold"><?= count($cartItems) ?> item</span>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <span class="fw-bold">Total Harga</span>
                                <h4 class="fw-bold text-danger mb-0">Rp <?= number_format($total, 0, ',', '.') ?></h4>
                            </div>

                            <a href="../order/checkout.php" class="btn btn-dark w-100 py-3 fw-bold rounded-3 shadow-sm">
                                Lanjut ke Checkout <i class="bi bi-shield-check ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
