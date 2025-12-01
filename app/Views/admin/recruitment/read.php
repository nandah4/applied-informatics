<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?= base_url() ?>">
    <title>Detail Recruitment - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS - Must load first -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Recruitment Read Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/recruitment/read.css') ?>">
</head>

<body>
    <!-- Alert Placeholder untuk notifikasi -->
    <div id="liveAlertPlaceholder"></div>

    <!-- CSRF Token untuk AJAX requests -->
    <?= CsrfHelper::tokenField() ?>

    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb-custom">
                <a href="<?= base_url('admin/recruitment') ?>">Data Recruitment</a>
                <span>/</span>
                <span>Detail Recruitment</span>
            </div>
            <h1 class="page-title">Detail Recruitment</h1>
            <p class="page-subtitle">Informasi lengkap tentang recruitment</p>
        </div>

        <!-- Detail Card -->
        <div class="card">
            <div class="card-body">
                <?php
                // Helper untuk status badge
                $statusBadge = $recruitment['status'] === 'buka' ? 'badge-success' : 'badge-danger';
                $statusLabel = $recruitment['status'] === 'buka' ? 'Recruitment Buka' : 'Recruitment Tutup';

                // Calculate days remaining
                $today = new DateTime();
                $endDate = new DateTime($recruitment['tanggal_tutup']);
                ?>

                <!-- Recruitment Header -->
                <div class="recruitment-header">
                    <div class="recruitment-header-info">
                        <h2 class="recruitment-title"><?= htmlspecialchars($recruitment['judul']) ?></h2>
                        <div class="recruitment-meta">
                            <!-- Status -->
                            <div class="meta-item">
                                <div class="meta-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                </div>
                                <div class="meta-content">
                                    <div class="meta-label">Status</div>
                                    <div class="meta-value">
                                        <span class="badge-custom <?= $statusBadge ?>"><?= $statusLabel ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tanggal Buka -->
                            <div class="meta-item">
                                <div class="meta-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </div>
                                <div class="meta-content">
                                    <div class="meta-label">Tanggal Buka</div>
                                    <div class="meta-value"><?= formatTanggal($recruitment['tanggal_buka']) ?></div>
                                </div>
                            </div>

                            <!-- Tanggal Tutup -->
                            <div class="meta-item">
                                <div class="meta-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                        <line x1="8" y1="14" x2="16" y2="14"></line>
                                    </svg>
                                </div>
                                <div class="meta-content">
                                    <div class="meta-label">Tanggal Tutup</div>
                                    <div class="meta-value"><?= formatTanggal($recruitment['tanggal_tutup']) ?></div>
                                </div>
                            </div>

                            <!-- Lokasi -->
                            <div class="meta-item">
                                <div class="meta-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>
                                <div class="meta-content">
                                    <div class="meta-label">Lokasi</div>
                                    <div class="meta-value"><?= htmlspecialchars($recruitment['lokasi'] ?? 'Belum ditentukan') ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recruitment Information -->
                <div class="recruitment-info-section">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        Informasi Detail
                    </h3>

                    <div class="info-row">
                        <div class="info-label">ID Recruitment</div>
                        <div class="info-value"><?= $recruitment['id']; ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Judul</div>
                        <div class="info-value"><?= htmlspecialchars($recruitment['judul']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Durasi</div>
                        <div class="info-value">
                            <?php
                            $start = new DateTime($recruitment['tanggal_buka']);
                            $end = new DateTime($recruitment['tanggal_tutup']);
                            $duration = $start->diff($end)->days;
                            echo $duration . ' hari';
                            ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Terakhir Diperbarui</div>
                        <div class="info-value"><?= formatTanggal($recruitment['updated_at'], true) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Tanggal Ditambahkan</div>
                        <div class="info-value"><?= formatTanggal($recruitment['created_at'], true) ?></div>
                    </div>
                </div>

                <!-- Description Section -->
                <?php if (!empty($recruitment['deskripsi'])): ?>
                    <div class="description-section">
                        <h3 class="section-title">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            Deskripsi
                        </h3>
                        <p class="description-text">
                            <?= nl2br(htmlspecialchars($recruitment['deskripsi'])) ?>
                        </p>
                    </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('admin/recruitment') ?>" class="btn-secondary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Kembali
                    </a>
                    <a href="<?= base_url('admin/recruitment/edit/' . $recruitment['id']) ?>" class="btn-primary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg>
                        Edit Data
                    </a>
                    <button class="btn-danger-custom" onclick="confirmDelete(<?= $recruitment['id'] ?>)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        Hapus Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?= asset_url('js/jquery.min.js') ?>"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <!-- Sidebar JS -->
    <script src="<?= asset_url('js/components/sidebar.js') ?>"></script>

    <!-- Helper Scripts -->
    <script src="<?= asset_url('js/helpers/jQueryHelpers.js') ?>"></script>

    <!-- Page Specific Scripts -->
    <script src="<?= asset_url('js/pages/recruitment/read.js') ?>"></script>
</body>

</html>