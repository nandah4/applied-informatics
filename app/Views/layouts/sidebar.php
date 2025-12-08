<?php
// Get current URL to set active menu
$current_url = $_SERVER['REQUEST_URI'];
$current_page = basename(parse_url($current_url, PHP_URL_PATH));

// Helper function to check if menu is active
function isActive($url, $current)
{
    return strpos($current, $url) !== false ? 'active' : '';
}

// Helper function to check if any submenu item is active
function hasActiveChild($urls, $current)
{
    foreach ($urls as $url) {
        if (strpos($current, $url) !== false) {
            return 'active';
        }
    }
    return '';
}
?>

<!-- Mobile Navbar (visible on mobile/tablet only) -->
<nav class="mobile-navbar" id="mobileNavbar">
    <div class="mobile-navbar-content">
        <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
            <i data-feather="menu"></i>
        </button>
        <div class="mobile-navbar-logo">
            <img src="<?= asset_url('images/lab-ai-logo.png') ?>" alt="Lab AI Logo" class="mobile-logo-img">
        </div>
        <div class="mobile-navbar-actions">
            <!-- Optional: Add user profile or notifications here -->
        </div>
    </div>
</nav>

<!-- Overlay for mobile sidebar -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar (Desktop: always visible, Mobile: toggle) -->
<aside class="sidebar" id="sidebar">

    <!-- Sidebar Header with Logo -->
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="<?= asset_url('images/lab-ai-logo.png') ?>" alt="Lab AI Logo" class="logo-img">
        </div>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="sidebar-nav">
        <ul class="nav flex-column">

            <!-- Dashboard -->
            <li class="nav-item">
                <a href="<?= base_url('admin/dashboard') ?>" class="nav-link parent-dashboard <?= isActive('admin/dashboard', $current_url) ?>">
                    <i data-feather="home"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="nav-divider">
                <span>Manajemen Data</span>
            </li>

            <!-- Manajemen Anggota -->
            <li class="nav-item">
                <a class="nav-link parent-menu <?= hasActiveChild(['admin/dosen'], $current_url) ?>">
                    <i data-feather="users"></i>
                    <span class="nav-text">Manajemen Anggota</span>
                </a>
                <ul class="nav flex-column submenu">
                    <li class="nav-item">
                        <a href="<?= base_url('admin/dosen') ?>" class="nav-link <?= isActive('admin/dosen', $current_url) ?>">
                            <span class="nav-text">Data Dosen</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/asisten-lab') ?>" class="nav-link <?= isActive('admin/asisten-lab', $current_url) ?>">
                            <span class="nav-text">Data Asisten Lab</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="<?= base_url('admin/daftar-pendaftar') ?>" class="nav-link <?= isActive('admin/daftar-pendaftar', $current_url) ?>">
                            <span class="nav-text">Data Pendaftar</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Konten Laboratorium -->
            <li class="nav-item">
                <a class="nav-link parent-menu <?= hasActiveChild(['admin/fasilitas', 'admin/produk', 'admin/mitra'], $current_url) ?>">
                    <i data-feather="grid"></i>
                    <span class="nav-text">Konten Laboratorium</span>
                </a>
                <ul class="nav flex-column submenu">
                    <li class="nav-item">
                        <a href="<?= base_url('admin/fasilitas') ?>" class="nav-link <?= isActive('admin/fasilitas', $current_url) ?>">
                            <span class="nav-text">Kelola Fasilitas</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/produk') ?>" class="nav-link <?= isActive('produk', $current_url) ?>">
                            <span class="nav-text">Kelola Produk</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/mitra') ?>" class="nav-link <?= isActive('admin/mitra', $current_url) ?>">
                            <span class="nav-text">Kelola Mitra Kerjasama</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Manajemen Aktivitas -->
            <li class="nav-item">
                <a class="nav-link parent-menu <?= hasActiveChild(['admin/aktivitas-lab', 'admin/publikasi-akademik', 'admin/recruitment'], $current_url) ?>">
                    <i data-feather="activity"></i>
                    <span class="nav-text">Manajemen Aktivitas</span>
                </a>
                <ul class="nav flex-column submenu">
                    <li class="nav-item">
                        <a href="<?= base_url('admin/publikasi-akademik') ?>" class="nav-link <?= isActive('admin/publikasi-akademik', $current_url) ?>">
                            <span class="nav-text">Kelola Publikasi Akademik</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/aktivitas-lab') ?>" class="nav-link <?= isActive('admin/aktivitas-lab', $current_url) ?>">
                            <span class="nav-text">Kelola Aktivitas Lab</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('admin/recruitment') ?>" class="nav-link <?= isActive('admin/recruitment', $current_url) ?>">
                            <span class="nav-text">Kelola Informasi Rekrutment</span>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a href="<?= base_url('index.php?url=aktivitas/publikasi') ?>" class="nav-link <?= isActive('publikasi', $current_url) ?>">
                            <span class="nav-text">Publikasi Akademik</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('index.php?url=aktivitas/penelitian') ?>" class="nav-link <?= isActive('penelitian', $current_url) ?>">
                            <span class="nav-text">Penelitian</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('index.php?url=aktivitas/pengabdian') ?>" class="nav-link <?= isActive('pengabdian', $current_url) ?>">
                            <span class="nav-text">Pengabdian</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('index.php?url=aktivitas/kekayaan') ?>" class="nav-link <?= isActive('kekayaan', $current_url) ?>">
                            <span class="nav-text">Kekayaan Intelektual</span>
                        </a>
                    </li> -->
                </ul>
            </li>

            <!-- Logout -->
            <li class="nav-item">
                <a href="<?= base_url('admin/logout') ?>" class="nav-link text-danger">
                    <i data-feather="log-out"></i>
                    <span class="nav-text">Keluar</span>
                </a>
            </li>
            <br><br>

        </ul>
    </nav>
</aside>