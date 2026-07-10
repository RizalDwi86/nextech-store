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
} elseif (strpos($currentUrl, '/views/admin') !== false) {
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
                    <button class="btn btn-sm btn-outline-light px-2" id="darkModeToggle" title="Toggle Dark Mode">
                        <i class="bi bi-moon-stars"></i>
                    </button>
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

<!-- Dark Mode Logic -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('darkModeToggle');
        const currentTheme = localStorage.getItem('theme') || 'light';
        
        if (currentTheme === 'dark') {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            toggleBtn.innerHTML = '<i class="bi bi-sun-fill text-warning"></i>';
        }

        toggleBtn.addEventListener('click', () => {
            const theme = document.documentElement.getAttribute('data-bs-theme');
            if (theme === 'dark') {
                document.documentElement.setAttribute('data-bs-theme', 'light');
                localStorage.setItem('theme', 'light');
                toggleBtn.innerHTML = '<i class="bi bi-moon-stars"></i>';
            } else {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                toggleBtn.innerHTML = '<i class="bi bi-sun-fill text-warning"></i>';
            }
        });
    });
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Generic Image Modal -->
<div class="modal fade" id="globalImageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-transparent border-0 shadow-none">
      <div class="modal-header border-0 pb-0">
        <button type="button" class="btn-close btn-close-white ms-auto bg-light p-2 rounded-circle" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center pt-0">
        <img src="" id="globalImageModalSrc" class="img-fluid rounded shadow-lg" alt="Full Image" style="max-height: 85vh; object-fit: contain;">
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Cari semua gambar yang memiliki class 'zoomable-img'
    const zoomableImages = document.querySelectorAll('.zoomable-img');
    zoomableImages.forEach(img => {
        img.style.cursor = 'zoom-in';
        img.title = 'Klik untuk memperbesar gambar';
        img.addEventListener('click', function(e) {
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
} elseif (strpos($currentUrl, '/views/admin') !== false) {
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
                    <button class="btn btn-sm btn-outline-light px-2" id="darkModeToggle" title="Toggle Dark Mode">
                        <i class="bi bi-moon-stars"></i>
                    </button>
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

<!-- Dark Mode Logic -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('darkModeToggle');
        const currentTheme = localStorage.getItem('theme') || 'light';
        
        if (currentTheme === 'dark') {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
            toggleBtn.innerHTML = '<i class="bi bi-sun-fill text-warning"></i>';
        }

        toggleBtn.addEventListener('click', () => {
            const theme = document.documentElement.getAttribute('data-bs-theme');
            if (theme === 'dark') {
                document.documentElement.setAttribute('data-bs-theme', 'light');
                localStorage.setItem('theme', 'light');
                toggleBtn.innerHTML = '<i class="bi bi-moon-stars"></i>';
            } else {
                document.documentElement.setAttribute('data-bs-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                toggleBtn.innerHTML = '<i class="bi bi-sun-fill text-warning"></i>';
            }
        });
    });
</script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Generic Image Modal -->
<div class="modal fade" id="globalImageModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content bg-transparent border-0 shadow-none">
      <div class="modal-header border-0 pb-0">
        <button type="button" class="btn-close btn-close-white ms-auto bg-light p-2 rounded-circle" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center pt-0">
        <img src="" id="globalImageModalSrc" class="img-fluid rounded shadow-lg" alt="Full Image" style="max-height: 85vh; object-fit: contain;">
      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Cari semua gambar yang memiliki class 'zoomable-img'
    const zoomableImages = document.querySelectorAll('.zoomable-img');
    zoomableImages.forEach(img => {
        img.style.cursor = 'zoom-in';
        img.title = 'Klik untuk memperbesar gambar';
        img.addEventListener('click', function(e) {
            e.preventDefault();
            const modalImg = document.getElementById('globalImageModalSrc');
            modalImg.src = this.src; // Atau this.getAttribute('data-full-src') jika ada
            const imageModal = new bootstrap.Modal(document.getElementById('globalImageModal'));
            imageModal.show();
        });
    });
});
</script>

<!-- Global Dark Mode Overrides -->
<style>
[data-bs-theme="dark"] body {
    background-color: #121212 !important;
    color: #e0e0e0 !important;
}
[data-bs-theme="dark"] .bg-white,
[data-bs-theme="dark"] .bg-light,
[data-bs-theme="dark"] .card {
    background-color: #1e1e1e !important;
    border-color: #333 !important;
}
[data-bs-theme="dark"] .text-dark {
    color: #e0e0e0 !important;
}
[data-bs-theme="dark"] .text-muted {
    color: #a0a0a0 !important;
}
[data-bs-theme="dark"] .form-control,
[data-bs-theme="dark"] .form-select,
[data-bs-theme="dark"] .input-group-text,
[data-bs-theme="dark"] .pagination .page-link {
    background-color: #2a2a2a !important;
    color: #e0e0e0 !important;
    border-color: #444 !important;
}
[data-bs-theme="dark"] .form-control:focus,
[data-bs-theme="dark"] .form-select:focus,
[data-bs-theme="dark"] .pagination .page-link:focus,
[data-bs-theme="dark"] .pagination .page-link:hover {
    background-color: #333 !important;
    color: #e0e0e0 !important;
    border-color: #e94560 !important;
}
[data-bs-theme="dark"] .table {
    color: #e0e0e0 !important;
}
[data-bs-theme="dark"] .table-light th,
[data-bs-theme="dark"] .table-light td {
    background-color: #2a2a2a !important;
    color: #e0e0e0 !important;
    border-color: #444 !important;
}
[data-bs-theme="dark"] .table-bordered > :not(caption) > * > * {
    border-color: #444 !important;
}
[data-bs-theme="dark"] .border-end,
[data-bs-theme="dark"] .border-bottom,
[data-bs-theme="dark"] .border {
    border-color: #444 !important;
}
[data-bs-theme="dark"] .modal-content {
    background-color: #1e1e1e !important;
}
[data-bs-theme="dark"] .menu-card:hover,
[data-bs-theme="dark"] .stat-card:hover {
    box-shadow: 0 12px 30px rgba(0,0,0,0.5) !important;
}
[data-bs-theme="dark"] .nav-tabs .nav-link.active {
    background-color: #1e1e1e !important;
    color: #e0e0e0 !important;
    border-color: #444 #444 #1e1e1e !important;
}
[data-bs-theme="dark"] .nav-tabs {
    border-bottom-color: #444 !important;
}
</style>
