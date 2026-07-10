<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$currentUrl = $_SERVER['REQUEST_URI'] ?? '';

// Tentukan path relatif berdasarkan URL
$role = $_SESSION['role'] ?? 'customer';

if (strpos($currentUrl, '/views/product') !== false) {
    $dashLink    = '../dashboard/' . $role . '.php';
    $profileLink = '../profile/profile.php';
    $logoutLink  = '../../../logout.php';
    $produkLink  = 'index.php';
} elseif (strpos($currentUrl, '/views/profile') !== false) {
    $dashLink    = '../dashboard/' . $role . '.php';
    $profileLink = 'profile.php';
    $logoutLink  = '../../../logout.php';
    $produkLink  = '../product/index.php';
} elseif (strpos($currentUrl, '/views/dashboard') !== false) {
    $dashLink    = $role === 'admin' ? 'admin.php' : 'customer.php';
    $profileLink = '../profile/profile.php';
    $logoutLink  = '../../../logout.php';
    $produkLink  = '../product/index.php';
} elseif (strpos($currentUrl, '/views/customer') !== false) {
    $dashLink    = '../dashboard/' . $role . '.php';
    $profileLink = '../profile/profile.php';
    $logoutLink  = '../../../logout.php';
    $produkLink  = '../product/index.php';
} elseif (strpos($currentUrl, '/views/cart') !== false) {
    $dashLink    = '../dashboard/' . $role . '.php';
    $profileLink = '../profile/profile.php';
    $logoutLink  = '../../../logout.php';
    $produkLink  = '../product/index.php';
} elseif (strpos($currentUrl, '/views/order') !== false) {
    $dashLink    = '../dashboard/' . $role . '.php';
    $profileLink = '../profile/profile.php';
    $logoutLink  = '../../../logout.php';
    $produkLink  = '../product/index.php';
} else {
    // Fallback (dari root)
    $dashLink    = 'app/views/dashboard/' . $role . '.php';
    $profileLink = 'app/views/profile/profile.php';
    $logoutLink  = 'logout.php';
    $produkLink  = 'app/views/product/index.php';
}
?>

<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(90deg, #1a1a2e, #0f3460);">
    <div class="container">

        <a class="navbar-brand fw-bold" href="<?php echo $dashLink; ?>">
            <i class="bi bi-cpu me-2" style="color:#e94560;"></i>NexTech Store
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMenu">

            <ul class="navbar-nav me-auto">
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'customer'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($currentUrl, '/customer/home.php') !== false ? 'active' : '' ?>" href="/nextech-store/app/views/customer/home.php"><i class="bi bi-house-door me-1"></i>Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($currentUrl, '/customer/catalog.php') !== false ? 'active' : '' ?>" href="/nextech-store/app/views/customer/catalog.php"><i class="bi bi-bag me-1"></i>Katalog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($currentUrl, '/cart/index.php') !== false ? 'active' : '' ?>" href="/nextech-store/app/views/cart/index.php">
                            <i class="bi bi-cart3 me-1"></i>Keranjang 
                            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                                <span class="badge bg-danger rounded-pill"><?= count($_SESSION['cart']) ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($currentUrl, '/order/history.php') !== false ? 'active' : '' ?>" href="/nextech-store/app/views/order/history.php"><i class="bi bi-clock-history me-1"></i>Riwayat Pesanan</a>
                    </li>
                <?php endif; ?>
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo strpos($currentUrl, '/views/product') !== false ? 'active' : ''; ?>"
                           href="<?php echo $produkLink; ?>">
                            <i class="bi bi-box-seam me-1"></i>Kelola Produk
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center gap-1">
                <li class="nav-item">
                    <span class="navbar-text me-2 text-white-50" style="font-size:0.85rem;">
                        <i class="bi bi-person-fill me-1"></i><?php echo htmlspecialchars($_SESSION['nama'] ?? ''); ?>
                        <?php if (isset($_SESSION['role'])): ?>
                            <span class="badge ms-1 <?php echo $_SESSION['role'] === 'admin' ? 'bg-danger' : 'bg-success'; ?>" style="font-size:0.65rem;">
                                <?php echo ucfirst($_SESSION['role']); ?>
                            </span>
                        <?php endif; ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $dashLink; ?>">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $profileLink; ?>">
                        <i class="bi bi-person-circle me-1"></i>Profil
                    </a>
                </li>
                <li class="nav-item ms-2">
                    <a class="btn btn-sm btn-outline-light px-3" href="<?php echo $logoutLink; ?>">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </a>
                </li>
            </ul>

        </div>
    </div>
</nav>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
