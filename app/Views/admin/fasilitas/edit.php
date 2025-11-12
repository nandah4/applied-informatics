<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Fasilitas - Applied Informatics Laboratory</title>

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
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb-custom">
                <a href="<?= base_url('fasilitas') ?>">Data Fasilitas</a>
                <span>/</span>
                <span>Edit Fasilitas</span>
            </div>
            <h1 class="page-title">Edit Fasilitas</h1>
            <p class="page-subtitle">Perbarui data fasilitas yang ada</p>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-body">
                <form id="formFasilitas" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="1">

                    <div class="row">
                        <!-- Nama Fasilitas -->
                        <div class="col-12 mb-3">
                            <label for="nama_fasilitas" class="form-label">
                                Nama Fasilitas <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="nama_fasilitas" name="nama_fasilitas" placeholder="Masukkan nama fasilitas" value="Komputer Lab A" required>
                            <div class="helper-text">Berikan nama yang jelas dan deskriptif untuk fasilitas</div>
                        </div>

                        <!-- Foto Fasilitas -->
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                Foto Fasilitas
                            </label>

                            <!-- Current Image -->
                            <div class="current-image-wrapper mb-3">
                                <div class="current-image-label">Foto saat ini:</div>
                                <img src="https://via.placeholder.com/400x300/01b5b9/ffffff?text=Lab+A" alt="Current Photo" class="current-image" id="currentImage">
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
                                    PNG, JPG, JPEG maksimal 5MB
                                </div>
                            </div>
                            <input type="file" class="file-upload-input" id="foto_fasilitas" name="foto_fasilitas" accept="image/png,image/jpg,image/jpeg">
                            <div class="image-preview" id="imagePreview" style="display: none;">
                                <img id="previewImg" src="" alt="Preview">
                                <button type="button" class="btn-remove-preview" id="btnRemovePreview">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
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
                        <button type="submit" class="btn-primary-custom">
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

    <script>
        // File upload preview
        const fileInput = document.getElementById('foto_fasilitas');
        const fileUploadWrapper = document.getElementById('fileUploadWrapper');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const btnRemovePreview = document.getElementById('btnRemovePreview');
        const currentImageWrapper = document.querySelector('.current-image-wrapper');

        fileUploadWrapper.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                    fileUploadWrapper.style.display = 'none';
                    if (currentImageWrapper) {
                        currentImageWrapper.style.display = 'none';
                    }
                }
                reader.readAsDataURL(file);
            }
        });

        // Remove preview
        btnRemovePreview.addEventListener('click', function(e) {
            e.stopPropagation();
            fileInput.value = '';
            imagePreview.style.display = 'none';
            fileUploadWrapper.style.display = 'flex';
            if (currentImageWrapper) {
                currentImageWrapper.style.display = 'block';
            }
        });

        // Form submit
        document.getElementById('formFasilitas').addEventListener('submit', function(e) {
            e.preventDefault();
            // Add your form submission logic here
            console.log('Form submitted');
            alert('Data fasilitas berhasil diupdate!');
        });
    </script>
</body>

</html>
