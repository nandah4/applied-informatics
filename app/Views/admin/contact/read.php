<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="/applied-informatics">
    <meta name="csrf-token" content="<?= CsrfHelper::generateToken() ?>">
    <title>Detail Pesan - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Base CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/variables.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/reset.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Contact Read CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/contact/read.css') ?>">

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
                <a href="<?= base_url('admin/contact') ?>">Pesan Masuk</a>
                <span>/</span>
                <span>Detail Pesan</span>
            </div>
            <h1 class="page-title">Detail Pesan</h1>
            <p class="page-subtitle">Informasi lengkap dan balasan pesan</p>
        </div>

        <!-- Message Card -->
        <div class="card">
            <div class="card-body">

                <!-- Status Badge -->
                <div style="margin-bottom: 2rem;">
                    <?php
                    $badgeClass = $pesan['status'] === 'Baru' ? 'badge-warning' : 'badge-success';
                    ?>
                    <span class="badge-custom-large <?= $badgeClass ?>">
                        <?= htmlspecialchars($pesan['status']) ?>
                    </span>
                </div>

                <!-- Basic Information -->
                <div style="margin-bottom: 2rem;">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        Informasi Pengirim
                    </h3>

                    <div class="info-row">
                        <div class="info-label">ID Pesan</div>
                        <div class="info-value"><?= htmlspecialchars($pesan['id']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Nama Pengirim</div>
                        <div class="info-value"><?= htmlspecialchars($pesan['nama_pengirim']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Email Pengirim</div>
                        <div class="info-value">
                            <a href="mailto:<?= htmlspecialchars($pesan['email_pengirim']) ?>">
                                <?= htmlspecialchars($pesan['email_pengirim']) ?>
                            </a>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Tanggal Dikirim</div>
                        <div class="info-value"><?= htmlspecialchars(formatTanggal($pesan['created_at'], true)) ?></div>
                    </div>
                </div>

                <!-- Message Content -->
                <div style="margin-bottom: 2rem;">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        Isi Pesan
                    </h3>

                    <div class="message-box">
                        <?= nl2br(htmlspecialchars($pesan['isi_pesan'])) ?>
                    </div>
                </div>

                <!-- Reply Section (if already replied) -->
                <?php if ($pesan['status'] === 'Dibalas'): ?>
                    <div style="margin-bottom: 2rem;">
                        <h3 class="section-title">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 17 4 12 9 7"></polyline>
                                <path d="M20 18v-2a4 4 0 0 0-4-4H4"></path>
                            </svg>
                            Balasan Email
                        </h3>

                        <div class="reply-box">
                            <?= $pesan['balasan_email'] ?>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Dibalas Oleh</div>
                            <div class="info-value"><?= htmlspecialchars($pesan['admin_email'] ?? 'Admin') ?></div>
                        </div>

                        <div class="info-row">
                            <div class="info-label">Tanggal Dibalas</div>
                            <div class="info-value"><?= htmlspecialchars(formatTanggal($pesan['tanggal_dibalas'], true)) ?></div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Reply Form (only if not replied yet) -->
                <?php if ($pesan['status'] === 'Baru'): ?>
                    <div style="margin-bottom: 2rem;">
                        <h3 class="section-title">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="9 17 4 12 9 7"></polyline>
                                <path d="M20 18v-2a4 4 0 0 0-4-4H4"></path>
                            </svg>
                            Balas Pesan
                        </h3>

                        <form id="formBalasPesan">
                            <input type="hidden" name="pesan_id" value="<?= $pesan['id'] ?>">

                            <div class="form-group mb-3">
                                <label for="balasan-editor" class="form-label">Balasan Email *</label>
                                <div id="balasan-editor"></div>
                                <input type="hidden" name="balasan_email" id="balasanInput">
                                <small class="text-muted">Format balasan email yang akan dikirim ke pengirim</small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="catatan_admin" class="form-label">Catatan Internal (Opsional)</label>
                                <textarea 
                                    class="form-control" 
                                    id="catatan_admin" 
                                    name="catatan_admin" 
                                    rows="3"
                                    placeholder="Catatan internal untuk admin (tidak akan dikirim ke pengirim)"><?= htmlspecialchars($pesan['catatan_admin'] ?? '') ?></textarea>
                            </div>

                            <button type="submit" class="btn-primary-custom" id="btnBalasPesan">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="22" y1="2" x2="11" y2="13"></line>
                                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                                </svg>
                                Kirim Balasan
                            </button>
                        </form>
                    </div>
                <?php endif; ?>

                <!-- Admin Notes (can always be updated) -->
                <div style="margin-bottom: 2rem;">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Catatan Admin
                    </h3>

                    <?php if ($pesan['status'] === 'Dibalas'): ?>
                        <div class="catatan-box">
                            <?= !empty($pesan['catatan_admin']) ? nl2br(htmlspecialchars($pesan['catatan_admin'])) : '<em class="text-muted">Tidak ada catatan</em>' ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('admin/contact') ?>" class="btn-secondary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Kembali
                    </a>
                    <button class="btn-danger-custom" onclick="confirmDelete(<?= $pesan['id'] ?>)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        Hapus Pesan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?= asset_url('js/jquery.min.js') ?>"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <!-- Sidebar JS -->
    <script src="<?= asset_url('js/components/sidebar.js') ?>"></script>

    <!-- Helper Scripts -->
    <script src="<?= asset_url('js/helpers/jQueryHelpers.js') ?>"></script>

    <!-- Quill Editor JS -->
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

    <!-- Contact Read Page JS -->
    <script src="<?= asset_url('js/pages/contact/read.js') ?>"></script>
</body>

</html>