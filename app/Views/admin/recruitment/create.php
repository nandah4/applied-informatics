<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?= base_url() ?>">
    <title>Tambah Rekrutment - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Recruitment Form Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/recruitment/form.css') ?>">

    <!-- Form Styles for Image Upload -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/aktivitas-lab/form.css') ?>">

    <!-- Rich Text Editor -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
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
                <a href="<?= base_url('admin/recruitment') ?>">Data Rekrutment</a>
                <span>/</span>
                <span>Tambah Recruitment</span>
            </div>
            <h1 class="page-title">Tambah Rekrutment</h1>
            <p class="page-subtitle">Tambahkan informasi rekrutment baru ke sistem</p>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-body">
                <form id="formRecruitment" method="POST" enctype="multipart/form-data">

                    <?= CsrfHelper::tokenField() ?>

                    <div class="row">
                        <!-- Judul -->
                        <div class="col-md-6 mb-3">
                            <label for="judul" class="form-label">
                                Judul <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="judul" name="judul" placeholder="Contoh: Asisten Praktikum AI" required>
                            <div id="judulError" class="invalid-feedback"></div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">
                                Status <span class="required">*</span>
                            </label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="buka">Buka</option>
                                <option value="tutup">Tutup</option>
                            </select>
                            <div id="statusError" class="invalid-feedback"></div>
                        </div>

                        <!-- Kategori -->
                        <div class="col-md-6 mb-3">
                            <label for="kategori" class="form-label">
                                Kategori <span class="required">*</span>
                            </label>
                            <select class="form-control" id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="asisten lab">Asisten Lab</option>
                                <option value="magang">Magang</option>
                            </select>
                            <div class="helper-text">Jenis posisi yang dibuka</div>
                            <div id="kategoriError" class="invalid-feedback"></div>
                        </div>

                        <!-- Periode -->
                        <div class="col-md-6 mb-3">
                            <label for="periode" class="form-label">
                                Periode <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="periode" name="periode" placeholder="Contoh: Ganjil 2024/2025" required>
                            <div class="helper-text">Periode semester posisi ini berlaku</div>
                            <div id="periodeError" class="invalid-feedback"></div>
                        </div>

                        <!-- Tanggal Buka -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_buka" class="form-label">
                                Tanggal Buka <span class="required">*</span>
                            </label>
                            <input type="date" class="form-control" id="tanggal_buka" name="tanggal_buka" required>
                            <div class="helper-text">Tanggal dimulainya periode rekrutment</div>
                            <div id="tanggalBukaError" class="invalid-feedback"></div>
                        </div>

                        <!-- Tanggal Tutup -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_tutup" class="form-label">
                                Tanggal Tutup <span class="required">*</span>
                            </label>
                            <input type="date" class="form-control" id="tanggal_tutup" name="tanggal_tutup" required>
                            <div class="helper-text">Tanggal berakhirnya periode rekrutment</div>
                            <div id="tanggalTutupError" class="invalid-feedback"></div>
                        </div>

                        <!-- Banner Image -->
                        <div class="col-12 mb-3 d-flex flex-column">
                            <label class="form-label">
                                Banner Image
                            </label>

                            <!-- File Upload -->
                            <div class="file-upload-wrapper" id="fileUploadWrapper">
                                <svg class="file-upload-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                <div class="file-upload-text">
                                    <strong>Klik untuk upload banner</strong> atau drag and drop
                                </div>
                                <div class="file-upload-hint">
                                    PNG, JPG, JPEG maksimal 2MB (Rekomendasi: 1200x400px)
                                </div>
                            </div>
                            <input type="file" class="file-upload-input" id="banner_image" name="banner_image" accept="image/png,image/jpg,image/jpeg">

                            <!-- Image Preview -->
                            <div class="image-preview" id="imagePreview" style="display: none;">
                                <img id="previewImg" src="" alt="Preview">
                                <div class="helper-text" id="helper-text-preview">Klik preview untuk hapus banner</div>
                            </div>
                            <div id="bannerImageError" class="invalid-feedback"></div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-12 mb-deskripsi">
                            <label for="deskripsi" class="form-label">
                                Deskripsi <span class="required">*</span>
                            </label>
                            <div id="editor-deskripsi"></div>
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
                        <button type="submit" id="btn-submit-create-recruitment" class="btn-primary-custom">
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

    <!-- Rich Text Editor -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <script>
        const toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'],
            [{
                'list': 'ordered'
            }, {
                'list': 'bullet'
            }],
            [{
                'header': [3, 4, false]
            }],
            [{
                'align': []
            }],
        ];
        const quill = new Quill('#editor-deskripsi', {
            theme: 'snow',
            placeholder: 'Deskripsikan posisi, tanggung jawab, dan benefit ...',
            modules: {
                toolbar: toolbarOptions
            },
        });
    </script>

    <!-- Data Recruitment Form Page JS -->
    <script src="<?= asset_url('js/pages/recruitment/form.js') ?>"></script>
</body>

</html>