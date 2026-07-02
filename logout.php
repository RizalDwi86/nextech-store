<?php

session_start();

// Hapus semua data session
$_SESSION = [];

session_unset();
session_destroy();

// Hapus cache browser
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

header("Location: index.php");
exit;