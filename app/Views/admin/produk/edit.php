<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS - Must load first -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Produk Form Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/produk/form.css') ?>">
</head>

<body>
    <!-- Alert Placeholder -->
    <div id="liveAlertPlaceholder"></div>

    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>

    <?php
    // Dummy data produk untuk edit - will be replaced with actual data from controller
    $produk = [
        'id_produk' => 1,
        'nama_produk' => 'Aplikasi E-Learning',
        'deskripsi' => 'Platform e-learning yang memudahkan proses pembelajaran online dengan fitur lengkap dan user-friendly.',
        'foto_produk' => 'elearning.jpg',
        'link_produk' => 'https://elearning.example.com',
        'author_dosen_id' => 1,
        'author_mahasiswa_nama' => null
    ];

    // Dummy dosen list
    $dummyDosen = [
        ['id' => 1, 'full_name' => 'Dr. John Doe'],
        ['id' => 2, 'full_name' => 'Prof. Jane Smith'],
        ['id' => 3, 'full_name' => 'Dr. Ahmad Abdullah'],
    ];

    $fotoUrl = upload_url('produk/' . $produk['foto_produk']);

    // Tentukan author type berdasarkan data yang ada
    if (!empty($produk['author_dosen_id']) && !empty($produk['author_mahasiswa_nama'])) {
        $authorType = 'kolaborasi';
    } elseif (!empty($produk['author_dosen_id'])) {
        $authorType = 'dosen';
    } else {
        $authorType = 'mahasiswa';
    }
    ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb-custom">
                <a href="<?= base_url('produk') ?>">Data Produk</a>
                <span>/</span>
                <span>Edit Produk</span>
            </div>
            <h1 class="page-title">Edit Produk</h1>
            <p class="page-subtitle">Perbarui data produk yang ada</p>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-body">
                <form id="formEditProduk" method="POST" action="<?= base_url('produk/edit/' . $produk['id_produk']) ?>" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $produk['id_produk'] ?>">

                    <div class="row">
                        <!-- Nama Produk -->
                        <div class="col-12 mb-3">
                            <label for="nama_produk" class="form-label">
                                Nama Produk <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" value="<?= htmlspecialchars($produk['nama_produk']) ?>" placeholder="Masukkan nama produk" required>
                            <div class="helper-text">Berikan nama yang jelas dan deskriptif</div>
                            <div id="namaProdukError" class="invalid-feedback"></div>
                        </div>

                        <!-- Link Produk -->
                        <div class="col-md-6 mb-3">
                            <label for="link_produk" class="form-label">
                                Link Produk
                            </label>
                            <input type="url" class="form-control" id="link_produk" name="link_produk" value="<?= htmlspecialchars($produk['link_produk']) ?>" placeholder="https://example.com">
                            <div class="helper-text">URL lengkap produk (opsional)</div>
                            <div id="linkProdukError" class="invalid-feedback"></div>
                        </div>

                        <!-- Foto Produk -->
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                Foto Produk
                            </label>

                            <!-- Current Photo -->
                            <div class="current-image-wrapper mb-3" id="currentImageWrapper">
                                <div class="current-image-label">Foto saat ini:</div>
                                <img src="<?= $fotoUrl ?>" alt="Foto <?= htmlspecialchars($produk['nama_produk']) ?>" class="current-image" id="currentImage">
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
                                    PNG, JPG, JPEG maksimal 2MB (Rekomendasi: 800x600px)
                                </div>
                            </div>
                            <input type="file" class="file-upload-input" id="foto_produk" name="foto_produk" accept="image/png,image/jpg,image/jpeg">

                            <!-- Image Preview -->
                            <div class="image-preview" id="imagePreview" style="display: none;">
                                <img id="previewImg" src="" alt="Preview">
                                <button type="button" class="btn-remove-preview" id="btnRemovePreview">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            </div>
                            <div id="fotoProdukError" class="invalid-feedback"></div>
                        </div>

                        <!-- Author Type Selection -->
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                Tipe Author <span class="required">*</span>
                            </label>
                            <div class="author-type-selection">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="author_type" id="author_type_dosen" value="dosen" <?= $authorType === 'dosen' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="author_type_dosen">
                                        Dosen
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="author_type" id="author_type_mahasiswa" value="mahasiswa" <?= $authorType === 'mahasiswa' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="author_type_mahasiswa">
                                        Mahasiswa
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="author_type" id="author_type_kolaborasi" value="kolaborasi" <?= $authorType === 'kolaborasi' ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="author_type_kolaborasi">
                                        Kolaborasi (Dosen & Mahasiswa)
                                    </label>
                                </div>
                            </div>
                            <div class="helper-text">Pilih apakah author adalah Dosen, Mahasiswa, atau Kolaborasi keduanya</div>
                        </div>

                        <!-- Author Dosen (Dropdown) -->
                        <div class="col-md-6 mb-3" id="author_dosen_wrapper" style="display: <?= ($authorType === 'dosen' || $authorType === 'kolaborasi') ? 'block' : 'none' ?>;">
                            <label for="author_dosen_id" class="form-label">
                                Pilih Dosen <span class="required" id="dosen_required">*</span>
                            </label>
                            <select class="form-select" id="author_dosen_id" name="author_dosen_id" <?= ($authorType === 'dosen' || $authorType === 'kolaborasi') ? 'required' : '' ?>>
                                <option value="">Pilih Dosen</option>
                                <?php foreach ($dummyDosen as $dosen) : ?>
                                    <option value="<?= $dosen['id'] ?>" <?= $produk['author_dosen_id'] == $dosen['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dosen['full_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="helper-text">Pilih dosen sebagai author produk</div>
                            <div id="authorDosenError" class="invalid-feedback"></div>
                        </div>

                        <!-- Author Mahasiswa (Text Input) -->
                        <div class="col-md-6 mb-3" id="author_mahasiswa_wrapper" style="display: <?= ($authorType === 'mahasiswa' || $authorType === 'kolaborasi') ? 'block' : 'none' ?>;">
                            <label for="author_mahasiswa_nama" class="form-label">
                                Nama Mahasiswa <span class="required" id="mahasiswa_required">*</span>
                            </label>
                            <input type="text" class="form-control" id="author_mahasiswa_nama" name="author_mahasiswa_nama" value="<?= htmlspecialchars($produk['author_mahasiswa_nama'] ?? '') ?>" placeholder="Masukkan nama mahasiswa" <?= ($authorType === 'mahasiswa' || $authorType === 'kolaborasi') ? 'required' : '' ?>>
                            <div class="helper-text">Masukkan nama lengkap mahasiswa</div>
                            <div id="authorMahasiswaError" class="invalid-feedback"></div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-12 mb-3">
                            <label for="deskripsi" class="form-label">
                                Deskripsi Produk
                            </label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="6" placeholder="Masukkan deskripsi produk"><?= htmlspecialchars($produk['deskripsi']) ?></textarea>
                            <div class="helper-text">Jelaskan fitur dan kegunaan produk (opsional)</div>
                            <div id="deskripsiError" class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <a href="<?= base_url('produk') ?>" class="btn-secondary-custom">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" class="btn-primary-custom" id="btn-submit-edit-produk">
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

    <!-- Page Specific Scripts -->
    <script src="<?= asset_url('js/pages/produk/form.js') ?>"></script>

    <script>
        // Initialize feather icons
        if (typeof feather !== "undefined") {
            feather.replace();
        }

        // Author type toggle
        const authorTypeDosen = document.getElementById('author_type_dosen');
        const authorTypeMahasiswa = document.getElementById('author_type_mahasiswa');
        const authorTypeKolaborasi = document.getElementById('author_type_kolaborasi');
        const authorDosenWrapper = document.getElementById('author_dosen_wrapper');
        const authorMahasiswaWrapper = document.getElementById('author_mahasiswa_wrapper');
        const authorDosenSelect = document.getElementById('author_dosen_id');
        const authorMahasiswaInput = document.getElementById('author_mahasiswa_nama');

        function toggleAuthorFields() {
            if (authorTypeDosen.checked) {
                // Hanya Dosen
                authorDosenWrapper.style.display = 'block';
                authorMahasiswaWrapper.style.display = 'none';
                authorDosenSelect.required = true;
                authorMahasiswaInput.required = false;
                authorMahasiswaInput.value = ''; // Clear mahasiswa input
            } else if (authorTypeMahasiswa.checked) {
                // Hanya Mahasiswa
                authorDosenWrapper.style.display = 'none';
                authorMahasiswaWrapper.style.display = 'block';
                authorDosenSelect.required = false;
                authorMahasiswaInput.required = true;
                authorDosenSelect.value = ''; // Clear dosen select
            } else if (authorTypeKolaborasi.checked) {
                // Kolaborasi - tampilkan keduanya
                authorDosenWrapper.style.display = 'block';
                authorMahasiswaWrapper.style.display = 'block';
                authorDosenSelect.required = true;
                authorMahasiswaInput.required = true;
                // Tidak perlu clear karena keduanya dibutuhkan
            }
        }

        authorTypeDosen.addEventListener('change', toggleAuthorFields);
        authorTypeMahasiswa.addEventListener('change', toggleAuthorFields);
        authorTypeKolaborasi.addEventListener('change', toggleAuthorFields);

        // File upload preview
        const fileInput = document.getElementById('foto_produk');
        const fileUploadWrapper = document.getElementById('fileUploadWrapper');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const btnRemovePreview = document.getElementById('btnRemovePreview');
        const currentImageWrapper = document.getElementById('currentImageWrapper');

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
    </script>
</body>

</html>
