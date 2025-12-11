<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="/applied-informatics">
    <title>Data Pendaftar - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">
    <!-- <link rel="stylesheet" href="<?= asset_url('css/components/admin_layout.css') ?>"> -->

    <!-- Data Publikasi Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/asisten-lab/index.css') ?>">
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
                    <h1 class="page-title">Data Asisten Lab</h1>
                    <p class="page-subtitle">Kelola data asisten laboratorium Applied Informatics</p>
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
                                placeholder="Cari nama, NIM, atau email..."
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
                    <div class="d-flex gap-3 align-items-center">
                        <label for="statusFilter" class="mb-0 info-value" style="white-space: nowrap;">Status:</label>
                        <select id="statusFilter" class="form-select info-value" style="width: auto; min-width: 150px;">
                            <option value="all" <?= (!isset($_GET['status_aktif']) || $_GET['status_aktif'] === 'all') ? 'selected' : '' ?>>Semua</option>
                            <option value="aktif" <?= (isset($_GET['status_aktif']) && $_GET['status_aktif'] === 'aktif') ? 'selected' : '' ?>>Aktif</option>
                            <option value="tidak_aktif" <?= (isset($_GET['status_aktif']) && $_GET['status_aktif'] === 'tidak_aktif') ? 'selected' : '' ?>>Tidak Aktif</option>
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
                            <th class="col-nim">NIM</th>
                            <th class="col-nama">Nama</th>
                            <th class="col-email">Email</th>
                            <th class="col-tipe">Tipe Anggota</th>
                            <th class="col-tipe">Periode Aktif</th>
                            <th class="col-status">Status</th>
                            <th class="col-created">Diperbarui</th>
                            <th class="col-created">Ditambahkan</th>
                            <th class="action-cell">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($listAsistenLab)): ?>
                            <tr>
                                <td colspan="9">
                                    <div class="empty-state">
                                        <h6>Tidak ada data Asisten Laboratorium</h6>
                                        <p>Mulai dengan seleksi para pendaftar.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($listAsistenLab as $dt): ?>
                                <tr>
                                    <td class="col-id"><?= $dt['id'] ?></td>
                                    <td class="col-posisi"><?= htmlspecialchars($dt['nim']) ?></td>
                                    <td class="col-posisi fw-bold">
                                        <?= htmlspecialchars($dt['nama']) ?>
                                    </td>
                                    <td class="col-posisi"><?= htmlspecialchars($dt['email']) ?></td>
                                    <td class="col-posisi">
                                        <span class="badge <?= ($dt['tipe_anggota'] ?? '') === 'magang' ? 'badge-warning' : 'badge-info' ?>">
                                            <?= ucwords(htmlspecialchars($dt['tipe_anggota'] ?? 'Asisten Lab')) ?>
                                        </span>
                                    </td>
                                    <td class="col-posisi"><?= htmlspecialchars($dt['periode_aktif'] ?? '-') ?></td>
                                    <td>
                                        <?php if ($dt['status_aktif']): ?>
                                            <span class="badge badge-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Tidak Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="col-id"><?= formatTanggal($dt['updated_at'], true) ?></td>
                                    <td class="col-id"><?= formatTanggal($dt['created_at'], true) ?></td>
                                    <td class="action-cell">
                                        <div class="action-buttons">
                                            <a href="<?= base_url('admin/asisten-lab/detail/' . $dt['id']) ?>" class="btn-action btn-view" title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a href="<?= base_url('admin/asisten-lab/edit/' . $dt['id']) ?>" class="btn-action btn-edit" title="Edit">
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
            <?php if (isset($pagination) && $pagination['total_pages'] > 0) :
                // Build query string untuk preserve search parameter
                $searchParam = !empty($_GET['search']) ? '&search=' . urlencode($_GET['search']) : '';
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
                            dari <strong><?= $pagination['total_records'] ?></strong> data
                        </span>
                    </div>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <!-- Previous Button -->
                            <li class="page-item <?= !$pagination['has_prev'] ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="<?= $pagination['has_prev'] ? base_url('admin/asisten-lab?page=' . $pagination['prev_page'] . '&per_page=' . $pagination['per_page'] . $searchParam)
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
                                            href="<?= base_url('admin/asisten-lab?page=' . $pageData['number'] . '&per_page=' . $pagination['per_page'] . $searchParam) ?>">
                                            <?= $pageData['number'] ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <!-- Next Button -->
                            <li class="page-item <?= !$pagination['has_next'] ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="<?= $pagination['has_next'] ? base_url('admin/asisten-lab?page=' . $pagination['next_page'] . '&per_page=' . $pagination['per_page'] . $searchParam)
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

    <!-- Sidebar JS (jQuery Version) -->
    <script src="<?= asset_url('js/components/sidebar.js') ?>"></script>

    <!-- Helper Scripts (Must load before form.js) -->
    <script src="<?= asset_url('js/helpers/jQueryHelpers.js') ?>"></script>

    <script src="<?= asset_url('js/pages/asisten-lab/index.js') ?>"></script>
</body>

</html>