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

    <section>
        <div class="container-fluid p-5">
            <h1 class="mb-4 title-section">Penelitian</h1>

            <?php if (empty($publikasiYears)): ?>
                <p class="subtitle-section">Belum ada data penelitian.</p>
            <?php else: ?>
                <div class="row g-4">
                    <!-- Kolom Kiri: Tahun Penelitian -->
                    <div class="col-md-3 text-center">
                        <div class="card card-tahun-penelitian overflow-auto fixed-height">
                            <div class="card-body">
                                <p class="tahun-penelitian mb-3">Tahun Penelitian</p>
                                <?php foreach ($publikasiYears as $index => $year): ?>
                                    <?php if ($index > 0): ?>
                                        <hr class="line">
                                    <?php endif; ?>
                                    <p class="tahun-penelitian year-item <?= $index === 0 ? 'active' : '' ?>"
                                        data-year="<?= $year ?>"
                                        style="cursor: pointer;">
                                        <?= $year ?>
                                    </p>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Kolom Kanan: Daftar Publikasi -->
                    <div class="col-md-9">
                        <div class="card card-judul-penelitian overflow-auto fixed-height">
                            <div class="card-body" id="publikasiContainer">
                                <!-- Publikasi akan dimuat di sini via JavaScript -->
                                <?php if (!empty($publikasiData)): ?>
                                    <?php foreach ($publikasiData as $index => $publikasi): ?>
                                        <?php if ($index > 0): ?>
                                            <hr>
                                        <?php endif; ?>
                                        <div class="publikasi-item">
                                            <p class="judul-penelitian">
                                                <?= htmlspecialchars($publikasi['judul']) ?>
                                                <?php if (!empty($publikasi['nama_dosen'])): ?>
                                                    - Oleh <?= htmlspecialchars($publikasi['nama_dosen']) ?>
                                                <?php endif; ?>
                                            </p>
                                            <?php if (!empty($publikasi['url_publikasi']) && $publikasi['url_publikasi'] !== 'null'): ?>
                                                <a href="<?= htmlspecialchars($publikasi['url_publikasi']) ?>" target="_blank">
                                                    <p class="link-penelitian">Baca disini</p>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-muted">Tidak ada publikasi untuk tahun ini.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
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

        // ============================================================
        // Script untuk handle tahun penelitian selection
        // ============================================================
        const yearItems = document.querySelectorAll('.year-item');
        const publikasiContainer = document.getElementById('publikasiContainer');

        yearItems.forEach(item => {
            item.addEventListener('click', function() {
                const year = this.getAttribute('data-year');

                // Update active state
                yearItems.forEach(y => y.classList.remove('active'));
                this.classList.add('active');

                // Show loading state
                publikasiContainer.innerHTML = '<p class="text-muted">Memuat data...</p>';

                // Fetch publikasi by year via AJAX
                fetch(`<?= base_url('api/publikasi/year/') ?>${year}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.data.length > 0) {
                            let html = '';
                            data.data.forEach((publikasi, index) => {
                                if (index > 0) {
                                    html += '<hr>';
                                }
                                html += '<div class="publikasi-item">';
                                html += '<p class="judul-penelitian">';
                                html += escapeHtml(publikasi.judul);
                                if (publikasi.nama_dosen) {
                                    html += ' - Oleh ' + escapeHtml(publikasi.nama_dosen);
                                }
                                html += '</p>';
                                if (publikasi.url_publikasi && publikasi.url_publikasi !== 'null') {
                                    html += '<a href="' + escapeHtml(publikasi.url_publikasi) + '" target="_blank">';
                                    html += '<p class="link-penelitian">Baca disini</p>';
                                    html += '</a>';
                                }
                                html += '</div>';
                            });
                            publikasiContainer.innerHTML = html;
                        } else {
                            publikasiContainer.innerHTML = '<p class="text-muted">Tidak ada publikasi untuk tahun ini.</p>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        publikasiContainer.innerHTML = '<p class="text-danger">Gagal memuat data. Silakan coba lagi.</p>';
                    });
            });
        });

        // Helper function to escape HTML
        function escapeHtml(text) {
            const map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };
            return text.replace(/[&<>"']/g, m => map[m]);
        }
    });
</script>

<?php
include __DIR__ . '/../layouts/footer.php';
?>