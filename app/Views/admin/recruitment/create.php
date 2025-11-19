<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Recruitment - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS - Must load first -->
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
    <?php
    include __DIR__ . '/../../layouts/sidebar.php';
    ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb-custom">
                <a href="<?= base_url('recruitment') ?>">Data Recruitment</a>
                <span>/</span>
                <span>Tambah Recruitment</span>
            </div>
            <h1 class="page-title">Tambah Recruitment</h1>
            <p class="page-subtitle">Tambahkan informasi recruitment baru ke sistem</p>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-body">
                <form id="formRecruitment" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Posisi -->
                        <div class="col-md-6 mb-3">
                            <label for="posisi" class="form-label">
                                Posisi <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="posisi" name="posisi" placeholder="Contoh: Asisten Praktikum AI" required>
                            <!-- Error Message -->
                            <div id="posisiError" class="invalid-feedback"></div>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">
                                Status <span class="required">*</span>
                            </label>
                            <select class="form-control" id="status" name="status" required>
                                <option value="">Pilih Status</option>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                            <div id="statusError" class="invalid-feedback"></div>
                        </div>

                        <!-- Tanggal Mulai -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_mulai" class="form-label">
                                Tanggal Mulai <span class="required">*</span>
                            </label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                            <div class="helper-text">Tanggal dimulainya periode recruitment</div>
                            <div id="tanggalMulaiError" class="invalid-feedback"></div>
                        </div>

                        <!-- Tanggal Berakhir -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_berakhir" class="form-label">
                                Tanggal Berakhir <span class="required">*</span>
                            </label>
                            <input type="date" class="form-control" id="tanggal_berakhir" name="tanggal_berakhir" required>
                            <div class="helper-text">Tanggal berakhirnya periode recruitment</div>
                            <div id="tanggalBerakhirError" class="invalid-feedback"></div>
                        </div>

                        <!-- Link Pendaftaran -->
                        <div class="col-12 mb-3">
                            <label for="link_pendaftaran" class="form-label">
                                Link Pendaftaran
                            </label>
                            <input type="url" class="form-control" id="link_pendaftaran" name="link_pendaftaran" placeholder="https://forms.google.com/...">
                            <div class="helper-text">Link formulir atau halaman pendaftaran</div>
                            <div id="linkPendaftaranError" class="invalid-feedback"></div>
                        </div>

                        <!-- Gambar Banner -->
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                Gambar Banner
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
                            <input type="file" class="file-upload-input" id="gambar_banner" name="gambar_banner" accept="image/png,image/jpg,image/jpeg">
                            <div class="image-preview" id="imagePreview" style="display: none;">
                                <img id="previewImg" src="" alt="Preview">
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-12 mb-3">
                            <label for="deskripsi" class="form-label">
                                Deskripsi <span class="required">*</span>
                            </label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" placeholder="Masukkan deskripsi singkat tentang posisi recruitment" required></textarea>
                            <div class="helper-text">Deskripsikan posisi, tanggung jawab, dan benefit</div>
                            <div id="deskripsiError" class="invalid-feedback"></div>
                        </div>

                        <!-- Persyaratan -->
                        <div class="col-12 mb-3">
                            <label for="persyaratan" class="form-label">
                                Persyaratan
                            </label>
                            <textarea class="form-control" id="persyaratan" name="persyaratan" rows="5" placeholder="Masukkan persyaratan untuk posisi ini (pisahkan dengan enter untuk setiap poin)"></textarea>
                            <div class="helper-text">Tuliskan persyaratan atau kualifikasi yang dibutuhkan</div>
                            <div id="persyaratanError" class="invalid-feedback"></div>
                        </div>

                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="<?= base_url('recruitment') ?>" class="btn-secondary-custom">
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

    <!-- Helper Scripts (Must load before form.js) -->
    <script src="<?= asset_url('js/helpers/jQueryHelpers.js') ?>"></script>
    <script src="<?= asset_url('js/helpers/validationHelpers.js') ?>"></script>

    <!-- Data Recruitment Form Page JS -->
    <script src="<?= asset_url('js/pages/recruitment/form.js') ?>"></script>
</body>

</html>
