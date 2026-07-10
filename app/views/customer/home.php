<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../../../index.php');
    exit;
}

require_once '../../controllers/CustomerController.php';
$controller = new CustomerController();
$viewData = $controller->home();
$recentProducts = $viewData['recentProducts'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - NexTech Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .hero-section {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 60px 0;
            border-radius: 16px;
            margin-top: 30px;
        }
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

    <div class="container pb-5">
        
        <!-- Hero Section -->
        <div class="hero-section text-center shadow-lg">
            <h1 class="fw-bold mb-3">Selamat Datang di NexTech Store</h1>
            <p class="lead mb-4 opacity-75">Pusat Belanja Elektronik Terbaik dan Terpercaya</p>
            <a href="catalog.php" class="btn btn-danger btn-lg px-4 rounded-pill fw-semibold">Belanja Sekarang <i class="bi bi-arrow-right ms-2"></i></a>
        </div>

        <!-- Recent Products -->
        <div class="mt-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-dark"><i class="bi bi-stars text-warning me-2"></i>Produk Terbaru</h4>
                <a href="catalog.php" class="text-decoration-none fw-semibold">Lihat Semua</a>
            </div>

            <div class="row g-4">
                <?php foreach ($recentProducts as $product): ?>
                    <div class="col-md-3">
                        <div class="card product-card shadow-sm">
                            <div class="product-img-wrapper border-bottom">
                                <?php if (!empty($product['gambar']) && file_exists(__DIR__ . '/../../../public/uploads/' . $product['gambar'])): ?>
                                    <img src="../../../public/uploads/<?= htmlspecialchars($product['gambar']) ?>" alt="<?= htmlspecialchars($product['nama']) ?>" class="product-img">
                                <?php else: ?>
                                    <i class="bi bi-image placeholder-img"></i>
                                <?php endif; ?>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title fw-bold text-truncate" title="<?= htmlspecialchars($product['nama']) ?>"><?= htmlspecialchars($product['nama']) ?></h6>
                                <p class="text-danger fw-bold mb-3">Rp <?= number_format($product['harga'], 0, ',', '.') ?></p>
                                <a href="detail.php?id=<?= $product['id'] ?>" class="btn btn-outline-dark w-100 mt-auto btn-sm">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
