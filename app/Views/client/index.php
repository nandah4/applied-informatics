<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';
?>
<main>

    <!-- ====== SECTION HERO ====== -->
    <section class="p-5 selamat-datang">
        <div class="container container-text-center">

            <h1 class="title-lab">Laboratorium Applied Informatics</h1>
            <div class="container-img-hero">
                <img src="<?= asset_url('images/beranda/assets-home.png') ?>" alt=""
                    class="img-fluid mx-auto d-block gambar-sambutan">
                <p class="">Jurusan Teknologi Informasi, Politeknik Negeri Malang.</p>
            </div>

            <p class="mt-4 sambutan">Selamat datang, mari jelajahi berbagai aktivitas, penelitian, dan inovasi yang
                terus kami kembangkan untuk menghadirkan dampak nyata.</p>
            <div class="publikasi-v2-footer">
                <a href="#vm-id" class="publikasi-v2-btn">
                    <span>Jelajahi Sekarang</span>
                    <i data-feather="arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ====== SECTION VISI MISI ====== -->
    <section id="vm-id" class="visi-misi-v2">
        <div class="container-fluid px-md-5">
            <div class="row g-4">
                <!-- VISI Card -->
                <div class="col-lg-6">
                    <div class="vm-v2-card vm-v2-visi">
                        <div class="vm-v2-card-header">
                            <div class="vm-v2-icon-wrapper">
                                <i data-feather="award"></i>
                            </div>
                            <h3 class="vm-v2-card-title">Visi</h3>
                        </div>

                        <p class="vm-v2-main-text">
                            Menjadi laboratorium unggulan dalam pengembangan dan penerapan teknologi informasi inovatif yang mendukung transformasi digital berkelanjutan.
                        </p>

                        <!-- Accordion untuk poin-poin Visi -->
                        <div class="accordion vm-v2-accordion" id="visiAccordion">
                            <div class="accordion-item vm-v2-accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button vm-v2-accordion-btn collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#visiPoint1">
                                        <span class="vm-v2-point-number">01</span>
                                        Transformasi Digital Berkelanjutan
                                    </button>
                                </h2>
                                <div id="visiPoint1" class="accordion-collapse collapse" data-bs-parent="#visiAccordion">
                                    <div class="accordion-body vm-v2-accordion-body">
                                        Mendorong adopsi teknologi digital yang ramah lingkungan dan berkelanjutan untuk generasi mendatang.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item vm-v2-accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button vm-v2-accordion-btn collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#visiPoint2">
                                        <span class="vm-v2-point-number">02</span>
                                        Kolaborasi Lintas Sektor
                                    </button>
                                </h2>
                                <div id="visiPoint2" class="accordion-collapse collapse" data-bs-parent="#visiAccordion">
                                    <div class="accordion-body vm-v2-accordion-body">
                                        Membangun kemitraan strategis dengan industri, pemerintah, dan akademisi untuk menciptakan dampak nyata.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item vm-v2-accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button vm-v2-accordion-btn collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#visiPoint3">
                                        <span class="vm-v2-point-number">03</span>
                                        Solusi Cerdas Berbasis Data
                                    </button>
                                </h2>
                                <div id="visiPoint3" class="accordion-collapse collapse" data-bs-parent="#visiAccordion">
                                    <div class="accordion-body vm-v2-accordion-body">
                                        Mengembangkan solusi berbasis AI dan data analytics untuk menjawab tantangan era Industri 4.0.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MISI Card -->
                <div class="col-lg-6">
                    <div class="vm-v2-card vm-v2-misi">
                        <div class="vm-v2-card-header">
                            <div class="vm-v2-icon-wrapper vm-v2-icon-misi">
                                <i data-feather="crosshair"></i>
                            </div>
                            <h3 class="vm-v2-card-title">Misi</h3>
                        </div>

                        <p class="vm-v2-main-text">
                            Berfokus pada solusi Industri 4.0 dengan mengembangkan dan menerapkan teknologi inovatif guna mengatasi tantangan modern.
                        </p>

                        <!-- Accordion untuk poin-poin Misi -->
                        <div class="accordion vm-v2-accordion" id="misiAccordion">
                            <div class="accordion-item vm-v2-accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button vm-v2-accordion-btn collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#misiPoint1">
                                        <span class="vm-v2-point-number">01</span>
                                        Otomatisasi & Integrasi Sistem
                                    </button>
                                </h2>
                                <div id="misiPoint1" class="accordion-collapse collapse" data-bs-parent="#misiAccordion">
                                    <div class="accordion-body vm-v2-accordion-body">
                                        Mengembangkan sistem otomatisasi yang terintegrasi untuk meningkatkan efisiensi operasional industri.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item vm-v2-accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button vm-v2-accordion-btn collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#misiPoint2">
                                        <span class="vm-v2-point-number">02</span>
                                        Pemrosesan Data Real-time
                                    </button>
                                </h2>
                                <div id="misiPoint2" class="accordion-collapse collapse" data-bs-parent="#misiAccordion">
                                    <div class="accordion-body vm-v2-accordion-body">
                                        Membangun infrastruktur untuk pengolahan data secara real-time guna mendukung pengambilan keputusan cepat.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item vm-v2-accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button vm-v2-accordion-btn collapsed" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#misiPoint3">
                                        <span class="vm-v2-point-number">03</span>
                                        Akselerasi Transformasi Digital
                                    </button>
                                </h2>
                                <div id="misiPoint3" class="accordion-collapse collapse" data-bs-parent="#misiAccordion">
                                    <div class="accordion-body vm-v2-accordion-body">
                                        Mempercepat adopsi teknologi digital di berbagai sektor untuk meningkatkan daya saing nasional.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====== SECTION STATISTIK ====== -->
    <section class="statistik">
        <div class="px-md-5 container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title-section">Profil Statistik Laboratorium</h3>
                    <p class="subtitle-section">Gambaran perkembangan dan aktivitas utama Laboratorium Applied Informatics</p>
                </div>

                <div class="col-md-6">
                    <div class="row row-angka">
                        <div
                            class="col-sl-4 col-md-6 mb-5 d-flex flex-column justify-content-center align-items-center ">
                            <h1 class="angka-statistik">
                                <?= $statisticData['total_publikasi'] > 800 ? 800 : $statisticData['total_publikasi'] ?>+
                            </h1>
                            <p class="info-statistik">Publikasi</p>
                        </div>

                        <div
                            class="col-sl-4 col-md-6 mb-5 d-flex flex-column justify-content-center align-items-center">
                            <h1 class="angka-statistik">
                                <?= $statisticData['total_anggota'] > 90 ? 90 : $statisticData['total_anggota'] ?>+
                            </h1>
                            <p class="info-statistik">Anggota Aktif</p>
                        </div>

                        <div
                            class="col-sl-4 col-md-6 mb-5 d-flex flex-column justify-content-center align-items-center">
                            <h1 class="angka-statistik">
                                <?= $statisticData['total_mitra'] > 50 ? 50 : $statisticData['total_mitra'] ?>+
                            </h1>
                            <p class="info-statistik">Mitra</p>
                        </div>
                        <div
                            class="col-sl-4 col-md-6 mb-5 d-flex flex-column justify-content-center align-items-center">
                            <h1 class="angka-statistik">
                                <?= $statisticData['total_produk'] > 30 ? 30 : $statisticData['total_produk'] ?>+
                            </h1>
                            <p class="info-statistik">Produk</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====== SECTION FASILITAS ====== -->
    <section class="fasilitas-v2-section ">
        <div class="container-fluid px-md-5">
            <div class="fasilitas-v2-header">

                <h3 class="title-section">Fasilitas Laboratorium</h3>
                <p class="subtitle-section text-center w-100">Kelengkapan ruang, perangkat dan teknologi untuk menunjang kegiatan Applied Informatics</p>
            </div>

            <?php if (empty($fasilitasData)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon mx-auto">
                        <i data-feather="inbox" class="icon-something-not-found"></i>
                    </div>
                    <h5 class="text-muted mb-2">Belum Ada Data Fasilitas.</h5>
                    <p class="text-secondary mb-0">Data fasilitas laboratorium belum tersedia saat ini.</p>
                </div>
            <?php else: ?>
                <div class="fasilitas-v2-grid">
                    <?php foreach ($fasilitasData as $index => $fasilitas): ?>
                        <?php
                        $fasilitasFoto = empty($fasilitas['foto'])
                            ? upload_url('default/image.png')
                            : upload_url('fasilitas/' . $fasilitas['foto']);
                        $deskripsi = !empty($fasilitas['deskripsi'])
                            ? htmlspecialchars($fasilitas['deskripsi'])
                            : "Tidak ada deskripsi.";
                        $nama = htmlspecialchars($fasilitas['nama']);
                        ?>

                        <div class="fasilitas-v2-card"
                            data-bs-toggle="modal"
                            data-bs-target="#fasilitasModal"
                            data-fasilitas-nama="<?= $nama ?>"
                            data-fasilitas-foto="<?= $fasilitasFoto ?>"
                            data-fasilitas-deskripsi="<?= $deskripsi ?>">

                            <!-- Bubble Decorations -->
                            <div class="bubble bubble-1"></div>
                            <div class="bubble bubble-2"></div>
                            <div class="bubble bubble-3"></div>

                            <!-- Content -->
                            <div class="fasilitas-v2-content">
                                <span class="fasilitas-v2-number"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></span>
                                <h4 class="fasilitas-v2-name"><?= truncateText($nama, 100) ?></h4>
                                <span class="fasilitas-v2-cta">
                                    <i data-feather="arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- ====== MODAL FASILITAS ====== -->
    <div class="modal fade" id="fasilitasModal" tabindex="-1" aria-labelledby="fasilitasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title modal-v2-title" id="fasilitasModalLabel">Nama Fasilitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Gambar Fasilitas -->
                    <div class="d-flex justify-content-center mb-4 mt-2">
                        <img src="" alt="Gambar Fasilitas" id="modalFasilitasGambar"
                            class="img-fluid w-100" style="max-height: 400px; object-fit: cover;">
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <p id="modalFasilitasDeskripsi" class="text-secondary modal-v2-desc mb-0">Deskripsi fasilitas akan muncul di
                            sini.</p>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== SECTION PUBLIKASI ====== -->
    <section class="publikasi-v2-section">
        <div class="container-fluid px-md-5">
            <!-- Header -->
            <div class="publikasi-v2-header">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="title-section">Publikasi Penelitian</h2>
                        <p class="subtitle-section">Karya ilmiah dan penelitian terbaru yang telah dipublikasikan oleh tim laboratorium</p>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end mt-3 mt-lg-0">
                        <span class="publikasi-v2-year-badge">
                            <i data-feather="calendar"></i>
                            Tahun <?= date('Y') ?>
                        </span>
                    </div>
                </div>
            </div>

            <?php if (!empty($listPublikasi)): ?>
                <!-- Horizontal Scroll Container (1 column, 2 rows) -->
                <div class="publikasi-v2-scroll-wrapper">
                    <div class="publikasi-v2-scroll-container">
                        <?php foreach ($listPublikasi as $index => $publikasi):
                            $judul = htmlspecialchars($publikasi['judul'] ?? 'Tanpa Judul');
                            $author = !empty($publikasi['dosen_name'])
                                ? htmlspecialchars($publikasi['dosen_name'])
                                : 'Penulis Tidak Diketahui';
                            $url = !empty($publikasi['url_publikasi']) && $publikasi['url_publikasi'] !== 'null'
                                ? htmlspecialchars($publikasi['url_publikasi'])
                                : null;
                        ?>
                            <div class="publikasi-v2-card">
                                <span class="publikasi-v2-card-number"><?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?></span>
                                <h4 class="publikasi-v2-card-title"><?= truncateText($judul, 70) ?></h4>
                                <div class="publikasi-v2-card-meta">
                                    <span class="publikasi-v2-card-author">
                                        <i data-feather="user"></i>
                                        <?= $author ?>
                                    </span>

                                    <?php if ($url): ?>
                                        <a href="<?= $url ?>" target="_blank" class="publikasi-v2-card-link" title="Buka Publikasi">
                                            <i data-feather="external-link"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-state-icon mx-auto">
                        <i data-feather="search" class="icon-something-not-found"></i>
                    </div>
                    <h5 class="text-muted mb-2">Belum Ada Hasil.</h5>
                    <p class="text-secondary mb-0">Tidak ada penelitian yang sesuai dengan pencarian Anda.</p>
                </div>
            <?php endif; ?>

            <!-- View All Button -->
            <div class="publikasi-v2-footer">
                <a href="<?= base_url('publikasi-dosen') ?>" class="publikasi-v2-btn">
                    <span>Lihat Semua Publikasi</span>
                    <i data-feather="arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ====== SECTION AKTIVITAS ====== -->
    <section class="aktivitas-section">
        <div class="container-fluid px-md-5">
            <!-- Header -->
            <div class="aktivitas-header">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="title-section">Aktivitas Laboratorium</h2>
                        <p class="subtitle-section">Beragam kegiatan penelitian, pengembangan, dan kolaborasi yang dilakukan oleh anggota laboratorium</p>
                    </div>
                    <div class="col-lg-4 text-center text-lg-end mt-3 mt-lg-0">
                        <a href="<?= base_url('aktivitas-laboratorium') ?>" class="aktivitas-view-all-btn">
                            <span>Semua Aktivitas</span>
                            <i data-feather="arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <?php if (empty($aktivitasData)): ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-state-icon mx-auto">
                        <i data-feather="calendar" class="icon-something-not-found"></i>
                    </div>
                    <h5 class="text-muted mb-2">Belum Ada Aktivitas.</h5>
                    <p class="text-secondary mb-0">Data aktivitas laboratorium belum tersedia saat ini.</p>
                </div>
            <?php else: ?>
                <!-- News Grid -->
                <div class="aktivitas-grid">
                    <?php foreach ($aktivitasData as $aktivitas):
                        $aktivitasFoto = empty($aktivitas['foto_aktivitas'])
                            ? upload_url('default/image.png')
                            : upload_url('aktivitas-lab/' . $aktivitas['foto_aktivitas']);
                        $judul = htmlspecialchars($aktivitas['judul_aktivitas']);
                        $tanggal = formatTanggal($aktivitas['tanggal_kegiatan']);
                    ?>
                        <a href="<?= base_url('aktivitas-laboratorium/' . $aktivitas['id']) ?>" class="aktivitas-card">
                            <!-- Image Container -->
                            <div class="aktivitas-card-img">
                                <img src="<?= $aktivitasFoto ?>" alt="<?= $judul ?>">
                                <div class="aktivitas-card-date">
                                    <i data-feather="calendar"></i>
                                    <span><?= $tanggal ?></span>
                                </div>
                            </div>
                            <!-- Content -->
                            <div class="aktivitas-card-content">
                                <h4 class="aktivitas-card-title"><?= truncateText($judul, 60) ?></h4>
                                <span class="aktivitas-read-more">
                                    Baca Selengkapnya
                                    <i data-feather="chevron-right"></i>
                                </span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- ====== CTA SECTION ====== -->
    <section class="cta-section">
        <div class="container-fluid px-md-5">
            <div class="cta-container">
                <!-- Glass Bubbles -->
                <div class="cta-bubble cta-bubble-1"></div>
                <div class="cta-bubble cta-bubble-2"></div>
                <div class="cta-bubble cta-bubble-3"></div>
                <div class="cta-bubble cta-bubble-4"></div>

                <div class="cta-content">
                    <h2 class="cta-title">Mari berkolaborasi bersama kami.</h2>
                    <p class="subtitle-section text-center mx-auto">Tertarik untuk bermitra atau bergabung dalam penelitian dan pengembangan teknologi?</p>
                    <a href="<?= base_url('contact-us') ?>" class="cta-button mt-4">
                        <i data-feather="mail"></i>
                        <span>Hubungi Kami</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>

<link rel="stylesheet" href="<?= asset_url('css/pages/home/home.css') ?>">

<!-- Script untuk handle modal fasilitas -->
<script>
    // Helper function untuk escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) {
            return map[m];
        });
    }

    document.addEventListener('DOMContentLoaded', function() {

        // ========================================
        // Modal Fasilitas V2 Handler
        // ========================================
        const fasilitasModalV2 = document.getElementById('fasilitasModal');
        if (fasilitasModalV2) {
            fasilitasModalV2.addEventListener('show.bs.modal', function(event) {
                const card = event.relatedTarget;

                // Ambil data dari data attributes
                const nama = card.getAttribute('data-fasilitas-nama');
                const foto = card.getAttribute('data-fasilitas-foto');
                const deskripsi = card.getAttribute('data-fasilitas-deskripsi');

                // Update modal content
                const modalTitle = fasilitasModal.querySelector('.modal-title');
                const modalGambar = document.getElementById('modalFasilitasGambar');
                const modalDeskripsi = document.getElementById('modalFasilitasDeskripsi');

                modalTitle.textContent = nama;
                modalGambar.src = foto;
                modalGambar.alt = nama;
                modalDeskripsi.textContent = deskripsi;

                // Handle jika gambar gagal dimuat
                modalGambar.onerror = function() {
                    this.src = upload_url('default/image.png');
                    this.alt = 'Gambar tidak tersedia';
                };
            });

            // Re-initialize feather icons when modal opens
            fasilitasModalV2.addEventListener('shown.bs.modal', function() {
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            });
        }
    });
</script>

<?php
include __DIR__ . '/../layouts/footer.php';
?>