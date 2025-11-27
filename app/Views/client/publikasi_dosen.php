<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';
?>

<main class="publikasi-dosen-page">

    <div class="container">
        <div class="breadcrumb-nav">
            <span class="breadcrumb-item">Laboratorium Applied Informatics</span>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-item active">Penelitian</span>
        </div>
    </div>

    <div class="container">

        <!-- Header Section -->
        <div class="header-content mb-5">
            <h1 class="title-section mb-3">Repositori Penelitian</h1>
            <p class="subtitle-section w-75">
                Temukan berbagai hasil penelitian yang dikembangkan oleh dosen laboratorium.
                Setiap penelitian dirancang untuk memberikan kontribusi serta mendukung pengembangan ilmu pengetahuan di berbagai bidang.
            </p>
        </div>

        <div class="divider-hr"></div>

        <!-- Search & Filter Section -->
        <div class="search-filter-container mb-4">
            <div class="row g-3">
                <div class="col-lg-5">
                    <div class="search-box">
                        <i class="fas fa-search search-icon" data-feather="search"></i>
                        <input
                            type="text"
                            class="form-control search-input"
                            placeholder="Cari penelitian berdasarkan judul, dosen, atau kata kunci..."
                            id="searchInput"
                            value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button class="btn-clear" id="btnClear" style="<?= !empty($_GET['search']) ? '' : 'display: none;' ?>">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <select class="form-select filter-select" id="filterTipe">
                        <option value="">Semua Tipe Publikasi</option>
                        <option value="Riset" <?= (isset($_GET['tipe_publikasi']) && $_GET['tipe_publikasi'] === 'Riset') ? 'selected' : '' ?>>Riset</option>
                        <option value="Kekayaan Intelektual" <?= (isset($_GET['tipe_publikasi']) && $_GET['tipe_publikasi'] === 'Kekayaan Intelektual') ? 'selected' : '' ?>>Kekayaan Intelektual</option>
                        <option value="PPM" <?= (isset($_GET['tipe_publikasi']) && $_GET['tipe_publikasi'] === 'PPM') ? 'selected' : '' ?>>PPM</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <button class="btn-search" id="btnSearch">
                        <i class="fas fa-search"></i>
                        Cari
                    </button>
                </div>
            </div>
        </div>

        <!-- Research List Container -->
        <div id="penelitianList" class="penelitian-list">

            <?php if (empty($listPublikasi)): ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i data-feather="search" class="icon-something-not-found"></i>
                    </div>
                    <h5 class="text-muted mb-2">Belum Ada Hasil.</h5>
                    <p class="text-secondary mb-0">Tidak ada penelitian yang sesuai dengan pencarian Anda.</p>
                </div>
            <?php else: ?>
                <?php foreach ($listPublikasi as $publikasi): ?>
                    <!-- Penelitian Card -->
                    <div class="penelitian-card">
                        <div class="row g-0">
                            <div class="col-md-9 px-4">
                                <div class="penelitian-content">
                                    <div class="penelitian-meta mb-2">
                                        <?php
                                        // Badge class berdasarkan tipe publikasi
                                        $badgeClass = 'badge-default';
                                        if ($publikasi['tipe_publikasi'] === 'Riset') {
                                            $badgeClass = 'badge-riset';
                                        } elseif ($publikasi['tipe_publikasi'] === 'Kekayaan Intelektual') {
                                            $badgeClass = 'badge-kekayaan';
                                        } elseif ($publikasi['tipe_publikasi'] === 'PPM') {
                                            $badgeClass = 'badge-ppm';
                                        }
                                        ?>
                                        <span class="badge-tipe <?= $badgeClass ?>">
                                            <?= htmlspecialchars($publikasi['tipe_publikasi']) ?>
                                        </span>
                                        <span class="penelitian-year">
                                            <i class="fas fa-calendar-alt"></i> <?= htmlspecialchars($publikasi['tahun_publikasi']) ?>
                                        </span>
                                    </div>
                                    <h3 class="penelitian-title">
                                        <?= htmlspecialchars($publikasi['judul']) ?>
                                    </h3>
                                    <div class="penelitian-author">
                                        <i data-feather="user"></i>
                                        <span><?= htmlspecialchars($publikasi['dosen_name']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="penelitian-action">
                                    <?php if (!empty($publikasi['url_publikasi'])): ?>
                                        <a href="<?= htmlspecialchars($publikasi['url_publikasi']) ?>" class="btn-detail" target="_blank" rel="noopener noreferrer">
                                            <i class="fas fa-external-link-alt"></i>
                                            Baca Publikasi
                                        </a>
                                    <?php else: ?>
                                        <span class="btn-detail disabled" style="opacity: 0.5; cursor: not-allowed;">
                                            <i class="fas fa-external-link-alt"></i>
                                            URL Tidak Tersedia
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        </div>

        <!-- Pagination -->
        <?php if (isset($pagination) && $pagination['total_pages'] > 0): ?>
            <div class="pagination-container mt-5">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <!-- Previous Button -->
                        <li class="page-item <?= !$pagination['has_prev'] ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="<?= $pagination['has_prev'] ? '?page=' . $pagination['prev_page'] . (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '') . (isset($_GET['tipe_publikasi']) ? '&tipe_publikasi=' . urlencode($_GET['tipe_publikasi']) : '') : '#' ?>"
                                tabindex="<?= !$pagination['has_prev'] ? '-1' : '' ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>

                        <!-- Page Numbers -->
                        <?php foreach ($pagination['page_numbers'] as $pageData): ?>
                            <?php if ($pageData['is_ellipsis']): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php else: ?>
                                <li class="page-item <?= ($pageData['number'] == $pagination['current_page']) ? 'active' : '' ?>">
                                    <a class="page-link"
                                        href="?page=<?= $pageData['number'] ?><?= isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '' ?><?= isset($_GET['tipe_publikasi']) ? '&tipe_publikasi=' . urlencode($_GET['tipe_publikasi']) : '' ?>">
                                        <?= $pageData['number'] ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <!-- Next Button -->
                        <li class="page-item <?= !$pagination['has_next'] ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="<?= $pagination['has_next'] ? '?page=' . $pagination['next_page'] . (isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '') . (isset($_GET['tipe_publikasi']) ? '&tipe_publikasi=' . urlencode($_GET['tipe_publikasi']) : '') : '#' ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>

    </div>
</main>

<link rel="stylesheet" href="<?= asset_url('css/pages/publikasi_dosen-user/publikasi_dosen.css') ?>">

<!-- jQuery -->
<script src="<?= asset_url('js/jquery.min.js') ?>"></script>

<!-- External JS for Search & Filter -->
<script src="<?= asset_url('js/pages/publikasi_dosen/publikasi_dosen.js') ?>"></script>

<!--footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>