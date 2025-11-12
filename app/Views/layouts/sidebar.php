<?php
// Get current URL to set active menu
$current_url = $_SERVER['REQUEST_URI'];
$current_page = basename(parse_url($current_url, PHP_URL_PATH));

// Helper function to check if menu is active
function isActive($url, $current)
{
    return strpos($current, $url) !== false ? 'active' : '';
}

// Helper function to check if parent menu should be expanded
function isExpanded($urls, $current)
{
    foreach ($urls as $url) {
        if (strpos($current, $url) !== false) {
            return 'show';
        }
    }
    return '';
}
?>

<aside class="sidebar" id="sidebar">

    <!-- Sidebar Header with Logo -->
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="<?= asset_url('images/lab-ai-logo.png') ?>" alt="Lab AI Logo" class="logo-img">
        </div>
        <!-- <button class="btn-toggle-sidebar" id="toggleSidebar">
            <i data-feather="chevron-left"></i>
        </button> -->
    </div>

    <!-- Sidebar Navigation -->
    <nav class="sidebar-nav">
        <ul class="nav flex-column">

            <!-- Dashboard -->
            <li class="nav-item">
                <a href="<?= base_url('dashboard') ?>" class="nav-link <?= isActive('dashboard', $current_url) ?>">
                    <i data-feather="home"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <li class="nav-divider">
                <span>Manajemen Data</span>
            </li>

            <!-- Manajemen Anggota (with collapse) -->
            <li class="nav-item">
                <a
                    class="nav-link" data-bs-toggle="collapse" href="#kontenAnggotaCollapse" role="button" aria-expanded="false"
                    aria-controls="collapseExample">
                    <span>
                        <i data-feather="users"></i>
                        <span class="nav-text">Manajemen Anggota</span>
                    </span>

                    <i data-feather="chevron-down" class="nav-arrow"></i>
                </a>

                <div class="collapse" id="kontenAnggotaCollapse">
                    <ul class="nav flex-column submenu">
                        <li class="nav-item">
                            <a href="<?= base_url('dosen') ?>"
                                class="nav-link">
                                <span class="nav-text">Data Dosen</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php?url=anggota/mahasiswa') ?>"
                                class="nav-link">
                                <span class="nav-text">Data Mahasiswa</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Manajemen Konten Lab (with collapse) -->
            <li class="nav-item">
                <a
                    class="btn nav-link" data-bs-toggle="collapse" href="#kontenLabCollapse" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <span>
                        <i data-feather="grid"></i>
                        <span class="nav-text">Konten Laboratorium</span>
                    </span>

                    <i data-feather="chevron-down" class="nav-arrow"></i>
                </a>

                <div class="collapse" id="kontenLabCollapse">
                    <ul class="nav flex-column submenu">
                        <li class="nav-item">
                            <a href="<?= base_url('fasilitas') ?>"
                                class="nav-link">
                                <span class="nav-text">Kelola Fasilitas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('produk') ?>"
                                class="nav-link">
                                <span class="nav-text">Kelola Produk</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('mitra') ?>"
                                class="nav-link">
                                <span class="nav-text">Kelola Mitra Kerjasama</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <!-- Manajemen Aktivitas (with collapse) -->
            <li class="nav-item">
                <a
                    class="btn nav-link" data-bs-toggle="collapse" href="#activityCollapse" role="button" aria-expanded="false" aria-controls="collapseExample">
                    <span>
                        <i data-feather="activity"></i>
                        <span class="nav-text">Manajemen Aktivitas</span>
                    </span>

                    <i data-feather="chevron-down" class="nav-arrow"></i>
                </a>

                <div class="collapse" id="activityCollapse">
                    <ul class="nav flex-column submenu">
                        <li class="nav-item">
                            <a href="<?= base_url('index.php?url=aktivitas/publikasi') ?>"
                                class="nav-link">
                                <span class="nav-text">Publikasi Akademik</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php?url=aktivitas/penelitian') ?>"
                                class="nav-link">
                                <span class="nav-text">Penelitian</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php?url=aktivitas/pengabdian') ?>"
                                class="nav-link">

                                <span class="nav-text">Pengabdian</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('index.php?url=aktivitas/kekayaan') ?>"
                                class="nav-link">

                                <span class="nav-text">Kekayaan Intelektual</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>


            <!-- Divider -->
            <li class="nav-divider">
                <span>Pengaturan</span>
            </li>

            <!-- Logout -->
            <li class="nav-item">
                <a href="<?= base_url('logout') ?>" class="nav-link text-danger">
                    <i data-feather="log-out"></i>
                    <span class="nav-text">Keluar</span>
                </a>
            </li>

        </ul>
    </nav>
</aside>