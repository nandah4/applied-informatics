<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="<?= base_url() ?>">
    <title>Data Recruitment - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/components/admin_layout.css') ?>">

    <!-- Data Recruitment Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/recruitment/index.css') ?>">
</head>

<body>
    <!-- Alert Placeholder untuk notifikasi -->
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
                    <h1 class="page-title">Data Recruitment</h1>
                    <p class="page-subtitle">Kelola informasi recruitment Laboratorium Applied Informatics</p>
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
                    <!-- Search Bar -->
                    <!-- <div class="search-wrapper">
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" class="search-input" placeholder="Cari judul, deskripsi, atau lokasi...">
                    </div> -->

                    <!-- Add Button -->
                    <a href="<?= base_url('admin/recruitment/create') ?>" class="btn-primary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah Recruitment
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="table-wrapper">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th class="col-id">ID</th>
                            <th class="col-judul">Judul</th>
                            <th class="col-deskripsi">Deskripsi</th>
                            <th class="col-lokasi">Lokasi</th>
                            <th class="col-periode">Periode</th>
                            <th class="col-status">Status</th>
                            <th class="action-cell">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($listRecruitment)): ?>
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <h6>Tidak ada data recruitment</h6>
                                        <p>Mulai dengan menambahkan informasi recruitment pertama Anda</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($listRecruitment as $dt): ?>
                                <tr>
                                    <td class="col-id"><?= $dt['id'] ?></td>
                                    <td class="col-judul">
                                        <div style="font-weight: 600; color: var(--color-gray-900);"><?= htmlspecialchars($dt['judul']) ?></div>
                                    </td>
                                    <td class="col-deskripsi ">
                                        <div class="text-truncate-2">
                                            <?= !empty($dt['deskripsi']) ? htmlspecialchars($dt['deskripsi']) : '-' ?>
                                        </div>
                                    </td>
                                    <td class="col-lokasi">
                                        <?= !empty($dt['lokasi']) ? htmlspecialchars($dt['lokasi']) : '-' ?>
                                    </td>
                                    <td class="col-periode">
                                        <div class="periode-wrapper">
                                            <span class="periode-date"><?= date('d/m/Y', strtotime($dt['tanggal_buka'])) ?></span>
                                            <span>-</span>
                                            <span class="periode-date"><?= date('d/m/Y', strtotime($dt['tanggal_tutup'])) ?></span>
                                        </div>
                                    </td>
                                    <td class="col-status">
                                        <?php if ($dt['status'] === 'buka'): ?>
                                            <span class="badge-custom badge-buka">Buka</span>
                                        <?php else: ?>
                                            <span class="badge-custom badge-tutup">Tutup</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-cell">
                                        <div class="action-buttons">
                                            <a href="<?= base_url('admin/recruitment/detail/' . $dt['id']) ?>" class="btn-action btn-view" title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a href="<?= base_url('admin/recruitment/edit/' . $dt['id']) ?>" class="btn-action btn-edit" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                </svg>
                                            </a>
                                            <button class="btn-action btn-delete" title="Hapus" onclick="confirmDelete(<?= $dt['id'] ?>)">
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
            <?php if (isset($pagination) && $pagination['total_pages'] > 0) : ?>
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
                            dari <strong><?= $pagination['total_records'] ?></strong> data
                        </span>
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <!-- Previous Button -->
                            <li class="page-item <?= !$pagination['has_prev'] ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="<?= $pagination['has_prev'] ? base_url('admin/recruitment?page=' . $pagination['prev_page'] . '&per_page=' . $pagination['per_page'])
                                                : '#' ?>"
                                    tabindex="<?= !$pagination['has_prev'] ? '-1' : '' ?>">
                                    Previous
                                </a>
                            </li>

                            <!-- Page Numbers dengan Ellipsis -->
                            <?php foreach ($pagination['page_numbers'] as $pageData): ?>
                                <?php if ($pageData['is_ellipsis']): ?>
                                    <!-- Ellipsis (...) -->
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php else: ?>
                                    <!-- Page Number -->
                                    <li class="page-item <?= ($pageData['number'] == $pagination['current_page']) ? 'active' : '' ?>">
                                        <a class="page-link"
                                            href="<?= base_url('admin/recruitment?page=' . $pageData['number'] . '&per_page=' . $pagination['per_page']) ?>">
                                            <?= $pageData['number'] ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <!-- Next Button -->
                            <li class="page-item <?= !$pagination['has_next'] ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="<?= $pagination['has_next'] ? base_url('admin/recruitment?page=' . $pagination['next_page'] . '&per_page=' . $pagination['per_page'])
                                                : '#' ?>">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif;  ?>

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

    <!-- Page Specific Scripts -->
    <script src="<?= asset_url('js/pages/recruitment/index.js') ?>"></script>
</body>

</html>