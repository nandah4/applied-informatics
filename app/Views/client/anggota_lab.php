<!-- Header -->
<?php
include __DIR__ . '/../layouts/header.php';

// Data dosen sudah di-fetch dari route
// $leadership - Data Kepala Laboratorium (dari database)
// $members - Data Dosen anggota (dari database)
?>

<!-- Main Content -->
<main class="tentang-kami-page">

    <!-- Leadership Section -->
    <section class="container-fluid px-md-5 pb-5">
        <div class="breadcrumb-nav">
            <span class="breadcrumb-item">Laboratorium Applied Informatics</span>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-item active">Anggota Laboratorium</span>
        </div>
        <div class="container">
            <div class="section-header">
                <h2 class="title-section">Struktur Pimpinan</h2>
            </div>

            <?php if (!empty($leadership)): ?>
                <?php foreach ($leadership as $leader): ?>
                    <div class="leader-profile">
                        <!-- Left Side - Photo -->
                        <div class="leader-photo-section">
                            <div class="leader-photo-wrapper">
                                <img src="<?= upload_url('dosen/' . $leader['foto_profil']) ?>"
                                    alt="<?= htmlspecialchars($leader['full_name']) ?>"
                                    class="leader-photo">
                                <div class="photo-decoration"></div>
                            </div>
                        </div>

                        <!-- Right Side - Information -->
                        <div class="leader-info-section">
                            <div class="leader-badge-container">
                                <span class="leader-badge"><?= htmlspecialchars($leader['jabatan_name']) ?></span>
                            </div>

                            <h3 class="leader-full-name"><?= htmlspecialchars($leader['full_name']) ?></h3>

                            <div class="leader-divider"></div>

                            <div class="leader-expertise-section mb-5">
                                <div class="leader-expertise-badges">
                                    <?php
                                    $keahlianArray = array_map('trim', explode(', ', $leader['keahlian_list']));
                                    $limit = 4;

                                    $tampil = array_slice($keahlianArray, 0, $limit);

                                    foreach ($tampil as $keahlian): ?>
                                        <span class="expertise-badge secondary"><?= htmlspecialchars($keahlian) ?></span>
                                    <?php endforeach; ?>

                                    <?php if (count($keahlianArray) > $limit): ?>
                                        <span class="expertise-badge secondary">...</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Btn -->
                            <a href="<?= base_url('dosen/detail/' . $leader['id']) ?>" class="btn-detail-dosen">
                                <span>Lihat Profil Lengkap</span>
                                <i data-feather="arrow-right"></i>
                            </a>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="mb-4 d-flex justify-content-center">
                        <i data-feather="user" class="icon-something-not-found"></i>
                    </div>
                    <h5 class="text-muted mb-2">Belum Ada Pimpinan Laboratorium.</h5>
                    <p class="text-secondary mb-0">Belum ada data pimpinan laboratorium.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Members Section -->
    <section class="members-section">
        <div class="container">
            <div class="section-header">
                <h2 class="title-section">Dosen Anggota</h2>
            </div>

            <?php if (!empty($members)): ?>
                <div class="members-grid">
                    <?php foreach ($members as $member): ?>
                        <a href="<?= base_url('dosen/detail/' . $member['id']) ?>" class="member-card">
                            <div class="member-photo">
                                <img src="<?= upload_url('dosen/' . $member['foto_profil']) ?>"
                                    alt="<?= htmlspecialchars($member['full_name']) ?>"
                                    class="member-image">
                            </div>
                            <div class="member-info">
                                <h3 class="member-name"><?= htmlspecialchars($member['full_name']) ?></h3>
                                <p class="member-position"><?= htmlspecialchars($member['jabatan_name']) ?></p>
                                <div class="member-expertise">
                                    <?php
                                    $keahlianArray = explode(', ', $member['keahlian_list']);
                                    $max = 3;

                                    foreach ($keahlianArray as $index => $keahlian):

                                        if ($index === $max) {
                                            echo '<span class="expertise-badge secondary">...</span>';
                                            break;
                                        }
                                    ?>
                                        <span class="expertise-badge secondary"><?= htmlspecialchars(trim($keahlian)) ?></span>
                                    <?php endforeach; ?>
                                </div>

                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="mb-4 d-flex justify-content-center">
                        <i data-feather="user" class="icon-something-not-found"></i>
                    </div>
                    <h5 class="text-muted mb-2">Belum Ada Dosen Anggota.</h5>
                    <p class="text-secondary mb-0">Belum ada data dosen anggota.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<!-- Page Specific CSS -->
<link rel="stylesheet" href="<?= asset_url('css/pages/anggota/anggota_lab.css') ?>">

<!-- Footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>