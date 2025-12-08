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
    <link rel="stylesheet" href="<?= asset_url('css/base/variables.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/reset.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Form CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/asisten-lab/form.css') ?>">
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
                <form id="formEditAsistenLab">
                    <!-- Hidden ID -->
                    <input type="hidden" name="id" value="<?= htmlspecialchars($asisten['id']) ?>">

                    <!-- NIM -->
                    <div class="mb-4">
                        <label for="nim" class="form-label">
                            NIM <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="nim" name="nim"
                               value="<?= htmlspecialchars($asisten['nim']) ?>"
                               placeholder="Contoh: 210511001" required>
                        <div id="nimError" class="invalid-feedback"></div>
                    </div>

                    <!-- Nama -->
                    <div class="mb-4">
                        <label for="nama" class="form-label">
                            Nama Lengkap <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" id="nama" name="nama"
                               value="<?= htmlspecialchars($asisten['nama']) ?>"
                               placeholder="Nama lengkap mahasiswa" required>
                        <div id="namaError" class="invalid-feedback"></div>
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="form-label">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control" id="email" name="email"
                               value="<?= htmlspecialchars($asisten['email']) ?>"
                               placeholder="email@example.com" required>
                        <div id="emailError" class="invalid-feedback"></div>
                    </div>

                    <!-- No HP -->
                    <div class="mb-4">
                        <label for="no_hp" class="form-label">
                            No. Handphone
                        </label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp"
                               value="<?= htmlspecialchars($asisten['no_hp'] ?? '') ?>"
                               placeholder="08xxxxxxxxxx">
                        <div id="noHpError" class="invalid-feedback"></div>
                    </div>

                    <!-- Semester -->
                    <div class="mb-4">
                        <label for="semester" class="form-label">
                            Semester <span class="text-danger">*</span>
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

                    <!-- Link Github -->
                    <div class="mb-4">
                        <label for="link_github" class="form-label">
                            Link Github
                        </label>
                        <input type="url" class="form-control" id="link_github" name="link_github"
                               value="<?= htmlspecialchars($asisten['link_github'] ?? '') ?>"
                               placeholder="https://github.com/username">
                        <small class="form-text text-muted">Opsional - Link profil Github Anda</small>
                        <div id="linkGithubError" class="invalid-feedback"></div>
                    </div>

                    <!-- Jabatan Lab -->
                    <div class="mb-4">
                        <label for="jabatan_lab" class="form-label">
                            Jabatan Lab <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="jabatan_lab" name="jabatan_lab" required>
                            <option value="">Pilih jabatan...</option>
                            <option value="Asisten Lab" <?= $asisten['jabatan_lab'] === 'Asisten Lab' ? 'selected' : '' ?>>Asisten Lab</option>
                            <!-- <option value="Koordinator Asisten" <?= $asisten['jabatan_lab'] === 'Koordinator Asisten' ? 'selected' : '' ?>>Koordinator Asisten</option>
                            <option value="Staff Lab" <?= $asisten['jabatan_lab'] === 'Staff Lab' ? 'selected' : '' ?>>Staff Lab</option> -->
                        </select>
                        <div id="jabatanLabError" class="invalid-feedback"></div>
                    </div>

                    <!-- Status Aktif -->
                    <div class="mb-4">
                        <label for="status_aktif" class="form-label">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="status_aktif" name="status_aktif" required>
                            <option value="1" <?= $asisten['status_aktif'] ? 'selected' : '' ?>>Aktif</option>
                            <option value="0" <?= !$asisten['status_aktif'] ? 'selected' : '' ?>>Tidak Aktif</option>
                        </select>
                        <div id="statusAktifError" class="invalid-feedback"></div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 justify-content-end mt-4">
                        <a href="<?= base_url('admin/asisten-lab')?>" class="btn-secondary-custom">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                            Batal
                        </a>
                        <button type="submit" class="btn-primary-custom" id="btnSubmit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                            Simpan Perubahan
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

    <!-- Asisten Lab Edit Page JS -->
    <script src="<?= asset_url('js/pages/asisten-lab/edit.js') ?>"></script>
</body>

</html>
