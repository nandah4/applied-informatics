<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="/applied-informatics">
    <meta name="csrf-token" content="<?= CsrfHelper::generateToken() ?>">
    <title>Detail Pendaftar - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS - Must load first -->
    <link rel="stylesheet" href="<?= asset_url('css/base/variables.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/reset.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Dosen Read Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/pendaftar/read.css') ?>">

    <!-- Quill Editor CSS -->
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
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
                <a href="<?= base_url('admin/daftar-pendaftar') ?>">Data Pendaftar</a>
                <span>/</span>
                <span>Detail Pendaftar</span>
            </div>
            <h1 class="page-title">Detail Pendaftar</h1>
            <p class="page-subtitle">Informasi lengkap tentang pendaftar</p>
        </div>

        <!-- Profile Card -->
        <div class="card">
            <div class="card-body">

                <!-- Basic Information -->
                <div style="margin-bottom: 2rem;">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Informasi Dasar Pendaftar
                    </h3>
                    <div class="info-row">
                        <div class="info-label">ID Pendaftar</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftar['id']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">ID Rekrutmen</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftar['rekrutmen_id']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Judul Rekrutmen</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftar['judul_rekrutmen']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">NIM</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftar['nim']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Nama</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftar['nama']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftar['email']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">No. Handphone</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftar['no_hp']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Semester</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftar['semester']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">IPK</div>
                        <div class="info-value"><?= htmlspecialchars($pendaftar['ipk']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Link Portfolio</div>
                        <div class="info-value">
                            <?php if (empty($pendaftar['link_portfolio'])): ?>
                                <span class="fw-bold">-</span>
                            <?php else: ?>
                                <a href="<?= htmlspecialchars($pendaftar['link_portfolio']) ?>" target="_blank">
                                    <?= htmlspecialchars($pendaftar['link_portfolio'] ? 'Lihat Portofolio' : '') ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Link Github</div>
                        <div class="info-value">
                            <?php if (empty($pendaftar['link_github'])): ?>
                                <span class="fw-bold">-</span>
                            <?php else: ?>
                                <a href="<?= htmlspecialchars($pendaftar['link_github']) ?>" target="_blank">
                                    <?= htmlspecialchars($pendaftar['link_github'] ? 'Lihat Github' : '') ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">File CV</div>
                        <div class="info-value">
                            <button type="button" class="info-value" data-bs-toggle="modal" data-bs-target="#staticCV">
                                Lihat CV
                            </button>

                            <div class="modal fade" id="staticCV" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <iframe src="<?= upload_url('cv/' . $pendaftar['file_cv']) ?>"
                                                width="100%"
                                                height="600vh"></iframe>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">File KHS</div>
                        <div class="info-value">
                            <button type="button" class="info-value" data-bs-toggle="modal" data-bs-target="#staticKHS">
                                Lihat KHS
                            </button>

                            <div class="modal fade" id="staticKHS" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <iframe src="<?= upload_url('khs/' . $pendaftar['file_khs']) ?>"
                                                width="100%"
                                                height="600vh"></iframe>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Status Seleksi</div>
                        <div class="info-value">
                            <form id="formUpdateStatus">
                                <input type="hidden" name="pendaftar_id" value="<?= $pendaftar['id'] ?>">

                                <select name="status_seleksi" class="form-select info-value w-50" id="selectStatusSeleksi">
                                    <option value="Pending" <?= $pendaftar['status_seleksi'] === 'Pending' ? 'selected' : '' ?>>
                                        Pending
                                    </option>
                                    <option value="Diterima" <?= $pendaftar['status_seleksi'] === 'Diterima' ? 'selected' : '' ?>>
                                        Diterima
                                    </option>
                                    <option value="Ditolak" <?= $pendaftar['status_seleksi'] === 'Ditolak' ? 'selected' : '' ?>>
                                        Ditolak
                                    </option>
                                </select>

                                <!-- Feedback Editor (shown only when Ditolak is selected) -->
                                <div class="feedback-container" id="feedbackContainer">
                                    <label for="feedback-editor">Alasan Penolakan</label>
                                    <div id="feedback-editor"></div>
                                    <input type="hidden" name="deskripsi" id="deskripsiInput">
                                </div>

                                <!-- Info Box untuk Status Behavior -->
                                <div class="col-12 mt-4">
                                    <div style="background: #fffbeb; border-left: 4px solid #f59e0b; padding: 1rem; border-radius: 8px;">
                                        <div style="display: flex; gap: 0.75rem; align-items: start;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink: 0; margin-top: 2px;">
                                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                            </svg>
                                            <div>
                                                <strong style="color: #92400e; font-size: 0.875rem;">Penting: Aksi Bersifat Final (Satu Arah)</strong>
                                                <ul style="color: #92400e; font-size: 0.8125rem; margin-top: 5px; padding-left: 0px; line-height: 1.6;">
                                                    <li>
                                                        <strong>Promosi Otomatis:</strong> Memilih status <b>Diterima</b> akan memicu sistem untuk memindahkan data pendaftar ke Database Anggota secara permanen.
                                                    </li>
                                                    <li>
                                                        <strong>Tidak Dapat Dibatalkan:</strong> Status yang sudah diubah menjadi <b>Diterima</b> atau <b>Ditolak</b> tidak dapat dikembalikan menjadi <i>Pending</i>.
                                                    </li>
                                                    <li>
                                                        <strong>Keputusan Mutlak:</strong> Pastikan hasil wawancara/seleksi sudah final sebelum menekan tombol update.
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn-primary-custom mt-3" id="btnUpdateStatus">
                                    Update Status
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Dibuat Pada</div>
                        <div class="info-value"><?= htmlspecialchars(formatTanggal($pendaftar['created_at'], true)) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Diperbarui Pada</div>
                        <div class="info-value"><?= htmlspecialchars(formatTanggal($pendaftar['updated_at'], true)) ?></div>
                    </div>

                </div>


                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('admin/daftar-pendaftar') ?>" class="btn-secondary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Kembali
                    </a>
                    <button class="btn-danger-custom" onclick="confirmDelete(<?= $pendaftar['id'] ?>)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        Hapus Data
                    </button>
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

    <!-- Helper Scripts -->
    <script src="<?= asset_url('js/helpers/jQueryHelpers.js') ?>"></script>
    <script src="<?= asset_url('js/helpers/validationHelpers.js') ?>"></script>

    <!-- Quill Editor JS -->
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

    <!-- Data Dosen Read Page JS -->
    <script src="<?= asset_url('js/pages/pendaftar/read.js') ?>"></script>
</body>

</html>