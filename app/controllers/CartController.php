<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/ProductModel.php';

class CartController extends Controller
{
    private $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

 
    public function index()
    {
        return $_SESSION['cart'];
    }

 
    public function add($productId, $qty)
    {
        $product = $this->productModel->getProductById($productId);
        
        if ($product && $product['stok'] >= $qty) {
            // Cek jika produk sudah ada di keranjang
            if (isset($_SESSION['cart'][$productId])) {
                // Tambah qty jika stok mencukupi
                $newQty = $_SESSION['cart'][$productId]['qty'] + $qty;
                if ($newQty <= $product['stok']) {
                    $_SESSION['cart'][$productId]['qty'] = $newQty;
                    $_SESSION['success'] = 'Jumlah produk di keranjang diperbarui.';
                } else {
                    $_SESSION['error'] = 'Stok tidak mencukupi untuk jumlah tersebut.';
                }
            } else {
                // Tambah produk baru ke keranjang
                $_SESSION['cart'][$productId] = [
                    'product' => $product,
                    'qty' => $qty
                ];
                $_SESSION['success'] = 'Produk berhasil ditambahkan ke keranjang.';
            }
        } else {
            $_SESSION['error'] = 'Produk tidak ditemukan atau stok habis.';
        }
    }


    public function update($productId, $qty)
    {
        if (isset($_SESSION['cart'][$productId])) {
            $product = $this->productModel->getProductById($productId);
            
            if ($qty > 0 && $qty <= $product['stok']) {
                $_SESSION['cart'][$productId]['qty'] = $qty;
                $_SESSION['success'] = 'Keranjang diperbarui.';
            } elseif ($qty <= 0) {
                $this->remove($productId);
            } else {
                $_SESSION['error'] = 'Maksimal pembelian adalah ' . $product['stok'] . ' stok.';
            }
        }
    }


    public function remove($productId)
    {
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            $_SESSION['success'] = 'Produk dihapus dari keranjang.';
        }
    }


    public function clear()
    {
        $_SESSION['cart'] = [];
    }


    public function getTotal()
    {
        $total = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total += $item['product']['harga'] * $item['qty'];
        }
        return $total;
    }
}
