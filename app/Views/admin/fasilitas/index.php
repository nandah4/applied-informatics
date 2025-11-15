<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Fasilitas - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Fasilitas Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/fasilitas/index.css') ?>">
</head>

<body>
    <!-- Alert Placeholder -->
    <div id="liveAlertPlaceholder"></div>

    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header-list">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title">Data Fasilitas</h1>
                    <p class="page-subtitle">Kelola data fasilitas Laboratorium Applied Informatics</p>
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
                        <input type="text" class="search-input" placeholder="Cari nama fasilitas...">
                    </div>

                    <!-- Add Button -->
                    <a href="<?= base_url('fasilitas/create') ?>" class="btn-primary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah Fasilitas
                    </a>
                </div>
            </div>

            <!-- Table -->
            <div class="table-wrapper">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th class="col-id">ID</th>
                            <th class="col-name">Nama Fasilitas</th>
                            <th class="col-photo">Foto</th>
                            <th class="action-cell">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (!empty($listFasilitas)) :
                            foreach ($listFasilitas as $fasilitas) :
                        ?>
                                <tr>
                                    <td><?= $fasilitas['fasilitas_id'] ?></td>
                                    <td>
                                        <div class="text-ellipsis" title="<?= htmlspecialchars($fasilitas['nama']) ?>">
                                            <?= htmlspecialchars($fasilitas['nama']) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($fasilitas['foto'])): ?>
                                            <img src="<?= upload_url('fasilitas/' . $fasilitas['foto']) ?>" alt="Foto" class="table-photo" >
                                        <?php else: ?>
                                            <img src="<?= upload_url('default/image.png') ?>" alt="No Photo" class="table-photo">
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-cell">
                                        <div class="action-buttons">
                                            <a href="<?= base_url('fasilitas/detail/' . $fasilitas['fasilitas_id']) ?>" class="btn-action btn-view" title="Lihat Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>
                                            <a href="<?= base_url('fasilitas/edit/' . $fasilitas['fasilitas_id']) ?>" class="btn-action btn-edit" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                                </svg>
                                            </a>
                                            <button
                                                class="btn-action btn-delete"
                                                title="Hapus"
                                                data-fasilitas-id="<?= $fasilitas['fasilitas_id'] ?>"
                                                onclick="confirmDelete(<?= $fasilitas['fasilitas_id'] ?>, '<?= base_url('fasilitas/delete/' . $fasilitas['fasilitas_id']) ?>')">
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
                                <td colspan="4">
                                    <div class="empty-state">
                                        <h6>Tidak ada data fasilitas</h6>
                                        <p>Mulai dengan menambahkan fasilitas pertama Anda</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
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
                                href="<?= $pagination['has_prev'] ? base_url('fasilitas?page=' . $pagination['prev_page'] . '&per_page=' . $pagination['per_page'])
                                            : '#' ?>"
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
                                        href="<?= base_url('fasilitas?page=' . $pageData['number'] . '&per_page=' . $pagination['per_page']) ?>">
                                        <?= $pageData['number'] ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <!-- Next Button -->
                        <li class="page-item <?= !$pagination['has_next'] ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="<?= $pagination['has_next'] ? base_url('fasilitas?page=' . $pagination['next_page'] . '&per_page=' . $pagination['per_page'])
                                            : '#' ?>">
                                Next
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
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

    <!-- Data Fasilitas Page JS -->
    <script src="<?= asset_url('js/pages/fasilitas/index.js') ?>"></script>

</body>

</html>