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

    public function home()
    {
        $recentProducts = $this->productModel->getAllProducts('', 4, 0);

        return [
            'recentProducts' => $recentProducts
        ];
    }


    public function catalog()
    {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $products = $this->productModel->getAllProducts($search);
        
        return [
            'products' => $products,
            'search' => $search
        ];
    }

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
