<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';
?>

<main class="mitra-page">

    <div class="container">
        <div class="breadcrumb-nav">
            <span class="breadcrumb-item">Laboratorium Applied Informatics</span>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-item active">Produk Laboratorium</span>
        </div>
    </div>

    <div class="container">

        <div class="mb-4">
            <h1 class="title-section mb-3">Semua Mitra</h1>
            <p class="subtitle-section 2-75">Beragam mitra yang bergabung dengan kami.</p>
        </div>

        <div class="divider-hr"></div>

        <!-- Section Mitra -->
        <section class="mitra-section">
            <div class="">
                <!-- Accordion Mitra -->
                <div class="accordion mitra-accordion" id="mitraAccordion">

                    <!-- Mitra Internasional -->
                    <div class="accordion-item mitra-accordion-item">
                        <h2 class="accordion-header" id="headingInternational">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseInternational" aria-expanded="false"
                                aria-controls="collapseInternational">
                                <div class="d-flex align-items-center gap-3 w-100">
                                    <div class="accordion-icon">
                                        <i data-feather="globe"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="accordion-title">Mitra Internasional</span>
                                    </div>
                                </div>
                            </button>
                        </h2>

                        <?php if (empty($internasionalList)): ?>
                            <div class="text-center py-5">
                                <div class="mb-4 d-flex justify-content-center">
                                    <i data-feather="user-minus" class="icon-something-not-found"></i>
                                </div>
                                <h5 class="text-muted mb-2">Belum Ada Mitra Industri.</h5>
                                <p class="text-secondary mb-0">Belum ada mitra yang tersedia.</p>
                            </div>
                        <?php else: ?>

                            <?php
                            // Ambil 4 data pertama untuk preview
                            $previewMitra = array_slice($internasionalList, 0, 4);

                            // Ambil sisanya (mulai dari offset 4 sampai habis)
                            $hiddenMitra = array_slice($internasionalList, 4);
                            ?>

                            <div class="mitra-preview p-4">
                                <div class="row g-3">
                                    <?php foreach ($previewMitra as $mitra): ?>
                                        <div class="col-6 col-md-3">
                                            <div class="mitra-card">
                                                <div class="mitra-logo-container">
                                                    <?php if (!empty($mitra['logo_mitra'])): ?>
                                                        <img src="<?= upload_url('mitra/' . $mitra['logo_mitra']) ?>"
                                                            alt="<?= htmlspecialchars($mitra['nama']) ?>" class="img-fluid">
                                                    <?php else: ?>
                                                        <span class="text-muted">No Logo</span>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="mitra-name"><?= htmlspecialchars($mitra['nama']) ?></p>

                                                <span class="badge mitra-badge">
                                                    <?= htmlspecialchars($mitra['kategori'] ?? 'Mitra') ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <?php if (count($hiddenMitra) > 0): ?>

                                <div id="collapseInternational" class="accordion-collapse collapse"
                                    aria-labelledby="headingInternational" data-bs-parent="#mitraAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <?php foreach ($hiddenMitra as $mitra): ?>
                                                <div class="col-6 col-md-3">
                                                    <div class="mitra-card">
                                                        <div class="mitra-logo-container">
                                                            <?php if (!empty($mitra['logo_mitra'])): ?>
                                                                <img src="<?= upload_url('images/mitra/' . $mitra['logo_mitra']) ?>"
                                                                    alt="<?= htmlspecialchars($mitra['nama']) ?>" class="img-fluid">
                                                            <?php else: ?>
                                                                <span class="text-muted">No Logo</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <p class="mitra-name"><?= htmlspecialchars($mitra['nama']) ?></p>
                                                        <span class="badge mitra-badge">
                                                            <?= htmlspecialchars($mitra['kategori'] ?? 'Mitra') ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>


                    </div>

                    <!-- Mitra Institusi Pendidikan -->
                    <div class="accordion-item mitra-accordion-item">
                        <h2 class="accordion-header" id="headingInternational">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseInternational" aria-expanded="false"
                                aria-controls="collapseInternational">
                                <div class="d-flex align-items-center gap-3 w-100">
                                    <div class="accordion-icon">
                                        <i data-feather="book"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="accordion-title">Mitra Institusi Pendidikan</span>
                                    </div>
                                </div>
                            </button>
                        </h2>

                        <?php if (empty($pendidikanList)): ?>
                            <div class="text-center py-5">
                                <div class="mb-4 d-flex justify-content-center">
                                    <i data-feather="user-minus" class="icon-something-not-found"></i>
                                </div>
                                <h5 class="text-muted mb-2">Belum Ada Mitra Instansi Pendidikan.</h5>
                                <p class="text-secondary mb-0">Belum ada mitra yang tersedia.</p>
                            </div>
                        <?php else: ?>

                            <?php
                            // Ambil 4 data pertama untuk preview
                            $previewMitra = array_slice($pendidikanList, 0, 4);

                            // Ambil sisanya (mulai dari offset 4 sampai habis)
                            $hiddenMitra = array_slice($pendidikanList, 4);
                            ?>

                            <div class="mitra-preview p-4">
                                <div class="row g-3">
                                    <?php foreach ($previewMitra as $mitra): ?>
                                        <div class="col-6 col-md-3">
                                            <div class="mitra-card">
                                                <div class="mitra-logo-container">
                                                    <?php if (!empty($mitra['logo_mitra'])): ?>
                                                        <img src="<?= upload_url('mitra/' . $mitra['logo_mitra']) ?>"
                                                            alt="<?= htmlspecialchars($mitra['nama']) ?>" class="img-fluid">
                                                    <?php else: ?>
                                                        <span class="text-muted">No Logo</span>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="mitra-name"><?= htmlspecialchars($mitra['nama']) ?></p>

                                                <span class="badge mitra-badge">
                                                    <?= htmlspecialchars($mitra['kategori'] ?? 'Mitra') ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <?php if (count($hiddenMitra) > 0): ?>

                                <div id="collapseInternational" class="accordion-collapse collapse"
                                    aria-labelledby="headingInternational" data-bs-parent="#mitraAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <?php foreach ($hiddenMitra as $mitra): ?>
                                                <div class="col-6 col-md-3">
                                                    <div class="mitra-card">
                                                        <div class="mitra-logo-container">
                                                            <?php if (!empty($mitra['logo_mitra'])): ?>
                                                                <img src="<?= upload_url('images/mitra/' . $mitra['logo_mitra']) ?>"
                                                                    alt="<?= htmlspecialchars($mitra['nama']) ?>" class="img-fluid">
                                                            <?php else: ?>
                                                                <span class="text-muted">No Logo</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <p class="mitra-name"><?= htmlspecialchars($mitra['nama']) ?></p>
                                                        <span class="badge mitra-badge">
                                                            <?= htmlspecialchars($mitra['kategori'] ?? 'Mitra') ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>


                    </div>

                    <!-- Mitra Instansi Pemerintah -->
                    <div class="accordion-item mitra-accordion-item">
                        <h2 class="accordion-header" id="headingInternational">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseInternational" aria-expanded="false"
                                aria-controls="collapseInternational">
                                <div class="d-flex align-items-center gap-3 w-100">
                                    <div class="accordion-icon">
                                        <i data-feather="shield"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="accordion-title">Mitra Institusi Pemerintah</span>
                                    </div>
                                </div>
                            </button>
                        </h2>

                        <?php if (empty($institusiPemerintahList)): ?>
                            <div class="text-center py-5">
                                <div class="mb-4 d-flex justify-content-center">
                                    <i data-feather="user-minus" class="icon-something-not-found"></i>
                                </div>
                                <h5 class="text-muted mb-2">Belum Ada Mitra Institusi Pemerintah .</h5>
                                <p class="text-secondary mb-0">Belum ada mitra yang tersedia.</p>
                            </div>
                        <?php else: ?>

                            <?php
                            // Ambil 4 data pertama untuk preview
                            $previewMitra = array_slice($institusiPemerintahList, 0, 4);

                            // Ambil sisanya (mulai dari offset 4 sampai habis)
                            $hiddenMitra = array_slice($institusiPemerintahList, 4);
                            ?>

                            <div class="mitra-preview p-4">
                                <div class="row g-3">
                                    <?php foreach ($previewMitra as $mitra): ?>
                                        <div class="col-6 col-md-3">
                                            <div class="mitra-card">
                                                <div class="mitra-logo-container">
                                                    <?php if (!empty($mitra['logo_mitra'])): ?>
                                                        <img src="<?= upload_url('mitra/' . $mitra['logo_mitra']) ?>"
                                                            alt="<?= htmlspecialchars($mitra['nama']) ?>" class="img-fluid">
                                                    <?php else: ?>
                                                        <span class="text-muted">No Logo</span>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="mitra-name"><?= htmlspecialchars($mitra['nama']) ?></p>

                                                <span class="badge mitra-badge">
                                                    <?= htmlspecialchars($mitra['kategori'] ?? 'Mitra') ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <?php if (count($hiddenMitra) > 0): ?>

                                <div id="collapseInternational" class="accordion-collapse collapse"
                                    aria-labelledby="headingInternational" data-bs-parent="#mitraAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <?php foreach ($hiddenMitra as $mitra): ?>
                                                <div class="col-6 col-md-3">
                                                    <div class="mitra-card">
                                                        <div class="mitra-logo-container">
                                                            <?php if (!empty($mitra['logo_mitra'])): ?>
                                                                <img src="<?= upload_url('images/mitra/' . $mitra['logo_mitra']) ?>"
                                                                    alt="<?= htmlspecialchars($mitra['nama']) ?>" class="img-fluid">
                                                            <?php else: ?>
                                                                <span class="text-muted">No Logo</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <p class="mitra-name"><?= htmlspecialchars($mitra['nama']) ?></p>
                                                        <span class="badge mitra-badge">
                                                            <?= htmlspecialchars($mitra['kategori'] ?? 'Mitra') ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>


                    </div>

                    <!-- Mitra Industri -->
                    <div class="accordion-item mitra-accordion-item">
                        <h2 class="accordion-header" id="headingInternational">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseInternational" aria-expanded="false"
                                aria-controls="collapseInternational">
                                <div class="d-flex align-items-center gap-3 w-100">
                                    <div class="accordion-icon">
                                        <i data-feather="slack"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <span class="accordion-title">Mitra Institusi Pemerintah</span>
                                    </div>
                                </div>
                            </button>
                        </h2>

                        <?php if (empty($industriList)): ?>
                            <div class="text-center py-5">
                                <div class="mb-4 d-flex justify-content-center">
                                    <i data-feather="user-minus" class="icon-something-not-found"></i>
                                </div>
                                <h5 class="text-muted mb-2">Belum Ada Mitra Industri .</h5>
                                <p class="text-secondary mb-0">Belum ada mitra yang tersedia.</p>
                            </div>
                        <?php else: ?>

                            <?php
                            // Ambil 4 data pertama untuk preview
                            $previewMitra = array_slice($industriList, 0, 4);

                            // Ambil sisanya (mulai dari offset 4 sampai habis)
                            $hiddenMitra = array_slice($industriList, 4);
                            ?>

                            <div class="mitra-preview p-4">
                                <div class="row g-3">
                                    <?php foreach ($previewMitra as $mitra): ?>
                                        <div class="col-6 col-md-3">
                                            <div class="mitra-card">
                                                <div class="mitra-logo-container">
                                                    <?php if (!empty($mitra['logo_mitra'])): ?>
                                                        <img src="<?= upload_url('mitra/' . $mitra['logo_mitra']) ?>"
                                                            alt="<?= htmlspecialchars($mitra['nama']) ?>" class="img-fluid">
                                                    <?php else: ?>

                                                        <span class="text-muted">No Logo</span>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="mitra-name"><?= htmlspecialchars($mitra['nama']) ?></p>

                                                <span class="badge mitra-badge">
                                                    <?= htmlspecialchars($mitra['kategori'] ?? 'Mitra') ?>
                                                </span>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <?php if (count($hiddenMitra) > 0): ?>

                                <div id="collapseInternational" class="accordion-collapse collapse"
                                    aria-labelledby="headingInternational" data-bs-parent="#mitraAccordion">
                                    <div class="accordion-body">
                                        <div class="row g-3">
                                            <?php foreach ($hiddenMitra as $mitra): ?>
                                                <div class="col-6 col-md-3">
                                                    <div class="mitra-card">
                                                        <div class="mitra-logo-container">
                                                            <?php if (!empty($mitra['logo_mitra'])): ?>
                                                                <img src="<?= upload_url('images/mitra/' . $mitra['logo_mitra']) ?>"
                                                                    alt="<?= htmlspecialchars($mitra['nama']) ?>" class="img-fluid">
                                                            <?php else: ?>
                                                                <span class="text-muted">No Logo</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <p class="mitra-name"><?= htmlspecialchars($mitra['nama']) ?></p>
                                                        <span class="badge mitra-badge">
                                                            <?= htmlspecialchars($mitra['kategori'] ?? 'Mitra') ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>


                    </div>



                </div>
            </div>
        </section>

        <!-- internasional -->
        <!-- <h1 class="mb-4 cakupan-wilayah">International</h1>
        <div class="row mb-5">


            <div class="col-sm-6 col-md-3 text-center">
                <div class="card-container mx-auto mb-1">
                    <div class="logo-container">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                </div>
                <p class="fw-bold nama-mitra">Gitea</p>
            </div>

            <div class="col-sm-6 col-md-3 text-center">
                <div class="card-container mx-auto mb-1">
                    <div class="logo-container">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                </div>
                <p class="fw-bold nama-mitra">Gitea</p>
            </div>

            <div class="col-sm-6 col-md-3 text-center">
                <div class="card-container mx-auto mb-1">
                    <div class="logo-container">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                </div>
                <p class="fw-bold nama-mitra">Gitea</p>
            </div>

            <div class="col-sm-6 col-md-3 text-center">
                <div class="card-container mx-auto mb-1">
                    <div class="logo-container">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                </div>
                <p class="fw-bold nama-mitra">Gitea</p>
            </div>
        </div>


        <h1 class="mb-4 cakupan-wilayah">National</h1>
        <div class="row mb-5">
            <div class="col-sm-6 col-md-3 text-center">
                <div class="card-container mx-auto mb-1">
                    <div class="logo-container">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                </div>
                <p class="fw-bold nama-mitra">Gitea</p>
            </div>

            <div class="col-sm-6 col-md-3 text-center">
                <div class="card-container mx-auto mb-1">
                    <div class="logo-container">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                </div>
                <p class="fw-bold nama-mitra">Gitea</p>
            </div>

            <div class="col-sm-6 col-md-3 text-center">
                <div class="card-container mx-auto mb-1">
                    <div class="logo-container">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                </div>
                <p class="fw-bold nama-mitra">Gitea</p>
            </div>

            <div class="col-sm-6 col-md-3 text-center">
                <div class="card-container mx-auto mb-1">
                    <div class="logo-container">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                </div>
                <p class="fw-bold nama-mitra">Gitea</p>
            </div>

            <div class="col-sm-6 col-md-3 text-center">
                <div class="card-container mx-auto mb-1">
                    <div class="logo-container">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                </div>
                <p class="fw-bold nama-mitra">Gitea</p>
            </div>
        </div> -->

    </div>



</main>

<link rel="stylesheet" href="<?= asset_url('css/pages/mitra-user/mitra.css') ?>">

<!--footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>