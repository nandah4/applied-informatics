<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';
?>

<main class="aktivitas-lab-page">
    <div class="container">
        <div class="breadcrumb-nav">
            <span class="breadcrumb-item">Laboratorium Applied Informatics</span>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-item active">Aktivitas Laboratorium</span>
        </div>
    </div>

    <div class="container">

        <div class="mb-4">
            <h1 class="title-section mb-3">Semua Aktivitas Lab</h1>
            <p class="subtitle-section w-75">Beragam kegiatan penelitian, pengembangan, dan kolaborasi yang dilakukan oleh
                anggota laboratorium.</p>
        </div>

        <div class="divider-hr"></div>

        <div class="row g-4" id="aktivitasContainer">
            <?php if (!empty($aktivitasData)): ?>
                <?php foreach ($aktivitasData as $aktivitas): ?>
                    <div class="col-md-6 col-lg-4 aktivitas-item">
                        <a href="<?= base_url("aktivitas/" . $aktivitas['id']) ?>" class="text-decoration-none">
                            <article class="news-card h-100">
                                <!-- Image Section -->
                                <div class="news-image-wrapper">
                                    <?php $urlPhoto = empty($aktivitas['foto_aktivitas']) ? upload_url("default/image.png") : upload_url("aktivitas-lab/" . $aktivitas['foto_aktivitas']); ?>
                                    <img src="<?= $urlPhoto ?>"
                                        alt="<?= htmlspecialchars($aktivitas['judul_aktivitas'], ENT_QUOTES, 'UTF-8') ?>"
                                        class="news-image">
                                    <div class="news-overlay">
                                        <span class="news-date">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            <?= date('d M Y', strtotime($aktivitas['tanggal_kegiatan'])) ?>
                                        </span>
                                    </div>
                                </div>

                                <!-- Content Section -->
                                <div class="news-content">
                                    <h3 class="news-title">
                                        <?php
                                        $judul = $aktivitas['judul_aktivitas'];
                                        $judul_display = mb_strlen($judul) > 80 ? mb_substr($judul, 0, 80) . '...' : $judul;
                                        echo htmlspecialchars($judul_display);
                                        ?>
                                    </h3>
                                    <p class="news-description">
                                        <?php
                                        $deskripsi = $aktivitas['deskripsi'];
                                        $deskripsi_display = mb_strlen($deskripsi) > 150 ? mb_substr($deskripsi, 0, 150) . '...' : $deskripsi;
                                        echo htmlspecialchars($deskripsi_display);
                                        ?>
                                    </p>

                                    <div class="news-footer">
                                        <span class="read-more-link">
                                            Baca Selengkapnya
                                            <i class="bi bi-arrow-right ms-1"></i>
                                        </span>
                                    </div>
                                </div>
                            </article>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-calendar-x fa-4x text-muted opacity-50" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="text-muted mb-2">Belum Ada Aktivitas</h5>
                        <p class="text-muted mb-0">Data aktivitas laboratorium belum tersedia saat ini.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Load More/Less Button -->
        <?php if (!empty($aktivitasData)): ?>
            <div class="text-center mt-5" id="loadMoreSection">
                <button type="button" class="btn btn-outline-primary btn-lg px-5" id="loadMoreBtn" onclick="loadMore()">
                    <i class="bi bi-arrow-down-circle me-2"></i>
                    Muat Lebih Banyak
                </button>
                <button type="button" class="btn btn-outline-secondary btn-lg px-5" id="loadLessBtn" onclick="loadLess()" style="display: none;">
                    <i class="bi bi-arrow-up-circle me-2"></i>
                    Tampilkan Lebih Sedikit
                </button>
            </div>
        <?php endif; ?>

    </div>
</main>

<link rel="stylesheet" href="<?= asset_url('css/pages/aktivitas-lab-user/aktivitas_lab.css') ?>">

<script>
    let currentLimit = 6;
    const initialLimit = 6;
    const incrementStep = 6;

    function loadMore() {
        currentLimit += incrementStep;
        reloadAktivitas();
    }

    function loadLess() {
        if (currentLimit > initialLimit) {
            currentLimit -= incrementStep;
            if (currentLimit < initialLimit) {
                currentLimit = initialLimit;
            }
            reloadAktivitas();
        }
    }

    function reloadAktivitas() {
        // Reload page with new limit parameter
        const url = new URL(window.location.href);
        url.searchParams.set('limit', currentLimit);
        window.location.href = url.toString();
    }

    // Check current limit from URL params
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const limitParam = urlParams.get('limit');

        if (limitParam) {
            currentLimit = parseInt(limitParam);
        }

        // Show/hide load less button
        const loadLessBtn = document.getElementById('loadLessBtn');
        const loadMoreBtn = document.getElementById('loadMoreBtn');

        if (currentLimit > initialLimit && loadLessBtn) {
            loadLessBtn.style.display = 'inline-block';
        }

        // Check if there might be more data
        const aktivitasItems = document.querySelectorAll('.aktivitas-item');
        if (aktivitasItems.length < currentLimit && loadMoreBtn) {
            loadMoreBtn.style.display = 'none';
        }
    });
</script>

<!--footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>
