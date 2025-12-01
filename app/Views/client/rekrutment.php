<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';

// Helper function untuk format tanggal Indonesia
function formatTanggalIndonesia($tanggal)
{
    if (empty($tanggal)) {
        return '-';
    }

    $bulan = [
        1 => 'Jan',
        2 => 'Feb',
        3 => 'Mar',
        4 => 'Apr',
        5 => 'Mei',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Agu',
        9 => 'Sep',
        10 => 'Okt',
        11 => 'Nov',
        12 => 'Des'
    ];

    $timestamp = strtotime($tanggal);
    $hari = date('d', $timestamp);
    $bulanAngka = (int)date('m', $timestamp);
    $tahun = date('Y', $timestamp);

    return $hari . ' ' . $bulan[$bulanAngka] . ' ' . $tahun;
}
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
                        Rekrutmen Terbuka
                        <span class="tab-badge badge-open"><?= count($recruitmentTerbuka) ?></span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tertutup-tab" data-bs-toggle="tab"
                        data-bs-target="#tertutup" type="button" role="tab">
                        Rekrutmen Tertutup
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
                                            <?= nl2br(htmlspecialchars($recruitment['deskripsi'])) ?>
                                        </p>

                                        <div class="card-meta">
                                            <div class="meta-item date-open">
                                                <i data-feather="calendar" class="icon-recruitment-detail"></i>
                                                <span>Dibuka: <strong><?= formatTanggalIndonesia($recruitment['tanggal_buka']) ?></strong></span>
                                            </div>
                                            <div class="meta-item date-close">
                                                <i data-feather="calendar" class="icon-recruitment-detail"></i>
                                                <span>Ditutup: <strong><?= formatTanggalIndonesia($recruitment['tanggal_tutup']) ?></strong></span>
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
                                        <a href="<?= base_url('rekrutment/form/' . $recruitment['id']) ?>" class="btn-apply">
                                            <span>Daftar Sekarang</span>
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
            </div>

            <!-- Tab Tertutup -->
            <div class="tab-pane fade" id="tertutup" role="tabpanel">
                <div class="row g-4">

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
                            <div class="col-lg-6 col-xl-4">
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
                                                <span>Dibuka: <strong><?= formatTanggalIndonesia($recruitment['tanggal_buka']) ?></strong></span>
                                            </div>
                                            <div class="meta-item">
                                                <i data-feather="calendar" class="icon-recruitment-detail"></i>
                                                <span>Ditutup: <strong><?= formatTanggalIndonesia($recruitment['tanggal_tutup']) ?></strong></span>
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
</script>

<!-- Footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>