<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Aktivitas - Applied Informatics Laboratory</title>
    <meta name="base-url" content="/applied-informatics">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Rich Text Editor -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Aktivitas Form Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/aktivitas-lab/form.css') ?>">
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
                <a href="<?= base_url('admin/aktivitas-lab') ?>">Aktivitas Laboratorium</a>
                <span>/</span>
                <span>Edit Aktivitas</span>
            </div>
            <h1 class="page-title">Edit Aktivitas</h1>
            <p class="page-subtitle">Perbarui data aktivitas laboratorium</p>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-body">
                <?php
                // Data aktivitas dari controller
                $fotoUrl = $aktivitas['foto_aktivitas'] ? upload_url('aktivitas-lab/' . $aktivitas['foto_aktivitas']) : upload_url('default/image.png');
                ?>

                <form id="formUpdateAktivitas" method="POST" action="<?= base_url('admin/aktivitas-lab/update') ?>" enctype="multipart/form-data">
                    <?= CsrfHelper::tokenField() ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($aktivitas['id']) ?>">

                    <div class="row">
                        <!-- Judul Aktivitas -->
                        <div class="col-12 mb-3">
                            <label for="judul_aktivitas" class="form-label">
                                Judul Aktivitas <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="judul_aktivitas" name="judul" value="<?= htmlspecialchars($aktivitas['judul_aktivitas']) ?>" placeholder="Masukkan judul aktivitas" required>
                            <div class="helper-text">Berikan judul yang jelas dan deskriptif</div>
                            <div id="judulAktivitasError" class="invalid-feedback"></div>
                        </div>

                        <!-- Tanggal Kegiatan -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_kegiatan" class="form-label">
                                Tanggal Kegiatan <span class="required">*</span>
                            </label>
                            <input type="date" class="form-control date-input-native" id="tanggal_kegiatan" name="tanggal_kegiatan" value="<?= $aktivitas['tanggal_kegiatan'] ?>" required>
                            <div class="helper-text">Tanggal pelaksanaan kegiatan</div>
                            <div id="tanggalKegiatanError" class="invalid-feedback"></div>
                        </div>

                        <!-- Foto Aktivitas -->
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                Foto Aktivitas
                            </label>

                            <!-- Current Photo -->
                            <div class="current-image-wrapper mb-3">
                                <img src="<?= $fotoUrl ?>" alt="Foto <?= htmlspecialchars($aktivitas['judul_aktivitas']) ?>" class="current-image" id="currentImage">
                                <div class="helper-text mt-2">Klik area upload di bawah untuk mengganti foto</div>
                            </div>

                            <!-- File Upload -->
                            <div class="file-upload-wrapper" id="fileUploadWrapper">
                                <svg class="file-upload-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                <div class="file-upload-text">
                                    <strong>Klik untuk upload foto baru</strong> atau drag and drop
                                </div>
                                <div class="file-upload-hint">
                                    PNG, JPG, JPEG maksimal 2MB (Rekomendasi: 1200x800px)
                                </div>
                            </div>
                            <input type="file" class="file-upload-input" id="foto_aktivitas" name="foto_aktivitas" accept="image/png,image/jpg,image/jpeg">

                            <!-- Image Preview -->
                            <div class="image-preview" id="imagePreview" style="display: none;">
                                <img id="previewImg" src="" alt="Preview">
                                <div class="helper-text" id="helper-text-preview">Klik preview untuk hapus foto baru aktivitas</div>
                            </div>
                            <div id="fotoAktivitasError" class="invalid-feedback"></div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-12 mb-deskripsi">
                            <label for="deskripsi" class="form-label">
                                Deskripsi Aktivitas <span class="required">*</span>
                            </label>
                            <!-- Create the editor container -->
                            <div id="editor-deskripsi">
                                <?= $aktivitas['deskripsi'] ?>
                            </div>
                            <div class="helper-text">Jelaskan detail aktivitas, tujuan, dan hasil yang dicapai</div>
                            <div id="deskripsiError" class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="<?= base_url('admin/aktivitas-lab') ?>" class="btn-secondary-custom">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" class="btn-primary-custom" id="btn-submit-update-aktivitas">
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

    <!-- Rich Text Editor -->
    <!-- Include the Quill library -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        const toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'],
            [{
                'list': 'ordered'
            }, {
                'list': 'bullet'
            }, {
                'list': 'check'
            }],
            // [{
            //     'script': 'sub'
            // }, {
            //     'script': 'super'
            // }], // superscript/subscript
            // [{
            //     'indent': '-1'
            // }, {
            //     'indent': '+1'
            // }], // outdent/indent
            // [{
            //     'direction': 'rtl'
            // }], // text direction

            [{
                'size': ['small', false, 'large']
            }], // custom dropdown
            [{
                'header': [3, 4, false]
            }],

            // [{
            //     'color': []
            // }, {
            //     'background': []
            // }], // dropdown with defaults from theme
            // [{
            //     'font': []
            // }],
            [{
                'align': []
            }],

            // ['clean'] // remove formatting button
        ];
        const quill = new Quill('#editor-deskripsi', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
        });
    </script>

    <!-- Sidebar JS -->
    <script src="<?= asset_url('js/components/sidebar.js') ?>"></script>

    <!-- Helper Scripts -->
    <script src="<?= asset_url('js/helpers/jQueryHelpers.js') ?>"></script>
    <script src="<?= asset_url('js/helpers/validationHelpers.js') ?>"></script>

    <!-- Page Spesific Scripts -->
    <script src="<?= asset_url('js/pages/aktivitas-lab/edit.js') ?>"></script>
</body>

</html>