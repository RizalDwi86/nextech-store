<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../../controllers/CartController.php';
    $cartController = new CartController();
    
    $productId = $_POST['product_id'] ?? null;
    
    if ($productId) {
        $cartController->remove($productId);
    }
}

header('Location: index.php');
exit;
