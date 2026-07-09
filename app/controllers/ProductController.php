<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/ProductModel.php';

class ProductController extends Controller
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    /**
     * Menampilkan daftar produk dengan fitur pencarian dan pagination.
     */
    public function index()
    {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5; // Jumlah produk per halaman
        $offset = ($page - 1) * $limit;

        $products = $this->productModel->getAllProducts($search, $limit, $offset);
        $totalProducts = $this->productModel->countProducts($search);
        $totalPages = ceil($totalProducts / $limit);

        return [
            'products' => $products,
            'search' => $search,
            'page' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts
        ];
    }

    /**
     * Menangani proses penambahan produk baru.
     */
    public function create()
    {
        $errors = [];
        $oldInput = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama']);
            $kategori = trim($_POST['kategori'] ?? '');
            $harga = trim($_POST['harga']);
            $stok = trim($_POST['stok']);
            $deskripsi = trim($_POST['deskripsi']);

            $oldInput = compact('nama', 'kategori', 'harga', 'stok', 'deskripsi');

            // 1. Validasi Input
            if (empty($nama)) {
                $errors['nama'] = 'Nama produk tidak boleh kosong.';
            }

            if ($harga === '' || !is_numeric($harga) || $harga < 0) {
                $errors['harga'] = 'Harga harus berupa angka dan tidak boleh negatif.';
            }

            if ($stok === '' || !is_numeric($stok) || $stok < 0) {
                $errors['stok'] = 'Stok harus berupa angka dan tidak boleh negatif.';
            }

            // 2. Validasi & Upload Gambar
            $gambarName = null;
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES['gambar'];
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                $maxSize = 2 * 1024 * 1024; // 2MB

                if (!in_array($file['type'], $allowedTypes)) {
                    $errors['gambar'] = 'Format file harus JPG, JPEG, atau PNG.';
                } elseif ($file['size'] > $maxSize) {
                    $errors['gambar'] = 'Ukuran file maksimal adalah 2MB.';
                } else {
                    // Generate nama file unik
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $gambarName = uniqid('prod_', true) . '.' . $ext;
                    
                    // Pastikan folder upload ada
                    $uploadDir = __DIR__ . '/../../public/uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    if (!move_uploaded_file($file['tmp_name'], $uploadDir . $gambarName)) {
                        $errors['gambar'] = 'Gagal mengunggah gambar produk.';
                    }
                }
            }

            // 3. Simpan ke database jika tidak ada error
            if (empty($errors)) {
                $data = [
                    'nama'     => $nama,
                    'kategori' => $kategori ?: null,
                    'harga'    => (int)$harga,
                    'stok'     => (int)$stok,
                    'deskripsi'=> $deskripsi,
                    'gambar'   => $gambarName
                ];

                if ($this->productModel->createProduct($data)) {
                    $_SESSION['success'] = 'Produk berhasil ditambahkan!';
                    header('Location: index.php');
                    exit;
                } else {
                    $errors['global'] = 'Gagal menyimpan produk ke database.';
                }
            }
        }

        return [
            'errors' => $errors,
            'oldInput' => $oldInput
        ];
    }

    /**
     * Menangani proses pengeditan produk.
     */
    public function edit($id)
    {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            $_SESSION['error'] = 'Produk tidak ditemukan.';
            header('Location: index.php');
            exit;
        }

        $errors = [];
        $oldInput = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = trim($_POST['nama']);
            $kategori = trim($_POST['kategori'] ?? '');
            $harga = trim($_POST['harga']);
            $stok = trim($_POST['stok']);
            $deskripsi = trim($_POST['deskripsi']);

            $oldInput = compact('nama', 'kategori', 'harga', 'stok', 'deskripsi');

            // 1. Validasi Input
            if (empty($nama)) {
                $errors['nama'] = 'Nama produk tidak boleh kosong.';
            }

            if ($harga === '' || !is_numeric($harga) || $harga < 0) {
                $errors['harga'] = 'Harga harus berupa angka dan tidak boleh negatif.';
            }

            if ($stok === '' || !is_numeric($stok) || $stok < 0) {
                $errors['stok'] = 'Stok harus berupa angka dan tidak boleh negatif.';
            }

            // 2. Validasi & Upload Gambar Baru (jika ada)
            $gambarName = $product['gambar']; // Bawaan gambar lama
            if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
                $file = $_FILES['gambar'];
                $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                $maxSize = 2 * 1024 * 1024; // 2MB

                if (!in_array($file['type'], $allowedTypes)) {
                    $errors['gambar'] = 'Format file harus JPG, JPEG, atau PNG.';
                } elseif ($file['size'] > $maxSize) {
                    $errors['gambar'] = 'Ukuran file maksimal adalah 2MB.';
                } else {
                    // Generate nama file unik
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $newGambarName = uniqid('prod_', true) . '.' . $ext;
                    
                    $uploadDir = __DIR__ . '/../../public/uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    if (move_uploaded_file($file['tmp_name'], $uploadDir . $newGambarName)) {
                        // Hapus gambar lama jika ada
                        if ($gambarName && file_exists($uploadDir . $gambarName)) {
                            unlink($uploadDir . $gambarName);
                        }
                        $gambarName = $newGambarName;
                    } else {
                        $errors['gambar'] = 'Gagal mengunggah gambar baru.';
                    }
                }
            }

            // 3. Simpan Perubahan jika tidak ada error
            if (empty($errors)) {
                $data = [
                    'nama'     => $nama,
                    'kategori' => $kategori ?: null,
                    'harga'    => (int)$harga,
                    'stok'     => (int)$stok,
                    'deskripsi'=> $deskripsi,
                    'gambar'   => $gambarName
                ];

                if ($this->productModel->updateProduct($id, $data)) {
                    $_SESSION['success'] = 'Produk berhasil diperbarui!';
                    header('Location: index.php');
                    exit;
                } else {
                    $errors['global'] = 'Gagal memperbarui produk di database.';
                }
            }
        }

        return [
            'product' => $product,
            'errors' => $errors,
            'oldInput' => $oldInput
        ];
    }

    /**
     * Menghapus produk beserta gambar fisiknya.
     */
    public function delete($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            // Hapus gambar fisik dari server
            if ($product['gambar']) {
                $filePath = __DIR__ . '/../../public/uploads/' . $product['gambar'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            if ($this->productModel->deleteProduct($id)) {
                $_SESSION['success'] = 'Produk berhasil dihapus!';
            } else {
                $_SESSION['error'] = 'Gagal menghapus produk dari database.';
            }
        } else {
            $_SESSION['error'] = 'Produk tidak ditemukan.';
        }

        header('Location: index.php');
        exit;
    }
}
