<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dosen - Applied Informatics Laboratory</title>

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
                <span>Edit Dosen</span>
            </div>
            <h1 class="page-title">Edit Dosen</h1>
            <p class="page-subtitle">Edit data dosen yang sudah ada</p>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-body">
                <form id="formDosen" method="POST" enctype="multipart/form-data">
                    <?= CsrfHelper::tokenField() ?>

                    <!-- Hidden Field: ID Dosen -->
                    <input type="hidden" id="dosen_id" name="id" value="<?= htmlspecialchars($dosen['id']) ?>">

                    <div class="row">
                        <!-- Nama Lengkap -->
                        <div class="col-md-6 mb-3">
                            <label for="full_name" class="form-label">
                                Nama Lengkap dan Gelar <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Masukkan nama lengkap" value="<?= htmlspecialchars($dosen['full_name']) ?>" required>
                            <!-- Error Message -->
                            <div id="fullNameError" class="invalid-feedback"></div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                Email <span class="required">*</span>
                            </label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email" value="<?= htmlspecialchars($dosen['email']) ?>" required>
                            <!-- Error Message -->
                            <div id="emailError" class="invalid-feedback"></div>
                        </div>

                        <!-- NIDN -->
                        <div class="col-md-6 mb-3">
                            <label for="nidn" class="form-label">
                                NIDN <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="nidn" name="nidn" placeholder="Masukkan NIDN" value="<?= htmlspecialchars($dosen['nidn']) ?>" required>
                            <div class="helper-text">Nomor Induk Dosen Nasional</div>
                            <div id="nidnError" class="invalid-feedback"></div>
                        </div>

                        <!-- Jabatan -->
                        <div class="col-md-6 mb-3">
                            <label for="jabatan" class="form-label">
                                Jabatan <span class="required">*</span>
                            </label>

                            <!-- Hidden input for form submission -->
                            <input type="hidden" id="jabatan" name="jabatan_id" value="<?= htmlspecialchars($dosen['jabatan_id']) ?>" required>

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
                            <input type="hidden" id="keahlian" name="keahlian_ids" required>

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
                            <input type="file" class="file-upload-input" id="foto_profil" name="foto_profil" accept="image/png,image/jpg,image/jpeg">
                            <div class="image-preview" id="imagePreview" style="display: none;">
                                <img id="previewImg" src="" alt="Preview">
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-12 mb-3">
                            <label for="deskripsi" class="form-label">
                                Deskripsi
                            </label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" placeholder="Masukkan deskripsi singkat tentang dosen"><?= htmlspecialchars($dosen['deskripsi'] ?? '') ?></textarea>
                            <div class="helper-text">Deskripsikan pengalaman, spesialisasi, dan pencapaian dosen</div>
                        </div>

                        <!-- Status Aktif -->
                        <div class="col-md-6 mb-3">
                            <label for="status_aktif" class="form-label">
                                Status <span class="required">*</span>
                            </label>
                            <select class="form-select" id="status_aktif" name="status_aktif" required>
                                <option value="1" <?= (isset($dosen['status_aktif']) && $dosen['status_aktif']) ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= (isset($dosen['status_aktif']) && !$dosen['status_aktif']) ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>
                            <div class="helper-text">Status keaktifan dosen saat ini</div>
                            <div id="statusAktifError" class="invalid-feedback"></div>
                        </div>

                        <!-- Divider -->
                        <div class="col-12 mb-3">
                            <hr class="form-divider">
                        </div>

                        <!-- Section: Profil Publikasi -->
                        <div class="col-12">
                            <div class="col-12 mb-3">
                                <div class="section-header">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                    </svg>
                                    <h5 class="section-title-form">Profil Publikasi</h5>
                                </div>
                                <p class="section-subtitle-form">Link ke profil publikasi dosen</p>
                            </div>

                            <!-- Container untuk list profil publikasi -->
                            <div id="profilPublikasiContainer" class="col-12">
                                <?php if (empty($listProfilPublikasi)): ?>
                                    <p class="text-muted">Belum ada profil publikasi.</p>
                                <?php else: ?>
                                    <?php
                                    $tipeLabels = [
                                        'SINTA' => 'SINTA',
                                        'SCOPUS' => 'Scopus',
                                        'GOOGLE_SCHOLAR' => 'Google Scholar',
                                        'ORCID' => 'ORCID',
                                        'RESEARCHGATE' => 'ResearchGate'
                                    ];

                                    foreach ($listProfilPublikasi as $profil):
                                        $label = $tipeLabels[$profil['tipe']] ?? $profil['tipe'];
                                    ?>
                                        <div class="profil-publikasi-item d-flex justify-content-between
  align-items-center mb-2 p-3 rounded">
                                            <div>
                                                <strong><?= htmlspecialchars($label) ?></strong>
                                                <br>
                                                <a href="<?= htmlspecialchars($profil['url_profil']) ?>" target="_blank"
                                                    class="text-truncate small">
                                                    <?= htmlspecialchars($profil['url_profil']) ?>
                                                </a>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn-primary-custom"
                                                    onclick="editProfilPublikasi(<?= $profil['id'] ?>, '<?= htmlspecialchars($profil['url_profil'], ENT_QUOTES) ?>', '<?= htmlspecialchars($label) ?>')">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn-danger-custom"
                                                    onclick="deleteProfilPublikasi(<?= $profil['id'] ?>)">
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <!-- Button Tambah Profil -->
                            <div class="col-12 mb-3">
                                <button type="button" class="btn-add-option" data-bs-toggle="modal" data-bs-target="#modalAddProfilPublikasi">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                    </svg>
                                    Tambah Profil Publikasi
                                </button>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="<?= base_url('admin/dosen') ?>" class="btn-secondary-custom">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                                Batal
                            </a>
                            <button type="submit" id="btn-submit-update-dosen" class="btn-primary-custom">
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

    <!-- Modal Add Profil Publikasi -->
    <div class="modal fade" id="modalAddProfilPublikasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Profil Publikasi Dosen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="newProfilPublikasi" class="form-label">Link Profil <span class="required">*</span></label>
                    <input type="text" class="form-control" id="newProfilPublikasi" placeholder="Masukkan link profil publikasi">
                    <div id="linkPublikasiError" class="invalid-feedback"></div>

                    <label for="tipeProfilPublikasi" class="form-label mt-3">Tipe Profil Publikasi <span class="required">*</span></label>
                    <select id="tipeProfilPublikasi" class="form-select" aria-label="Default select example">
                        <option selected value="">Pilih Profil Publikasi</option>
                        <option value="SINTA">SINTA</option>
                        <option value="SCOPUS">SCOPUS</option>
                        <option value="GOOGLE_SCHOLAR">GOOGLE SCHOLAR</option>
                        <option value="ORCID">ORCID</option>
                        <option value="RESEARCHGATE">RESEARCHGATE</option>
                    </select>
                    <div id="selectedTipePublikasiError" class="invalid-feedback"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-primary-custom" id="btn-add-new-profil-publikasi">Tambah</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Profil Publikasi -->
    <div class="modal fade" id="modalEditProfilPublikasi" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Profil Publikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editProfilId">
                    <div class="mb-3">
                        <label class="form-label">Tipe Profil</label>
                        <input type="text" class="form-control" id="editProfilTipe" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editProfilUrl" class="form-label">URL Profil <span class="required">*</span></label>
                        <input type="url" class="form-control" id="editProfilUrl" placeholder="https://...">
                        <div id="editProfilUrlError" class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-primary-custom" id="btn-update-profil-publikasi">Update</button>
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

    <!-- Helper Scripts (Must load before edit.js) -->
    <script src="<?= asset_url('js/helpers/jQueryHelpers.js') ?>"></script>
    <script src="<?= asset_url('js/helpers/validationHelpers.js') ?>"></script>

    <!-- Setup Edit Mode Data -->
    <script>
        // Data dosen untuk edit mode (dari PHP)
        window.EDIT_MODE = true;
        window.DOSEN_DATA = {
            id: <?= json_encode($dosen['id']) ?>,
            full_name: <?= json_encode($dosen['full_name']) ?>,
            email: <?= json_encode($dosen['email']) ?>,
            nidn: <?= json_encode($dosen['nidn']) ?>,
            jabatan_id: <?= json_encode($dosen['jabatan_id']) ?>,
            jabatan_name: <?= json_encode($dosen['jabatan_name']) ?>,
            keahlian_list: <?= json_encode($dosen['keahlian_list'] ?? '') ?>,
            foto_profil: <?= json_encode($dosen['foto_profil']) ?>,
            deskripsi: <?= json_encode($dosen['deskripsi'] ?? '') ?>
        };
    </script>

    <!-- Data Dosen Edit Page JS (edit-specific logic, includes keahlian dropdown) -->
    <script src="<?= asset_url('js/pages/dosen/edit.js') ?>"></script>
</body>

</html>