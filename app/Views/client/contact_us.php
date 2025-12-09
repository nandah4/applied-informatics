<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';
?>

<main class="contactus-page mb-5">
    <div class="container-fluid px-md-5 px-3 pb-5">
        <!-- Breadcrumb Navigation -->
        <nav aria-label="breadcrumb" class="mt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Laboratorium Applied Informatics</li>
                <li class="breadcrumb-item active" aria-current="page">Contact us</li>
            </ol>
        </nav>
    </div>

    <div class="container-fluid mb-5 px-md-5 px-3">
        <!-- Header Section -->
        <div class="mb-5 text-center text-md-start">
            <h1 class="title-section mb-3">Hubungi Tim Kami</h1>
            <p class="subtitle-section w-75">Laboratorium Applied Informatics siap mendukung pengembangan proyek software Anda. Diskusikan kebutuhan digital Anda dengan tim ahli kami.</p>
        </div>

        <!-- Main Content -->
        <div class="row g-4 gx-lg-5">
            <!-- Left Column - Contact Form -->
            <div class="col-lg-6 col-md-12 mb-5">
                <div class="contact-form-wrapper p-4 p-md-5 h-100">
                    <h2 class="mb-4">Mari Diskusikan Proyek Anda</h2>

                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="name" placeholder="Masukkan nama Lengkap Anda">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Masukkan email Anda">
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label">Tulis Pesan</label>
                            <textarea class="form-control" id="message" rows="4" placeholder="Sampaikan pesan Anda di sini"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3">Kirim Pesan</button>
                    </form>
                </div>
            </div>

            <!-- Right Column - Contact Info and Map -->
            <div class="col-lg-6 col-md-12">
                <div class="contact-info-wrapper h-100">
                    <h2 class="mb-4">Ingin menghubungi kami secara langsung?</h2>

                    <!-- Contact Details -->
                    <div class="contact-details mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-telephone text-primary me-3 fs-5"></i>
                            <span>+62-8234-5678-8901</span>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-envelope text-primary me-3 fs-5"></i>
                            <span>contact@landingplay.com</span>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-clock text-primary me-3 fs-5"></i>
                            <span>Monday to Friday, 9 AM - 6 PM (GMT)</span>
                        </div>
                    </div>

                    <!-- Map -->
                    <div class="map-wrapper">
                        <div class="map-container">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1975.7693098921495!2d112.6140782!3d-7.943157599999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78830014402101%3A0xb909c1117857383d!2sApplied%20Informatics%20Laboratory!5e0!3m2!1sen!2sid!4v1764481175684!5m2!1sen!2sid"
                                style="border:0;" 
                                allowfullscreen 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<link rel="stylesheet" href="<?= asset_url('css/pages/contact-us/contact_us.css') ?>">

<!-- Footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>