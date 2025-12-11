<!-- Footer Section -->
<footer class="modern-footer">
    <div class="container-fluid px-5">

        <!-- Main Footer Content -->
        <div class="footer-main py-5">
            <div class="row g-4">

                <!-- Brand Column -->
                <div class="col-lg-4 col-md-6">
                    <div class="footer-brand mb-4">
                        <img src="<?= asset_url('images/lab-ai-logo.png') ?>"
                            alt="Lab AI Logo"
                            class="footer-logo mb-3">
                        <p class="footer-tagline">
                            Laboratorium Applied Informatics - Mengembangkan inovasi teknologi
                            untuk masa depan yang lebih baik.
                        </p>
                    </div>

                    <!-- Social Media -->
                    <div class="footer-social">
                        <a href="#" class="social-link" aria-label="Instagram">
                            <i data-feather="instagram"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="LinkedIn">
                            <i data-feather="linkedin"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="GitHub">
                            <i data-feather="github"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Email">
                            <i data-feather="mail"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links Column -->
                <div class="col-lg-2 col-md-6">
                    <h3 class="footer-title">Menu</h3>
                    <ul class="footer-links">
                        <li><a href="<?= base_url("") ?>">Beranda</a></li>
                        <li><a href="<?= base_url("anggota-laboratorium") ?>">Anggota</a></li>
                        <li><a href="<?= base_url("aktivitas-laboratorium") ?>">Aktivitas Laboratorium</a></li>
                        <li><a href="<?= base_url("mitra-laboratorium") ?>">Mitra</a></li>
                        <li><a href="<?= base_url("rekrutment") ?>">Rekrutmen</a></li>
                    </ul>
                </div>

                <!-- Riset & Produk Column -->
                <div class="col-lg-3 col-md-6">
                    <h3 class="footer-title">Riset & Produk</h3>
                    <ul class="footer-links">
                        <li><a href="<?= base_url("publikasi-dosen") ?>">Publikasi Dosen</a></li>
                        <li><a href="<?= base_url("produk-lab") ?>">Produk Lab</a></li>
                        <li><a href="<?= base_url("ar-showcase") ?>">AR</a></li>
                    </ul>
                </div>

                <!-- Contact Column -->
                <div class="col-lg-3 col-md-6">
                    <h3 class="footer-title">Kontak</h3>
                    <ul class="footer-links">
                        <li><a href="<?= base_url("contact-us") ?>">Contact Us</a></li>
                        <ul class="footer-contact">
                            <li>
                                <i data-feather="home"></i>
                                <span>Gedung Pascasarjana Lt. 2<br>Polinema, Malang</span>
                            </li>
                            <li>
                                <i data-feather="phone"></i>
                                <span>(0341) 404424</span>
                            </li>
                            <li>
                                <i data-feather="mail"></i>
                                <span>ai.lab@polinema.ac.id</span>
                            </li>
                        </ul>
                    </ul>
                </div>

            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom py-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <p class="footer-copyright mb-0">
                        © 2025 Applied Informatics Lab - Polinema. All rights reserved.
                    </p>
                </div>
            </div>
        </div>

    </div>
</footer>


<!-- jQuery -->
<script src="<?= asset_url('js/jquery.min.js') ?>"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>

<!-- Feather Icons -->
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>
    feather.replace();
</script>

<!-- CSS Footer -->
<link rel="stylesheet" href="<?= asset_url('css/components/footer.css') ?>">


</body>

</html>