<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../controllers/CartController.php';
    $cartController = new CartController();
    
    $productId = $_POST['product_id'] ?? null;
    $qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
    
    if ($productId) {
        $cartController->update($productId, $qty);
    }
}

header('Location: index.php');
exit;
