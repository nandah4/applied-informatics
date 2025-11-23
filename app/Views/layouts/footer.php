<!-- Footer Section -->
<footer class="footer">
    <div class="container-fluid p-5">

        <!-- Logo -->
        <div class="mb-4 text-center text-md-start">
            <img src="<?= asset_url('images/lab-ai-logo.png') ?>" 
                 alt="Lab AI Logo" 
                 class="img-fluid logo-footer" 
                 style="max-height: 70px;">
        </div>

        <!-- Main Menu -->
        <div class="row gy-3 mb-5 text-center text-md-start">

            <div class="col-12 col-md-auto">
                <a href="">Beranda</a>
            </div>

            <div class="col-12 col-md-auto">
                <a href="">Tentang Kami</a>
            </div>

            <div class="col-12 col-md-auto dropdown text-center text-md-start">
                <button class="dropdown-toggle px-2 py-1" 
                        type="button" 
                        id="dropdownMenuButton1"
                        data-bs-toggle="dropdown">
                    Riset dan Produk
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li><a href="" class="dropdown-item">Publikasi Dosen</a></li>
                    <li><a href="" class="dropdown-item">Produk Lab</a></li>
                </ul>
            </div>

            <div class="col-12 col-md-auto">
                <a href="">Mitra</a>
            </div>

            <div class="col-12 col-md-auto">
                <a href="">Rekrutment</a>
            </div>
        </div>

        <!-- Contact & Info -->
        <div class="row mb-4 text-center text-md-start">

            <div class="col-12 col-md-3">
                <i data-feather="home"></i>
                <p class="mb-0">
                    Jl. Soekarno Hatta No.9, Jatimulyo, Lowokwaru, Malang, Jawa Timur 65141
                </p>
            </div>

            <div class="col-12 col-md-3">
                <p class="mb-0">
                    Applied Informatics Laboratory, Postgraduate Building, 2nd Floor, Polinema
                </p>
            </div>

            <div class="col-12 col-md-3">
                <p class="mb-0">0984018041</p>
            </div>

            <div class="col-12 col-md-3">
                <p class="mb-0">info@company.com</p>
            </div>

        </div>

        <hr>

        <div class="text-center fw-bold">
            <p class="mb-0">Copyright © 2025 Lab Applied Informatics Polinema</p>
        </div>
    </div>
</footer>



<!-- jQuery -->
<script src="<?= asset_url('js/jquery.min.js') ?>"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>

<!-- CSS Footer -->
<link rel="stylesheet" href="<?= asset_url('css/components/footer.css') ?>">

<!-- Feather Icon -->
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

</body>

</html>