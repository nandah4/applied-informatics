<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="/applied-informatics">
    <title>Pesan Masuk - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/components/admin_layout.css') ?>">

    <!-- Contact Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/contact/index.css') ?>">
</head>

<body>
    <div id="liveAlertPlaceholder"></div>

    <!-- CSRF Token untuk AJAX requests -->
    <?= CsrfHelper::tokenField() ?>

    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header-list">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title"> Data Pesan Masuk</h1>
                    <p class="page-subtitle">Kelola pesan dari formulir Contact Us</p>
                </div>
                <button class="btn-mobile-menu d-md-none" id="mobileMenuBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <!-- Search Bar with Button -->
                    <div class="d-flex gap-2">
                        <div class="search-wrapper">
                            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                            <input type="text"
                                id="searchInput"
                                class="search-input"
                                placeholder="Cari nama, email, atau isi pesan..."
                                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                            <?php if (!empty($_GET['search'])): ?>
                                <button type="button" class="btn-clear-search" id="btnClearSearch" title="Hapus pencarian">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                    </svg>
                                </button>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="btn-search-custom" id="btnSearch">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.35-4.35"></path>
                            </svg>
                            Cari
                        </button>
                    </div>

                    <!-- Filter Status -->
                    <div class="d-flex gap-2">
                        <select id="statusFilter" class="form-select form-select-sm" style="width: auto;">
                            <option value="">Semua Status</option>
                            <option value="Baru" <?= (isset($_GET['status']) && $_GET['status'] === 'Baru') ? 'selected' : '' ?>>Baru</option>
                            <option value="Dibalas" <?= (isset($_GET['status']) && $_GET['status'] === 'Dibalas') ? 'selected' : '' ?>>Dibalas</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-wrapper">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th class="col-id">ID</th>
                            <th class="col-nama">Nama Pengirim</th>
                            <th class="col-email">Email</th>
                            <th class="col-pesan">Isi Pesan</th>
                            <th class="col-status">Status</th>
                            <th class="col-tanggal">Tanggal</th>
                            <th class="action-cell">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($listPesan)): ?>
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <h6>Tidak ada pesan masuk</h6>
                                        <p>Belum ada pesan dari formulir Contact Us</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($listPesan as $dt): ?>
                                <tr>
                                    <td class="col-id"><?= $dt['id'] ?></td>
                                    <td class="col-nama">
                                        <div style="font-weight: 600; color: var(--color-gray-900);">
                                            <?= htmlspecialchars($dt['nama_pengirim']) ?>
                                        </div>
                                    </td>
                                    <td class="col-email"><?= htmlspecialchars($dt['email_pengirim']) ?></td>
                                    <td class="col-pesan">
                                        <div class="pesan-preview">
                                            <?= htmlspecialchars(substr($dt['isi_pesan'], 0, 80)) ?>
                                            <?= strlen($dt['isi_pesan']) > 80 ? '...' : '' ?>
                                        </div>
                                    </td>
                                    <td class="col-status">
                                        <?php
                                        $badgeClass = $dt['status'] === 'Baru' ? 'badge-warning' : 'badge-success';
                                        ?>
                                        <span class="badge-custom <?= $badgeClass ?>">
                                            <?= htmlspecialchars($dt['status']) ?>
                                        </span>
                                    </td>
                                    <td class="col-tanggal">
                                        <?= htmlspecialchars(formatTanggal($dt['created_at'], true)) ?>
                                    </td>
                                    <td class="action-cell">
                                        <div class="action-buttons">
                                            <a href="<?= base_url('admin/contact/detail/' . $dt['id']) ?>" 
                                               class="btn-action btn-view" 
                                               title="Lihat & Balas">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <button class="btn-action btn-delete" 
                                                    title="Hapus" 
                                                    onclick="confirmDelete(<?= $dt['id'] ?>)">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if (isset($pagination) && $pagination['total_pages'] > 0):
                $searchParam = !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '';
                $statusParam = !empty($_GET['status']) ? '&status=' . urlencode($_GET['status']) : '';
                $queryParams = $searchParam . $statusParam;
            ?>
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        <span>Menampilkan
                            <select id="perPageSelect" class="per-page-select">
                                <option value="5" <?= ($pagination['per_page'] == 5) ? 'selected' : '' ?>>5</option>
                                <option value="10" <?= ($pagination['per_page'] == 10) ? 'selected' : '' ?>>10</option>
                                <option value="25" <?= ($pagination['per_page'] == 25) ? 'selected' : '' ?>>25</option>
                                <option value="50" <?= ($pagination['per_page'] == 50) ? 'selected' : '' ?>>50</option>
                                <option value="100" <?= ($pagination['per_page'] == 100) ? 'selected' : '' ?>>100</option>
                            </select>
                            dari <strong><?= $pagination['total_records'] ?></strong> pesan
                        </span>
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <li class="page-item <?= !$pagination['has_prev'] ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="<?= $pagination['has_prev'] ? base_url('admin/contact?page=' . $pagination['prev_page'] . '&per_page=' . $pagination['per_page'] . $queryParams) : '#' ?>"
                                    tabindex="<?= !$pagination['has_prev'] ? '-1' : '' ?>">
                                    Previous
                                </a>
                            </li>

                            <?php foreach ($pagination['page_numbers'] as $pageData): ?>
                                <?php if ($pageData['is_ellipsis']): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item <?= ($pageData['number'] == $pagination['current_page']) ? 'active' : '' ?>">
                                        <a class="page-link"
                                            href="<?= base_url('admin/contact?page=' . $pageData['number'] . '&per_page=' . $pagination['per_page'] . $queryParams) ?>">
                                            <?= $pageData['number'] ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <li class="page-item <?= !$pagination['has_next'] ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="<?= $pagination['has_next'] ? base_url('admin/contact?page=' . $pagination['next_page'] . '&per_page=' . $pagination['per_page'] . $queryParams) : '#' ?>">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- jQuery -->
    <script src="<?= asset_url('js/jquery.min.js') ?>"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <!-- Sidebar JS -->
    <script src="<?= asset_url('js/components/sidebar.js') ?>"></script>

    <!-- Helper Scripts -->
    <script src="<?= asset_url('js/helpers/jQueryHelpers.js') ?>"></script>

    <!-- Contact Page JS -->
    <script src="<?= asset_url('js/pages/contact/index.js') ?>"></script>
</body>

</html>