<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';
?>

<main class="mitra-page">

    <div class="container-fluid px-md-5 pb-5">
        <div class="breadcrumb-nav">
            <span class="breadcrumb-item">Laboratorium Applied Informatics</span>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-item active">Mitra Laboratorium</span>
        </div>

        <div class="mb-5">
            <h1 class="title-section mb-3">Semua Mitra</h1>
            <p class="subtitle-section 2-75">Mitra yang bergabung dengan kami.</p>
        </div>


        <!-- Section Mitra -->
        <section class="mitra-section">

            <?php if (empty($industriList) && empty($pendidikanList) && empty($institusiPemerintahList) && empty($internasionalList) && empty($komunitasList)): ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-back fa-4x text-muted opacity-50" style="font-size: 4rem;"></i>
                        </div>
                        <h5 class="text-muted mb-2">Belum Ada Mitra</h5>
                        <p class="text-muted mb-0">Belum terdapat data mitra yang dapat ditampilkan..</p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Accordion Mitra -->
            <div class="accordion mitra-accordion" id="mitraAccordion">

                <!-- Mitra Internasional -->
                <?php if (!empty($internasionalList)): ?>
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

                        <?php
                        // Ambil 4 data pertama untuk preview
                        $previewMitra = array_slice($internasionalList, 0, 4);

                        // Ambil sisanya (mulai dari offset 4 sampai habis)
                        $hiddenMitra = array_slice($internasionalList, 4);
                        ?>

                        <div class="mitra-preview p-4">
                            <div class="row g-3">
                                <?php foreach ($previewMitra as $mitra): ?>
                                    <div class="col-12 col-md-3">
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
                                            <div class="col-12 col-md-3">
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
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Mitra Institusi Pendidikan -->
                <?php if (!empty($pendidikanList)): ?>
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


                        <?php
                        // Ambil 4 data pertama untuk preview
                        $previewMitra = array_slice($pendidikanList, 0, 4);

                        // Ambil sisanya (mulai dari offset 4 sampai habis)
                        $hiddenMitra = array_slice($pendidikanList, 4);
                        ?>

                        <div class="mitra-preview p-4">
                            <div class="row g-3">
                                <?php foreach ($previewMitra as $mitra): ?>
                                    <div class="col-12 col-md-3">
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
                                            <div class="col-12 col-md-3">
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
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Mitra Instansi Pemerintah -->
                <?php if (!empty($institusiPemerintahList)): ?>
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



                        <?php
                        // Ambil 4 data pertama untuk preview
                        $previewMitra = array_slice($institusiPemerintahList, 0, 4);

                        // Ambil sisanya (mulai dari offset 4 sampai habis)
                        $hiddenMitra = array_slice($institusiPemerintahList, 4);
                        ?>

                        <div class="mitra-preview p-4">
                            <div class="row g-3">
                                <?php foreach ($previewMitra as $mitra): ?>
                                    <div class="col-12 col-md-3">
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
                                            <div class="col-12 col-md-3">
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
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Mitra Industri -->
                <?php if (!empty($industriList)): ?>
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
                                        <span class="accordion-title">Mitra Industri</span>
                                    </div>
                                </div>
                            </button>
                        </h2>



                        <?php
                        // Ambil 4 data pertama untuk preview
                        $previewMitra = array_slice($industriList, 0, 4);

                        // Ambil sisanya (mulai dari offset 4 sampai habis)
                        $hiddenMitra = array_slice($industriList, 4);
                        ?>

                        <div class="mitra-preview p-4">
                            <div class="row g-3">
                                <?php foreach ($previewMitra as $mitra): ?>
                                    <div class="col-12 col-md-3">
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
                                            <div class="col-12 col-md-3">
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
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Mitra Komunitas -->
                <?php if (!empty($komunitasList)): ?>
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
                                        <span class="accordion-title">Mitra Komunitas</span>
                                    </div>
                                </div>
                            </button>
                        </h2>



                        <?php
                        // Ambil 4 data pertama untuk preview
                        $previewMitra = array_slice($komunitasList, 0, 4);

                        // Ambil sisanya (mulai dari offset 4 sampai habis)
                        $hiddenMitra = array_slice($komunitasList, 4);
                        ?>

                        <div class="mitra-preview p-4">
                            <div class="row g-3">
                                <?php foreach ($previewMitra as $mitra): ?>
                                    <div class="col-12 col-md-3">
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
                                            <div class="col-12 col-md-3">
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
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        </section>
    </div>
</main>

<link rel="stylesheet" href="<?= asset_url('css/pages/mitra/mitra_user.css') ?>">

<!--footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>