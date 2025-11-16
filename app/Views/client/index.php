<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';
?>
<section class="p-5 beranda">
    <div class="container-fluid">
        <!-- Baris 1: Dummy dan Deskripsi bersebelahan -->
        <div class="row mb-4">
            <div class="col-md-5">
                <div class="dummy">
                    <h5>Dummy dulu Bro</h5>
                    <p>Lorem Ipsum Cihuyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy</p>
                    <button class="btn btn-custom rounded-pill">Get Started -></button>
                </div>
            </div>
            <div class="col-md-7">
                <div class="deskripsi">
                    <h1>Lab Applied Informatics Politeknik Negeri Malang</h1>
                    <p>The Applied Informatics Laboratory at Malang State Polytechnic is an innovation center focused on
                        developing
                        information technology-based solutions.</p>
                </div>
            </div>
        </div>

        <!-- Baris 2: Gambar full width -->
        <div class="row">
            <div class="col-12">
                <div class="gambar-lab text-center">
                    <!-- Gambar akan dimasukkan di sini -->
                    <img src="<?= asset_url('images/lab-ai-logo.png') ?>" alt="Logo Laboratorium Applied Informatics"
                        class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<link rel="stylesheet" href="<?= asset_url('css/pages/home/home.css') ?>">

<?php
include __DIR__ . '/../layouts/footer.php';
?>