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
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Contact Read CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/hubungi-kami/read.css') ?>">

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
                <a href="<?= base_url('admin/hubungi-kami') ?>"> Data Pesan Masuk</a>
                <span>/</span>
                <span>Detail Pesan</span>
            </div>
            <h1 class="page-title">Detail Pesan</h1>
            <p class="page-subtitle">Informasi lengkap dan balasan pesan</p>
        </div>

        <!-- Message Card -->
        <div class="card">
            <div class="card-body">

                <!-- Message Header with Meta Information (Only 2 Items) -->
                <div class="message-header">
                    <div class="message-meta">
                        <!-- Status -->
                        <div class="meta-item">
                            <div class="meta-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Status Pesan</div>
                                <div class="meta-value">
                                    <?php
                                    $badgeClass = $pesan['status'] === 'Baru' ? 'badge-warning' : 'badge-success';
                                    ?>
                                    <span class="badge-custom <?= $badgeClass ?>">
                                        <?= htmlspecialchars($pesan['status']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Tanggal Dikirim -->
                        <div class="meta-item">
                            <div class="meta-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                            </div>
                            <div class="meta-content">
                                <div class="meta-label">Tanggal Dikirim</div>
                                <div class="meta-value"><?= htmlspecialchars(formatTanggal($pesan['created_at'], true)) ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div style="margin-bottom: 2rem;">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        Informasi Detail
                    </h3>

                    <div class="info-row">
                        <div class="info-label">ID Pesan</div>
                        <div class="info-value"><?= htmlspecialchars($pesan['id']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value"><?= htmlspecialchars($pesan['nama_pengirim']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Alamat Email</div>
                        <div class="info-value">
                            <a href="mailto:<?= htmlspecialchars($pesan['email_pengirim']) ?>">
                                <?= htmlspecialchars($pesan['email_pengirim']) ?>
                            </a>
                        </div>
                    </div>

                    <!-- <div class="info-row">
                        <div class="info-label">Dibuat Pada</div>
                        <div class="info-value"><?= htmlspecialchars(formatTanggal($pesan['created_at'], true)) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Diperbarui Pada</div>
                        <div class="info-value"><?= htmlspecialchars(formatTanggal($pesan['updated_at'], true)) ?></div>
                    </div> -->
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
                                    rows="5"
                                    placeholder="Catatan internal untuk admin (tidak akan dikirim ke pengirim)"><?= htmlspecialchars($pesan['catatan_admin'] ?? '') ?></textarea>
                                <small class="text-muted">Catatan ini hanya untuk internal admin, tidak akan dikirim ke pengirim</small>
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

                <!-- Admin Notes Section -->
                <div style="margin-bottom: 2rem;">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        Catatan Admin
                    </h3>

                    <?php if ($pesan['status'] === 'Baru'): ?>
                        <!-- Pesan Belum Dibalas - Show Info Box -->
                        <div class="info-box-custom warning-box">
                            <div class="info-box-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                            </div>
                            <div class="info-box-content">
                                <div class="info-box-title">Pesan Belum Dibalas</div>
                                <div class="info-box-text">
                                    Pesan ini belum mendapat balasan. Silakan balas pesan di atas untuk mengirim email balasan ke pengirim. 
                                    Anda juga dapat menambahkan catatan internal untuk dokumentasi admin.
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Pesan Sudah Dibalas - Show Catatan or Empty State -->
                        <?php if (!empty($pesan['catatan_admin']) && trim($pesan['catatan_admin']) !== ''): ?>
                            <!-- Ada Catatan Admin -->
                            <div class="catatan-box">
                                <?= nl2br(htmlspecialchars($pesan['catatan_admin'])) ?>
                            </div>
                        <?php else: ?>
                            <!-- Tidak Ada Catatan Admin -->
                            <div class="info-box-custom info-box">
                                <div class="info-box-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="12" y1="18" x2="12" y2="12"></line>
                                        <line x1="9" y1="15" x2="15" y2="15"></line>
                                    </svg>
                                </div>
                                <div class="info-box-content">
                                    <div class="info-box-title">Tidak Ada Catatan Internal</div>
                                    <div class="info-box-text">
                                        Tidak ada catatan internal yang ditambahkan untuk pesan ini. 
                                        Catatan internal bersifat opsional dan hanya untuk dokumentasi admin.
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('admin/hubungi-kami') ?>" class="btn-secondary-custom">
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
    <script src="<?= asset_url('js/pages/hubungi-kami/read.js') ?>"></script>
</body>

</html>