<!-- header -->
<?php
include __DIR__ . '/../layouts/header.php';
?>

<main class="contactus-page mb-5">
    <div class="container-fluid px-md-5 pb-5">
        <div class="breadcrumb-nav">
            <span class="breadcrumb-item">Laboratorium Applied Informatics</span>
            <span class="breadcrumb-separator">›</span>
            <span class="breadcrumb-item active">Hubungi Kami</span>
        </div>

        <!-- Header Section -->
        <div class="mb-5 text-center text-md-start">
            <h1 class="title-section mb-3">Hubungi Tim Kami</h1>
            <p class="subtitle-section">Laboratorium Applied Informatics siap mendukung pengembangan proyek
                software Anda. Diskusikan kebutuhan digital Anda dengan tim ahli kami.</p>
        </div>

        <!-- Main Content -->
        <div class="row g-4 gx-lg-5">
            <!-- Left Column - Contact Form -->

            <div class="col-lg-6 col-md-12 mb-5">
                <div class="contact-form-wrapper p-4 p-md-5">
                    <h3 class="mb-4 contact-us-title">Mari Diskusikan Proyek Anda</h3>

                    <!-- CSRF Token -->
                    <?= CsrfHelper::tokenField() ?>

                    <!-- Contact Form -->
                    <form id="contactForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input
                                type="text"
                                class="form-control"
                                id="name"
                                name="nama_pengirim"
                                placeholder="Masukkan nama lengkap Anda"
                                maxlength="150">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
                            <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email_pengirim"
                                placeholder="Masukkan email Anda"
                                maxlength="150">
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label">Tulis Pesan <span class="text-danger">*</span></label>
                            <textarea
                                class="form-control"
                                id="message"
                                name="isi_pesan"
                                rows="4"
                                placeholder="Sampaikan pesan Anda di sini (Minimal 10 karakter)"
                                minlength="10"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3">Kirim Pesan</button>
                    </form>
                </div>
            </div>

            <!-- Right Column - Contact Info and Map -->
            <div class="col-lg-6 col-md-12">
                <div class="contact-info-wrapper h-100">
                    <h3 class="mb-4 contact-us-title">Ingin menghubungi kami secara langsung?</h3>

                    <!-- Contact Details -->
                    <div class="contact-details mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-telephone me-3 fs-5"></i>
                            <span>+62-8234-5678-8901</span>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-envelope me-3 fs-5"></i>
                            <span>contact@appliedinformatics.com</span>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-clock me-3 fs-5"></i>
                            <span>Monday to Friday, 9 AM - 6 PM (GMT)</span>
                        </div>
                    </div>

                    <!-- Map Section - Enhanced -->
                    <div class="map-card">
                        <!-- Map Container with Floating Info -->
                        <div class="map-container">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1975.7693098921495!2d112.6140782!3d-7.943157599999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e78830014402101%3A0xb909c1117857383d!2sApplied%20Informatics%20Laboratory!5e0!3m2!1sen!2sid!4v1764481175684!5m2!1sen!2sid"
                                style="border:0;"
                                allowfullscreen
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>

                            <!-- Floating Location Info -->
                            <div class="location-info-floating">
                                <div class="location-header">
                                    <div class="location-icon">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </div>
                                    <div class="location-title">
                                        <h4>Applied Informatics Laboratory</h4>

                                    </div>
                                </div>

                                <p class="location-address">
                                    Jl. Simpang Remujung, Jatimulyo, Kec. Lowokwaru, Kota Malang, Jawa Timur 65141
                                </p>

                                <div class="location-actions">
                                    <a href="https://www.google.com/maps/dir//Applied+Informatics+Laboratory,+3J47%2BQH5,+Jl.+Simpang+Remujung,+Jatimulyo,+Kec.+Lowokwaru,+Kota+Malang,+Jawa+Timur+65141"
                                        target="_blank"
                                        class="btn-directions">
                                        <i class="bi bi-sign-turn-right"></i>
                                        <span>Directions</span>
                                    </a>
                                    <a href="https://www.google.com/maps/place/Applied+Informatics+Laboratory/@-7.9431017,112.6114072,799m/data=!3m2!1e3!4b1!4m6!3m5!1s0x2e78830014402101:0xb909c1117857383d!8m2!3d-7.9431017!4d112.6139821!16s%2Fg%2F11l_8x_qd_?entry=ttu&g_ep=EgoyMDI1MTIwOS4wIKXMDSoKLDEwMDc5MjA3M0gBUAM%3D"
                                        target="_blank"
                                        class="btn-view-larger">
                                        <i class="bi bi-arrows-fullscreen"></i>
                                        <span>View Larger</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- CSS -->
<link rel="stylesheet" href="<?= asset_url('css/pages/contact-us/contact_us.css') ?>">

<!-- JavaScript -->
<script src="<?= asset_url('js/jquery.min.js') ?>"></script>
<script>
    /**
     * Deskripsi: JavaScript untuk form Contact Us (Client Side)
     *
     * Fitur:
     * - Validasi form client-side
     * - Submit form via AJAX
     * - Feedback visual untuk user
     *
     * Dependencies:
     * - jQuery
     */

    (function() {
        "use strict";

        const BASE_URL = $('meta[name="base-url"]').attr("content") || "/applied-informatics";

        $(document).ready(function() {
            // ========================================
            // Form Contact Us Submit Handler
            // ========================================

            $('#contactForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');

                // Clear previous errors
                clearFormErrors(form);

                // Get form data
                const formData = {
                    nama_pengirim: $('#name').val().trim(),
                    email_pengirim: $('#email').val().trim(),
                    isi_pesan: $('#message').val().trim(),
                    csrf_token: $('input[name="csrf_token"]').val()
                };

                // Client-side validation
                if (!validateForm(formData, form)) {
                    return;
                }

                // Disable button & show loading
                submitBtn.prop('disabled', true);
                const originalButtonText = submitBtn.text();
                submitBtn.html(`
                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                Mengirim...
            `);

                // AJAX Request
                $.ajax({
                    url: `${BASE_URL}/contact-us/submit`,
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            showSuccessMessage(response.message);

                            // Reset form
                            form[0].reset();

                            // Scroll to top to show success message
                            $('html, body').animate({
                                scrollTop: 0
                            }, 500);
                        } else {
                            showErrorMessage(response.message || 'Terjadi kesalahan. Silakan coba lagi.');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error:', xhr);
                        let errorMessage = 'Terjadi kesalahan sistem. Silakan coba lagi nanti.';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        showErrorMessage(errorMessage);
                    },
                    complete: function() {
                        // Re-enable button
                        submitBtn.prop('disabled', false);
                        submitBtn.text(originalButtonText);
                    }
                });
            });

            // ========================================
            // Validation Function
            // ========================================

            function validateForm(data, form) {
                let isValid = true;

                // Validate name
                if (data.nama_pengirim.length < 3) {
                    showFieldError(form, '#name', 'Nama minimal 3 karakter');
                    isValid = false;
                }

                // Validate email
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(data.email_pengirim)) {
                    showFieldError(form, '#email', 'Format email tidak valid');
                    isValid = false;
                }

                // Validate message
                if (data.isi_pesan.length < 10) {
                    showFieldError(form, '#message', 'Pesan minimal 10 karakter');
                    isValid = false;
                }

                return isValid;
            }

            // ========================================
            // Helper Functions
            // ========================================

            function showFieldError(form, fieldSelector, message) {
                const field = form.find(fieldSelector);
                field.addClass('is-invalid');

                // Remove existing error message
                field.siblings('.invalid-feedback').remove();

                // Add error message
                field.after(`<div class="invalid-feedback d-block">${message}</div>`);
            }

            function clearFormErrors(form) {
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').remove();
                $('.alert').remove();
            }

            function showSuccessMessage(message) {
                const alertHtml = `
                <div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                    <div class="d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle-fill me-2" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                        <strong>Berhasil!</strong> ${message}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

                $('.contactus-page .container-fluid').prepend(alertHtml);

                // Auto dismiss after 5 seconds
                setTimeout(() => {
                    $('.alert').fadeOut('slow', function() {
                        $(this).remove();
                    });
                }, 5000);
            }

            function showErrorMessage(message) {
                const alertHtml = `
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-exclamation-triangle-fill me-2" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        <strong>Error!</strong> ${message}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

                $('.contactus-page .container-fluid').prepend(alertHtml);

                // Scroll to alert
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
            }
        });

    })();
</script>

<!-- Footer -->
<?php
include __DIR__ . '/../layouts/footer.php';
?>