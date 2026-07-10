<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/ProductModel.php';

class CustomerController extends Controller
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    /**
     * Menampilkan halaman beranda customer
     */
    public function home()
    {
        // Ambil 4 produk terbaru untuk beranda
        $recentProducts = $this->productModel->getAllProducts('', 4, 0);

        return [
            'recentProducts' => $recentProducts
        ];
    }

    /**
     * Menampilkan halaman katalog semua produk
     */
    public function catalog()
    {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $products = $this->productModel->getAllProducts($search);
        
        return [
            'products' => $products,
            'search' => $search
        ];
    }

    /**
     * Menampilkan halaman detail produk
     */
    public function detail($id)
    {
        $product = $this->productModel->getProductById($id);
        
        if (!$product) {
            $_SESSION['error'] = 'Produk tidak ditemukan.';
            header('Location: catalog.php');
            exit;
        }

        return [
            'product' => $product
        ];
    }
}
