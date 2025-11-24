<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Publikasi - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS - Must load first -->
    <link rel="stylesheet" href="<?= asset_url('css/base/variables.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/reset.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Publikasi Read Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/publikasi/read.css') ?>">
</head>

<body>
    <div id="liveAlertPlaceholder"></div>

    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>
    <?= CsrfHelper::tokenField(); ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb-custom">
                <a href="<?= base_url('publikasi') ?>">Data Publikasi</a>
                <span>/</span>
                <span>Detail Publikasi</span>
            </div>
            <h1 class="page-title">Detail Publikasi</h1>
            <p class="page-subtitle">Informasi lengkap tentang publikasi</p>
        </div>

        <!-- Profile Card -->
        <div class="card">
            <div class="card-body">
                <!-- Publikasi Header -->
                <div class="publikasi-header">
                    <div class="publikasi-info">
                        <h2 class="publikasi-title"><?= htmlspecialchars($publikasi['judul']) ?></h2>
                        <div class="publikasi-meta">
                            <div class="meta-item">
                                <svg class="meta-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <div>
                                    <div class="meta-label">Dosen</div>
                                    <div class="meta-value"><?= htmlspecialchars($publikasi['dosen_name'] ?? '-') ?></div>
                                </div>
                            </div>
                            <div class="meta-item">
                                <svg class="meta-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                                <div>
                                    <div class="meta-label">Tipe Publikasi</div>
                                    <div class="meta-value">
                                        <?php
                                        $badgeClass = 'badge-primary';
                                        if ($publikasi['tipe_publikasi'] === 'Kekayaan Intelektual') {
                                            $badgeClass = 'badge-success';
                                        } elseif ($publikasi['tipe_publikasi'] === 'PPM') {
                                            $badgeClass = 'badge-warning';
                                        }
                                        ?>
                                        <span class="badge-custom <?= $badgeClass ?>"><?= htmlspecialchars($publikasi['tipe_publikasi']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div style="margin-bottom: 2rem;">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        Informasi Dasar
                    </h3>
                    <div class="info-row">
                        <div class="info-label">ID Publikasi</div>
                        <div class="info-value"><?= htmlspecialchars($publikasi['id']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Dosen</div>
                        <div class="info-value"><?= htmlspecialchars($publikasi['dosen_name'] ?? '-') ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Judul Publikasi</div>
                        <div class="info-value"><?= htmlspecialchars($publikasi['judul']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tipe Publikasi</div>
                        <div class="info-value">
                            <?php
                            $badgeClass = 'badge-primary';
                            if ($publikasi['tipe_publikasi'] === 'Kekayaan Intelektual') {
                                $badgeClass = 'badge-success';
                            } elseif ($publikasi['tipe_publikasi'] === 'PPM') {
                                $badgeClass = 'badge-warning';
                            }
                            ?>
                            <span class="badge-custom <?= $badgeClass ?>"><?= htmlspecialchars($publikasi['tipe_publikasi']) ?></span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tahun Publikasi</div>
                        <div class="info-value"><?= htmlspecialchars($publikasi['tahun_publikasi'] ?? '-') ?></div>
                    </div>
                    <?php if (empty($publikasi['url_publikasi']) || $publikasi['url_publikasi'] === "null"): ?>
                        <div class="info-row">
                            <div class="info-label">URL Publikasi</div>
                            <div class="info-value">URL tidak tersedia</div>
                        </div>

                    <?php else: ?>
                        <div class="info-row">
                            <div class="info-label">URL Publikasi</div>
                            <div class="info-value">
                                <a href="<?= htmlspecialchars($publikasi['url_publikasi']) ?>" target="_blank" class="url-link">
                                    <?= htmlspecialchars($publikasi['url_publikasi']) ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; margin-left: 4px;">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        <polyline points="15 3 21 3 21 9"></polyline>
                                        <line x1="10" y1="14" x2="21" y2="3"></line>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="info-row">
                        <div class="info-label">Ditambahkan Pada</div>
                        <div class="info-value"><?= formatTanggal($publikasi['created_at']) ?> </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Terakhir Diperbarui</div>
                        <div class="info-value"><?= formatTanggal($publikasi['updated_at']) ?></div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('admin/publikasi-akademik') ?>" class="btn-secondary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Kembali
                    </a>
                    <a href="<?= base_url('admin/publikasi-akademik/edit/' . $publikasi['id']) ?>" class="btn-primary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg>
                        Edit Data
                    </a>
                    <button class="btn-danger-custom" onclick="confirmDelete(<?= $publikasi['id'] ?>)">
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

    <!-- jQuery Helpers (required for AJAX operations) -->
    <script src="<?= asset_url('js/helpers/jQueryHelpers.js') ?>"></script>

    <!-- Data Publikasi Read Page JS -->
    <script src="<?= asset_url('js/pages/publikasi/read.js') ?>"></script>
    <script src="<?= asset_url('js/pages/publikasi/index.js') ?>"></script>
</body>

</html>