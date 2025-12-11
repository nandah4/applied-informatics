<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="/applied-informatics">
    <meta name="csrf-token" content="<?= CsrfHelper::generateToken() ?>">
    <title>Detail Asisten Lab - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/variables.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/reset.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Read Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/asisten-lab/read.css') ?>">
</head>

<body>
    <div id="liveAlertPlaceholder"></div>

    <!-- CSRF Token -->
    <?= CsrfHelper::tokenField() ?>

    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb-custom">
                <a href="<?= base_url('admin/asisten-lab') ?>">Data Asisten Lab</a>
                <span>/</span>
                <span>Detail Asisten Lab</span>
            </div>
            <h1 class="page-title">Detail Asisten Lab</h1>
            <p class="page-subtitle">Informasi lengkap tentang anggota laboratorium</p>
        </div>

        <!-- Profile Card -->
        <div class="card">
            <div class="card-body">

                <!-- Section: Informasi Dasar -->
                <div style="margin-bottom: 2rem;">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Informasi Dasar
                    </h3>

                    <div class="info-row">
                        <div class="info-label">ID</div>
                        <div class="info-value"><?= htmlspecialchars($asisten['id']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">NIM</div>
                        <div class="info-value"><?= htmlspecialchars($asisten['nim']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Nama</div>
                        <div class="info-value fw-bold"><?= htmlspecialchars($asisten['nama']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value"><?= htmlspecialchars($asisten['email']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">No. Handphone</div>
                        <div class="info-value">
                            <?php if (!empty($asisten['no_hp'])): ?>
                                <?= htmlspecialchars($asisten['no_hp']) ?>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Semester</div>
                        <div class="info-value"><?= htmlspecialchars($asisten['semester']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Link Github</div>
                        <div class="info-value">
                            <?php if (!empty($asisten['link_github'])): ?>
                                <a href="<?= htmlspecialchars($asisten['link_github']) ?>" target="_blank" class="text-primary">
                                    <?= htmlspecialchars($asisten['link_github']) ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Section: Status Keanggotaan -->
                <div style="margin-bottom: 2rem;">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Status Keanggotaan
                    </h3>

                    <div class="info-row">
                        <div class="info-label">Tipe Anggota</div>
                        <div class="info-value">
                            <span class="badge <?= ($asisten['tipe_anggota'] ?? '') === 'magang' ? 'badge-warning' : 'badge-info' ?>">
                                <?= ucwords(htmlspecialchars($asisten['tipe_anggota'] ?? 'Asisten Lab')) ?>
                            </span>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <?php if ($asisten['status_aktif']): ?>
                                <span class="badge badge-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Tidak Aktif</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Periode Aktif</div>
                        <div class="info-value"><?= htmlspecialchars($asisten['periode_aktif'] ?? '-') ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Tanggal Gabung</div>
                        <div class="info-value"><?= htmlspecialchars(formatTanggal($asisten['tanggal_gabung'], false)) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Tanggal Selesai</div>
                        <div class="info-value">
                            <?php if (!empty($asisten['tanggal_selesai'])): ?>
                                <?= htmlspecialchars(formatTanggal($asisten['tanggal_selesai'], false)) ?>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($asisten['asal_pendaftar_id'])): ?>
                        <div class="info-row">
                            <div class="info-label">Asal Pendaftar ID</div>
                            <div class="info-value"><?= htmlspecialchars($asisten['asal_pendaftar_id']) ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="info-row">
                        <div class="info-label">Dibuat Pada</div>
                        <div class="info-value"><?= htmlspecialchars(formatTanggal($asisten['created_at'], true)) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Diperbarui Pada</div>
                        <div class="info-value"><?= htmlspecialchars(formatTanggal($asisten['updated_at'], true)) ?></div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('admin/asisten-lab') ?>" class="btn-secondary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Kembali
                    </a>
                    <a href="<?= base_url('admin/asisten-lab/edit/' . $asisten['id']) ?>" class="btn-primary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Edit Data
                    </a>
                    <button class="btn-danger-custom" onclick="confirmDelete(<?= $asisten['id'] ?>)">
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

    <!-- Asisten Lab Read Page JS -->
    <script src="<?= asset_url('js/pages/asisten-lab/read.js') ?>"></script>
</body>

</html>