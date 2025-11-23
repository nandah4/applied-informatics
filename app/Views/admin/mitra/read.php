<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="/applied-informatics">
    <title>Detail Mitra - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS - Must load first -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Mitra Read Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/mitra/read.css') ?>">
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
                <a href="<?= base_url('admin/mitra') ?>">Data Mitra</a>
                <span>/</span>
                <span>Detail Mitra</span>
            </div>
            <h1 class="page-title">Detail Mitra</h1>
            <p class="page-subtitle">Informasi lengkap tentang mitra</p>
        </div>

        <!-- Detail Card -->
        <div class="card">
            <div class="card-body">
                <?php
                // Helper functions
                function getKategoriClassDetail($kategori)
                {
                    $mapping = [
                        'industri' => 'badge-industri',
                        'internasional' => 'badge-internasional',
                        'institusi pemerintah' => 'badge-institusi-pemerintah',
                        'institusi pendidikan' => 'badge-institusi-pendidikan',
                        'komunitas' => 'badge-komunitas'
                    ];
                    return $mapping[$kategori] ?? 'badge-industri';
                }

                $logoUrl = $mitra['logo_mitra']
                    ? upload_url('mitra/' . $mitra['logo_mitra'])
                    : upload_url('default/image.png');

                $statusBadge = $mitra['status'] === 'aktif' ? 'badge-success' : 'badge-warning';
                $statusLabel = $mitra['status'] === 'aktif' ? 'Mitra Aktif' : 'Mitra Non-Aktif';

                $kategoriClass = getKategoriClassDetail($mitra['kategori']);
                $kategoriLabel = ucwords($mitra['kategori']);
                ?>

                <!-- Mitra Header with Logo -->
                <div class="partner-header">
                    <div class="partner-logo-container">
                        <img src="<?= $logoUrl ?>" alt="Logo <?= htmlspecialchars($mitra['nama']) ?>" class="partner-logo">
                    </div>
                    <div class="partner-header-info">
                        <h2 class="partner-title"><?= htmlspecialchars($mitra['nama']) ?></h2>
                        <div class="partner-meta">
                            <span class="badge-custom <?= $statusBadge ?>"><?= $statusLabel ?></span>
                        </div>
                    </div>
                </div>

                <!-- Partner Information -->
                <div class="partner-info-section">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Informasi Mitra
                    </h3>

                    <div class="info-row">
                        <div class="info-label">ID Mitra</div>
                        <div class="info-value"><?= $mitra['id']; ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Nama Mitra</div>
                        <div class="info-value"><?= htmlspecialchars($mitra['nama']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <span class="badge-custom <?= $statusBadge ?>"><?= $statusLabel ?></span>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Kategori Mitra</div>
                        <div class="info-value">
                            <span class="badge-kategori <?= $kategoriClass ?>"><?= $kategoriLabel ?></span>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Tanggal Mulai Kerjasama</div>
                        <div class="info-value"><?= formatTanggal($mitra['tanggal_mulai']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Tanggal Akhir Kerjasama</div>
                        <div class="info-value"><?= formatTanggal($mitra['tanggal_akhir']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Terakhir Diperbarui</div>
                        <div class="info-value"><?= formatTanggal($mitra['updated_at'], true) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Tanggal Ditambahkan</div>
                        <div class="info-value"><?= formatTanggal($mitra['created_at'], true) ?></div>
                    </div>
                </div>

                <!-- Description Section -->
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
                        <?= $mitra['deskripsi'] ? nl2br(htmlspecialchars($mitra['deskripsi'])) : 'Tidak ada deskripsi.' ?>
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('admin/mitra') ?>" class="btn-secondary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Kembali
                    </a>
                    <a href="<?= base_url('admin/mitra/edit/' . $mitra['id']) ?>" class="btn-primary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg>
                        Edit Data
                    </a>
                    <button class="btn-danger-custom" onclick="confirmDelete(<?= $mitra['id'] ?>, '<?= htmlspecialchars($mitra['nama']) ?>')">
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
    <script src="<?= asset_url('js/pages/mitra/read.js') ?>"></script>
</body>

</html>