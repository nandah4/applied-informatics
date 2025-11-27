<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="/applied-informatics">
    <title>Edit Publikasi - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Select2 CSS for dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

    <!-- Base CSS - Must load first -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Publikasi Form Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/publikasi/form.css') ?>">
</head>

<body>
    <!-- Alert Placeholder untuk notifikasi -->
    <div id="liveAlertPlaceholder"></div>

    <!-- Sidebar -->
    <?php
    include __DIR__ . '/../../layouts/sidebar.php';
    ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb-custom">
                <a href="<?= base_url('admin/publikasi-akademik') ?>">Data Publikasi</a>
                <span>/</span>
                <span>Edit Publikasi</span>
            </div>
            <h1 class="page-title">Edit Publikasi</h1>
            <p class="page-subtitle">Edit data publikasi yang sudah ada</p>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-body">
                <form id="formPublikasi" method="POST">
                    <?= CsrfHelper::tokenField(); ?>
                    <!-- Hidden Field: ID Publikasi -->
                    <input type="hidden" id="publikasi_id" name="id" value="<?= htmlspecialchars($publikasi['id']) ?>">

                    <div class="row">
                        <!-- Dosen -->
                        <div class="col-md-6 mb-3">
                            <label for="dosen_id" class="form-label">
                                Dosen <span class="required">*</span>
                            </label>
                            <select class="form-control" id="dosen_id" name="dosen_id" required>
                                <option value="">Pilih Dosen</option>
                                <?php if (!empty($listDosen)): ?>
                                    <?php foreach ($listDosen as $dosen): ?>
                                        <option value="<?= $dosen['id'] ?>" <?= ($publikasi['dosen_id'] == $dosen['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($dosen['full_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <div id="dosenIdError" class="invalid-feedback"></div>
                        </div>

                        <!-- Tipe Publikasi -->
                        <div class="col-md-6 mb-3">
                            <label for="tipe_publikasi" class="form-label">
                                Tipe Publikasi <span class="required">*</span>
                            </label>
                            <select class="form-control" id="tipe_publikasi" name="tipe_publikasi" required>
                                <option value="">Pilih Tipe Publikasi</option>
                                <option value="Riset" <?= ($publikasi['tipe_publikasi'] === 'Riset') ? 'selected' : '' ?>>Riset</option>
                                <option value="Kekayaan Intelektual" <?= ($publikasi['tipe_publikasi'] === 'Kekayaan Intelektual') ? 'selected' : '' ?>>Kekayaan Intelektual</option>
                                <option value="PPM" <?= ($publikasi['tipe_publikasi'] === 'PPM') ? 'selected' : '' ?>>PPM</option>
                            </select>
                            <div id="tipePublikasiError" class="invalid-feedback"></div>
                        </div>

                        <!-- Judul -->
                        <div class="col-12 mb-3">
                            <label for="judul" class="form-label">
                                Judul Publikasi <span class="required">*</span>
                            </label>
                            <textarea class="form-control" id="judul" name="judul" rows="3" placeholder="Masukkan judul publikasi" required><?= htmlspecialchars($publikasi['judul']) ?></textarea>
                            <div class="helper-text">Judul lengkap publikasi</div>
                            <div id="judulError" class="invalid-feedback"></div>
                        </div>

                        <!-- Tahun Publikasi -->
                        <div class="col-md-6 mb-3">
                            <label for="tahun_publikasi" class="form-label">
                                Tahun Publikasi
                            </label>
                            <input type="number" class="form-control" id="tahun_publikasi" name="tahun_publikasi" placeholder="Contoh: 2024" min="1900" max="2100" value="<?= htmlspecialchars($publikasi['tahun_publikasi'] ?? '') ?>">
                            <div class="helper-text">Tahun publikasi diterbitkan</div>
                            <div id="tahunPublikasiError" class="invalid-feedback"></div>
                        </div>

                        <!-- URL Publikasi -->
                        <div class="col-md-6 mb-3">
                            <label for="url_publikasi" class="form-label">
                                URL Publikasi
                            </label>
                            <input type="url" class="form-control" id="url_publikasi" name="url_publikasi" placeholder="https://..." value="<?= htmlspecialchars($publikasi['url_publikasi'] ?? '') ?>">
                            <div class="helper-text">Link ke publikasi online (opsional)</div>
                            <div id="urlPublikasiError" class="invalid-feedback"></div>
                        </div>

                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="<?= base_url('admin/publikasi-akademik') ?>" class="btn-secondary-custom">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" id="btn-submit-update-publikasi" class="btn-primary-custom">
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

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <!-- Sidebar JS -->
    <script src="<?= asset_url('js/components/sidebar.js') ?>"></script>

    <!-- Helper Scripts (Must load before form.js) -->
    <script src="<?= asset_url('js/helpers/jQueryHelpers.js') ?>"></script>
    <script src="<?= asset_url('js/helpers/validationHelpers.js') ?>"></script>

    <!-- Data Publikasi Form Page JS (shared logic) -->
    <script src="<?= asset_url('js/pages/publikasi/form.js') ?>"></script>

    <!-- Data Publikasi Edit Page JS (edit-specific logic) -->
    <script src="<?= asset_url('js/pages/publikasi/edit.js') ?>"></script>
</body>

</html>