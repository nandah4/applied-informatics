<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';
?>

<main class="p-5">
    <div class="container-fluid">

        <div class="halaman d-flex flex-row gap-4 mb-5">
            <p>Riset dan Publikasi</p>
            <p>></p>
            <p class="halaman-ini">Riset</p>
        </div>


        <div class="mb-4">
            <h1 class="judul mb-4">Repositori Penelitian</h1>
            <p class="deskripsi">Halaman ini menyediakan akses ke beragam hasil penelitian dosen. Gunakan fitur
                pencarian untuk menemukan studi, publikasi, dan karya ilmiah berdasarkan topik, dosen, atau kata kunci
                tertentu.</p>
        </div>

        <hr class="mb-5">

        <div class="d-flex justify-content-center mb-5">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari Penelitian">
            </div>
        </div>

        <div class="konten">
            <p class="judul-riset">Prediksi Harga Saham dengan Indikator Analisis Teknikal Menggunakan Long Short Term Memor, Dana Departemen - Oleh Dr. John Doe S.Tr.Kom.</p>
            <a href="" class="redirect-link">Baca disini</a>

            <hr>

            <p class="judul-riset">Prediksi Harga Saham dengan Indikator Analisis Teknikal Menggunakan Long Short Term Memor, Dana Departemen - Oleh Dr. John Doe S.Tr.Kom.</p>
            <a href="" class="redirect-link">Baca disini</a>
        </div>

    </div>
</main>

<link rel="stylesheet" href="<?= asset_url('css/pages/publikasi_dosen-user/publikasi_dosen.css') ?>">

<!--footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>