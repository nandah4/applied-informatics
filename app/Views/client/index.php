<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';
?>
<main>

    <!-- Section Selamat Datang -->
    <section class="p-5 selamat-datang">
        <div class="container container-text-center">
            <!-- <h5 class="fw-normal header-welcome">Selamat Datang, Mari mengenal lebih dekat aktivitas dan penelitian kami.</h5> -->
            <h1 class="title-lab">Laboratorium Applied Informatics</h1>
            <img src="<?= asset_url('images/beranda/assets-home.png') ?>" alt=""
                class="img-fluid mx-auto d-block gambar-sambutan">
            <p class="mt-5 sambutan">Selamat Datang, mari jelajahi berbagai aktivitas, penelitian, dan inovasi yang terus kami kembangkan untuk menghadirkan dampak nyata.</p>
            <a href="" class="btn rounded-pill mt-3 px-3 btn-riset-hero">Lihat Riset Kami</a>
        </div>
    </section>

    <!-- ------ -->

    <!-- Section Visi Misi -->
    <section class="visi-misi p-5">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card rounded-md card-visi card-custom-border">
                        <div class="card-body">
                            <i data-feather="award" class="icon-vm-size"></i>

                            <h5 class="mb-3 visi">Visi Laboratorium Applied Informatics</h5>
                            <p>Menjadi laboratorium unggulan dalam pengembangan dan penerapan teknologi informasi inovatif
                                yang
                                mendukung transformasi digital berkelanjutan, mendorong kolaborasi lintas sektor, serta
                                menciptakan solusi cerdas berbasis data untuk menghadapi tantangan era industri 4.0.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-misi">
                        <div class="card-body">
                            <i data-feather="crosshair" class="icon-vm-size"></i>

                            <h5 class="mb-3 misi">Misi Laboratorium Applied Informatics</h5>
                            <p>Berfokus solusi Industri 4.0 dengan mengembangkan dan menerapkan teknologi inovatif guna
                                mengatasi tantangan seperti otomatisasi, integrasi sistem, dan pemrosesan data real-time,
                                yang
                                akan mendukung akselerasi transformasi digital dan meningkatkan efisiensi operasional di
                                berbagai sektor.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ------ -->

    <!-- Section Statistik -->
    <section class="statistik p-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="title-section">Profil Statistik Laboratorium</h3>
                    <p class="subtitle-section">Gambaran perkembangan dan aktivitas utama Laboratorium Applied Informatics</p>
                </div>

                <div class="col-md-6">
                    <div class="row row-angka">
                        <div class="col-sl-4 col-md-6 mb-5 d-flex flex-column justify-content-center align-items-center ">
                            <h1 class="angka-statistik"><?= $statisticData['total_publikasi'] > 800 ? 800 : $statisticData['total_publikasi'] ?>+</h1>
                            <p class="info-statistik">Publikasi</p>
                        </div>

                        <div class="col-sl-4 col-md-6 mb-5 d-flex flex-column justify-content-center align-items-center">
                            <h1 class="angka-statistik"><?= $statisticData['total_anggota'] > 90 ? 90 : $statisticData['total_anggota'] ?>+</h1>
                            <p class="info-statistik">Anggota Aktif</p>
                        </div>

                        <div class="col-sl-4 col-md-6 mb-5 d-flex flex-column justify-content-center align-items-center">
                            <h1 class="angka-statistik"><?= $statisticData['total_mitra'] > 50 ? 50 : $statisticData['total_mitra'] ?>+</h1>
                            <p class="info-statistik">Mitra</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ------ -->

    <!-- Section Fasilitas -->
    <section class=" fasilitas-section">
        <div class="px-5 container-fluid">
            <button class="btn-fasilitas rounded-pill px-4 mb-3">Fasilitas Laboratorium</button>
            <p class="mb-5 subtitle-section">Kelengkapan ruang, perangkat dan teknologi untuk menunjang kegiatan Applied
                Informatics</p>
        </div>

        <div>
            <?php if (empty($fasilitasData)): ?>
                <p class="subtitle-section px-5">Fasilitas tidak ditemukan! 😞</p>
            <?php else: ?>
                <div class="d-flex column-gap-4 overflow-auto flex-nowrap px-5 pb-3">
                    <?php foreach ($fasilitasData as $fasilitas): ?>
                        <?php
                        $fasilitasFoto = empty($fasilitas['foto'])
                            ? upload_url('default/image.png')
                            : upload_url('fasilitas/' . $fasilitas['foto']);

                        $deskripsi = !empty($fasilitas['deskripsi'])
                            ? htmlspecialchars($fasilitas['deskripsi'])
                            : "Tidak ada deskripsi.";
                        ?>

                        <div class="container-item-fasilitas">

                            <!-- IMG sebagai trigger modal -->
                            <img
                                src="<?= $fasilitasFoto ?>"
                                alt="<?= htmlspecialchars($fasilitas['nama']) ?>"
                                class="foto-fasilitas rounded-3 mb-2"
                                data-bs-toggle="modal"
                                data-bs-target="#fasilitasModal"
                                data-fasilitas-nama="<?= htmlspecialchars($fasilitas['nama']) ?>"
                                data-fasilitas-foto="<?= $fasilitasFoto ?>"
                                data-fasilitas-deskripsi="<?= $deskripsi ?>"
                                style="cursor: pointer;"
                                role="button"
                                tabindex="0">

                            <?php
                            $nama = htmlspecialchars($fasilitas['nama']);
                            $namaPendek = strlen($nama) > 40
                                ? substr($nama, 0, 40) . '...'
                                : $nama;
                            ?>

                            <p class="title-fasilitas"><?= $namaPendek ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>



    </section>

    <!-- Modal Fasilitas -->
    <div class="modal fade" id="fasilitasModal" tabindex="-1" aria-labelledby="fasilitasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="fasilitasModalLabel">Nama Fasilitas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalFasilitasDeskripsi" class="text-secondary">Deskripsi fasilitas akan muncul di sini.</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ------ -->


    <!-- Section Penelitian -->
    <section class="penelitian-section py-5">
        <div class="container-fluid px-5">
            <!-- Header Section -->
            <div class="mb-4">
                <button class="btn-penelitian rounded-pill px-4 mb-3">Publikasi Penelitian</button>
                <p class="subtitle-section mb-0">Karya ilmiah dan penelitian yang telah dipublikasikan oleh tim laboratorium</p>
            </div>

            <!-- Year Filter Pills -->
            <div class="year-filter-container mb-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted me-2 flex-shrink-0">Filter Tahun:</span>

                    <div class="overflow-auto d-flex gap-2 flex-nowrap pb-2" style="scrollbar-width: thin;">
                        <?php
                        $years = range(date('Y'), date('Y') - 20); // 21 tahun terakhir
                        foreach ($years as $index => $year):
                        ?>
                            <button
                                class="year-pill flex-shrink-0 <?= $index === 0 ? 'active' : '' ?>"
                                data-year="<?= $year ?>">
                                <?= $year ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Publikasi Container -->
            <div id="publikasiContainer" class="publikasi-container">
                <!-- Loading state awal -->
                <div class="loading-state text-center py-5">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted">Memuat publikasi...</p>
                </div>
            </div>
        </div>
    </section>

    <section class="p-5">
        <div class="container-fluid">
            <h3 class="title-section mb-2">Aktivitas Lab Terbaru</h3>
            <div class="row">
                <div class="col-md-8">
                    <p class="subtitle-section">Beragam kegiatan penelitian, pengembangan, dan kolaborasi yang dilakukan oleh anggota laboratorium.</p>
                </div>

                <div class="col-md-4 text-md-end">
                    <a href="<?= base_url("aktivitas-laboratorium") ?>" class="btn btn-riset-hero rounded-pill px-3">Semua Aktivitas</a>
                </div>
            </div>

            <?php if (empty($aktivitasData)): ?>
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-calendar-x fa-4x text-muted opacity-50" style="font-size: 4rem;"></i>
                    </div>
                    <h5 class="text-muted mb-2">Belum Ada Aktivitas</h5>
                    <p class="text-muted mb-0">Data aktivitas laboratorium belum tersedia saat ini.</p>
                </div>
            <?php else: ?>
                <div class="row g-4 mt-3">
                    <?php foreach ($aktivitasData as $aktivitas): ?>
                        <?php
                        $aktivitasFoto = empty($aktivitas['foto_aktivitas'])
                            ? upload_url('default/image.png')
                            : upload_url('aktivitas-lab/' . $aktivitas['foto_aktivitas']);

                        $judul = htmlspecialchars($aktivitas['judul_aktivitas']);
                        $judulPendek = strlen($judul) > 40 ? substr($judul, 0, 40) . '...' : $judul;
                        ?>

                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="card shadow-none h-100 border-0">
                                <img src="<?= $aktivitasFoto ?>" class="card-img-top" alt="<?= $judul ?>">

                                <div class="card-body">
                                    <small class="teks-tanggal-aktivitas d-block mb-1">
                                        <?= formatTanggal($aktivitas['tanggal_kegiatan']); ?>
                                    </small>
                                    <p class="title-fasilitas m-0"><?= $judulPendek ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>



            <!-- <div class="col-md-4">
                    <img src="<?= asset_url('images/login/login.jpg') ?>" alt="" class="img-fluid rounded mb-3">
                    <p class="fw-bold">Gedung Pascasarjana Teknik Mesin, Lantai 2.</p>
                </div>

                <div class="col-md-4">
                    <img src="<?= asset_url('images/login/login.jpg') ?>" alt="" class="img-fluid rounded mb-3">
                    <p class="fw-bold">Gedung Pascasarjana Teknik Mesin, Lantai 2.</p>
                </div> -->
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
        // Get modal element
        const fasilitasModal = document.getElementById('fasilitasModal');

        // Event listener saat modal akan ditampilkan
        fasilitasModal.addEventListener('show.bs.modal', function(event) {
            // Tombol/image yang di-klik
            const button = event.relatedTarget;

            // Ambil data dari data attributes
            const nama = button.getAttribute('data-fasilitas-nama');
            const deskripsi = button.getAttribute('data-fasilitas-deskripsi');

            // Update modal content
            const modalTitle = fasilitasModal.querySelector('.modal-title');
            const modalDeskripsi = document.getElementById('modalFasilitasDeskripsi');

            modalTitle.textContent = nama;
            modalDeskripsi.textContent = deskripsi;
        });

        const $yearPills = $('.year-pill');
        const $publikasiContainer = $('#publikasiContainer');

        // Load publikasi pertama kali (tahun aktif)
        const activeYear = $('.year-pill.active').data('year');
        if (activeYear) {
            loadPublikasi(activeYear);
        }

        // Handle click year pill
        $yearPills.on('click', function() {
            const year = $(this).data('year');

            // Update active state
            $yearPills.removeClass('active');
            $(this).addClass('active');

            // Load publikasi
            loadPublikasi(year);
        });

        function loadPublikasi(year) {
            // Show loading state
            $publikasiContainer.html(`
            <div class="loading-state text-center py-5">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted">Memuat publikasi tahun ${year}...</p>
            </div>
        `);

            // Fetch data via AJAX
            $.ajax({
                url: `<?= base_url('api/publikasi/year/') ?>${year}`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.data && response.data.length > 0) {
                        let html = '';

                        response.data.forEach((publikasi, index) => {
                            const judul = escapeHtml(publikasi.judul || 'Tanpa Judul');
                            const author = publikasi.dosen_name ? escapeHtml(publikasi.dosen_name) : 'Penulis Tidak Diketahui';
                            const url = publikasi.url_publikasi && publikasi.url_publikasi !== 'null' ?
                                escapeHtml(publikasi.url_publikasi) :
                                null;

                            html += `
                            <div class="publikasi-card">
                                <div class="publikasi-number">${index + 1}</div>
                                <h3 class="publikasi-title">${judul}</h3>
                                <div class="publikasi-meta">
                                    <span class="publikasi-author">
                                        <i class="bi bi-person-fill"></i>
                                        ${author}
                                    </span>
                                    <span class="publikasi-date">
                                        <i class="bi bi-calendar-fill"></i>
                                        ${year}
                                    </span>
                                </div>
                                ${url ? `
                                    <a href="${url}" target="_blank" class="publikasi-link">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                        Baca Publikasi
                                    </a>
                                ` : ''}
                            </div>
                        `;
                        });

                        $publikasiContainer.html(html);
                    } else {
                        showEmptyState(year);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    console.error('Response:', xhr.responseText);
                    $publikasiContainer.html(`
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                        </div>
                        <h4 class="empty-state-title">Gagal Memuat Data</h4>
                        <p class="empty-state-text">Terjadi kesalahan saat memuat publikasi. Silakan coba lagi.</p>
                    </div>
                `);
                }
            });
        }

        function showEmptyState(year) {
            $publikasiContainer.html(`
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="bi bi-folder2-open"></i>
                </div>
                <h5 class="text-muted mb-2">Belum Ada Publikasi.</h5>
                <p class="text-secondary mb-0">Tidak ada publikasi yang ditemukan untuk tahun ${year}.</p>
            </div>
        `);
        }
    });
</script>

<?php
include __DIR__ . '/../layouts/footer.php';
?>