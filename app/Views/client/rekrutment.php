<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';
?>


<main class="rekrutmen-page">

    <div class="container-fluid px-5 pb-5">

        <div class="breadcrumb-nav">
            <span class="breadcrumb-item">Laboratorium Applied Informatics</span>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-item active">Rekrutmen Laboratorium</span>
        </div>

        <!-- Header Section -->
        <div class="header-section mb-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="title-section mb-3">Rekrutmen Laboratorium</h1>
                    <p class="subtitle-section">Bergabunglah dengan tim laboratorium kami dan kembangkan kemampuan Anda dalam penelitian, inovasi, dan teknologi terkini.</p>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="tabs-container mb-4">
            <ul class="nav nav-tabs custom-tabs" id="rekrutmenTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="terbuka-tab" data-bs-toggle="tab"
                        data-bs-target="#terbuka" type="button" role="tab">
                        Rekrutmen Dibuka
                        <span class="tab-badge badge-open"><?= count($recruitmentTerbuka) ?></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tertutup-tab" data-bs-toggle="tab"
                        data-bs-target="#tertutup" type="button" role="tab">
                        Rekrutmen Ditutup
                        <span class="tab-badge badge-closed"><?= count($recruitmentTertutup) ?></span>
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tabs Content -->
        <div class="tab-content" id="rekrutmenTabsContent">

            <!-- Tab Terbuka -->
            <div class="tab-pane fade show active" id="terbuka" role="tabpanel">
                <div class="row g-4">

                    <?php if (empty($recruitmentTerbuka)): ?>
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4 d-flex justify-content-center">
                                <i data-feather="user-x" class="icon-something-not-found"></i>
                            </div>
                            <h5 class="text-muted mb-2">Tidak Ada Rekrutmen Terbuka.</h5>
                            <p class="text-secondary mb-0">Saat ini belum ada rekrutmen yang sedang dibuka. Silakan cek kembali nanti.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recruitmentTerbuka as $recruitment): ?>
                            <!-- Card Recruitment Terbuka -->
                            <div class="col-lg-6 col-xl-4">
                                <div class="rekrutmen-card">
                                    <div class="card-header-custom">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h3 class="card-title-custom"><?= htmlspecialchars($recruitment['judul']) ?></h3>
                                            <span class="status-badge status-open">Terbuka</span>
                                        </div>
                                    </div>

                                    <div class="card-body-custom">
                                        <p class="card-description">
                                            <?= truncateText($recruitment['deskripsi'], 300) ?>
                                        </p>

                                        <div class="card-meta">
                                            <div class="meta-item date-open">
                                                <i data-feather="calendar" class="icon-recruitment-detail"></i>
                                                <span>Dibuka: <strong><?= formatTanggal($recruitment['tanggal_buka']) ?></strong></span>
                                            </div>
                                            <div class="meta-item date-close">
                                                <i data-feather="calendar" class="icon-recruitment-detail"></i>
                                                <span>Ditutup: <strong><?= formatTanggal($recruitment['tanggal_tutup']) ?></strong></span>
                                            </div>
                                            <?php if (!empty($recruitment['lokasi'])): ?>
                                                <div class="meta-item">
                                                    <i data-feather="map-pin" class="icon-recruitment-detail"></i>
                                                    <span><strong><?= htmlspecialchars($recruitment['lokasi']) ?></strong></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="card-footer-custom">
                                        <a target="_blank" href="<?= base_url('rekrutment/form/' . $recruitment['id']) ?>" class="btn-apply">
                                            <span>Daftar Sekarang</span>
                                            <i class="fas fa-arrow-right"></i>
                                        </a>

                                        <button type="button" class="btn-detail"
                                            data-bs-toggle="modal"
                                            data-bs-target="#recruitmentModal"
                                            data-id="<?= $recruitment['id'] ?>"
                                            data-judul="<?= htmlspecialchars($recruitment['judul']) ?>"
                                            data-kategori="<?= htmlspecialchars($recruitment['kategori'] ?? 'asisten lab') ?>"
                                            data-periode="<?= htmlspecialchars($recruitment['periode'] ?? '-') ?>"
                                            data-tanggal-buka="<?= formatTanggal($recruitment['tanggal_buka']) ?>"
                                            data-tanggal-tutup="<?= formatTanggal($recruitment['tanggal_tutup']) ?>"
                                            data-status="<?= $recruitment['status'] ?>"
                                            data-deskripsi="<?= htmlspecialchars($recruitment['deskripsi']) ?>"
                                            data-banner="<?= htmlspecialchars($recruitment['banner_image'] ?? '') ?>">
                                            <i data-feather="archive"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>

            <!-- Modal Detail -->
            <div class="modal fade" id="recruitmentModal" tabindex="-1" aria-labelledby="recruitmentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content recruitment-modal">
                        <!-- Modal Header -->
                        <div class="modal-header recruitment-modal-header">
                            <div class="modal-header-content">
                                <span class="modal-kategori" id="modalKategori">Asisten Lab</span>
                                <h2 class="modal-title" id="modalTitle">Judul Recruitment</h2>
                                <div class="modal-periode" id="modalPeriode">
                                    <i data-feather="clock"></i>
                                    <span>Periode: Ganjil 2024/2025</span>
                                </div>
                            </div>
                            <button type="button" class="btn-close-modal" data-bs-dismiss="modal" aria-label="Close">
                                <i data-feather="x"></i>
                            </button>
                        </div>

                        <!-- Modal Body -->
                        <div class="modal-body recruitment-modal-body">
                            <!-- Banner Image -->
                            <div class="modal-banner" id="modalBanner" style="display: none;">
                                <img src="" alt="Banner" id="modalBannerImg">
                            </div>

                            <!-- Info Grid -->
                            <div class="modal-info-grid">
                                <div class="info-card">
                                    <div class="info-icon info-icon-open">
                                        <i data-feather="calendar"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">Tanggal Buka</span>
                                        <span class="info-value" id="modalTanggalBuka">-</span>
                                    </div>
                                </div>
                                <div class="info-card">
                                    <div class="info-icon info-icon-close">
                                        <i data-feather="calendar"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">Tanggal Tutup</span>
                                        <span class="info-value" id="modalTanggalTutup">-</span>
                                    </div>
                                </div>
                                <div class="info-card">
                                    <div class="info-icon info-icon-status" id="modalStatusIcon">
                                        <i data-feather="check-circle"></i>
                                    </div>
                                    <div class="info-content">
                                        <span class="info-label">Status</span>
                                        <span class="info-value" id="modalStatus">Terbuka</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->

                            <div class="modal-description" id="modalDeskripsi">
                                Deskripsi recruitment akan ditampilkan di sini...
                            </div>

                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer recruitment-modal-footer">
                            <button type="button" class="btn-modal-close" data-bs-dismiss="modal">
                                Tutup
                            </button>
                            <a target="_blank" href="#" class="btn-modal-apply" id="modalApplyBtn">
                                <span>Daftar Sekarang</span>
                                <i data-feather="arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab Tertutup -->
            <div class="tab-pane fade" id="tertutup" role="tabpanel">

                <!-- Filter Section for Closed Recruitments -->
                <div class="filter-section mb-4">
                    <div class="d-flex justify-content-end align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <label for="filterRekrutmenMY" class="filter-label mb-0">Filter periode:</label>
                            <input type="month" id="filterRekrutmenMY" class="filter-input"
                                value="<?= htmlspecialchars($filterMonthParam ?? '') ?>">
                        </div>
                        <button type="button" class="btn-filter" id="btnFilterRekrutmen">
                            <i data-feather="search" class="btn-filter-icon"></i>
                            Cari
                        </button>
                        <button type="button" class="btn-filter btn-filter-secondary" id="btnShowAllRekrutmen">
                            <i data-feather="list" class="btn-filter-icon"></i>
                            Semua
                        </button>
                    </div>
                </div>

                <div class="row g-4" id="rekrutmenTertutupContainer">
                    <?php if (empty($recruitmentTertutup)): ?>
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4 d-flex justify-content-center">
                                <i data-feather="user-x" class="icon-something-not-found"></i>
                            </div>
                            <h5 class="text-muted mb-2">Tidak Ada Rekrutmen Tertutup.</h5>
                            <p class="text-secondary mb-0">Belum ada riwayat rekrutmen yang ditutup.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($recruitmentTertutup as $recruitment): ?>
                            <!-- Card Recruitment Tertutup -->
                            <div class="col-lg-6 col-xl-4 rekrutmen-tertutup-item" data-tanggal-tutup="<?= date('Y-m', strtotime($recruitment['tanggal_tutup'])) ?>">
                                <div class="rekrutmen-card card-closed">
                                    <div class="card-header-custom">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <h3 class="card-title-custom"><?= htmlspecialchars($recruitment['judul']) ?></h3>
                                            <span class="status-badge status-closed">Ditutup</span>
                                        </div>
                                    </div>

                                    <div class="card-body-custom">
                                        <p class="card-description">
                                            <?= nl2br(htmlspecialchars($recruitment['deskripsi'])) ?>
                                        </p>

                                        <div class="card-meta">
                                            <div class="meta-item">
                                                <i data-feather="calendar" class="icon-recruitment-detail"></i>
                                                <span>Dibuka: <strong><?= formatTanggal($recruitment['tanggal_buka']) ?></strong></span>
                                            </div>
                                            <div class="meta-item">
                                                <i data-feather="calendar" class="icon-recruitment-detail"></i>
                                                <span>Ditutup: <strong><?= formatTanggal($recruitment['tanggal_tutup']) ?></strong></span>
                                            </div>
                                            <?php if (!empty($recruitment['lokasi'])): ?>
                                                <div class="meta-item">
                                                    <i data-feather="map-pin" class="icon-recruitment-detail"></i>
                                                    <span><strong><?= htmlspecialchars($recruitment['lokasi']) ?></strong></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="card-footer-custom">
                                        <button class="btn-apply" disabled>
                                            <i class="fas fa-lock me-2"></i>
                                            <span>Rekrutmen Ditutup</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>

        </div>

    </div>
</main>


<link rel="stylesheet" href="<?= asset_url('css/pages/rekrutment-user/rekrutment.css') ?>">

<!-- Feather Icons -->
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>
    // Initialize Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Modal data population
    const recruitmentModal = document.getElementById('recruitmentModal');
    if (recruitmentModal) {
        recruitmentModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const baseUrl = '<?= base_url() ?>';
            const uploadUrl = '<?= upload_url("recruitment/") ?>';

            // Get data from button attributes
            const id = button.getAttribute('data-id');
            const judul = button.getAttribute('data-judul');
            const kategori = button.getAttribute('data-kategori');
            const periode = button.getAttribute('data-periode');
            const tanggalBuka = button.getAttribute('data-tanggal-buka');
            const tanggalTutup = button.getAttribute('data-tanggal-tutup');
            const status = button.getAttribute('data-status');
            const deskripsi = button.getAttribute('data-deskripsi');
            const banner = button.getAttribute('data-banner');

            // Populate modal
            document.getElementById('modalTitle').textContent = judul;
            document.getElementById('modalKategori').textContent = kategori.charAt(0).toUpperCase() + kategori.slice(1);
            document.getElementById('modalPeriode').querySelector('span').textContent = 'Periode: ' + periode;
            document.getElementById('modalTanggalBuka').textContent = tanggalBuka;
            document.getElementById('modalTanggalTutup').textContent = tanggalTutup;
            document.getElementById('modalStatus').textContent = status === 'buka' ? 'Terbuka' : 'Tertutup';
            document.getElementById('modalDeskripsi').innerHTML = deskripsi;
            document.getElementById('modalApplyBtn').href = baseUrl + '/rekrutment/form/' + id;

            // Status icon styling
            const statusIcon = document.getElementById('modalStatusIcon');
            if (status === 'buka') {
                statusIcon.classList.remove('info-icon-closed');
                statusIcon.classList.add('info-icon-open');
            } else {
                statusIcon.classList.remove('info-icon-open');
                statusIcon.classList.add('info-icon-closed');
            }

            // Banner image
            const bannerContainer = document.getElementById('modalBanner');
            const bannerImg = document.getElementById('modalBannerImg');
            if (banner && banner.trim() !== '') {
                bannerImg.src = uploadUrl + banner;
                bannerContainer.style.display = 'block';
            } else {
                bannerContainer.style.display = 'none';
            }

            // Apply button visibility
            const applyBtn = document.getElementById('modalApplyBtn');
            if (status === 'buka') {
                applyBtn.style.display = 'inline-flex';
            } else {
                applyBtn.style.display = 'none';
            }

            // Re-initialize feather icons in modal
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    }

    // ========================================
    // FILTER REKRUTMEN TERTUTUP BY MONTH/YEAR
    // Server-side filtering via URL navigation
    // ========================================

    const BASE_URL = '<?= base_url() ?>';

    /**
     * Filter rekrutmen tertutup berdasarkan bulan & tahun tutup
     * Navigasi ke URL dengan parameter ?month=YYYY-MM
     */
    function filterRekrutmenByMonth() {
        const filterInput = document.getElementById('filterRekrutmenMY');
        const filterValue = filterInput.value; // Format: "2024-12"

        if (!filterValue) {
            alert('Silakan pilih bulan dan tahun terlebih dahulu');
            return;
        }

        // Redirect with month parameter and switch to tertutup tab
        window.location.href = `${BASE_URL}rekrutment?month=${filterValue}#tertutup`;
    }

    /**
     * Tampilkan semua rekrutmen tertutup (reset filter)
     * Navigasi ke URL tanpa parameter month
     */
    function showAllRekrutmen() {
        window.location.href = `${BASE_URL}rekrutment#tertutup`;
    }

    // Event listeners for filter buttons
    document.addEventListener('DOMContentLoaded', function() {
        const btnFilterRekrutmen = document.getElementById('btnFilterRekrutmen');
        const btnShowAllRekrutmen = document.getElementById('btnShowAllRekrutmen');
        const filterInput = document.getElementById('filterRekrutmenMY');
        const btnTabBuka = document.getElementById('terbuka-tab');

        btnTabBuka.addEventListener('click', function() {
            if (window.location.hash === '#tertutup') {
                window.location.href = `${BASE_URL}rekrutment`;
            }
        });

        if (btnFilterRekrutmen) {
            btnFilterRekrutmen.addEventListener('click', filterRekrutmenByMonth);
        }

        if (btnShowAllRekrutmen) {
            btnShowAllRekrutmen.addEventListener('click', showAllRekrutmen);
        }

        // Enter key on filter input
        if (filterInput) {
            filterInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    filterRekrutmenByMonth();
                }
            });
        }

        // Auto-switch to tertutup tab if URL has month param or hash
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('month') || window.location.hash === '#tertutup') {
            const tertutupTab = document.getElementById('tertutup-tab');
            if (tertutupTab) {
                const tab = new bootstrap.Tab(tertutupTab);
                tab.show();
            }
        }
    });
</script>

<!-- Footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>