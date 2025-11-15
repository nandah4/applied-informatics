<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Fasilitas - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS - Must load first -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Fasilitas Form Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/fasilitas/form.css') ?>">
</head>

<body>
    <!-- Alert Placeholder -->
    <div id="liveAlertPlaceholder"></div>

    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb-custom">
                <a href="<?= base_url('fasilitas') ?>">Data Fasilitas</a>
                <span>/</span>
                <span>Tambah Fasilitas</span>
            </div>
            <h1 class="page-title">Tambah Fasilitas</h1>
            <p class="page-subtitle">Tambahkan data fasilitas baru ke sistem</p>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-body">
                <form id="formFasilitas" method="POST" enctype="multipart/form-data"
                    data-ajax-url="<?= base_url('fasilitas/create') ?>"
                    data-redirect-url="<?= base_url('fasilitas') ?>"
                    data-success-message="Data fasilitas berhasil ditambahkan.">

                    <div class="row">
                        <!-- Nama Fasilitas -->
                        <div class="col-12 mb-3">
                            <label for="nama" class="form-label">
                                Nama Fasilitas <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama fasilitas" required>
                            <!-- Tambahkan div untuk error message -->
                            <div id="namaError" class="invalid-feedback"></div>
                            <div class="helper-text">Berikan nama yang jelas dan deskriptif untuk fasilitas</div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-12 mb-3">
                            <label for="deskripsi" class="form-label">
                                Deskripsi
                            </label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi singkat tentang fasilitas"></textarea>
                            <!-- Tambahkan div untuk error message -->
                            <div id="deskripsiError" class="invalid-feedback"></div>
                            <div class="helper-text">Deskripsikan lebih lanjut mengenai fasilitas tersebut</div>
                        </div>

                        <!-- Foto Fasilitas -->
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                Foto Fasilitas <span class="required">*</span>
                            </label>
                            <div class="file-upload-wrapper" id="fileUploadWrapper">
                                <svg class="file-upload-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                <div class="file-upload-text">
                                    <strong>Klik untuk upload</strong> atau drag and drop
                                </div>
                                <div class="file-upload-hint">
                                    PNG, JPG, JPEG maksimal 2MB
                                </div>
                            </div>
                            <input type="file" class="file-upload-input" id="foto" name="foto" accept="image/png,image/jpg,image/jpeg">
                            <div id="fotoError" class="invalid-feedback"></div>
                            <div class="image-preview" id="imagePreview" style="display: none;">
                                <img id="previewImg" src="" alt="Preview">
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="<?= base_url('fasilitas') ?>" class="btn-secondary-custom">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" class="btn-primary-custom" id="btn-submit-create-fasilitas">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Simpan Data
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

    <!-- Helper Scripts (Must load before form.js) -->
    <script src="<?= asset_url('js/helpers/jQueryHelpers.js') ?>"></script>
    <script src="<?= asset_url('js/helpers/validationHelpers.js') ?>"></script>

    <!-- Data fasilitas Form Page JS -->
    <script src="<?= asset_url('js/pages/fasilitas/form.js') ?>"></script>

</body>

</html>