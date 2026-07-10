<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../../../index.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: catalog.php');
    exit;
}

require_once '../../controllers/CustomerController.php';
$controller = new CustomerController();
$viewData = $controller->detail($_GET['id']);
$product = $viewData['product'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['nama']) ?> - NexTech Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .product-img-wrapper {
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border-radius: 12px;
            padding: 20px;
        }
        .product-img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }
        .placeholder-img {
            font-size: 5rem;
            color: #dee2e6;
        }
        .card {
            border: none;
            border-radius: 16px;
        }
    </style>
</head>
<body>

    <?php require_once "../layout/navbar.php"; ?>

    <div class="container py-5">
        
        <div class="mb-4">
            <a href="catalog.php" class="text-decoration-none text-muted fw-semibold">
                <i class="bi bi-arrow-left me-2"></i>Kembali ke Katalog
            </a>
        </div>

        <div class="card shadow-sm p-4">
            <div class="row g-5">
                
                <!-- Gambar Produk -->
                <div class="col-md-5">
                    <div class="product-img-wrapper border shadow-sm">
                        <?php if (!empty($product['gambar']) && file_exists(__DIR__ . '/../../../public/uploads/' . $product['gambar'])): ?>
                            <img src="../../../public/uploads/<?= htmlspecialchars($product['gambar']) ?>" alt="<?= htmlspecialchars($product['nama']) ?>" class="product-img">
                        <?php else: ?>
                            <i class="bi bi-image placeholder-img"></i>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Info Produk -->
                <div class="col-md-7">
                    <?php if (!empty($product['kategori'])): ?>
                        <span class="badge bg-secondary mb-2 px-3 py-2"><?= htmlspecialchars($product['kategori']) ?></span>
                    <?php endif; ?>
                    
                    <h2 class="fw-bold mb-3"><?= htmlspecialchars($product['nama']) ?></h2>
                    <h3 class="text-danger fw-bold mb-4">Rp <?= number_format($product['harga'], 0, ',', '.') ?></h3>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold">Deskripsi Produk</h6>
                        <p class="text-muted" style="white-space: pre-wrap; line-height: 1.6;"><?= htmlspecialchars($product['deskripsi'] ?: 'Tidak ada deskripsi.') ?></p>
                    </div>

                    <hr>

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <span class="fs-6 text-muted">
                            Status Stok: 
                            <?php if ($product['stok'] > 0): ?>
                                <strong class="text-success"><?= $product['stok'] ?> Pcs</strong>
                            <?php else: ?>
                                <strong class="text-danger">Habis</strong>
                            <?php endif; ?>
                        </span>
                    </div>

                    <!-- Form Tambah Keranjang -->
                    <?php if ($product['stok'] > 0): ?>
                        <form action="../cart/add.php" method="POST" class="d-flex gap-3">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <div class="input-group" style="width: 130px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="this.nextElementSibling.stepDown()">-</button>
                                <input type="number" name="qty" class="form-control text-center fw-bold" value="1" min="1" max="<?= $product['stok'] ?>">
                                <button class="btn btn-outline-secondary" type="button" onclick="this.previousElementSibling.stepUp()">+</button>
                            </div>
                            <button type="submit" class="btn btn-dark px-4 flex-grow-1 fw-semibold shadow-sm">
                                <i class="bi bi-cart-plus me-2"></i>Tambah ke Keranjang
                            </button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-secondary px-4 w-100 fw-semibold" disabled>
                            <i class="bi bi-x-circle me-2"></i>Stok Habis
                        </button>
                    <?php endif; ?>
                    
                </div>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
