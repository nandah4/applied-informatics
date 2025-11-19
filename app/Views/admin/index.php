<?php
// Get user data from session
$userFullname = $_SESSION['user_fullname'] ?? 'User';
$userEmail = $_SESSION['user_email'] ?? '';

// Sample static data untuk statistik dashboard
$stats = [
    'total_dosen' => 12,
    'total_publikasi' => 48,
    'total_mitra_aktif' => 8,
    'total_produk' => 15,
    'total_fasilitas' => 6,
    'total_keahlian' => 18,
    'total_jabatan' => 4,
    'total_aktivitas' => 24,
];

// Sample data publikasi berdasarkan tipe
$publikasiByTipe = [
    'Riset' => 25,
    'Kekayaan Intelektual' => 12,
    'PPM' => 11,
];

// Sample data mitra berdasarkan kategori
$mitraByKategori = [
    'Industri' => 3,
    'Internasional' => 2,
    'Institusi Pemerintah' => 1,
    'Institusi Pendidikan' => 1,
    'Komunitas' => 1,
];

// Sample recent publikasi
$recentPublikasi = [
    ['judul' => 'Implementasi Machine Learning untuk Prediksi Cuaca', 'dosen' => 'Dr. Ahmad Rizki, M.Kom', 'tipe' => 'Riset', 'tahun' => 2024],
    ['judul' => 'Sistem Monitoring IoT untuk Smart Campus', 'dosen' => 'Dr. Siti Nurhaliza, M.T', 'tipe' => 'Riset', 'tahun' => 2024],
    ['judul' => 'Aplikasi Mobile Pembelajaran Adaptif', 'dosen' => 'Budi Santoso, S.Kom., M.Kom', 'tipe' => 'Kekayaan Intelektual', 'tahun' => 2024],
    ['judul' => 'Pelatihan Digital Marketing untuk UMKM', 'dosen' => 'Dr. Dewi Lestari, M.M', 'tipe' => 'PPM', 'tahun' => 2024],
    ['judul' => 'Analisis Big Data untuk E-Commerce', 'dosen' => 'Prof. Dr. Hendra Wijaya', 'tipe' => 'Riset', 'tahun' => 2023],
];

// Sample recent aktivitas
$recentAktivitas = [
    ['judul' => 'Workshop Python untuk Data Science', 'tanggal' => '2024-11-15'],
    ['judul' => 'Seminar Nasional AI dan Machine Learning', 'tanggal' => '2024-11-10'],
    ['judul' => 'Hackathon Mahasiswa Informatika', 'tanggal' => '2024-11-05'],
    ['judul' => 'Webinar Cloud Computing untuk Pemula', 'tanggal' => '2024-10-28'],
    ['judul' => 'Pelatihan IoT dan Arduino', 'tanggal' => '2024-10-20'],
];

// Sample rekrutmen aktif
$rekrutmenAktif = [
    ['judul' => 'Asisten Laboratorium Pemrograman', 'tanggal_tutup' => '2024-12-01', 'lokasi' => 'Lab Applied Informatics'],
    ['judul' => 'Asisten Riset Machine Learning', 'tanggal_tutup' => '2024-11-30', 'lokasi' => 'Lab Applied Informatics'],
];

// Data untuk chart aktivitas per bulan (6 bulan terakhir)
$aktivitasPerBulan = [
    'labels' => ['Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov'],
    'data' => [3, 5, 4, 6, 4, 2]
];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Dashboard Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/dashboard.css') ?>">
</head>

<body>
    <!-- Dashboard Layout -->
    <div class="dashboard-layout">

        <!-- Sidebar -->
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Topbar -->
            <!-- <div class="topbar">
                <div class="topbar-left">
                    <button class="btn-mobile-menu" id="mobileMenuBtn">
                        <i data-feather="menu"></i>
                    </button>
                    <div>
                        <h6 class="mb-0 fw-bold">Dashboard</h6>
                        <small class="text-muted">Laboratorium Applied Informatics</small>
                    </div>
                </div>
                <div class="topbar-right">
                    <div class="user-info-topbar">
                        <span class="fw-medium"><?= htmlspecialchars($userFullname) ?></span>
                        <small class="text-muted d-block"><?= htmlspecialchars($userEmail) ?></small>
                    </div>
                </div>
            </div> -->

            <!-- Dashboard Content -->
            <div class="dashboard-content">

                <!-- Welcome Section -->
                <div class="welcome-section">
                    <div class="welcome-text">
                        <h4 class="welcome-title">Selamat Datang, <?= htmlspecialchars(explode(' ', $userFullname)[0]) ?>!</h4>
                        <p class="welcome-subtitle">Berikut adalah ringkasan statistik Laboratorium Applied Informatics</p>
                    </div>
                    <div class="welcome-date">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        <span><?= date('d F Y') ?></span>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    <!-- Total Dosen -->
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-primary">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <p class="stat-label">Total Dosen</p>
                            <h3 class="stat-value"><?= $stats['total_dosen'] ?></h3>
                            <span class="stat-change stat-positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
                                </svg>
                                +2 dari bulan lalu
                            </span>
                        </div>
                    </div>

                    <!-- Total Publikasi -->
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-success">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <p class="stat-label">Total Publikasi</p>
                            <h3 class="stat-value"><?= $stats['total_publikasi'] ?></h3>
                            <span class="stat-change stat-positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
                                </svg>
                                +8 dari bulan lalu
                            </span>
                        </div>
                    </div>

                    <!-- Total Mitra Aktif -->
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-warning">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <polyline points="17 11 19 13 23 9"></polyline>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <p class="stat-label">Mitra Aktif</p>
                            <h3 class="stat-value"><?= $stats['total_mitra_aktif'] ?></h3>
                            <span class="stat-change stat-neutral">
                                Dari <?= array_sum($mitraByKategori) ?> total mitra
                            </span>
                        </div>
                    </div>

                    <!-- Total Produk -->
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-info">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <p class="stat-label">Total Produk</p>
                            <h3 class="stat-value"><?= $stats['total_produk'] ?></h3>
                            <span class="stat-change stat-positive">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline>
                                    <polyline points="17 6 23 6 23 12"></polyline>
                                </svg>
                                +3 dari bulan lalu
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Charts Row -->
                <div class="charts-row">
                    <!-- Publikasi Chart -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h5 class="chart-title">Publikasi Berdasarkan Tipe</h5>
                            <p class="chart-subtitle">Distribusi publikasi berdasarkan kategori</p>
                        </div>
                        <div class="chart-container">
                            <canvas id="publikasiChart"></canvas>
                        </div>
                        <div class="chart-legend">
                            <div class="legend-item">
                                <span class="legend-color" style="background: #01B5B9;"></span>
                                <span class="legend-label">Riset</span>
                                <span class="legend-value"><?= $publikasiByTipe['Riset'] ?></span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color" style="background: #FFA500;"></span>
                                <span class="legend-label">Kekayaan Intelektual</span>
                                <span class="legend-value"><?= $publikasiByTipe['Kekayaan Intelektual'] ?></span>
                            </div>
                            <div class="legend-item">
                                <span class="legend-color" style="background: #10B981;"></span>
                                <span class="legend-label">PPM</span>
                                <span class="legend-value"><?= $publikasiByTipe['PPM'] ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Aktivitas Trend Chart -->
                    <div class="chart-card">
                        <div class="chart-header">
                            <h5 class="chart-title">Trend Aktivitas Laboratorium</h5>
                            <p class="chart-subtitle">6 Bulan Terakhir</p>
                        </div>
                        <div class="chart-container">
                            <canvas id="aktivitasChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Data Row -->
                <div class="recent-row">
                    <!-- Recent Publikasi -->
                    <div class="recent-card">
                        <div class="recent-header">
                            <h5 class="recent-title">Publikasi Terbaru</h5>
                            <a href="<?= base_url('publikasi') ?>" class="btn-view-all">
                                Lihat Semua
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </a>
                        </div>
                        <div class="recent-list">
                            <?php foreach ($recentPublikasi as $pub): ?>
                                <div class="recent-item">
                                    <div class="recent-item-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="recent-item-content">
                                        <p class="recent-item-title"><?= htmlspecialchars($pub['judul']) ?></p>
                                        <p class="recent-item-meta">
                                            <span><?= htmlspecialchars($pub['dosen']) ?></span>
                                            <span class="separator">•</span>
                                            <span class="badge-mini badge-<?= $pub['tipe'] === 'Riset' ? 'primary' : ($pub['tipe'] === 'Kekayaan Intelektual' ? 'warning' : 'success') ?>">
                                                <?= htmlspecialchars($pub['tipe']) ?>
                                            </span>
                                            <span class="separator">•</span>
                                            <span><?= $pub['tahun'] ?></span>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Recent Aktivitas & Rekrutmen -->
                    <div class="sidebar-cards">
                        <!-- Recent Aktivitas -->
                        <div class="recent-card recent-card-compact">
                            <div class="recent-header">
                                <h5 class="recent-title">Aktivitas Terbaru</h5>
                            </div>
                            <div class="recent-list recent-list-compact">
                                <?php foreach (array_slice($recentAktivitas, 0, 3) as $aktivitas): ?>
                                    <div class="recent-item-compact">
                                        <div class="recent-item-compact-dot"></div>
                                        <div>
                                            <p class="recent-item-compact-title"><?= htmlspecialchars($aktivitas['judul']) ?></p>
                                            <p class="recent-item-compact-date"><?= date('d M Y', strtotime($aktivitas['tanggal'])) ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Rekrutmen Aktif -->
                        <div class="recent-card recent-card-compact">
                            <div class="recent-header">
                                <h5 class="recent-title">Rekrutmen Aktif</h5>
                                <span class="badge-count"><?= count($rekrutmenAktif) ?></span>
                            </div>
                            <div class="recent-list recent-list-compact">
                                <?php foreach ($rekrutmenAktif as $rekrutmen): ?>
                                    <div class="recruitment-item">
                                        <div class="recruitment-item-icon">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="recruitment-item-title"><?= htmlspecialchars($rekrutmen['judul']) ?></p>
                                            <p class="recruitment-item-meta">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <polyline points="12 6 12 12 16 14"></polyline>
                                                </svg>
                                                Tutup: <?= date('d M Y', strtotime($rekrutmen['tanggal_tutup'])) ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="quick-stats-card">
                            <h5 class="quick-stats-title">Statistik Lainnya</h5>
                            <div class="quick-stats-grid">
                                <div class="quick-stat-item">
                                    <div class="quick-stat-value"><?= $stats['total_fasilitas'] ?></div>
                                    <div class="quick-stat-label">Fasilitas</div>
                                </div>
                                <div class="quick-stat-item">
                                    <div class="quick-stat-value"><?= $stats['total_keahlian'] ?></div>
                                    <div class="quick-stat-label">Keahlian</div>
                                </div>
                                <div class="quick-stat-item">
                                    <div class="quick-stat-value"><?= $stats['total_jabatan'] ?></div>
                                    <div class="quick-stat-label">Jabatan</div>
                                </div>
                                <div class="quick-stat-item">
                                    <div class="quick-stat-value"><?= $stats['total_aktivitas'] ?></div>
                                    <div class="quick-stat-label">Aktivitas</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </main>

    </div>

    <!-- jQuery -->
    <script src="<?= asset_url('js/jquery.min.js') ?>"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- Sidebar JS (jQuery Version) -->
    <script src="<?= asset_url('js/components/sidebar.js') ?>"></script>

    <!-- Dashboard JS -->
    <script src="<?= asset_url('js/pages/dashboard.js') ?>"></script>

    <script>
        // Initialize Feather Icons
        feather.replace();

        // Pass PHP data to JavaScript
        window.DASHBOARD_DATA = {
            publikasiByTipe: <?= json_encode($publikasiByTipe) ?>,
            aktivitasPerBulan: <?= json_encode($aktivitasPerBulan) ?>
        };
    </script>

</body>

</html>
