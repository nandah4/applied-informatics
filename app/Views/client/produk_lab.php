<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';

// Data produk sudah di-fetch dari route
// $listProduk - Array data produk dari database
?>

<main class="produk-lab-page">

    <div class="container-fluid px-md-5 pb-5">
        <div class="breadcrumb-nav">
            <span class="breadcrumb-item">Laboratorium Applied Informatics</span>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-item active">Produk Laboratorium</span>
        </div>

        <div class="mb-5">
            <h1 class="title-section mb-3">Semua Produk</h1>
            <p class="subtitle-section">Temukan berbagai produk inovatif yang dikembangkan oleh tim laboratorium. Setiap produk dirancang untuk memberikan nilai serta mendukung pengembangan teknologi di berbagai bidang.</p>
        </div>

        <?php if (!empty($listProduk)): ?>
            <div class="row">
                <?php foreach ($listProduk as $produk): ?>
                    <?php
                    // Prepare data
                    $produkFoto = upload_url('produk/' . $produk['foto_produk']);
                    $deskripsi = !empty($produk['deskripsi']) ? htmlspecialchars($produk['deskripsi']) : "Tidak ada deskripsi.";
                    $kontributor = !empty($produk['dosen_names']) ? htmlspecialchars($produk['dosen_names']) : "";
                    $timMahasiswa = !empty($produk['tim_mahasiswa']) ? htmlspecialchars($produk['tim_mahasiswa']) : "";
                    $linkProduk = !empty($produk['link_produk']) ? htmlspecialchars($produk['link_produk']) : "";
                    ?>

                    <div class="col-sm-6 col-md-3 produk-item mb-4">
                        <div class="card-container mx-auto mb-1 " data-bs-toggle="modal"
                            data-bs-target="#produkModal"
                            data-produk-nama="<?= htmlspecialchars($produk['nama_produk']) ?>"
                            data-produk-foto="<?= $produkFoto ?>"
                            data-produk-deskripsi="<?= $deskripsi ?>"
                            data-produk-kontributor="<?= $kontributor ?>"
                            data-produk-tim="<?= $timMahasiswa ?>"
                            data-produk-link="<?= $linkProduk ?>"
                            style="cursor: pointer;"
                            role="button"
                            tabindex="0">
                            <!-- ketika logo di klik maka muncul modal -->
                            <img src="<?= $produkFoto ?>"
                                alt="<?= htmlspecialchars($produk['nama_produk']) ?>"
                                class="mx-auto d-block gambar-produk logo-container">
                            <!-- Teks hover -->
                            <div class="hover-text">Lihat Detail Produk</div>
                        </div>
                        <p class="w-75 nama-produk mt-3"><?= htmlspecialchars($produk['nama_produk']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <div class="mb-4 d-flex justify-content-center">
                    <i data-feather="grid" class="icon-something-not-found"></i>
                </div>
                <h5 class="text-muted mb-2">Belum Ada Produk.</h5>
                <p class="text-secondary mb-0">Belum ada produk yang tersedia.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Single Modal Produk Detail -->
    <div class="modal fade" id="produkModal" tabindex="-1" aria-labelledby="produkModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 id="modal-title" class="subtitle-section text-start" style="color: black;">Nama Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Foto Produk -->
                    <div class="text-center mb-4">
                        <img id="modalProdukFoto"
                            src=""
                            alt="Produk"
                            class="img-fluid rounded"
                            style="max-height: 100px; object-fit: contain;">
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <h6 class="label-desk-produk">
                            Deskripsi Produk
                        </h6>
                        <p class="body-desk-produk" id="modalProdukDeskripsi">Deskripsi produk akan muncul di sini.</p>
                    </div>

                    <!-- Kontributor Dosen -->
                    <div class="mb-4" id="kontributorSection" style="display: none;">
                        <h6 class="label-desk-produk">
                            Kontributor Dosen
                        </h6>
                        <div id="modalProdukKontributor" class="d-flex flex-wrap gap-2 body-desk-produk"></div>
                    </div>

                    <!-- Tim Mahasiswa -->
                    <div class="mb-3" id="timSection" style="display: none;">
                        <h6 class="label-desk-produk">
                            Tim Mahasiswa
                        </h6>
                        <p class="body-desk-produk" id="modalProdukTim"></p>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <a id="modalProdukLink"
                        href="#"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-primary"
                        style="background: linear-gradient(135deg, var(--color-primary-500), var(--color-primary-600)); border: none; padding: 10px 24px; display: none;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px;">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <line x1="10" y1="14" x2="21" y2="3"></line>
                        </svg>
                        Lihat Demo
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="padding: 10px 24px;">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</main>

<link rel="stylesheet" href="<?= asset_url('css/pages/produk/produk_user.css') ?>">

<!-- Script untuk handle modal produk -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get modal element
        const produkModal = document.getElementById('produkModal');

        // Event listener saat modal akan ditampilkan
        produkModal.addEventListener('show.bs.modal', function(event) {
            // Tombol/image yang di-klik
            const button = event.relatedTarget;

            // Ambil data dari data attributes
            const nama = button.getAttribute('data-produk-nama');
            const foto = button.getAttribute('data-produk-foto');
            const deskripsi = button.getAttribute('data-produk-deskripsi');
            const kontributor = button.getAttribute('data-produk-kontributor');
            const tim = button.getAttribute('data-produk-tim');
            const link = button.getAttribute('data-produk-link');

            // Update modal content
            const modalTitle = produkModal.querySelector('#modal-title');
            const modalFoto = document.getElementById('modalProdukFoto');
            const modalDeskripsi = document.getElementById('modalProdukDeskripsi');
            const modalKontributor = document.getElementById('modalProdukKontributor');
            const modalTim = document.getElementById('modalProdukTim');
            const modalLink = document.getElementById('modalProdukLink');
            const kontributorSection = document.getElementById('kontributorSection');
            const timSection = document.getElementById('timSection');

            // Set basic info
            modalTitle.textContent = nama;
            modalFoto.src = foto;
            modalFoto.alt = nama;
            modalDeskripsi.textContent = deskripsi;

            // Handle kontributor
            if (kontributor && kontributor.trim() !== '') {
                kontributorSection.style.display = 'block';

                // 1. Bersihkan isi kontainer sebelumnya
                modalKontributor.innerHTML = '';

                // 2. Split string berdasarkan delimiter dari SQL (", $$$")
                // Hasilnya akan menjadi array nama-nama dosen
                const listDosen = kontributor.split('-$$$-');

                // 3. Loop setiap nama dosen dan buat badge
                listDosen.forEach(function(nama) {
                    if (nama.trim() !== '') {
                        // Buat elemen span untuk badge
                        const badge = document.createElement('span');

                        badge.className = 'badge-produk rounded-pill mb-1 me-1';

                        badge.style.backgroundColor = 'var(--color-primary-50)';
                        badge.style.color = 'var(--color-primary-700)';
                        badge.style.fontSize = 'var(--fs-xs)';
                        badge.style.padding = '10px 16px';
                        badge.textContent = nama.trim();

                        // Masukkan ke dalam container
                        modalKontributor.appendChild(badge);
                    }
                });
            } else {
                kontributorSection.style.display = 'none';
            }

            // Handle tim mahasiswa
            if (tim && tim.trim() !== '') {
                timSection.style.display = 'block';
                modalTim.textContent = tim;
            } else {
                timSection.style.display = 'none';
            }

            // Handle link demo
            if (link && link.trim() !== '') {
                modalLink.href = link;
                modalLink.style.display = 'inline-flex';
            } else {
                modalLink.style.display = 'none';
            }
        });
    });
</script>

<!--footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>