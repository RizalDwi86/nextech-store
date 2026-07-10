<?php

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../controllers/CartController.php';

class OrderController extends Controller
{
    private $orderModel;
    private $cartController;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->cartController = new CartController();
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Menampilkan halaman checkout dan memproses form checkout
     */
    public function checkout()
    {
        $cartItems = $this->cartController->index();
        
        if (empty($cartItems)) {
            $_SESSION['error'] = 'Keranjang belanja kosong. Silakan belanja terlebih dahulu.';
            header('Location: ../customer/catalog.php');
            exit;
        }

        $total = $this->cartController->getTotal();
        $errors = [];

        // Proses form checkout jika method POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $namaPenerima = trim($_POST['nama_penerima']);
            $alamat = trim($_POST['alamat']);
            $noHp = trim($_POST['no_hp']);

            // Validasi
            if (empty($namaPenerima)) $errors[] = 'Nama penerima harus diisi.';
            if (empty($alamat)) $errors[] = 'Alamat pengiriman harus diisi.';
            if (empty($noHp)) $errors[] = 'Nomor HP harus diisi.';

            if (empty($errors)) {
                $userId = $_SESSION['id'];
                
                // Simpan ke database
                $success = $this->orderModel->createOrder($userId, $namaPenerima, $alamat, $noHp, $total, $cartItems);

                if ($success) {
                    // Kosongkan keranjang
                    $this->cartController->clear();
                    
                    $_SESSION['success'] = 'Pesanan berhasil dibuat! Terima kasih telah berbelanja.';
                    header('Location: history.php');
                    exit;
                } else {
                    $errors[] = 'Terjadi kesalahan sistem saat memproses pesanan Anda.';
                }
            }
        }

        return [
            'cartItems' => $cartItems,
            'total' => $total,
            'errors' => $errors
        ];
    }

    /**
     * Menampilkan riwayat pesanan
     */
    public function history()
    {
        $userId = $_SESSION['id'];
        $orders = $this->orderModel->getOrdersByUser($userId);
        
        return [
            'orders' => $orders
        ];
    }

    /**
     * Mengambil detail pesanan spesifik (bisa dipanggil via AJAX atau halaman detail)
     */
    public function detail($orderId)
    {
        return $this->orderModel->getOrderDetails($orderId);
    }
}
