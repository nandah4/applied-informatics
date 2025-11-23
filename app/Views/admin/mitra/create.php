<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="/applied-informatics">
    <title>Tambah Mitra - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS - Must load first -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Mitra Form Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/mitra/form.css') ?>">
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
                <a href="<?= base_url('admin/mitra') ?>">Data Mitra</a>
                <span>/</span>
                <span>Tambah Mitra</span>
            </div>
            <h1 class="page-title">Tambah Mitra</h1>
            <p class="page-subtitle">Tambahkan data mitra baru ke sistem</p>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-body">
                <form id="formMitra" method="POST" enctype="multipart/form-data">

                    <?= CsrfHelper::tokenField() ?>

                    <div class="row">
                        <!-- Nama Mitra -->
                        <div class="col-md-6 mb-3">
                            <label for="nama_mitra" class="form-label">
                                Nama Mitra <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="nama_mitra" name="nama_mitra" placeholder="Masukkan nama mitra" required>
                            <div class="helper-text">Berikan nama lengkap mitra</div>
                            <div id="namaMitraError" class="invalid-feedback"></div>
                        </div>

                        <!-- Status Mitra -->
                        <div class="col-md-6 mb-3 flex-col">
                            <label for="status_mitra" class="form-label">
                                Status Mitra
                            </label>
                            <select class="form-select" id="status_mitra" name="status_mitra">
                                <option value="aktif" selected>Aktif</option>
                                <option value="non-aktif">Non Aktif</option>
                            </select>
                            <div id="statusError" class="invalid-feedback"></div>
                        </div>

                        <!-- Kategori Mitra -->
                        <div class="col-md-6 mb-3">
                            <label for="kategori_mitra" class="form-label">
                                Kategori Mitra <span class="required">*</span>
                            </label>
                            <select class="form-select" id="kategori_mitra" name="kategori_mitra" required>
                                <option value="" disabled selected>Pilih Kategori Mitra</option>
                                <option value="industri">Industri</option>
                                <option value="internasional">Internasional</option>
                                <option value="institusi pemerintah">Institusi Pemerintah</option>
                                <option value="institusi pendidikan">Institusi Pendidikan</option>
                                <option value="komunitas">Komunitas</option>
                            </select>
                            <div class="helper-text">Pilih kategori yang sesuai dengan jenis mitra</div>
                            <div id="kategoriMitraError" class="invalid-feedback"></div>
                        </div>

                        <!-- Tanggal Mulai Kerjasama -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_mulai" class="form-label">
                                Tanggal Mulai Kerjasama <span class="required">*</span>
                            </label>
                            <input type="date" class="form-control date-input-native" id="tanggal_mulai" name="tanggal_mulai" required>
                            <div class="helper-text">Tanggal dimulainya kerjasama dengan mitra</div>
                            <div id="tanggalMulaiError" class="invalid-feedback"></div>
                        </div>

                        <!-- Tanggal Akhir Kerjasama -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_akhir" class="form-label">
                                Tanggal Akhir Kerjasama
                            </label>
                            <input type="date" class="form-control date-input-native" id="tanggal_akhir" name="tanggal_akhir">
                            <div class="helper-text">Kosongkan jika kerjasama masih berlangsung</div>
                            <div id="tanggalAkhirError" class="invalid-feedback"></div>
                        </div>

                        <!-- Logo Mitra -->
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                Logo Mitra
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
                                    PNG, JPG, JPEG, SVG maksimal 2MB (Rekomendasi: rasio 1:1)
                                </div>
                            </div>
                            <input type="file" class="file-upload-input" id="logo_mitra" name="logo_mitra" accept="image/png,image/jpg,image/jpeg,image/svg+xml" required>
                            <div class="image-preview" id="imagePreview" style="display: none;">
                                <img id="previewImg" src="" alt="Preview">
                            </div>
                            <div class="helper-text" id="helper-text-preview">Klik preview untuk hapus logo mitra</div>
                            <div id="logoMitraError" class="invalid-feedback"></div>
                        </div>

                        <!-- Deskripsi (Optional) -->
                        <div class="col-12 mb-3">
                            <label for="deskripsi" class="form-label">
                                Deskripsi
                            </label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi singkat tentang mitra"></textarea>
                            <div class="helper-text">Deskripsikan profil dan kerjasama dengan mitra</div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="<?= base_url('admin/mitra') ?>" class="btn-secondary-custom">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" class="btn-primary-custom" id="btn-submit-create-mitra">
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

    <!-- Helper Scripts -->
    <script src="<?= asset_url('js/helpers/jQueryHelpers.js') ?>"></script>
    <script src="<?= asset_url('js/helpers/validationHelpers.js') ?>"></script>

    <!-- Form JS -->
    <script src="<?= asset_url('js/pages/mitra/form.js') ?>"></script>
</body>

</html>