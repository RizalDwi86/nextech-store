<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../../../index.php');
    exit;
}

require_once '../../controllers/CustomerController.php';
$controller = new CustomerController();
$viewData = $controller->catalog();
$products = $viewData['products'];
$search = $viewData['search'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - NexTech Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .product-card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .product-img-wrapper {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            padding: 15px;
        }
        .product-img {
            max-height: 100%;
            max-width: 100%;
            object-fit: contain;
        }
        .placeholder-img {
            font-size: 3rem;
            color: #dee2e6;
        }
    </style>
</head>
<body>

    <?php require_once "../layout/navbar.php"; ?>

    <div class="container py-5">
        
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h3 class="fw-bold mb-0">Katalog Produk</h3>
            </div>
            <div class="col-md-6 mt-3 mt-md-0">
                <form action="catalog.php" method="GET" class="d-flex">
                    <div class="input-group shadow-sm rounded-pill overflow-hidden">
                        <input type="text" name="search" class="form-control border-0 px-4" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">
                        <button class="btn btn-dark px-4" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (!empty($search)): ?>
            <p class="text-muted mb-4">Menampilkan hasil pencarian untuk: <strong>"<?= htmlspecialchars($search) ?>"</strong></p>
        <?php endif; ?>

        <div class="row g-4">
            <?php if (empty($products)): ?>
                <div class="col-12 text-center py-5">
                    <i class="bi bi-box-seam display-1 text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">Tidak ada produk yang ditemukan.</h5>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <div class="col-md-3">
                        <div class="card product-card shadow-sm">
                            <div class="product-img-wrapper border-bottom">
                                <?php if (!empty($product['gambar']) && file_exists(__DIR__ . '/../../../public/uploads/' . $product['gambar'])): ?>
                                    <img src="../../../public/uploads/<?= htmlspecialchars($product['gambar']) ?>" alt="<?= htmlspecialchars($product['nama']) ?>" class="product-img zoomable-img">
                                <?php else: ?>
                                    <i class="bi bi-image placeholder-img"></i>
                                <?php endif; ?>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title fw-bold text-truncate" title="<?= htmlspecialchars($product['nama']) ?>"><?= htmlspecialchars($product['nama']) ?></h6>
                                <p class="text-danger fw-bold mb-2">Rp <?= number_format($product['harga'], 0, ',', '.') ?></p>
                                
                                <div class="mb-3">
                                    <?php if ($product['stok'] > 0): ?>
                                        <span class="badge bg-success-subtle text-success">Stok: <?= $product['stok'] ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-danger-subtle text-danger">Habis</span>
                                    <?php endif; ?>
                                </div>

                                <a href="detail.php?id=<?= $product['id'] ?>" class="btn btn-outline-dark w-100 mt-auto btn-sm">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
