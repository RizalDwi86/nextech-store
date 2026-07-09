<?php

session_start();

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['id'])) {
    header("Location: ../../../index.php");
    exit;
}

if ($_SESSION['role'] != "admin") {
    header("Location: customer.php");
    exit;
}

require_once '../../core/Database.php';

$database = new Database();
$conn = $database->getConnection();

// Statistik Total Produk (try-catch kalau tabel belum ada)
try {
    $stmtProd = $conn->query("SELECT COUNT(*) FROM products");
    $totalProduk = $stmtProd ? $stmtProd->fetchColumn() : 0;
} catch (Exception $e) {
    $totalProduk = 0;
}

// Statistik Total User (customer saja)
try {
    $stmtUser = $conn->query("SELECT COUNT(*) FROM users WHERE role = 'customer'");
    $totalUser = $stmtUser ? $stmtUser->fetchColumn() : 0;
} catch (Exception $e) {
    $totalUser = 0;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Admin - NexTech Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            padding-top: 20px;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
        }

        .sidebar-brand {
            color: #fff;
            font-size: 1.2rem;
            font-weight: 700;
            padding: 15px 20px 25px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand span {
            color: #e94560;
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 12px 20px;
            border-radius: 0;
            transition: all 0.2s;
            font-size: 0.92rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background: rgba(255,255,255,0.1);
            border-left: 3px solid #e94560;
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
            width: 20px;
        }

        .sidebar-section-title {
            color: rgba(255,255,255,0.4);
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 20px 20px 8px;
            font-weight: 600;
        }

        .main-content {
            margin-left: 250px;
            padding: 0;
        }

        .topbar {
            background: #fff;
            padding: 15px 30px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .stat-card {
            border: none;
            border-radius: 16px;
            padding: 25px;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12) !important;
        }

        .stat-card .icon-wrap {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .stat-card .stat-number {
            font-size: 2rem;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 5px;
        }

        .stat-card .stat-label {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
        }

        .menu-card {
            border: none;
            border-radius: 16px;
            padding: 25px;
            text-decoration: none;
            color: inherit;
            transition: all 0.25s;
            display: block;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.13);
            color: inherit;
        }

        .menu-card .menu-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin-bottom: 15px;
        }

        .menu-card .menu-title {
            font-size: 1rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .menu-card .menu-desc {
            font-size: 0.82rem;
            color: #6c757d;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #e94560, #0f3460);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .sidebar { display: none; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="bi bi-cpu" style="color:#e94560;"></i>
            Nex<span>Tech</span> Store
        </div>

        <div class="sidebar-section-title">Main Menu</div>

        <nav class="nav flex-column">
            <a href="admin.php" class="nav-link active">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="../product/index.php" class="nav-link">
                <i class="bi bi-box-seam"></i> Kelola Produk
            </a>
        </nav>

        <div class="sidebar-section-title">Akun</div>
        <nav class="nav flex-column">
            <a href="../profile/profile.php" class="nav-link">
                <i class="bi bi-person-circle"></i> Profil Saya
            </a>
            <a href="../../../logout.php" class="nav-link text-danger">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Topbar -->
        <div class="topbar">
            <div>
                <h5 class="mb-0 fw-bold text-dark">Dashboard Admin</h5>
                <small class="text-muted">Selamat datang kembali, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</small>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-md-block">
                    <div class="fw-semibold text-dark" style="font-size:0.9rem;"><?php echo htmlspecialchars($_SESSION['nama']); ?></div>
                    <small class="text-muted">Administrator</small>
                </div>
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['nama'], 0, 1)); ?>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="container-fluid p-4">

            <!-- Statistik Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card bg-white shadow-sm">
                        <div class="icon-wrap bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                        <div class="stat-number text-dark"><?php echo number_format($totalProduk); ?></div>
                        <div class="stat-label">Total Produk</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card bg-white shadow-sm">
                        <div class="icon-wrap bg-success bg-opacity-10 text-success">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stat-number text-dark"><?php echo number_format($totalUser); ?></div>
                        <div class="stat-label">Total Customer</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card bg-white shadow-sm">
                        <div class="icon-wrap bg-warning bg-opacity-10 text-warning">
                            <i class="bi bi-cart-fill"></i>
                        </div>
                        <div class="stat-number text-dark">0</div>
                        <div class="stat-label">Total Order</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="stat-card bg-white shadow-sm">
                        <div class="icon-wrap bg-danger bg-opacity-10 text-danger">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div class="stat-number text-dark">Rp 0</div>
                        <div class="stat-label">Total Pendapatan</div>
                    </div>
                </div>
            </div>

            <!-- Menu Cepat -->
            <h6 class="fw-bold text-muted text-uppercase mb-3" style="font-size:0.78rem; letter-spacing:1.5px;">Menu Cepat</h6>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <a href="../product/index.php" class="menu-card bg-white">
                        <div class="menu-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div class="menu-title">Kelola Produk</div>
                        <div class="menu-desc">Tambah, edit, hapus, dan kelola semua produk elektronik.</div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="../product/create.php" class="menu-card bg-white">
                        <div class="menu-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-plus-circle"></i>
                        </div>
                        <div class="menu-title">Tambah Produk</div>
                        <div class="menu-desc">Langsung tambahkan produk baru ke katalog toko.</div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-4">
                    <a href="../profile/profile.php" class="menu-card bg-white">
                        <div class="menu-icon bg-info bg-opacity-10 text-info">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div class="menu-title">Profil Saya</div>
                        <div class="menu-desc">Lihat dan edit profil akun administrator.</div>
                    </a>
                </div>
            </div>

            <!-- Info Akun -->
            <div class="card border-0 shadow-sm rounded-4 mt-4">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Akun</h6>
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th style="width:160px;" class="text-muted fw-normal">Nama</th>
                            <td class="fw-semibold"><?php echo htmlspecialchars($_SESSION['nama']); ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Email</th>
                            <td class="fw-semibold"><?php echo htmlspecialchars($_SESSION['email']); ?></td>
                        </tr>
                        <tr>
                            <th class="text-muted fw-normal">Role</th>
                            <td><span class="badge bg-danger">Administrator</span></td>
                        </tr>
                    </table>
                </div>
            </div>

        </div><!-- end container -->
    </div><!-- end main-content -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>