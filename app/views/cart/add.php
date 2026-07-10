<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../controllers/CartController.php';
    $cartController = new CartController();
    
    $productId = $_POST['product_id'] ?? null;
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
    
    if ($productId) {
        $cartController->add($productId, $qty);
    }
}

// Kembali ke halaman sebelumnya
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
