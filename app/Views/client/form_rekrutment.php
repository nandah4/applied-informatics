<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Rekrutmen - Applied Informatics Laboratory</title>

    <!-- Base CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/pages/rekrutment-user/form_rekrutment.css') ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<body>

    <div class="main-container">
        <!-- Header Card -->
        <div class="header-card">
            <div class="header-icon">
                <img src="<?= asset_url('images/lab-ai-logo.png') ?>" alt="Lab AI Logo" class="logo">
            </div>
            <h3 class="header-title"><?= htmlspecialchars($recruitmentData['judul']) ?></h3>
            <p class="header-subtitle">Laboratorium Applied Informatics </p>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?= $_SESSION['error_message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <form action="<?= base_url('rekrutment/submit') ?>" method="POST" enctype="multipart/form-data" id="formPendaftaran">

                <input type="hidden" name="rekrutmen_id" value="<?= $rekrutmenId ?? $recruitmentData['id'] ?>">

                <!-- Section 1: Informasi Pribadi -->
                <div class="section-header">
                    <div class="section-number">1</div>
                    <h3 class="section-title">Informasi Pribadi</h3>
                </div>

                <div class="row g-4">
                    <div class="col-12">
                        <label class="form-label">Nama Lengkap<span class="required">*</span></label>
                        <input type="text" class="form-control" name="nama" placeholder="Masukkan nama lengkap sesuai KTM" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email<span class="required">*</span></label>
                        <input type="email" class="form-control" name="email" placeholder="nama@email.com" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Nomor WhatsApp</label>
                        <input type="text" class="form-control" name="no_hp" placeholder="08xxxxxxxxxx">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">NIM<span class="required">*</span></label>
                        <input type="text" class="form-control" name="nim" placeholder="210511001" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Semester<span class="required">*</span></label>
                        <select class="form-select" name="semester" required>
                            <option value="" selected disabled>Pilih semester</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">IPK Terakhir</label>
                        <input type="number" step="0.01" max="4.00" min="0" class="form-control" name="ipk" placeholder="3.50">
                    </div>
                </div>

                <div class="spacer"></div>

                <!-- Section 2: Kompetensi -->
                <div class="section-header">
                    <div class="section-number">2</div>
                    <h3 class="section-title">Kompetensi & Portfolio</h3>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Link Portfolio</label>
                        <input type="url" class="form-control" name="link_portfolio" placeholder="https://portfolio.com/nama">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Link Github</label>
                        <input type="url" class="form-control" name="link_github" placeholder="https://github.com/username">
                    </div>
                </div>

                <div class="spacer"></div>

                <!-- Section 3: Dokumen Pendukung -->
                <div class="section-header">
                    <div class="section-number">3</div>
                    <h3 class="section-title">Dokumen Pendukung</h3>
                </div>

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label">Curriculum Vitae (CV)<span class="required">*</span></label>
                        <div class="file-upload-wrapper">
                            <input type="file" name="file_cv" class="file-input-hidden" accept=".pdf" required onchange="updateFileName(this, 'cv-name')">
                            <label class="file-upload-label">
                                <i class="bi bi-file-earmark-pdf file-upload-icon"></i>
                                <div class="file-upload-text">
                                    <span class="main-text">Klik untuk upload CV</span>
                                    <span class="sub-text">PDF, maksimal 2MB</span>
                                </div>
                                <div id="cv-name" class="file-name"></div>
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Kartu Hasil Studi (KHS)</label>
                        <div class="file-upload-wrapper">
                            <input type="file" name="file_khs" class="file-input-hidden" accept=".pdf" onchange="updateFileName(this, 'khs-name')">
                            <label class="file-upload-label">
                                <i class="bi bi-file-earmark-text file-upload-icon"></i>
                                <div class="file-upload-text">
                                    <span class="main-text">Klik untuk upload KHS</span>
                                    <span class="sub-text">PDF, maksimal 2MB</span>
                                </div>
                                <div id="khs-name" class="file-name"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="spacer"></div>

                <!-- Submit Button -->
                <div class="d-grid">
                    <button type="submit" class="btn-submit">
                        <span>Kirim Pendaftaran</span>
                        <i class="bi bi-arrow-right"></i>
                    </button>
                    <p class="info-text">
                        <i class="bi bi-info-circle me-1"></i>
                        Pastikan semua data sudah benar sebelum mengirim
                    </p>
                </div>

            </form>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?= asset_url('js/jquery.min.js') ?>"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <script>
        // Function untuk update nama file yang diupload
        function updateFileName(input, targetId) {
            const file = input.files[0];
            const target = document.getElementById(targetId);

            if (file) {
                // Cek ukuran file (max 2MB)
                const maxSize = 2 * 1024 * 1024; // 2MB in bytes
                if (file.size > maxSize) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB');
                    input.value = ''; // Reset input
                    target.textContent = '';
                    return;
                }

                // Cek tipe file (harus PDF)
                if (file.type !== 'application/pdf') {
                    alert('File harus berformat PDF!');
                    input.value = ''; // Reset input
                    target.textContent = '';
                    return;
                }

                // Display filename
                target.innerHTML = `<i class="bi bi-check-circle-fill me-2"></i>${file.name}`;
            } else {
                target.textContent = '';
            }
        }

        // Validasi form sebelum submit
        document.getElementById('formPendaftaran').addEventListener('submit', function(e) {
            const fileCV = document.querySelector('input[name="file_cv"]').files[0];

            // Validasi CV wajib diupload
            if (!fileCV) {
                e.preventDefault();
                alert('Curriculum Vitae (CV) wajib diupload!');
                return false;
            }

            // Validasi semester
            const semester = document.querySelector('select[name="semester"]').value;
            if (!semester) {
                e.preventDefault();
                alert('Semester wajib dipilih!');
                return false;
            }

            // Show loading state
            const submitBtn = document.querySelector('.btn-submit');
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
            submitBtn.disabled = true;
        });
    </script>

</body>

</html>