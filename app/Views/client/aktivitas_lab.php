<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';
?>

<main class="aktivitas-lab-page">

    <div class="container-fluid px-5 pb-5">
        <div class="breadcrumb-nav">
            <span class="breadcrumb-item">Laboratorium Applied Informatics</span>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-item active">Aktivitas Laboratorium</span>
        </div>

        <div class="mb-5">
            <h1 class="title-section mb-3">Semua Aktivitas Lab</h1>
            <p class="subtitle-section w-75">Beragam kegiatan penelitian, pengembangan, dan kolaborasi yang dilakukan oleh
                anggota laboratorium.</p>
        </div>

        <div class="d-flex justify-content-center align-items-center mb-5">
            <div class="w-auto d-flex gap-3 align-items-center">
                <div class="d-flex gap-3 align-items-center">
                    <label for="idAktivitasMY" class="news-description m-0">Filter bulan & tahun: </label>
                    <input type="month" id="idAktivitasMY" name="aktivitasMonthYear"
                        value="<?= htmlspecialchars($filterMonth ?? '') ?>">
                </div>
                <button type="button" class="btn-search" id="btnFilterCari">
                    Cari
                </button>
                <button type="button" class="btn-search" id="btnFilterSemua">
                    Semua Aktivitas
                </button>

            </div>
        </div>

        <div class="row g-4" id="aktivitasContainer">
            <?php if (!empty($aktivitasData)): ?>
                <?php foreach ($aktivitasData as $aktivitas): ?>
                    <div class="col-md-6 col-lg-4 aktivitas-item">
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
                                        <?= formatTanggal($aktivitas['tanggal_kegiatan']) ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Content Section -->
                            <div class="news-content">
                                <h3 class="news-title text-truncate-2">
                                    <?= truncateText($aktivitas['judul_aktivitas'], 100) ?>
                                </h3>
                                <div class="news-description ">
                                    <?= truncateText($aktivitas['deskripsi'], 200) ?>
                                </div>

                                <div class="news-footer">
                                    <a href="<?= base_url("aktivitas-laboratorium/" . $aktivitas['id']) ?>" class="read-more-link">
                                        Baca Selengkapnya
                                        <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </article>

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
                <button type="button" class="btn btn-outline-primary px-5" id="loadMoreBtn" onclick="loadMore()">
                    <i class="bi bi-arrow-down-circle me-2"></i>
                    Muat Lebih Banyak
                </button>
                <button type="button" class="btn btn-outline-secondary px-5" id="loadLessBtn" onclick="loadLess()" style="display: none;">
                    <i class="bi bi-arrow-up-circle me-2"></i>
                    Tampilkan Lebih Sedikit
                </button>
            </div>
        <?php endif; ?>

    </div>
</main>

<link rel="stylesheet" href="<?= asset_url('css/pages/aktivitas-lab/aktivitas_lab_user.css') ?>">

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
        // Reload page with new limit parameter, preserve month filter
        const url = new URL(window.location.href);
        url.searchParams.set('limit', currentLimit);
        window.location.href = url.toString();
    }

    // Filter by month/year
    function filterByMonth() {
        const monthInput = document.getElementById('idAktivitasMY');
        const monthValue = monthInput.value;

        if (!monthValue) {
            alert('Silakan pilih bulan dan tahun terlebih dahulu');
            return;
        }

        const url = new URL(window.location.origin + window.location.pathname);
        url.searchParams.set('month', monthValue);
        url.searchParams.set('limit', initialLimit); // Reset limit when filtering
        window.location.href = url.toString();
    }

    // Show all aktivitas (clear filter)
    function showAllAktivitas() {
        const url = new URL(window.location.origin + window.location.pathname);
        url.searchParams.set('limit', initialLimit);
        // Don't add month param = show all
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

        // Add event listeners for filter buttons
        const btnFilterCari = document.getElementById('btnFilterCari');
        const btnFilterSemua = document.getElementById('btnFilterSemua');

        if (btnFilterCari) {
            btnFilterCari.addEventListener('click', filterByMonth);
        }

        if (btnFilterSemua) {
            btnFilterSemua.addEventListener('click', showAllAktivitas);
        }

        // Allow Enter key on month input to trigger filter
        const monthInput = document.getElementById('idAktivitasMY');
        if (monthInput) {
            monthInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    filterByMonth();
                }
            });
        }
    });
</script>

<!--footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>