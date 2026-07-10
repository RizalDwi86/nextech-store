<?php
session_start();


if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../dashboard/customer.php');
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = 'ID produk tidak valid.';
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];

require_once '../../controllers/ProductController.php';
$controller = new ProductController();

// Memproses input edit produk
$result = $controller->edit($id);
$product = $result['product'];
$errors = $result['errors'];
$oldInput = $result['oldInput'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - NexTech Store</title>
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
        .form-label {
            font-weight: 600;
            color: #495057;
        }
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px dashed #dee2e6;
            padding: 5px;
        }
        .preview-container {
            min-height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

    <!-- Load Navbar -->
    <?php require_once "../layout/navbar.php"; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <!-- Back Link -->
                <div class="mb-3">
                    <a href="index.php" class="text-decoration-none text-muted fw-semibold">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Kelola Produk
                    </a>
                </div>

                <!-- Form Card -->
                <div class="card p-4">
                    <h3 class="fw-bold mb-1 text-dark"><i class="bi bi-pencil-square me-2"></i>Edit Produk</h3>
                    <p class="text-muted">Perbarui data produk dengan ID <strong>#<?= htmlspecialchars($product['id']) ?></strong>.</p>
                    <hr class="mb-4">

                    <!-- Global Error -->
                    <?php if (isset($errors['global'])): ?>
                        <div class="alert alert-danger border-0 shadow-sm" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $errors['global'] ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <!-- Left Column: Details -->
                            <div class="col-md-7">
                                <!-- Nama Produk -->
                                <div class="mb-3">
                                    <label for="nama" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>" 
                                           id="nama" name="nama" 
                                           value="<?= htmlspecialchars($oldInput['nama'] ?? $product['nama']) ?>" required>
                                    <?php if (isset($errors['nama'])): ?>
                                        <div class="invalid-feedback"><?= $errors['nama'] ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="row">
                                    <!-- Harga -->
                                    <div class="col-md-6 mb-3">
                                        <label for="harga" class="form-label">Harga (Rupiah) <span class="text-danger">*</span></label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control <?= isset($errors['harga']) ? 'is-invalid' : '' ?>" 
                                                   id="harga" name="harga" 
                                                   value="<?= htmlspecialchars($oldInput['harga'] ?? $product['harga']) ?>" min="0" required>
                                            <?php if (isset($errors['harga'])): ?>
                                                <div class="invalid-feedback"><?= $errors['harga'] ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Stok -->
                                    <div class="col-md-6 mb-3">
                                        <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control <?= isset($errors['stok']) ? 'is-invalid' : '' ?>" 
                                               id="stok" name="stok" 
                                               value="<?= htmlspecialchars($oldInput['stok'] ?? $product['stok']) ?>" min="0" required>
                                        <?php if (isset($errors['stok'])): ?>
                                            <div class="invalid-feedback"><?= $errors['stok'] ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi Produk</label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" 
                                              placeholder="Tulis deskripsi produk di sini..."><?= htmlspecialchars($oldInput['deskripsi'] ?? $product['deskripsi']) ?></textarea>
                                </div>
                            </div>

                            <!-- Right Column: Image Upload -->
                            <div class="col-md-5">
                                <div class="mb-4">
                                    <label class="form-label">Gambar Produk</label>
                                    
                                    <!-- Preview Container -->
                                    <div class="preview-container mb-3" id="previewContainer">
                                        <?php if (!empty($product['gambar']) && file_exists(__DIR__ . '/../../../public/uploads/' . $product['gambar'])): ?>
                                            <img id="imgPreview" src="../../../public/uploads/<?= htmlspecialchars($product['gambar']) ?>" 
                                                 class="image-preview" alt="Preview Gambar">
                                            <span id="previewPlaceholder" class="text-muted text-center p-3 d-none">
                                                <i class="bi bi-cloud-upload fs-1 d-block mb-2"></i>
                                                Belum ada gambar terpilih
                                            </span>
                                        <?php else: ?>
                                            <img id="imgPreview" class="image-preview" style="display: none;" alt="Preview Gambar">
                                            <span id="previewPlaceholder" class="text-muted text-center p-3">
                                                <i class="bi bi-cloud-upload fs-1 d-block mb-2"></i>
                                                Belum ada gambar terpilih
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Input File -->
                                    <input type="file" class="form-control <?= isset($errors['gambar']) ? 'is-invalid' : '' ?>" 
                                           id="gambar" name="gambar" accept="image/png, image/jpeg, image/jpg">
                                    <div class="form-text mt-2">Format file: JPG, JPEG, PNG. Maksimal ukuran: 2MB. Biarkan kosong jika tidak ingin mengubah gambar.</div>
                                    
                                    <?php if (isset($errors['gambar'])): ?>
                                        <div class="invalid-feedback d-block mt-2"><?= $errors['gambar'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-outline-secondary px-4 py-2 fw-semibold">Batal</a>
                            <button type="submit" class="btn btn-dark px-4 py-2 fw-semibold">
                                <i class="bi bi-save me-2"></i>Perbarui Produk
                            </button>
                        </div>
                    </form>

                </div>

            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    
    <script>
        const gambarInput = document.getElementById('gambar');
        const imgPreview = document.getElementById('imgPreview');
        const previewPlaceholder = document.getElementById('previewPlaceholder');

        gambarInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.addEventListener('load', function() {
                    imgPreview.setAttribute('src', this.result);
                    imgPreview.style.display = 'block';
                    if (previewPlaceholder) {
                        previewPlaceholder.style.display = 'none';
                    }
                });
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
