<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';
?>


<main class="detail-aktivitas-page">

    <!-- Article Container -->
    <article class="container-fluid px-md-5 pb-5">
        <div class="breadcrumb-nav">
            <a href="<?= base_url('') ?>" class="breadcrumb-item">Laboratorium Applied Informatics</a>
            <span class="breadcrumb-separator">›</span>
            <a href="<?= base_url('aktivitas-laboratorium') ?>" class="breadcrumb-item">Aktivitas Laboratorium</a>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-item active">Detail Aktivitas</span>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">

                <!-- Article Header -->
                <header class="article-header">
                    <h1 class="article-title">
                        <?= htmlspecialchars($aktivitas['judul_aktivitas']) ?>
                    </h1>

                    <div class="article-meta">
                        <div class="meta-item">
                            <i class="bi bi-person"></i>
                            <span><?= $aktivitas['penulis_nama'] ?? 'Laboratorium Applied Informatics' ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="bi bi-calendar-event"></i>
                            <span><?= formatTanggal($aktivitas['tanggal_kegiatan']) ?></span>
                        </div>
                        <?php if (!empty($aktivitas['created_at'])): ?>
                            <div class="meta-item">
                                <i class="bi bi-clock"></i>
                                <span>Dipublikasi <?= formatTanggal($aktivitas['created_at']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </header>

                <!-- Featured Image -->
                <div class="featured-image-wrapper">
                    <?php
                    $urlPhoto = empty($aktivitas['foto_aktivitas'])
                        ? upload_url("default/image.png")
                        : upload_url("aktivitas-lab/" . $aktivitas['foto_aktivitas']);
                    ?>
                    <img src="<?= $urlPhoto ?>"
                        alt="<?= htmlspecialchars($aktivitas['judul_aktivitas'], ENT_QUOTES, 'UTF-8') ?>"
                        class="featured-image">
                </div>

                <!-- Article Content -->
                <div class="article-content">
                    <div class="content-body">
                        <?= $aktivitas['deskripsi'] ?>
                    </div>
                </div>

                <!-- Article Footer -->
                <footer class="article-footer">
                    <a href="<?= base_url('aktivitas-laboratorium') ?>" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left me-2"></i>
                        Kembali ke Semua Aktivitas
                    </a>
                </footer>

            </div>
        </div>
    </article>

</main>

<link rel="stylesheet" href="<?= asset_url('css/pages/aktivitas-lab/detail_aktivitas_user.css') ?>">


<!--footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>