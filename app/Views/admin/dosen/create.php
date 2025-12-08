<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="/applied-informatics">
    <title>Tambah Dosen - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS - Must load first -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Dosen Form Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/dosen/form.css') ?>">
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
                <a href="<?= base_url('admin/dosen') ?>">Data Dosen</a>
                <span>/</span>
                <span>Tambah Dosen</span>
            </div>
            <h1 class="page-title">Tambah Dosen</h1>
            <p class="page-subtitle">Tambahkan data dosen baru ke sistem</p>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-body">
                <form id="formDosen" method="POST" enctype="multipart/form-data">

                    <?= CsrfHelper::tokenField() ?>

                    <div class="row">
                        <!-- Nama Lengkap -->
                        <div class="col-md-6 mb-3">
                            <label for="full_name" class="form-label">
                                Nama Lengkap dan Gelar<span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Masukkan nama lengkap" required>
                            <!-- Error Message -->
                            <div id="fullNameError" class="invalid-feedback"></div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                Email <span class="required">*</span>
                            </label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" required>
                            <!-- Error Message -->
                            <div id="emailError" class="invalid-feedback"></div>
                        </div>

                        <!-- NIDN -->
                        <div class="col-md-6 mb-3">
                            <label for="nidn" class="form-label">
                                NIDN <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="nidn" name="nidn" placeholder="Masukkan NIDN" required>
                            <div class="helper-text">Nomor Induk Dosen Nasional</div>
                            <div id="nidnError" class="invalid-feedback"></div>
                        </div>

                        <!-- NIP  -->
                        <div class="col-md-6 mb-3">
                            <label for="nip" class="form-label">
                                NIP <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="nip" name="nip"
                                placeholder="Masukkan NIP" required>
                            <div class="helper-text">Nomor Induk Pegawai</div>
                            <div id="nipError" class="invalid-feedback"></div>
                        </div>

                        <!-- Jabatan -->
                        <div class="col-md-6 mb-3">
                            <label for="jabatan" class="form-label">
                                Jabatan <span class="required">*</span>
                            </label>

                            <!-- Hidden input for form submission -->
                            <input type="hidden" id="jabatan" name="jabatan" required>

                            <!-- Custom Dropdown -->
                            <div class="custom-dropdown" id="customDropdownJabatan">
                                <div class="custom-dropdown-trigger" id="jabatanTrigger">
                                    <span class="custom-dropdown-text" id="jabatanText">Pilih Jabatan</span>
                                    <svg class="custom-dropdown-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </div>

                                <div class="custom-dropdown-menu" id="jabatanMenu">
                                    <?php if (empty($listJabatan)): ?>
                                        <div class="custom-dropdown-empty">Belum ada jabatan</div>
                                    <?php else: ?>
                                        <?php foreach ($listJabatan as $jab): ?>
                                            <div class="custom-dropdown-item" data-value="<?= $jab['id'] ?>" data-id="<?= $jab['id'] ?>">
                                                <span class="item-text"><?= htmlspecialchars($jab['nama_jabatan']) ?></span>
                                                <button type="button" class="item-delete-btn" data-id="<?= $jab['id'] ?>" data-name="<?= htmlspecialchars($jab['nama_jabatan']) ?>" title="Hapus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div id="jabatanError" class="invalid-feedback"></div>

                            <button type="button" class="btn-add-option" data-bs-toggle="modal" data-bs-target="#modalAddJabatan">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Tambah Jabatan Baru
                            </button>
                        </div>

                        <!-- Keahlian -->
                        <div class="col-12 mb-3">
                            <label for="keahlian" class="form-label">
                                Keahlian <span class="required">*</span>
                            </label>

                            <!-- Hidden input for form submission (multiple values) -->
                            <input type="hidden" id="keahlian" name="keahlian" required>

                            <!-- Selected Keahlian Badges -->
                            <div class="selected-badges" id="selectedKeahlianBadges"></div>

                            <!-- Custom Dropdown -->
                            <div class="custom-dropdown" id="customDropdownKeahlian">
                                <div class="custom-dropdown-trigger" id="keahlianTrigger">
                                    <span class="custom-dropdown-text" id="keahlianText">Pilih Keahlian</span>
                                    <svg class="custom-dropdown-arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </div>

                                <div class="custom-dropdown-menu" id="keahlianMenu">
                                    <?php if (empty($listKeahlian)): ?>
                                        <div class="custom-dropdown-empty">Belum ada keahlian</div>
                                    <?php else: ?>
                                        <?php foreach ($listKeahlian as $skill): ?>
                                            <div class="custom-dropdown-item" data-value="<?= $skill['id'] ?>" data-id="<?= $skill['id'] ?>">
                                                <span class="item-text"><?= htmlspecialchars($skill['nama_keahlian']) ?></span>
                                                <button type="button" class="item-delete-btn" data-id="<?= $skill['id'] ?>" data-name="<?= htmlspecialchars($skill['nama_keahlian']) ?>" title="Hapus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="helper-text">Anda dapat memilih lebih dari satu keahlian</div>
                            <div id="keahlianError" class="invalid-feedback"></div>
                            <button type="button" class="btn-add-option" data-bs-toggle="modal" data-bs-target="#modalAddKeahlian">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                                Tambah Keahlian Baru
                            </button>
                        </div>

                        <!-- Foto Profil -->
                        <div class="col-12 mb-3">
                            <label class="form-label">
                                Foto Profil
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
                            <input type="file" class="file-upload-input" id="photo_profile" name="photo_profile" accept="image/png,image/jpg,image/jpeg">
                            <div class="image-preview" id="imagePreview" style="display: none;">
                                <img id="previewImg" src="" alt="Preview">
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-12 mb-3">
                            <label for="deskripsi" class="form-label">
                                Deskripsi
                            </label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi singkat tentang dosen"></textarea>
                            <div class="helper-text">Deskripsikan pengalaman, spesialisasi, dan pencapaian dosen</div>
                        </div>

                        <!-- Status Aktif -->
                        <div class="col-md-6 mb-3">
                            <label for="status_aktif" class="form-label">
                                Status <span class="required">*</span>
                            </label>
                            <select class="form-select" id="status_aktif" name="status_aktif" required>
                                <option value="1" selected>Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                            <div class="helper-text">Status keaktifan dosen saat ini</div>
                            <div id="statusAktifError" class="invalid-feedback"></div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="<?= base_url("admin/dosen") ?>" class="btn-secondary-custom">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                                Batal
                            </a>
                            <button type="submit" id="btn-submit-create-dosen" class="btn-primary-custom">
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

    <!-- Modal Add Jabatan -->
    <div class="modal fade" id="modalAddJabatan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jabatan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="newJabatan" class="form-label">Nama Jabatan</label>
                    <input type="text" class="form-control" id="newJabatan" placeholder="Masukkan nama jabatan baru">
                    <div id="jabatanError" class="invalid-feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-primary-custom" id="btn-add-new-jabatan">Tambah</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Keahlian -->
    <div class="modal fade" id="modalAddKeahlian" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Keahlian Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="newKeahlian" class="form-label">Nama Keahlian</label>
                    <input type="text" class="form-control" id="newKeahlian" placeholder="Masukkan nama keahlian baru">
                    <div id="keahlianError" class="invalid-feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-primary-custom" id="btn-add-new-keahlian">Tambah</button>
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

    <!-- Helper Scripts (Must load before form.js) -->
    <script src="<?= asset_url('js/helpers/jQueryHelpers.js') ?>"></script>
    <script src="<?= asset_url('js/helpers/validationHelpers.js') ?>"></script>

    <!-- Data Dosen Form Page JS -->
    <script src="<?= asset_url('js/pages/dosen/form.js') ?>"></script>
</body>

</html>