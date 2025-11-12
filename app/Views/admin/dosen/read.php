<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Dosen - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS - Must load first -->
    <link rel="stylesheet" href="<?= asset_url('css/base/variables.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/reset.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Dosen Read Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/dosen/read.css') ?>">
</head>

<body>
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb-custom">
                <a href="<?= base_url('dosen') ?>">Data Dosen</a>
                <span>/</span>
                <span>Detail Dosen</span>
            </div>
            <h1 class="page-title">Detail Dosen</h1>
            <p class="page-subtitle">Informasi lengkap tentang dosen</p>
        </div>

        <!-- Stats Card (Optional) -->
        <div class="stat-card">
            <div class="stat-grid">
                <div class="stat-item">
                    <div class="stat-value">10+</div>
                    <div class="stat-label">Tahun Pengalaman</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">50+</div>
                    <div class="stat-label">Penelitian</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">2</div>
                    <div class="stat-label">Bidang Keahlian</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">100+</div>
                    <div class="stat-label">Mahasiswa Dibimbing</div>
                </div>
            </div>
        </div>

        <!-- Profile Card -->
        <div class="card">
            <div class="card-body">
                <!-- Profile Header -->
                <div class="profile-header">
                    <img src="<?= empty($dosen['foto_profil'])
                                    ? upload_url('default/image.png')
                                    : upload_url('dosen/' . $dosen['foto_profil']) ?>"
                        alt="Profile Photo" class="profile-photo">
                    <div class="profile-info">
                        <h2 class="profile-name"><?= htmlspecialchars($dosen['full_name']) ?></h2>
                        <div class="profile-jabatan"><?= htmlspecialchars($listJabatan[$dosen['jabatan_id']]) ?></div>
                        <div class="profile-meta">
                            <div>
                                <div class="meta-item">
                                    <svg class="meta-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                    <div>
                                        <div class="meta-label">Email</div>
                                        <div class="meta-value"><?= $dosen['email'] ?></div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="meta-item">
                                    <svg class="meta-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="8.5" cy="7" r="4"></circle>
                                        <polyline points="17 11 19 13 23 9"></polyline>
                                    </svg>
                                    <div>
                                        <div class="meta-label">NIDN</div>
                                        <div class="meta-value"><?= htmlspecialchars($dosen['nidn']) ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div style="margin-bottom: 2rem;">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Informasi Dasar
                    </h3>
                    <div class="info-row">
                        <div class="info-label">ID Dosen</div>
                        <div class="info-value"><?= htmlspecialchars($dosen['id']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value"><?= htmlspecialchars($dosen['full_name']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">
                            <a href="mailto:<?= htmlspecialchars($dosen['email']) ?>" style="color: var(--color-primary-500); text-decoration: none;">
                                <?= htmlspecialchars($dosen['email']) ?>
                            </a>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">NIDN</div>
                        <div class="info-value">0123456789</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Jabatan</div>
                        <div class="info-value">
                            <span class="badge-custom badge-primary"><?= htmlspecialchars($listJabatan[$dosen['jabatan_id']]) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Expertise -->
                <div style="margin-bottom: 2rem;">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                        Bidang Keahlian
                    </h3>
                    <div>
                        <?php foreach ($listKeahlian as $dt): ?>
                            <span class="badge-custom badge-secondary"><?= htmlspecialchars($dt['keahlian']) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Description -->
                <div style="margin-bottom: 2rem;">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Deskripsi
                    </h3>

                    <p class="description-text">
                        <?= empty($dosen['deskripsi']) ? "Deskripsi dosen belum ditambahkan." :  $dosen['deskripsi']; ?>
                    </p>
                </div>

                <!-- Profil Publikasi -->
                <!-- <div style="margin-bottom: 2rem;">
                    <h3 class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        Profil Publikasi
                    </h3>
                    <div class="publication-links">

                        <a href="https://sinta.kemdikbud.go.id/authors/profile/6001234" target="_blank" class="publication-card sinta-card">
                            <div class="publication-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                </svg>
                            </div>
                            <div class="publication-info">
                                <div class="publication-name">Sinta</div>
                                <div class="publication-url">sinta.kemdikbud.go.id</div>
                            </div>
                            <div class="publication-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    <polyline points="15 3 21 3 21 9"></polyline>
                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                </svg>
                            </div>
                        </a>


                        <a href="https://scholar.google.com/citations?user=abcd1234" target="_blank" class="publication-card scholar-card">
                            <div class="publication-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <path d="M12 16v-4"></path>
                                    <path d="M12 8h.01"></path>
                                </svg>
                            </div>
                            <div class="publication-info">
                                <div class="publication-name">Google Scholar</div>
                                <div class="publication-url">scholar.google.com</div>
                            </div>
                            <div class="publication-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    <polyline points="15 3 21 3 21 9"></polyline>
                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                </svg>
                            </div>
                        </a>


                        <a href="https://www.scopus.com/authid/detail.uri?authorId=57123456789" target="_blank" class="publication-card scopus-card">
                            <div class="publication-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>
                            </div>
                            <div class="publication-info">
                                <div class="publication-name">Scopus</div>
                                <div class="publication-url">scopus.com</div>
                            </div>
                            <div class="publication-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    <polyline points="15 3 21 3 21 9"></polyline>
                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                </svg>
                            </div>
                        </a>


                        <a href="https://orcid.org/0000-0001-2345-6789" target="_blank" class="publication-card orcid-card">
                            <div class="publication-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <div class="publication-info">
                                <div class="publication-name">ORCID</div>
                                <div class="publication-url">orcid.org</div>
                            </div>
                            <div class="publication-arrow">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    <polyline points="15 3 21 3 21 9"></polyline>
                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                </svg>
                            </div>
                        </a>
                    </div>
                </div> -->

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('dosen') ?>" class="btn-secondary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Kembali
                    </a>
                    <a href="<?= base_url('dosen/edit/' . $dosen['id']) ?>" class="btn-primary-custom">
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

    <!-- Data Dosen Read Page JS -->
    <script src="<?= asset_url('js/pages/dosen/read.js') ?>"></script>
</body>

</html>