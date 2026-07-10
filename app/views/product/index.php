<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../dashboard/customer.php');
    exit;
}

require_once '../../controllers/ProductController.php';
$controller = new ProductController();


if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $controller->delete($_GET['id']);
}

$viewData = $controller->index();
$products = $viewData['products'];
$search = $viewData['search'];
$page = $viewData['page'];
$totalPages = $viewData['totalPages'];
$totalProducts = $viewData['totalProducts'];
$offset = ($page - 1) * 5; // 5 = $limit, untuk penomoran baris
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk - NexTech Store</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom Style for Premium Look -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }
        .table th {
            background-color: #343a40;
            color: #ffffff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            padding: 15px;
            vertical-align: middle;
        }
        .table td {
            padding: 15px;
            vertical-align: middle;
        }
        .product-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .product-img:hover {
            transform: scale(1.05);
        }
        .product-img-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 1.5rem;
            border: 1px solid #dee2e6;
        }
        .btn-action {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s ease;
        }
        .btn-action:hover {
            transform: translateY(-2px);
        }
        .page-link {
            border-radius: 8px;
            margin: 0 3px;
            color: #495057;
            border: 1px solid #dee2e6;
        }
        .page-item.active .page-link {
            background-color: #212529;
            border-color: #212529;
            color: #fff;
        }
        .badge-stok {
            font-size: 0.8rem;
            padding: 6px 10px;
            border-radius: 30px;
        }
    </style>
</head>
<body>

    <!-- Load Navbar -->
    <?php require_once "../layout/navbar.php"; ?>

    <div class="container my-5">
        
        <!-- Header -->
        <div class="row align-items-center mb-4">
            <div class="col-md-6">
                <h3 class="fw-bold text-dark mb-1"><i class="bi bi-box-seam me-2"></i>Kelola Produk</h3>
                <p class="text-muted mb-0">Total produk terdaftar: <strong><?= $totalProducts ?></strong> item</p>
            </div>
            <div class="col-md-6 text-md-end mt-3 mt-md-0">
                <a href="create.php" class="btn btn-dark px-4 py-2 fw-semibold shadow-sm">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Produk Baru
                </a>
            </div>
        </div>

        <!-- Alert Notification -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                    <div><?= $_SESSION['success'] ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                    <div><?= $_SESSION['error'] ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Table Card -->
        <div class="card p-3">
            <!-- Search & Actions -->
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <form method="GET" action="" class="d-flex gap-2">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0" 
                                   placeholder="Cari nama produk..." value="<?= htmlspecialchars($search) ?>">
                        </div>
                        <button type="submit" class="btn btn-secondary px-3">Cari</button>
                        <?php if (!empty($search)): ?>
                            <a href="index.php" class="btn btn-outline-secondary btn-action" title="Reset Pencarian">
                                <i class="bi bi-arrow-clockwise"></i>
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="50" class="text-center">No</th>
                            <th width="90" class="text-center">Gambar</th>
                            <th>Nama Produk</th>
                            <th width="180">Harga</th>
                            <th width="120" class="text-center">Stok</th>
                            <th>Deskripsi</th>
                            <th width="120" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    Tidak ada produk ditemukan.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php 
                            $no = $offset + 1;
                            foreach ($products as $product): 
                            ?>
                                <tr>
                                    <td class="text-center fw-semibold text-muted"><?= $no++ ?></td>
                                    <td class="text-center">
                                        <?php if (!empty($product['gambar']) && file_exists(__DIR__ . '/../../../public/uploads/' . $product['gambar'])): ?>
                                            <img src="../../../public/uploads/<?= htmlspecialchars($product['gambar']) ?>" 
                                                 alt="<?= htmlspecialchars($product['nama']) ?>" class="product-img"
                                                 onclick="showFullImage(this.src)" title="Klik untuk memperbesar">
                                        <?php else: ?>
                                            <div class="product-img-placeholder">
                                                <i class="bi bi-image"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($product['nama']) ?></div>
                                        <small class="text-muted">ID: #<?= $product['id'] ?></small>
                                    </td>
                                    <td class="fw-bold text-success">
                                        Rp <?= number_format($product['harga'], 0, ',', '.') ?>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($product['stok'] > 10): ?>
                                            <span class="badge bg-success-subtle text-success badge-stok">
                                                <?= $product['stok'] ?> Pcs
                                            </span>
                                        <?php elseif ($product['stok'] > 0): ?>
                                            <span class="badge bg-warning-subtle text-warning badge-stok">
                                                <?= $product['stok'] ?> Pcs
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger badge-stok">
                                                Habis
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div style="max-width: 250px; font-size: 0.9rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;" title="<?= htmlspecialchars($product['deskripsi']) ?>">
                                            <?= htmlspecialchars($product['deskripsi'] ?: '-') ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="edit.php?id=<?= $product['id'] ?>" 
                                               class="btn btn-outline-primary btn-action" title="Edit Produk">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="index.php?action=delete&id=<?= $product['id'] ?>" 
                                               class="btn btn-outline-danger btn-action" title="Hapus Produk"
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini? File gambar produk juga akan dihapus secara permanen.');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <span class="text-muted small">
                        Halaman <strong><?= $page ?></strong> dari <strong><?= $totalPages ?></strong>
                    </span>
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            <!-- Prev Link -->
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">
                                    <i class="bi bi-chevron-left"></i>
                                </a>
                            </li>

                            <!-- Page Numbers -->
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                    <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- Next Link -->
                            <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">
                                    <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Modal for Full Image -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body text-center position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 10;"></button>
                    <img src="" id="fullImagePreview" class="img-fluid rounded shadow-lg" style="max-height: 85vh; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let modalInstance = null;
        function showFullImage(src) {
            document.getElementById('fullImagePreview').src = src;
            if (!modalInstance) {
                modalInstance = new bootstrap.Modal(document.getElementById('imageModal'));
            }
            modalInstance.show();
        }
    </script>
</body>
</html>
