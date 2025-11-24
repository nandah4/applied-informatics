<?php
/**
 * File: Views/admin/index.php
 * Deskripsi: Dashboard admin dengan statistik dinamis dari database
 *
 * Data yang diterima dari routes:
 * - $stats: Statistik dari v_dashboard_count
 * - $recentPublikasi: 5 publikasi terbaru dari vw_show_publikasi
 * - $publikasiByTipe: Jumlah publikasi per tipe (Riset, Kekayaan Intelektual, PPM)
 */

// Get user data from session
$userFullname = $_SESSION['user_fullname'] ?? 'User';
$userEmail = $_SESSION['user_email'] ?? '';

// Ensure $stats has default values if empty
if (empty($stats)) {
    $stats = [
        'total_dosen' => 0,
        'total_publikasi' => 0,
        'total_mitra' => 0,
        'total_produk' => 0,
        'total_fasilitas' => 0,
        'total_keahlian' => 0,
        'total_jabatan' => 0,
        'total_aktivitas_lab' => 0,
    ];
}

// Ensure publikasiByTipe has default values
if (empty($publikasiByTipe)) {
    $publikasiByTipe = [
        'Riset' => 0,
        'Kekayaan Intelektual' => 0,
        'PPM' => 0,
    ];
}

// Ensure recentPublikasi is array
if (empty($recentPublikasi)) {
    $recentPublikasi = [];
}

// TODO: Implement these data from database (future development)
$recentAktivitas = [];
$rekrutmenAktif = [];
$mitraByKategori = [];
$aktivitasPerBulan = [
    'labels' => ['Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov'],
    'data' => [0, 0, 0, 0, 0, 0]
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
                            <h3 class="stat-value"><?= $stats['total_dosen'] ?? 0 ?></h3>
                            <span class="stat-change stat-neutral">
                                Dosen aktif
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
                            <h3 class="stat-value"><?= $stats['total_publikasi'] ?? 0 ?></h3>
                            <span class="stat-change stat-neutral">
                                Publikasi akademik
                            </span>
                        </div>
                    </div>

                    <!-- Total Mitra -->
                    <div class="stat-card">
                        <div class="stat-icon stat-icon-warning">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <polyline points="17 11 19 13 23 9"></polyline>
                            </svg>
                        </div>
                        <div class="stat-content">
                            <p class="stat-label">Total Mitra</p>
                            <h3 class="stat-value"><?= $stats['total_mitra'] ?? 0 ?></h3>
                            <span class="stat-change stat-neutral">
                                Mitra kerjasama lab
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
                            <h3 class="stat-value"><?= $stats['total_produk'] ?? 0 ?></h3>
                            <span class="stat-change stat-neutral">
                                Produk laboratorium
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
                            <a href="<?= base_url('admin/publikasi-akademik') ?>" class="btn-view-all">
                                Lihat Semua
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </a>
                        </div>
                        <div class="recent-list">
                            <?php if (empty($recentPublikasi)): ?>
                                <div class="recent-item">
                                    <div class="recent-item-content">
                                        <p class="recent-item-meta" style="text-align: center; color: #999;">
                                            Belum ada data publikasi
                                        </p>
                                    </div>
                                </div>
                            <?php else: ?>
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
                                                <span><?= htmlspecialchars($pub['dosen_name'] ?? '-') ?></span>
                                                <span class="separator">•</span>
                                                <span class="badge-mini badge-<?= $pub['tipe_publikasi'] === 'Riset' ? 'primary' : ($pub['tipe_publikasi'] === 'Kekayaan Intelektual' ? 'warning' : 'success') ?>">
                                                    <?= htmlspecialchars($pub['tipe_publikasi']) ?>
                                                </span>
                                                <span class="separator">•</span>
                                                <span><?= $pub['tahun_publikasi'] ?? '-' ?></span>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
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
                                <?php if (empty($recentAktivitas)): ?>
                                    <p style="text-align: center; color: #999; padding: 1rem;">
                                        Belum ada data aktivitas
                                    </p>
                                <?php else: ?>
                                    <?php foreach (array_slice($recentAktivitas, 0, 3) as $aktivitas): ?>
                                        <div class="recent-item-compact">
                                            <div class="recent-item-compact-dot"></div>
                                            <div>
                                                <p class="recent-item-compact-title"><?= htmlspecialchars($aktivitas['judul']) ?></p>
                                                <p class="recent-item-compact-date"><?= date('d M Y', strtotime($aktivitas['tanggal'])) ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Rekrutmen Aktif -->
                        <div class="recent-card recent-card-compact">
                            <div class="recent-header">
                                <h5 class="recent-title">Rekrutmen Aktif</h5>
                                <?php if (!empty($rekrutmenAktif)): ?>
                                    <span class="badge-count"><?= count($rekrutmenAktif) ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="recent-list recent-list-compact">
                                <?php if (empty($rekrutmenAktif)): ?>
                                    <p style="text-align: center; color: #999; padding: 1rem;">
                                        Tidak ada rekrutmen aktif
                                    </p>
                                <?php else: ?>
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
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="quick-stats-card">
                            <h5 class="quick-stats-title">Statistik Lainnya</h5>
                            <div class="quick-stats-grid">
                                <div class="quick-stat-item">
                                    <div class="quick-stat-value"><?= $stats['total_fasilitas'] ?? 0 ?></div>
                                    <div class="quick-stat-label">Fasilitas</div>
                                </div>
                                <div class="quick-stat-item">
                                    <div class="quick-stat-value"><?= $stats['total_keahlian'] ?? 0 ?></div>
                                    <div class="quick-stat-label">Keahlian</div>
                                </div>
                                <div class="quick-stat-item">
                                    <div class="quick-stat-value"><?= $stats['total_jabatan'] ?? 0 ?></div>
                                    <div class="quick-stat-label">Jabatan</div>
                                </div>
                                <div class="quick-stat-item">
                                    <div class="quick-stat-value"><?= $stats['total_aktivitas_lab'] ?? 0 ?></div>
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
