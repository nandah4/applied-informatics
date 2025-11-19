<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Produk Read Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/produk/read.css') ?>">
</head>

<body>
    <!-- Alert Placeholder -->
    <div id="liveAlertPlaceholder"></div>

    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>

    <?php
    // Generate foto URL
    $fotoUrl = !empty($produk['foto_produk']) 
        ? upload_url('produk/' . $produk['foto_produk']) 
        : upload_url('default/image.png');
    ?>

    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb-custom">
                <a href="<?= base_url('produk') ?>">Data Produk</a>
                <span>/</span>
                <span>Detail Produk</span>
            </div>
            <h1 class="page-title"><?= htmlspecialchars($produk['nama_produk']) ?></h1>
            <p class="page-subtitle">Informasi lengkap tentang produk</p>
        </div>

        <!-- Detail Card -->
        <div class="card">
            <div class="card-body">
                <!-- Foto Produk -->
                <div class="produk-photo-container">
                    <img src="<?= $fotoUrl ?>" alt="Foto <?= htmlspecialchars($produk['nama_produk']) ?>" class="produk-photo">
                </div>

                <!-- Informasi Produk -->
                <div class="produk-info-section">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <polyline points="21 15 16 10 5 21"></polyline>
                        </svg>
                        Informasi Produk
                    </h3>

                    <div class="info-row">
                        <div class="info-label">ID Produk</div>
                        <div class="info-value"><?= $produk['id'] ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Nama Produk</div>
                        <div class="info-value"><?= htmlspecialchars($produk['nama_produk']) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Author</div>
                        <div class="info-value">
                            <?php if (!empty($produk['author_dosen_id']) && !empty($produk['author_mahasiswa_nama'])) : ?>
                                <!-- Kolaborasi: Dosen & Mahasiswa -->
                                <div class="author-badges-wrapper">
                                    <span class="author-badge author-dosen">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <?= htmlspecialchars($produk['dosen_name']) ?> (Dosen)
                                    </span>
                                    <span class="author-badge author-mahasiswa" style="margin-left: 8px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                        <?= htmlspecialchars($produk['author_mahasiswa_nama']) ?> (Mahasiswa)
                                    </span>
                                </div>
                            <?php elseif (!empty($produk['author_dosen_id'])) : ?>
                                <!-- Hanya Dosen -->
                                <span class="author-badge author-dosen">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <?= htmlspecialchars($produk['dosen_name']) ?> (Dosen)
                                </span>
                            <?php elseif (!empty($produk['author_mahasiswa_nama'])) : ?>
                                <!-- Hanya Mahasiswa -->
                                <span class="author-badge author-mahasiswa">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="12" cy="7" r="4"></circle>
                                    </svg>
                                    <?= htmlspecialchars($produk['author_mahasiswa_nama']) ?> (Mahasiswa)
                                </span>
                            <?php else : ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Link Produk</div>
                        <div class="info-value">
                            <?php if (!empty($produk['link_produk'])) : ?>
                                <a href="<?= htmlspecialchars($produk['link_produk']) ?>" target="_blank" class="info-link">
                                    <?= htmlspecialchars($produk['link_produk']) ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                        <polyline points="15 3 21 3 21 9"></polyline>
                                        <line x1="10" y1="14" x2="21" y2="3"></line>
                                    </svg>
                                </a>
                            <?php else : ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Terakhir Diperbarui</div>
                        <div class="info-value">
                            <?= date('d F Y, H:i', strtotime($produk['updated_at'])) ?> WIB
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Tanggal Ditambahkan</div>
                        <div class="info-value">
                            <?= date('d F Y, H:i', strtotime($produk['created_at'])) ?> WIB
                        </div>
                    </div>
                </div>

                <!-- Deskripsi Section -->
                <div class="description-section">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Deskripsi
                    </h3>
                    <p class="description-text">
                        <?= !empty($produk['deskripsi'])
                            ? nl2br(htmlspecialchars($produk['deskripsi']))
                            : 'Tidak ada deskripsi'
                        ?>
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('produk') ?>" class="btn-secondary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Kembali
                    </a>
                    <a href="<?= base_url('produk/edit/' . $produk['id']) ?>" class="btn-primary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg>
                        Edit Data
                    </a>
                    <button 
                        class="btn-danger-custom" 
                        data-produk-id="<?= $produk['id'] ?>"
                        onclick="confirmDelete(<?= $produk['id'] ?>, '<?= base_url('produk/delete/' . $produk['id']) ?>')">
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <!-- Sidebar JS -->
    <script src="<?= asset_url('js/components/sidebar.js') ?>"></script>

    <!-- Helper Scripts -->
    <script src="<?= asset_url('js/helpers/jQueryHelpers.js') ?>"></script>

    <!-- Produk Page JS -->
    <script src="<?= asset_url('js/pages/produk/read.js') ?>"></script>
</body>

</html>