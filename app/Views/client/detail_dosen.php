<!-- Header -->
<?php
include __DIR__ . '/../layouts/header.php';

// Data dosen dan publikasi sudah di-fetch dari route
// $dosenData - Data lengkap dosen
// $profilPublikasi - Data profil publikasi dosen
// $publikasiGrouped - Data publikasi digroup berdasarkan tipe
?>

<!-- Main Content -->
<main class="detail-dosen-page">
    <div class="container-fluid px-5 ">
        <div class="breadcrumb-nav">
            <a href="<?= base_url('anggota-laboratorium') ?>" class="breadcrumb-item breadcrumb-link">Anggota Laboratorium</a>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-item active">Detail Dosen</span>
        </div>
    </div>

    <!-- Hero Section - Profile Header -->
    <section class="container-fluid px-5 pb-5">

        <div class="container">
            <div class="hero-content">
                <!-- Photo Section -->
                <div class="hero-photo-wrapper">
                    <div class="photo-container">
                        <img src="<?= upload_url('dosen/' . $dosenData['foto_profil']) ?>"
                            alt="<?= htmlspecialchars($dosenData['full_name']) ?>"
                            class="profile-photo">
                        <div class="photo-gradient"></div>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="hero-info">
                    <div class="jabatan-badge">
                        <?= htmlspecialchars($dosenData['jabatan_name']) ?>
                    </div>
                    <h1 class="profile-name"><?= htmlspecialchars($dosenData['full_name']) ?></h1>
                    <div class="contact-info">
                        <div class="contact-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <a href="mailto:<?= htmlspecialchars($dosenData['email']) ?>" class="contact-link">
                                <?= htmlspecialchars($dosenData['email']) ?>
                            </a>
                        </div>
                        <div class="contact-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <polyline points="17 11 19 13 23 9"></polyline>
                            </svg>
                            <span>NIDN: <?= htmlspecialchars($dosenData['nidn']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Expertise Section -->
    <section class="expertise-section">
        <div class="container">
            <div class="section-header-simple">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="section-icon">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                </svg>
                <h2 class="section-title-simple">Bidang Keahlian</h2>
            </div>

            <?php if (!empty($dosenData['keahlian_list'])): ?>
                <div class="expertise-grid">
                    <?php
                    $keahlianArray = explode(', ', $dosenData['keahlian_list']);
                    foreach ($keahlianArray as $keahlian):
                    ?>
                        <div class="expertise-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="expertise-icon">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span><?= htmlspecialchars(trim($keahlian)) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="empty-state">Belum ada data keahlian yang tersedia.</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Description Section -->
    <?php if (!empty($dosenData['deskripsi'])): ?>
        <section>
            <div class="container">
                <div class="section-header-simple">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="section-icon">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <h2 class="section-title-simple">Tentang</h2>
                </div>
                <div class="description-content">
                    <p><?= nl2br(htmlspecialchars($dosenData['deskripsi'])) ?></p>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Profil Publikasi Section -->
    <?php if (!empty($profilPublikasi)): ?>
        <section class="profil-publikasi-section">
            <div class="container">
                <div class="section-header-simple">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="section-icon">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    <h2 class="section-title-simple">Profil Publikasi</h2>
                </div>
                <div class="profil-publikasi-grid">
                    <?php
                    $tipeLabels = [
                        'SINTA' => 'SINTA',
                        'SCOPUS' => 'Scopus',
                        'GOOGLE_SCHOLAR' => 'Google Scholar',
                        'ORCID' => 'ORCID',
                        'RESEARCHGATE' => 'ResearchGate'
                    ];

                    $tipeClasses = [
                        'SINTA' => 'sinta',
                        'SCOPUS' => 'scopus',
                        'GOOGLE_SCHOLAR' => 'scholar',
                        'ORCID' => 'orcid',
                        'RESEARCHGATE' => 'researchgate'
                    ];

                    foreach ($profilPublikasi as $profil):
                        $label = $tipeLabels[$profil['tipe']] ?? $profil['tipe'];
                        $cardClass = $tipeClasses[$profil['tipe']] ?? '';
                        $urlHost = parse_url($profil['url_profil'], PHP_URL_HOST) ?? '';
                    ?>
                        <a href="<?= htmlspecialchars($profil['url_profil']) ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="profil-card profil-<?= $cardClass ?>">
                            <div class="profil-card-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                            </div>
                            <div class="profil-card-content">
                                <div class="profil-card-title"><?= htmlspecialchars($label) ?></div>
                                <div class="profil-card-url"><?= htmlspecialchars($urlHost) ?></div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="profil-card-arrow">
                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                <polyline points="15 3 21 3 21 9"></polyline>
                                <line x1="10" y1="14" x2="21" y2="3"></line>
                            </svg>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Publikasi Akademik Section -->
    <?php if (!empty($publikasiGrouped)): ?>
        <section class="publikasi-section">
            <div class="container">
                <div class="section-header-simple">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="section-icon">
                        <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                    </svg>
                    <h2 class="section-title-simple">Publikasi Akademik</h2>
                </div>

                <!-- Publikasi Tabs -->
                <div class="publikasi-tabs">
                    <?php
                    $tipeOrder = ['Publikasi', 'Riset', 'PPM', 'Kekayaan Intelektual'];
                    $firstTab = true;
                    foreach ($tipeOrder as $tipe):
                        if (isset($publikasiGrouped[$tipe])):
                            $safeId = str_replace(' ', '_', strtolower($tipe));
                    ?>
                            <button class="tab-button <?= $firstTab ? 'active' : '' ?>"
                                data-tab="<?= $safeId ?>">
                                <?= htmlspecialchars($tipe) ?>
                                <span class="tab-count"><?= count($publikasiGrouped[$tipe]) ?></span>
                            </button>
                    <?php
                            $firstTab = false;
                        endif;
                    endforeach;
                    ?>
                </div>

                <!-- Publikasi Content -->
                <div class="publikasi-wrapper">
                    <div class="publikasi-content" id="publikasiContent">
                        <?php
                        $firstContent = true;
                        foreach ($tipeOrder as $tipe):
                            if (isset($publikasiGrouped[$tipe])):
                                $safeId = str_replace(' ', '_', strtolower($tipe));
                        ?>
                                <div class="tab-content <?= $firstContent ? 'active' : '' ?>"
                                    id="<?= $safeId ?>">
                                    <div class="publikasi-list">
                                        <?php foreach ($publikasiGrouped[$tipe] as $publikasi): ?>
                                            <div class="publikasi-item">
                                                <div class="publikasi-header">
                                                    <h3 class="publikasi-title"><?= htmlspecialchars($publikasi['judul']) ?></h3>
                                                    <span class="publikasi-year"><?= htmlspecialchars($publikasi['tahun_publikasi']) ?></span>
                                                </div>
                                                <?php if (!empty($publikasi['url_publikasi'])): ?>
                                                    <a href="<?= htmlspecialchars($publikasi['url_publikasi']) ?>"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="publikasi-link">
                                                        Lihat Publikasi
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                            <polyline points="15 3 21 3 21 9"></polyline>
                                                            <line x1="10" y1="14" x2="21" y2="3"></line>
                                                        </svg>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                        <?php
                                $firstContent = false;
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Back Button -->
    <div class="container pb-5">
        <div class="back-button-wrapper">
            <a href="<?= base_url('anggota-laboratorium') ?>" class="btn-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Kembali ke Anggota Laboratorium
            </a>
        </div>
    </div>
</main>

<!-- Page Specific CSS -->
<link rel="stylesheet" href="<?= asset_url('css/pages/anggota/detail_dosen.css') ?>">

<!-- Page Specific JS -->
<script>
    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        const publikasiContent = document.getElementById('publikasiContent');
        const publikasiWrapper = document.querySelector('.publikasi-wrapper');


        // Tab switching
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');

                // Remove active class from all buttons and contents
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                // Add active class to clicked button and corresponding content
                this.classList.add('active');
                document.getElementById(tabId).classList.add('active');

                // Reset scroll position and check scrollable
                if (publikasiContent) {
                    publikasiContent.scrollTop = 0;
                    setTimeout(checkScrollable, 100);
                }
            });
        });

        // Check scroll on scroll event
        if (publikasiContent) {
            publikasiContent.addEventListener('scroll', checkScrollable);
        }

        // Initial check
        setTimeout(checkScrollable, 100);

        // Check on window resize
        window.addEventListener('resize', checkScrollable);
    });
</script>

<!-- Footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>