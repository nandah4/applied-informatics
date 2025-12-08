<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?= base_url() ?>">
    <title>Edit Recruitment - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Recruitment Form Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/recruitment/form.css') ?>">
</head>

<body>
    <!-- Alert Placeholder untuk notifikasi -->
    <div id="liveAlertPlaceholder"></div>

    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb-custom">
                <a href="<?= base_url('admin/recruitment') ?>">Data Recruitment</a>
                <span>/</span>
                <span>Edit Recruitment</span>
            </div>
            <h1 class="page-title">Edit Recruitment</h1>
            <p class="page-subtitle">Edit data recruitment yang sudah ada</p>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-body">
                <form id="formUpdateRecruitment" method="POST" action="<?= base_url('admin/recruitment/update') ?>" enctype="multipart/form-data">

                    <?= CsrfHelper::tokenField() ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($recruitment['id']) ?>">

                    <div class="row">
                        <!-- Judul -->
                        <div class="col-md-6 mb-3">
                            <label for="judul" class="form-label">
                                Judul <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="judul" name="judul" value="<?= htmlspecialchars($recruitment['judul']) ?>" placeholder="Contoh: Asisten Praktikum AI" required>
                            <div id="judulError" class="invalid-feedback"></div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">
                                Status <span class="required">*</span>
                            </label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="buka" <?= $recruitment['status'] === 'buka' ? 'selected' : '' ?>>Buka</option>
                                <option value="tutup" <?= $recruitment['status'] === 'tutup' ? 'selected' : '' ?>>Tutup</option>
                            </select>
                            <div id="statusError" class="invalid-feedback"></div>
                        </div>

                        <!-- Tanggal Buka -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_buka" class="form-label">
                                Tanggal Buka <span class="required">*</span>
                            </label>
                            <input type="date" class="form-control" id="tanggal_buka" name="tanggal_buka" value="<?= htmlspecialchars($recruitment['tanggal_buka']) ?>" required>
                            <div class="helper-text">Tanggal dimulainya periode recruitment</div>
                            <div id="tanggalBukaError" class="invalid-feedback"></div>
                        </div>

                        <!-- Tanggal Tutup -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_tutup" class="form-label">
                                Tanggal Tutup <span class="required">*</span>
                            </label>
                            <input type="date" class="form-control" id="tanggal_tutup" name="tanggal_tutup" value="<?= htmlspecialchars($recruitment['tanggal_tutup']) ?>" required>
                            <div class="helper-text">Tanggal berakhirnya periode recruitment</div>
                            <div id="tanggalTutupError" class="invalid-feedback"></div>
                        </div>

                        <!-- Info Box untuk Status Behavior -->
                        <div class="col-12 mb-3">
                            <div style="background: #f0f9ff; border-left: 4px solid #3b82f6; padding: 1rem; border-radius: 8px;">
                                <div style="display: flex; gap: 0.75rem; align-items: start;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                    </svg>
                                    <div>
                                        <strong style="color: #1e40af; font-size: 0.875rem;">Informasi Kontrol Status</strong>
                                        <ul style="color: #1e40af; font-size: 0.8125rem; margin: 0.5rem 0 0 0; padding-left: 1.25rem; line-height: 1.6;">
                                            <li><strong>Status Manual:</strong> Anda dapat menutup recruitment secara manual meskipun tanggal tutup masih panjang (Misal: Kuota terpenuhi)</li>
                                            <li><strong>Auto-Close:</strong> Recruitment otomatis tertutup jika tanggal tutup sudah terlewat</li>
                                            <li><strong>Auto-Open:</strong> Recruitment otomatis terbuka jika Anda memperpanjang tanggal dari expired ke masa depan</li>
                                            <li><strong>Tidak Bisa Buka:</strong> Recruitment yang sudah expired tidak dapat dibuka kembali tanpa memperpanjang tanggal</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Lokasi -->
                        <div class="col-12 mb-3">
                            <label for="lokasi" class="form-label">
                                Lokasi <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="lokasi" name="lokasi" value="<?= htmlspecialchars($recruitment['lokasi'] ?? '') ?>" placeholder="Contoh: Lab AI - Gedung H7 Lantai 8" required>
                            <div class="helper-text">Lokasi pelaksanaan recruitment atau pekerjaan</div>
                            <div id="lokasiError" class="invalid-feedback"></div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-12 mb-3">
                            <label for="deskripsi" class="form-label">
                                Deskripsi <span class="required">*</span>
                            </label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi singkat tentang posisi recruitment" required><?= htmlspecialchars($recruitment['deskripsi']) ?></textarea>
                            <div class="helper-text">Deskripsikan posisi, tanggung jawab, dan benefit</div>
                            <div id="deskripsiError" class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="<?= base_url('admin/recruitment') ?>" class="btn-secondary-custom">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" id="btn-update-recruitment" class="btn-primary-custom">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Update Data
                        </button>
                    </div>
                </form>
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
    <script src="<?= asset_url('js/helpers/validationHelpers.js') ?>"></script>

    <!-- Page Spesific Scripts -->
    <script src="<?= asset_url('js/pages/recruitment/edit.js') ?>"></script>
</body>

</html>