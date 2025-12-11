<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="/applied-informatics">
    <title>Edit Asisten Lab - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Form CSS (reuse dosen form styles) -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/dosen/form.css') ?>">
</head>

<body>
    <div id="liveAlertPlaceholder"></div>

    <!-- CSRF Token -->
    <?= CsrfHelper::tokenField() ?>

    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb-custom">
                <a href="<?= base_url('admin/asisten-lab') ?>">Data Asisten Lab</a>
                <span>/</span>
                <span>Edit Asisten Lab</span>
            </div>
            <h1 class="page-title">Edit Data Asisten Lab</h1>
            <p class="page-subtitle">Update informasi asisten laboratorium</p>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-body">
                <form id="formEditAsistenLab" method="POST">
                    <!-- Hidden ID -->
                    <input type="hidden" name="id" value="<?= htmlspecialchars($asisten['id']) ?>">

                    <div class="row">
                        <!-- NIM -->
                        <div class="col-md-6 mb-3">
                            <label for="nim" class="form-label">
                                NIM <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="nim" name="nim"
                                value="<?= htmlspecialchars($asisten['nim']) ?>"
                                placeholder="Contoh: 210511001" required>
                            <div id="nimError" class="invalid-feedback"></div>
                        </div>

                        <!-- Nama -->
                        <div class="col-md-6 mb-3">
                            <label for="nama" class="form-label">
                                Nama Lengkap <span class="required">*</span>
                            </label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                value="<?= htmlspecialchars($asisten['nama']) ?>"
                                placeholder="Nama lengkap mahasiswa" required>
                            <div id="namaError" class="invalid-feedback"></div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                Email <span class="required">*</span>
                            </label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?= htmlspecialchars($asisten['email']) ?>"
                                placeholder="email@example.com" required>
                            <div id="emailError" class="invalid-feedback"></div>
                        </div>

                        <!-- No HP -->
                        <div class="col-md-6 mb-3">
                            <label for="no_hp" class="form-label">
                                No. Handphone
                            </label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp"
                                value="<?= htmlspecialchars($asisten['no_hp'] ?? '') ?>"
                                placeholder="08xxxxxxxxxx">
                            <div id="noHpError" class="invalid-feedback"></div>
                        </div>

                        <!-- Semester -->
                        <div class="col-md-6 mb-3">
                            <label for="semester" class="form-label">
                                Semester <span class="required">*</span>
                            </label>
                            <select class="form-select" id="semester" name="semester" required>
                                <option value="">Pilih semester...</option>
                                <?php for ($i = 1; $i <= 8; $i++): ?>
                                    <option value="<?= $i ?>" <?= (isset($asisten['semester']) && $asisten['semester'] == $i) ? 'selected' : '' ?>>
                                        Semester <?= $i ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                            <div id="semesterError" class="invalid-feedback"></div>
                        </div>

                        <!-- Tipe Anggota -->
                        <div class="col-md-6 mb-3">
                            <label for="tipe_anggota" class="form-label">
                                Tipe Anggota <span class="required">*</span>
                            </label>
                            <select class="form-select" id="tipe_anggota" name="tipe_anggota" required>
                                <option value="">Pilih tipe...</option>
                                <option value="asisten lab" <?= ($asisten['tipe_anggota'] ?? '') === 'asisten lab' ? 'selected' : '' ?>>Asisten Lab</option>
                                <option value="magang" <?= ($asisten['tipe_anggota'] ?? '') === 'magang' ? 'selected' : '' ?>>Magang</option>
                            </select>
                            <div class="helper-text">Kategori keanggotaan di laboratorium</div>
                            <div id="tipeAnggotaError" class="invalid-feedback"></div>
                        </div>

                        <!-- Periode Aktif -->
                        <div class="col-md-6 mb-3">
                            <label for="periode_aktif" class="form-label">
                                Periode Aktif
                            </label>
                            <input type="text" class="form-control" id="periode_aktif" name="periode_aktif"
                                value="<?= htmlspecialchars($asisten['periode_aktif'] ?? '') ?>"
                                placeholder="Contoh: Ganjil 2024/2025">
                            <div class="helper-text">Periode semester saat menjadi anggota</div>
                            <div id="periodeAktifError" class="invalid-feedback"></div>
                        </div>

                        <!-- Status Aktif -->
                        <div class="col-md-6 mb-3">
                            <label for="status_aktif" class="form-label">
                                Status <span class="required">*</span>
                            </label>
                            <select class="form-select" id="status_aktif" name="status_aktif" required>
                                <option value="1" <?= $asisten['status_aktif'] ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= !$asisten['status_aktif'] ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>
                            <div class="helper-text">Status keaktifan anggota saat ini</div>
                            <div id="statusAktifError" class="invalid-feedback"></div>
                        </div>

                        <!-- Divider -->
                        <div class="col-12 mb-3">
                            <hr class="form-divider">
                        </div>

                        <!-- Section: Informasi Tambahan -->
                        <div class="col-12 mb-3">
                            <div class="section-header">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                                <h5 class="section-title-form">Informasi Tambahan</h5>
                            </div>
                            <p class="section-subtitle-form">Data opsional tentang anggota</p>
                        </div>

                        <!-- Link Github -->
                        <div class="col-md-6 mb-3">
                            <label for="link_github" class="form-label">
                                Link Github
                            </label>
                            <input type="url" class="form-control" id="link_github" name="link_github"
                                value="<?= htmlspecialchars($asisten['link_github'] ?? '') ?>"
                                placeholder="https://github.com/username">
                            <div class="helper-text">Link profil Github (opsional)</div>
                            <div id="linkGithubError" class="invalid-feedback"></div>
                        </div>

                        <!-- Tanggal Selesai -->
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_selesai" class="form-label">
                                Tanggal Selesai
                            </label>
                            <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai"
                                value="<?= htmlspecialchars($asisten['tanggal_selesai'] ?? '') ?>">
                            <div class="helper-text">Isi jika anggota sudah selesai/alumni</div>
                            <div id="tanggalSelesaiError" class="invalid-feedback"></div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="<?= base_url('admin/asisten-lab') ?>" class="btn-secondary-custom">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                                Batal
                            </a>
                            <button type="submit" id="btnSubmit" class="btn-primary-custom">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
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

    <!-- Asisten Lab Edit Page JS -->
    <script src="<?= asset_url('js/pages/asisten-lab/edit.js') ?>"></script>
</body>

</html>