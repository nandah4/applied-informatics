<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Aktivitas - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Aktivitas Detail Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/aktivitas-lab/read.css') ?>">
    <!-- <link rel="stylesheet" href="<?= asset_url('css/pages/mitra/read.css') ?>"> -->
</head>

<body>
     <!-- Alert Placeholder untuk notifikasi -->
    <div id="liveAlertPlaceholder"></div>
    
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <?php
        // Data aktivitas dari controller
        $fotoUrl = upload_url('aktivitas-lab/' . $aktivitas['foto_aktivitas']);
        ?>

        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb-custom">
                <a href="<?= base_url('aktivitas-lab') ?>">Aktivitas Laboratorium</a>
                <span>/</span>
                <span>Detail Aktivitas</span>
            </div>
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="page-title">Detail Aktivitas</h1>
                    <p class="page-subtitle">Informasi lengkap aktivitas laboratorium</p>
                </div>
            </div>
        </div>


        <!-- Detail Card -->
        <div class="card">
            <div class="card-body">

                <!-- Div : Photo -->
                <div class="aktivitas-photo-container">
                    <?php if (!empty($aktivitas['foto_aktivitas'])): ?>
                        <img src="<?= upload_url('aktivitas-lab/' . $aktivitas['foto_aktivitas']) ?>"
                            alt="Foto Aktivitas: <?= htmlspecialchars($aktivitas['judul_aktivitas']) ?>"
                            class="aktivitas-photo">
                    <?php else: ?>
                        <img src="<?= upload_url('default/image.png') ?>"
                            alt="No Photo Available"
                            class="aktivitas-photo">
                    <?php endif; ?>
                </div>

                <!-- Div : Informasi Detail -->
                <div class="aktivitas-info-section">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Informasi Aktivitas
                    </h3>

                    <div class="info-row">
                        <div class="info-label">ID Aktivitas</div>
                        <div class="info-value"><?= $aktivitas['id'] ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Nama Aktifitas</div>
                        <div class="info-value"><?= htmlspecialchars($aktivitas['judul_aktivitas']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Tanggal Aktivitas</div>
                        <div class="info-value"><?= date('d M Y H', strtotime($aktivitas['tanggal_kegiatan'])) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Terakhir Diperbarui</div>
                        <div class="info-value"><?= formatTanggal($aktivitas['updated_at'], true); ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tanggal Ditambahkan</div>
                        <div class="info-value"><?= formatTanggal($aktivitas['updated_at'], true); ?></div>
                    </div>
                </div>


                <!-- Div : Description Section -->
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
                    <p class="description-text"><?= nl2br(htmlspecialchars($aktivitas['deskripsi'])) ?></p>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('aktivitas-lab') ?>" class="btn-secondary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Kembali
                    </a>
                    <a href="<?= base_url('aktivitas-lab/edit/' . $aktivitas['id']) ?>" class="btn-primary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg>
                        Edit Data
                    </a>
                     <button class="btn-danger-custom" onclick="confirmDelete(<?= $aktivitas['id'] ?>, '<?= htmlspecialchars($aktivitas['judul_aktivitas']) ?>')">
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

    <!-- Main Logic JS -->
    <script src="<?= asset_url('js/pages/aktivitas-lab/read.js') ?>"></script>

    <script>
        // Initialize feather icons
        if (typeof feather !== "undefined") {
            feather.replace();
        }
    </script>
</body>

</html>