<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';
?>

<main class="p-5">
    <div class="container-fluid">
        <div class="halaman d-flex flex-row gap-4 mb-5">
            <p>Mitra</p>
            <p>></p>
            <p class="halaman-ini">Mitra</p>
        </div>

        <div class="mb-4">
            <h1 class="judul mb-4">Semua Mitra</h1>
            <p class="deskripsi">Beragam mitra yang bergabung dengan kami.</p>
        </div>

        <hr class="mb-5">

        <!-- internasional -->
        <h1 class="mb-4 cakupan-wilayah">International</h1>
        <div class="mb-5 p-3">
            

            <div class="d-flex overflow-auto international-container">
                <!-- card -->
                <div class="mitra-international text-center">
                    <div class="logo-container-international mb-1">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <p class="fw-bold nama-mitra-international">Gitea</p>
                </div>

                <div class="mitra-international text-center">
                    <div class="logo-container-international mb-1">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <p class="fw-bold nama-mitra-international">Gitea</p>
                </div>

                <div class="mitra-international text-center">
                    <div class="logo-container-international mb-1">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <p class="fw-bold nama-mitra-international">Gitea</p>
                </div>

                <div class="mitra-international text-center">
                    <div class="logo-container-international mb-1">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <p class="fw-bold nama-mitra-international">Gitea</p>
                </div>

                <div class="mitra-international text-center">
                    <div class="logo-container-international mb-1">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <p class="fw-bold nama-mitra-international">Gitea</p>
                </div>

                <div class="mitra-international text-center">
                    <div class="logo-container-international mb-1">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <p class="fw-bold nama-mitra-international">Gitea</p>
                </div>
            </div>
        </div>

        <h1 class="mb-4 cakupan-wilayah">National</h1>
        <div class="mb-5 p-3 national-scrollbar">
           

            <div class="d-flex overflow-auto national-container">
                <!-- card -->
                <div class="mitra-national text-center">
                    <div class="logo-container-national mb-1">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <p class="fw-bold nama-mitra-national">Gitea</p>
                </div>

                <div class="mitra-national text-center">
                    <div class="logo-container-national mb-1">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <p class="fw-bold nama-mitra-national">Gitea</p>
                </div>

                <div class="mitra-national text-center">
                    <div class="logo-container-national mb-1">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <p class="fw-bold nama-mitra-national">Gitea</p>
                </div>

                <div class="mitra-national text-center">
                    <div class="logo-container-national mb-1">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <p class="fw-bold nama-mitra-national">Gitea</p>
                </div>

                <div class="mitra-national text-center">
                    <div class="logo-container-national mb-1">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <p class="fw-bold nama-mitra-national">Gitea</p>
                </div>

                <div class="mitra-national text-center">
                    <div class="logo-container-national mb-1">
                        <img src="<?= asset_url('images/produk/gitea.png') ?>"
                            class="img-fluid mx-auto d-block gambar-produk">
                    </div>
                    <p class="fw-bold nama-mitra-national">Gitea</p>
                </div>
            </div>
        </div>
    </div>



</main>

<link rel="stylesheet" href="<?= asset_url('css/pages/mitra-user/mitra.css') ?>">

<!--footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>