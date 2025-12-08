<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Produk Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/produk/index.css') ?>">
</head>

<body>
    <!-- Alert Placeholder -->
    <div id="liveAlertPlaceholder"></div>

    <!-- CSRF Token Hidden Field -->
    <?= CsrfHelper::tokenField() ?>

    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header-list">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">Data Produk</h1>
                    <p class="page-subtitle">Kelola data produk Laboratorium Applied Informatics</p>
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
                                placeholder="Cari nama atau author produk..."
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
                    <!-- Add Button -->
                    <a href="<?= base_url('admin/produk/create') ?>" class="btn-primary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah Produk
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="table-wrapper">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th class="col-id">ID</th>
                            <th class="col-logo">Foto</th>
                            <th class="col-name">Nama Produk</th>
                            <th class="col-author">Author</th>
                            <th class="col-link">Link Produk</th>
                            <th class="action-cell">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($listProduk)) :
                            foreach ($listProduk as $produk) :

                                $fotoUrl = !empty($produk['foto_produk'])
                                    ? upload_url('produk/' . $produk['foto_produk'])
                                    : upload_url('default/image.png');
                        ?>
                                <tr>
                                    <td class="col-id"><?= htmlspecialchars($produk['id']) ?></td>
                                    <td class="col-foto">
                                        <img src="<?= $fotoUrl ?>" alt="Foto <?= htmlspecialchars($produk['nama_produk']) ?>" class="foto-produk">
                                    </td>
                                    <td class="col-name">
                                        <div class="product-name"><?= htmlspecialchars($produk['nama_produk']) ?></div>
                                    </td>
                                    <td class="col-author">
                                        <?php
                                        $dosenHtml = '';
                                        if (!empty($produk['dosen_names'])) {
                                            // Pecah string berdasarkan delimiter dari View SQL
                                            $dosenArray = explode('-$$$-', $produk['dosen_names']);
                                            $totalDosen = count($dosenArray);

                                            if ($totalDosen > 2) {
                                                $firstTwo = array_slice($dosenArray, 0, 2);
                                                $remaining = array_slice($dosenArray, 2);
                                                $remainingText = htmlspecialchars(implode(', ', $remaining));

                                                // Gabungkan string
                                                $dosenHtml = htmlspecialchars(implode(', ', $firstTwo)) .
                                                    ', <span class="fw-bold" style="cursor:help;" data-bs-toggle="tooltip" title="' . $remainingText . '">...</span>';
                                            } else {
                                                // Jika <= 2, tampilkan semua
                                                $dosenHtml = htmlspecialchars(implode(', ', $dosenArray));
                                            }
                                        }

                                        // --- 2. LOGIC CEK KETERSEDIAAN DATA ---
                                        $hasDosen = !empty($dosenHtml);
                                        $hasMahasiswa = !empty($produk['tim_mahasiswa']);
                                        ?>

                                        <?php if ($hasDosen && $hasMahasiswa): ?>
                                            <div class="author-badges-wrapper d-flex flex-column gap-2">

                                                <div class="d-flex align-items-center">
                                                    <span class="author-badge me-2">
                                                        Dosen
                                                    </span>
                                                    <span class="author-names text-sm">
                                                        <?= $dosenHtml ?>
                                                    </span>
                                                </div>

                                                <div class="d-flex align-items-center">
                                                    <span class="author-badge  me-2">
                                                        Mahasiswa
                                                    </span>
                                                    <span class="author-names text-sm">
                                                        <?= htmlspecialchars($produk['tim_mahasiswa']) ?>
                                                    </span>
                                                </div>
                                            </div>

                                        <?php elseif ($hasDosen): ?>
                                            <div class="d-flex align-items-center">
                                                <span class="author-badge me-2">
                                                    Dosen -
                                                </span>
                                                <span class="author-names text-sm">
                                                    <?= $dosenHtml ?>
                                                </span>
                                            </div>

                                        <?php elseif ($hasMahasiswa): ?>
                                            <div class="d-flex align-items-center">
                                                <span class="author-badge me-2">
                                                    Mahasiswa -
                                                </span>
                                                <span class="author-names text-sm">
                                                    <?= htmlspecialchars($produk['tim_mahasiswa']) ?>
                                                </span>
                                            </div>

                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="col-link">
                                        <?php if (!empty($produk['link_produk'])) : ?>
                                            <a href="<?= htmlspecialchars($produk['link_produk']) ?>" target="_blank" class="product-link">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                    <polyline points="15 3 21 3 21 9"></polyline>
                                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                                </svg>
                                                <?= htmlspecialchars($produk['link_produk']) ?>
                                            </a>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-cell">
                                        <div class="action-buttons">
                                            <a href="<?= base_url('admin/produk/detail/' . $produk['id']) ?>" class="btn-action btn-view" title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a href="<?= base_url('admin/produk/edit/' . $produk['id']) ?>" class="btn-action btn-edit" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                </svg>
                                            </a>
                                            <button
                                                class="btn-action btn-delete"
                                                title="Hapus"
                                                data-produk-id="<?= $produk['id'] ?>"
                                                onclick="confirmDelete(<?= $produk['id'] ?>, '<?= base_url('admin/produk/delete/' . $produk['id']) ?>')">
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
                                        <h6>Tidak ada data produk</h6>
                                        <p>Mulai dengan menambahkan produk pertama Anda</p>
                                    </div>
                                </td>
                            </tr>
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
                                    href="<?= $pagination['has_prev'] ? base_url('admin/produk?page=' . $pagination['prev_page'] . '&per_page=' . $pagination['per_page'] . $searchParam) : '#' ?>"
                                    tabindex="<?= !$pagination['has_prev'] ? '-1' : '' ?>">
                                    Previous
                                </a>
                            </li>

                            <!-- Page Numbers dengan Ellipsis -->
                            <?php foreach ($pagination['page_numbers'] as $pageData): ?>
                                <?php if ($pageData['is_ellipsis']): ?>
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                <?php else: ?>
                                    <li class="page-item <?= ($pageData['number'] == $pagination['current_page']) ? 'active' : '' ?>">
                                        <a class="page-link"
                                            href="<?= base_url('admin/produk?page=' . $pageData['number'] . '&per_page=' . $pagination['per_page'] . $searchParam) ?>">
                                            <?= $pageData['number'] ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <!-- Next Button -->
                            <li class="page-item <?= !$pagination['has_next'] ? 'disabled' : '' ?>">
                                <a class="page-link"
                                    href="<?= $pagination['has_next'] ? base_url('admin/produk?page=' . $pagination['next_page'] . '&per_page=' . $pagination['per_page'] . $searchParam) : '#' ?>">
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

    <!-- Data Produk Page JS -->
    <script src="<?= asset_url('js/pages/produk/index.js') ?>"></script>

</body>

</html>