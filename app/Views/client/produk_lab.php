<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';
?>

<main class="p-5">

    <div class="container-fluid">
        <div class="halaman d-flex flex-row gap-4 mb-3">
            <p>Riset dan Produk</p>
            <p>></p>
            <p class="halaman-ini">Produk</p>
        </div>

        <div class="mb-4">
            <h1 class="judul mb-4">Semua Produk</h1>
            <p class="deskripsi">Halaman ini menyediakan akses ke beragam hasil produk labrolatorium. Gunakan fitur pencarian untuk
                menemukan produk berdasarkan nama, kategori, atau kata kunci tertentu.</p>
        </div>

        <hr class="mb-5">

        <div class="row">
            <div class="col-sm-6 col-md-3 produk-item mb-3">
                <div class="card-container mx-auto mb-1">
                    <div class="logo-container">
                        <img src="<?= asset_url('images/produk/amati.png') ?>" class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <!-- Teks hover -->
                    <div class="hover-text">Klik untuk melihat</div>
                </div>
                <p class="tipe">Digital</p>
                <p class="fw-bold nama-produk">Automated Cyber Security Maturity Assessment (AMATI)</p>
            </div>

            <div class="col-sm-6 col-md-3 produk-item mb-3">
                <div class="card-container mx-auto mb-1">
                    <div class="logo-container">
                        <img src="<?= asset_url('images/produk/agrilink.png') ?>" class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <div class="hover-text">Klik untuk melihat</div>
                </div>
                <p class="tipe">Digital</p>
                <p class="fw-bold nama-produk">Agrilink Vocpro</p>
            </div>

            <div class="col-sm-6 col-md-3 produk-item mb-3">
                <div class="card-container mx-auto mb-1">
                    <div class="logo-container">
                        <img src="<?= asset_url('images/produk/seals.png') ?>" class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <div class="hover-text">Klik untuk melihat</div>
                </div>
                <p class="tipe">Digital</p>
                <p class="fw-bold nama-produk">Smart Adaptive Learning System (SEALS)</p>
            </div>

            <div class="col-sm-6 col-md-3 produk-item mb-3">
                <div class="card-container mx-auto mb-1">
                    <div class="logo-container">
                        <img src="<?= asset_url('images/produk/crowdfunding.png') ?>" class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <div class="hover-text">Klik untuk melihat</div>
                </div>
                <p class="tipe">Digital</p>
                <p class="fw-bold nama-produk">Crowdfunding</p>
            </div>

            <div class="col-sm-6 col-md-6 produk-item mb-3">
                <div class="card-container-owncloud mx-auto mb-1">
                    <div class="logo-container">
                        <img src="<?= asset_url('images/produk/owncloud.png') ?>" class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <div class="hover-text">Klik untuk melihat</div>
                </div>
                <p class="tipe">Digital</p>
                <p class="fw-bold nama-produk">Owncloud Server</p>
            </div>

            <div class="col-sm-6 col-md-6 produk-item mb-3">
                <div class="card-container-gitea mx-auto mb-1">
                    <div class="logo-container">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>" class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <div class="hover-text">Klik untuk melihat</div>
                </div>
                <p class="tipe">Digital</p>
                <p class="fw-bold nama-produk">Gitea</p>
            </div>
        </div>
    </div>
</main>

<link rel="stylesheet" href="<?= asset_url('css/pages/produk-user/produk.css') ?>">

<!--footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>