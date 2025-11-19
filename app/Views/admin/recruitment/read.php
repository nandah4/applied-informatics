<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Recruitment - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS - Must load first -->
    <link rel="stylesheet" href="<?= asset_url('css/base/variables.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/reset.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Recruitment Read Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/recruitment/read.css') ?>">
</head>

<body>
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb-custom">
                <a href="<?= base_url('recruitment') ?>">Data Recruitment</a>
                <span>/</span>
                <span>Detail Recruitment</span>
            </div>
            <h1 class="page-title">Detail Recruitment</h1>
            <p class="page-subtitle">Informasi lengkap tentang recruitment</p>
        </div>

        <!-- Profile Card -->
        <div class="card">
            <div class="card-body">
                <!-- Banner Image (if exists) -->
                <?php if (!empty($recruitment['gambar_banner'])): ?>
                    <div class="banner-container">
                        <img src="<?= upload_url('recruitment/' . $recruitment['gambar_banner']) ?>" alt="Banner Recruitment" class="banner-image-detail">
                    </div>
                <?php endif; ?>

                <!-- Recruitment Header -->
                <div class="recruitment-header">
                    <div class="recruitment-info">
                        <h2 class="recruitment-title"><?= htmlspecialchars($recruitment['posisi']) ?></h2>
                        <div class="recruitment-meta">
                            <div class="meta-item">
                                <svg class="meta-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <div>
                                    <div class="meta-label">Periode</div>
                                    <div class="meta-value">
                                        <?= date('d M Y', strtotime($recruitment['tanggal_mulai'])) ?> -
                                        <?= date('d M Y', strtotime($recruitment['tanggal_berakhir'])) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="meta-item">
                                <svg class="meta-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <div>
                                    <div class="meta-label">Status</div>
                                    <div class="meta-value">
                                        <?php if ($recruitment['status'] === 'aktif'): ?>
                                            <span class="badge-custom badge-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge-custom badge-secondary">Nonaktif</span>
                                        <?php endif; ?>
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
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Informasi Dasar
                    </h3>
                    <div class="info-row">
                        <div class="info-label">ID Recruitment</div>
                        <div class="info-value"><?= htmlspecialchars($recruitment['id']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Posisi</div>
                        <div class="info-value"><?= htmlspecialchars($recruitment['posisi']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tanggal Mulai</div>
                        <div class="info-value"><?= date('d F Y', strtotime($recruitment['tanggal_mulai'])) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Tanggal Berakhir</div>
                        <div class="info-value"><?= date('d F Y', strtotime($recruitment['tanggal_berakhir'])) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <?php if ($recruitment['status'] === 'aktif'): ?>
                                <span class="badge-custom badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge-custom badge-secondary">Nonaktif</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($recruitment['link_pendaftaran'])): ?>
                        <div class="info-row">
                            <div class="info-label">Link Pendaftaran</div>
                            <div class="info-value">
                                <a href="<?= htmlspecialchars($recruitment['link_pendaftaran']) ?>" target="_blank" style="color: var(--color-primary-500); text-decoration: none;">
                                    <?= htmlspecialchars($recruitment['link_pendaftaran']) ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: inline; margin-left: 4px;">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        <polyline points="15 3 21 3 21 9"></polyline>
                                        <line x1="10" y1="14" x2="21" y2="3"></line>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Description -->
                <div style="margin-bottom: 2rem;">
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
                        <?= empty($recruitment['deskripsi']) ? "Deskripsi recruitment belum ditambahkan." : nl2br(htmlspecialchars($recruitment['deskripsi'])); ?>
                    </p>
                </div>

                <!-- Requirements -->
                <?php if (!empty($recruitment['persyaratan'])): ?>
                    <div style="margin-bottom: 2rem;">
                        <h3 class="section-title">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 11 12 14 22 4"></polyline>
                                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                            </svg>
                            Persyaratan
                        </h3>
                        <div class="requirements-list">
                            <?php
                            $persyaratanArray = explode("\n", $recruitment['persyaratan']);
                            foreach ($persyaratanArray as $item):
                                $trimmedItem = trim($item);
                                if (!empty($trimmedItem)):
                            ?>
                                    <div class="requirement-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="20 6 9 17 4 12"></polyline>
                                        </svg>
                                        <span><?= htmlspecialchars($trimmedItem) ?></span>
                                    </div>
                            <?php
                                endif;
                            endforeach;
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('recruitment') ?>" class="btn-secondary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Kembali
                    </a>
                    <a href="<?= base_url('recruitment/edit/' . $recruitment['id']) ?>" class="btn-primary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg>
                        Edit Data
                    </a>
                    <button class="btn-danger-custom" onclick="confirmDelete()">
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

    <!-- Data Recruitment Read Page JS -->
    <script src="<?= asset_url('js/pages/recruitment/read.js') ?>"></script>
</body>

</html>
