<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';
?>

<main class="contactus-page mb-5">

    <div class="container">
        <div class="breadcrumb-nav">
            <span class="breadcrumb-item">Laboratorium Applied Informatics</span>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-item active">Contact us</span>
        </div>
    </div>

    <div class="container mb-5">

        <div>
            <h1 class="title-section mb-3">Get in Touch With Our Team</h1>
            <p class="subtitle-section w-75"> We're here to answer your questions, discuss your project, and help you
                find the best solutions for your software needs. Reach out to us, and let's start building something
                great together</p>
        </div>

        <div class="divider-hr"></div>

        <div class="row">
            <!-- Left Column - Contact Form -->
            <div class="col-sm-12 col-md-6">
                <div class="contact-form-wrapper p-5">
                    <h2 class="mb-4">Let's Talk About Your Project</h2>

                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Your full name">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email"
                                placeholder="We'll get back to you here">
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" rows="4"
                                placeholder="Tell us how we can help"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3">Send Message</button>
                    </form>
                </div>
            </div>

            <!-- Right Column - Contact Info and Map -->
            <div class="col-sm-12 col-md-6">
                <div class="contact-info-wrapper">
                    <h2 class="mb-4">Prefer a Direct Approach?</h2>

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
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1975.7693098921495!2d112.6140782!3d-7.943157599999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78830014402101%3A0xb909c1117857383d!2sApplied%20Informatics%20Laboratory!5e0!3m2!1sen!2sid!4v1764481175684!5m2!1sen!2sid"
                            width="600" height="340" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>

                        <!--
                        <div class="office-address mt-3 p-3 bg-light rounded">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-geo-alt-fill text-primary me-2 fs-5"></i>
                                <span>123 SaaS Street, Innovation City, Techland 56789</span>
                            </div>
                            <a href="#" class="btn btn-outline-primary btn-sm mt-2">
                                Get a Direction <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        -->

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