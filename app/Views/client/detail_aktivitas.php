<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';
?>


<main class="detail-aktivitas-page">

    <!-- Article Container -->
    <article class="container-fluid px-5 pb-5">
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
                        <?= htmlspecialchars($aktivitas['judul_aktivitas'], ENT_QUOTES, 'UTF-8') ?>
                    </h1>

                    <div class="article-meta">
                        <div class="meta-item">
                            <i class="bi bi-calendar-event"></i>
                            <span><?= date('d F Y', strtotime($aktivitas['tanggal_kegiatan'])) ?></span>
                        </div>
                        <?php if (!empty($aktivitas['created_at'])): ?>
                            <div class="meta-item">
                                <i class="bi bi-clock"></i>
                                <span>Dipublikasi <?= date('d M Y', strtotime($aktivitas['created_at'])) ?></span>
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

    <!-- Share Section (Optional) -->
    <!-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="share-section">
                    <h5 class="share-title">Bagikan Aktivitas Ini</h5>
                    <div class="share-buttons">
                        <a href="#" class="share-btn share-facebook" onclick="shareToFacebook(event)">
                            <i class="bi bi-facebook"></i>
                            <span>Facebook</span>
                        </a>
                        <a href="#" class="share-btn share-twitter" onclick="shareToTwitter(event)">
                            <i class="bi bi-twitter"></i>
                            <span>Twitter</span>
                        </a>
                        <a href="#" class="share-btn share-whatsapp" onclick="shareToWhatsApp(event)">
                            <i class="bi bi-whatsapp"></i>
                            <span>WhatsApp</span>
                        </a>
                        <a href="#" class="share-btn share-link" onclick="copyLink(event)">
                            <i class="bi bi-link-45deg"></i>
                            <span>Salin Link</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
</main>

<link rel="stylesheet" href="<?= asset_url('css/pages/aktivitas-lab/detail_aktivitas_user.css') ?>">

<script>
    function shareToFacebook(e) {
        e.preventDefault();
        const url = encodeURIComponent(window.location.href);
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
    }

    function shareToTwitter(e) {
        e.preventDefault();
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent(document.querySelector('.article-title').textContent);
        window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank', 'width=600,height=400');
    }

    function shareToWhatsApp(e) {
        e.preventDefault();
        const url = encodeURIComponent(window.location.href);
        const text = encodeURIComponent(document.querySelector('.article-title').textContent);
        window.open(`https://wa.me/?text=${text}%20${url}`, '_blank');
    }

    function copyLink(e) {
        e.preventDefault();
        const url = window.location.href;

        navigator.clipboard.writeText(url).then(() => {
            // Show success feedback
            const btn = e.currentTarget;
            const originalText = btn.querySelector('span').textContent;
            btn.querySelector('span').textContent = 'Tersalin!';
            btn.classList.add('copied');

            setTimeout(() => {
                btn.querySelector('span').textContent = originalText;
                btn.classList.remove('copied');
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
            alert('Gagal menyalin link');
        });
    }
</script>

<!--footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>