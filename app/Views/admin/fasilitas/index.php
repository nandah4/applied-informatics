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
                        <!-- Sample Data Row 1 -->
                        <tr>
                            <td class="col-id">1</td>
                            <td class="col-name">
                                <div class="facility-name">Komputer Lab A</div>
                            </td>
                            <td class="col-photo">
                                <img src="https://via.placeholder.com/80x60/01b5b9/ffffff?text=Lab+A" alt="Photo" class="photo-facility">
                            </td>
                            <td class="action-cell">
                                <div class="action-buttons">
                                    <a href="<?= base_url('fasilitas/detail') ?>" class="btn-action btn-view" title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>
                                    <a href="<?= base_url('fasilitas/edit') ?>" class="btn-action btn-edit" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                        </svg>
                                    </a>
                                    <button class="btn-action btn-delete" title="Hapus" onclick="confirmDelete(1)">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Sample Data Row 2 -->
                        <tr>
                            <td class="col-id">2</td>
                            <td class="col-name">
                                <div class="facility-name">Proyektor LCD</div>
                            </td>
                            <td class="col-photo">
                                <img src="https://via.placeholder.com/80x60/ff7d10/ffffff?text=Proyektor" alt="Photo" class="photo-facility">
                            </td>
                            <td class="action-cell">
                                <div class="action-buttons">
                                    <a href="<?= base_url('fasilitas/detail/2') ?>" class="btn-action btn-view" title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>
                                    <a href="<?= base_url('fasilitas/edit/2') ?>" class="btn-action btn-edit" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                        </svg>
                                    </a>
                                    <button class="btn-action btn-delete" title="Hapus" onclick="confirmDelete(2)">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Sample Data Row 3 -->
                        <tr>
                            <td class="col-id">3</td>
                            <td class="col-name">
                                <div class="facility-name">Ruang Meeting</div>
                            </td>
                            <td class="col-photo">
                                <img src="https://via.placeholder.com/80x60/02416c/ffffff?text=Meeting" alt="Photo" class="photo-facility">
                            </td>
                            <td class="action-cell">
                                <div class="action-buttons">
                                    <a href="<?= base_url('fasilitas/detail/3') ?>" class="btn-action btn-view" title="Lihat Detail">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>
                                    <a href="<?= base_url('fasilitas/edit/3') ?>" class="btn-action btn-edit" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                                        </svg>
                                    </a>
                                    <button class="btn-action btn-delete" title="Hapus" onclick="confirmDelete(3)">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Empty State (Uncomment when there's no data) -->
                        <!-- <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                    </svg>
                                    <h6>Tidak ada data fasilitas</h6>
                                    <p>Mulai dengan menambahkan fasilitas pertama Anda</p>
                                </div>
                            </td>
                        </tr> -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Menampilkan <strong>1-3</strong> dari <strong>3</strong> data
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
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

    <script>
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus fasilitas ini?')) {
                // Handle delete action
                console.log('Delete facility ID:', id);
                // Add your delete logic here
            }
        }
    </script>

</body>

</html>
