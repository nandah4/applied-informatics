<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="base-url" content="/applied-informatics">
    <meta name="csrf-token" content="<?= CsrfHelper::generateToken() ?>">
    <title>Detail Dosen - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Base CSS - Must load first -->
    <link rel="stylesheet" href="<?= asset_url('css/base/variables.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Data Dosen Read Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/dosen/read.css') ?>">
</head>

<body>
    <div id="liveAlertPlaceholder"></div>

    <!-- CSRF Token -->
    <?= CsrfHelper::tokenField() ?>

    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layouts/sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb-custom">
                <a href="<?= base_url('admin/dosen') ?>">Data Dosen</a>
                <span>/</span>
                <span>Detail Dosen</span>
            </div>
            <h1 class="page-title">Detail Dosen</h1>
            <p class="page-subtitle">Informasi lengkap tentang dosen</p>
        </div>

        <!-- Profile Card -->
        <div class="card">
            <div class="card-body">
                <!-- Profile Header -->
                <div class="profile-header">
                    <img src="<?= empty($dosenData['foto_profil'])
                                    ? upload_url('default/image.png')
                                    : upload_url('dosen/' . $dosenData['foto_profil']) ?>"
                        alt="Profile Photo" class="profile-photo">
                    <div class="profile-info">
                        <h2 class="profile-name"><?= htmlspecialchars($dosenData['full_name']) ?></h2>
                        <div class="profile-jabatan"><?= htmlspecialchars($dosenData['jabatan_name']) ?></div>
                        <div class="profile-meta">
                            <div>
                                <div class="meta-item">
                                    <svg class="meta-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                    <div>
                                        <div class="meta-label">Email</div>
                                        <div class="meta-value"><?= htmlspecialchars($dosenData['email']) ?></div>
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
                                        <div class="meta-value"><?= htmlspecialchars($dosenData['nidn']) ?></div>
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
                                        <div class="meta-label">NIP</div>
                                        <div class="meta-value"><?= htmlspecialchars($dosenData['nip'] ?? '-') ?></div>
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
                        <div class="info-value"><?= htmlspecialchars($dosenData['id']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value"><?= htmlspecialchars($dosenData['full_name']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">
                            <a href="mailto:<?= htmlspecialchars($dosenData['email']) ?>" style="color: var(--color-primary-500); text-decoration: none;">
                                <?= htmlspecialchars($dosenData['email']) ?>
                            </a>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">NIDN</div>
                        <div class="info-value"><?= htmlspecialchars($dosenData['nidn']) ?></div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Jabatan</div>
                        <div class="info-value">
                            <span class="badge-custom badge-primary"><?= htmlspecialchars($dosenData['jabatan_name']) ?></span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Status</div>
                        <div class="info-value">
                            <?php if ($dosenData['status_aktif']): ?>
                                <span class="badge-custom badge-secondary">Aktif</span>
                            <?php else: ?>
                                <span class="badge-custom badge-primary">Tidak Aktif</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Terakhir Diperbarui</div>
                        <div class="info-value"><?= formatTanggal($dosenData['updated_at'], true) ?></div>
                    </div>

                    <div class="info-row">
                        <div class="info-label">Tanggal Ditambahkan</div>
                        <div class="info-value"><?= formatTanggal($dosenData['created_at'], true) ?></div>
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
                        <?php if (empty($dosenData['keahlian_list'])): ?>
                            <p class="description-text">
                                Keahlian dosen belum ditambahkan.
                            </p>
                        <?php else: ?>
                            <?php
                            $keahlianArray = explode(', ', $dosenData['keahlian_list']);

                            foreach ($keahlianArray as $dt): ?>
                                <span class="badge-custom badge-secondary"><?= htmlspecialchars($dt) ?></span>
                            <?php endforeach;
                            ?>
                        <?php endif; ?>

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
                        <?= empty($dosenData['deskripsi']) ? "Deskripsi dosen belum ditambahkan." : htmlspecialchars($dosenData['deskripsi']); ?>
                    </p>
                </div>

                <!-- Profil Publikasi -->
                <div style="margin-bottom: 2rem;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="section-title mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            Profil Publikasi
                        </h3>
                        <button type="button" class="btn-add-option d-flex gap-2" data-bs-toggle="modal" data-bs-target="#addProfilModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Tambah
                        </button>
                    </div>

                    <?php if (empty($profilPublikasi)): ?>
                        <p class="description-text">Belum ada profil publikasi yang ditambahkan.</p>
                    <?php else: ?>
                        <div class="publication-links">
                            <?php
                            $tipeLabels = [
                                'SINTA' => 'SINTA',
                                'SCOPUS' => 'Scopus',
                                'GOOGLE_SCHOLAR' => 'Google Scholar',
                                'ORCID' => 'ORCID',
                                'RESEARCHGATE' => 'ResearchGate'
                            ];

                            $tipeClasses = [
                                'SINTA' => 'sinta-card',
                                'SCOPUS' => 'scopus-card',
                                'GOOGLE_SCHOLAR' => 'scholar-card',
                                'ORCID' => 'orcid-card',
                                'RESEARCHGATE' => 'researchgate-card'
                            ];

                            foreach ($profilPublikasi as $profil):
                                $label = $tipeLabels[$profil['tipe']] ?? $profil['tipe'];
                                $cardClass = $tipeClasses[$profil['tipe']] ?? '';
                                $urlHost = parse_url($profil['url_profil'], PHP_URL_HOST) ?? '';
                            ?>
                                <div class="publication-card-wrapper mb-2">
                                    <a href="<?= htmlspecialchars($profil['url_profil']) ?>" target="_blank" class="publication-card <?= $cardClass ?>">
                                        <div class="publication-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="publication-info">
                                            <div class="publication-name"><?= htmlspecialchars($label) ?></div>
                                            <div class="publication-url"><?= htmlspecialchars($urlHost) ?></div>
                                        </div>
                                        <div class="publication-arrow">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                                <polyline points="15 3 21 3 21 9"></polyline>
                                                <line x1="10" y1="14" x2="21" y2="3"></line>
                                            </svg>
                                        </div>
                                    </a>
                                    <div class="publication-actions mt-3 d-flex gap-2">
                                        <button type="button" class="btn-primary-custom"
                                            onclick="editProfilPublikasi(<?= $profil['id'] ?>, '<?= htmlspecialchars($profil['url_profil'], ENT_QUOTES) ?>', '<?= htmlspecialchars($label) ?>')">
                                            Edit
                                        </button>
                                        <button type="button" class="btn-danger-custom"
                                            onclick="deleteProfilPublikasi(<?= $profil['id'] ?>)">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Modal Add Profil Publikasi -->
                <div class="modal fade" id="addProfilModal" tabindex="-1" aria-labelledby="addProfilModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addProfilModalLabel">Tambah Profil Publikasi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="url_profil" class="form-label">URL Profil <span class="required">*</span></label>
                                    <input type="url" class="form-control" id="url_profil" placeholder="https://...">
                                    <div id="urlProfilError" class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3">
                                    <label for="tipe" class="form-label">Tipe Profil <span class="required">*</span></label>
                                    <select class="form-select" id="tipe">
                                        <option value="">Pilih Tipe</option>
                                        <option value="SINTA">SINTA</option>
                                        <option value="SCOPUS">Scopus</option>
                                        <option value="GOOGLE_SCHOLAR">Google Scholar</option>
                                        <option value="ORCID">ORCID</option>
                                        <option value="RESEARCHGATE">ResearchGate</option>
                                    </select>
                                    <div id="tipeError" class="invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                                <button type="button" class="btn-primary-custom" id="btn-add-profil-publikasi">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Edit Profil Publikasi -->
                <div class="modal fade" id="editProfilModal" tabindex="-1" aria-labelledby="editProfilModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editProfilModalLabel">Edit Profil Publikasi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" id="edit_profil_id">
                                <div class="mb-3">
                                    <label for="edit_url_profil" class="form-label">URL Profil <span class="required">*</span></label>
                                    <input type="url" class="form-control" id="edit_url_profil" placeholder="https://...">
                                    <div id="editUrlProfilError" class="invalid-feedback"></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tipe Profil</label>
                                    <input type="text" class="form-control" id="edit_profil_tipe" readonly>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                                <button type="button" class="btn-primary-custom" id="btn-update-profil-publikasi">Update</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('admin/dosen') ?>" class="btn-secondary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        Kembali
                    </a>
                    <a href="<?= base_url('admin/dosen/edit/' . $dosenData['id']) ?>" class="btn-primary-custom">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path>
                        </svg>
                        Edit Data
                    </a>
                    <button class="btn-danger-custom" onclick="confirmDelete(<?= $dosenData['id'] ?>)">
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

    <!-- Helper Scripts -->
    <script src="<?= asset_url('js/helpers/jQueryHelpers.js') ?>"></script>
    <script src="<?= asset_url('js/helpers/validationHelpers.js') ?>"></script>

    <!-- Pass dosen_id to JavaScript -->
    <script>
        const DOSEN_ID = <?= $dosenData['id'] ?>;
    </script>

    <!-- Data Dosen Read Page JS -->
    <script src="<?= asset_url('js/pages/dosen/read.js') ?>"></script>
</body>

</html>