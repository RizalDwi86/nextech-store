<?php
session_start();

if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'customer') {
    header('Location: ../../../index.php');
    exit;
}

// Redirect ke halaman beranda customer yang baru
header('Location: ../customer/home.php');
exit;