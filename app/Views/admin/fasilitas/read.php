<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail: <?= htmlspecialchars($fasilitas['nama']) ?> - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Fasilitas Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/fasilitas/read.css') ?>">
</head>

<body>
    <div id="liveAlertPlaceholder"></div>

    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>

    <div class="main-content">
        <div class="page-header">
            <div class="breadcrumb-custom">
                <a href="<?= base_url('fasilitas') ?>">Data Fasilitas</a>
                <span>/</span>
                <span>Detail Fasilitas</span>
            </div>
            <h1 class="page-title"><?= htmlspecialchars($fasilitas['nama']) ?></h1>
            <p class="page-subtitle">Informasi lengkap tentang fasilitas</p>
        </div>

        <div class="card">
            <div class="card-body">
                <!-- Foto Fasilitas -->
                <div class="facility-photo-container">
                    <?php if (!empty($fasilitas['foto'])): ?>
                        <img src="<?= upload_url('fasilitas/' . $fasilitas['foto']) ?>"
                            alt="Foto Fasilitas: <?= htmlspecialchars($fasilitas['nama']) ?>"
                            class="facility-photo">
                    <?php else: ?>
                        <img src="<?= upload_url('default/image.png') ?>"
                            alt="No Photo Available"
                            class="facility-photo">
                    <?php endif; ?>
                </div>

                <!-- Informasi Fasilitas -->
                <div class="facility-info-section">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Informasi Fasilitas
                    </h3>

                    <div class="info-row">
                        <div class="info-label">ID Fasilitas</div>
                        <div class="info-value">#<?= $fasilitas['fasilitas_id'] ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Nama Fasilitas</div>
                        <div class="info-value"><?= htmlspecialchars($fasilitas['nama']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Deskripsi</div>
                        <div class="info-value">
                            <?= !empty($fasilitas['deskripsi'])
                                ? nl2br(htmlspecialchars($fasilitas['deskripsi']))
                                : '<span style="color: var(--color-gray-400);">Tidak ada deskripsi</span>'
                            ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Tanggal Ditambahkan</div>
                        <div class="info-value">
                            <?= date('d F Y, H:i', strtotime($fasilitas['created_at'])) ?> WIB
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Terakhir Diperbarui</div>
                        <div class="info-value">
                            <?= date('d F Y, H:i', strtotime($fasilitas['updated_at'])) ?> WIB
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('fasilitas') ?>" class="btn-secondary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Kembali
                    </a>
                    <a href="<?= base_url('fasilitas/edit/' . $fasilitas['fasilitas_id']) ?>" class="btn-primary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg>
                        Edit Data
                    </a>
                    <!-- Tambahkan data-fasilitas-id attribute -->
                    <button
                        class="btn-danger-custom"
                        data-fasilitas-id="<?= $fasilitas['fasilitas_id'] ?>"
                        onclick="confirmDelete(<?= $fasilitas['fasilitas_id'] ?>, '<?= base_url('fasilitas/delete/' . $fasilitas['fasilitas_id']) ?>')">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    
    <!-- Sidebar JS (jQuery Version) -->
    <script src="<?= asset_url('js/components/sidebar.js') ?>"></script>

    <!-- Helper Scripts -->
    <script src="<?= asset_url('js/helpers/jQueryHelpers.js') ?>"></script>

    <!-- Data Fasilitas Page JS -->
    <script src="<?= asset_url('js/pages/fasilitas/read.js') ?>"></script>
</body>

</html>