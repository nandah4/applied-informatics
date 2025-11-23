<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mitra - Applied Informatics Laboratory</title>
    <meta name="base-url" content="/applied-informatics">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Mitra Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/mitra/index.css') ?>">
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
                    <h1 class="page-title">Data Mitra</h1>
                    <p class="page-subtitle">Kelola data mitra Laboratorium Applied Informatics</p>
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
                    <div class="search-wrapper">
                        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                        <input type="text" class="search-input" placeholder="Cari nama mitra...">
                    </div>

                    <!-- Add Button -->
                    <a href="<?= base_url('admin/mitra/create') ?>" class="btn-primary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah Mitra
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="table-wrapper">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th class="col-id">ID</th>
                            <th class="col-logo">Logo</th>
                            <th class="col-name">Nama Mitra</th>
                            <th class="col-kategori">Kategori</th>
                            <th class="col-status">Status</th>
                            <th class="action-cell">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Helper function untuk kategori badge class
                        function getKategoriClass($kategori)
                        {
                            $mapping = [
                                'industri' => 'badge-industri',
                                'internasional' => 'badge-internasional',
                                'institusi pemerintah' => 'badge-institusi-pemerintah',
                                'institusi pendidikan' => 'badge-institusi-pendidikan',
                                'komunitas' => 'badge-komunitas'
                            ];
                            return $mapping[$kategori] ?? 'badge-industri';
                        }

                        // Helper function untuk format kategori label
                        function formatKategoriLabel($kategori)
                        {
                            return ucwords($kategori);
                        }

                        if (!empty($listMitra)) :
                            foreach ($listMitra as $mitra) :
                                $logoUrl = $mitra['logo_mitra']
                                    ? upload_url('mitra/' . $mitra['logo_mitra'])
                                    : upload_url('default/image.png');

                                $kategoriClass = getKategoriClass($mitra['kategori']);
                                $kategoriLabel = formatKategoriLabel($mitra['kategori']);
                                $statusLabel = ucfirst($mitra['status']);
                        ?>
                                <tr>
                                    <td class="col-id"><?= htmlspecialchars($mitra['id']) ?></td>
                                    <td class="col-logo">
                                        <img src="<?= $logoUrl ?>" alt="Logo <?= htmlspecialchars($mitra['nama']) ?>" class="logo-partner">
                                    </td>
                                    <td class="col-name">
                                        <div class="partner-name"><?= htmlspecialchars($mitra['nama']) ?></div>
                                    </td>
                                    <td class="col-kategori">
                                        <span class="badge-kategori <?= $kategoriClass ?>"><?= $kategoriLabel ?></span>
                                    </td>
                                    <td class="col-status"><?= $statusLabel ?></td>
                                    <td class="action-cell">
                                        <div class="action-buttons">
                                            <a href="<?= base_url('admin/mitra/detail/' . $mitra['id']) ?>" class="btn-action btn-view" title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a href="<?= base_url('admin/mitra/edit/' . $mitra['id']) ?>" class="btn-action btn-edit" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                </svg>
                                            </a>
                                            <button class="btn-action btn-delete" title="Hapus" onclick="confirmDelete(<?= $mitra['id'] ?>, '<?= htmlspecialchars($mitra['nama']) ?>')">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                        else :
                            ?>
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <h6>Tidak ada data mitra</h6>
                                        <p>Mulai dengan menambahkan mitra pertama Anda</p>
                                    </div>
                                </td>
                            </tr>
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
                                   href="<?= $pagination['has_prev'] ? base_url('admin/mitra?page=' . $pagination['prev_page'] . '&per_page=' . $pagination['per_page']) : '#' ?>"
                                   tabindex="<?= !$pagination['has_prev'] ? '-1' : '' ?>">
                                    Previous
                                </a>
                            </li>

                            <!-- Page Numbers -->
                            <?php foreach ($pagination['page_numbers'] as $pageData) : ?>
                                <?php if ($pageData['is_ellipsis']) : ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php else : ?>
                                    <li class="page-item <?= $pageData['number'] == $pagination['current_page'] ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= base_url('admin/mitra?page=' . $pageData['number'] . '&per_page=' . $pagination['per_page']) ?>">
                                            <?= $pageData['number'] ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <!-- Next Button -->
                            <li class="page-item <?= !$pagination['has_next'] ? 'disabled' : '' ?>">
                                <a class="page-link"
                                   href="<?= $pagination['has_next'] ? base_url('admin/mitra?page=' . $pagination['next_page'] . '&per_page=' . $pagination['per_page']) : '#' ?>">
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

    <!-- Sidebar JS (jQuery Version) -->
    <script src="<?= asset_url('js/components/sidebar.js') ?>"></script>

    <!-- Helper Scripts -->
    <script src="<?= asset_url('js/helpers/jQueryHelpers.js') ?>"></script>

    <!-- Page Specific Scripts -->
    <script src="<?= asset_url('js/pages/mitra/index.js') ?>"></script>

</body>

</html>