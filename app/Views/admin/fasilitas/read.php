<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Fasilitas - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS - Must load first -->
    <link rel="stylesheet" href="<?= asset_url('css/base/variables.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/reset.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Fasilitas Read Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/fasilitas/read.css') ?>">
</head>

<body>
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb-custom">
                <a href="<?= base_url('fasilitas') ?>">Data Fasilitas</a>
                <span>/</span>
                <span>Detail Fasilitas</span>
            </div>
            <h1 class="page-title">Detail Fasilitas</h1>
            <p class="page-subtitle">Informasi lengkap tentang fasilitas</p>
        </div>

        <!-- Detail Card -->
        <div class="card">
            <div class="card-body">
                <!-- Facility Photo -->
                <div class="facility-photo-container">
                    <img src="https://via.placeholder.com/800x400/01b5b9/ffffff?text=Komputer+Lab+A" alt="Facility Photo" class="facility-photo">
                </div>

                <!-- Facility Information -->
                <div class="facility-info-section">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        Informasi Fasilitas
                    </h3>

                    <div class="info-row">
                        <div class="info-label">ID Fasilitas</div>
                        <div class="info-value">#001</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Nama Fasilitas</div>
                        <div class="info-value">Komputer Lab A</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Deskripsi</div>
                        <div class="info-value">Komputer Lab A</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Tanggal Ditambahkan</div>
                        <div class="info-value">10 November 2024</div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Terakhir Diperbarui</div>
                        <div class="info-value">11 November 2024</div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('fasilitas') ?>" class="btn-secondary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Kembali
                    </a>
                    <a href="<?= base_url('fasilitas/edit/1') ?>" class="btn-primary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg>
                        Edit Data
                    </a>
                    <button class="btn-danger-custom" onclick="confirmDelete()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        Hapus Data
                    </button>
                </div>
            </div>
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

    <script>
        function confirmDelete() {
            if (confirm('Apakah Anda yakin ingin menghapus fasilitas ini?')) {
                // Handle delete action
                console.log('Delete facility');
                alert('Fasilitas berhasil dihapus!');
                window.location.href = '<?= base_url('fasilitas') ?>';
            }
        }
    </script>
</body>

</html>
