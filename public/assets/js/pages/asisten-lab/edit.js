/**
 * File: js/pages/asisten-lab/edit.js
 * Deskripsi: JavaScript untuk halaman edit asisten lab
 *
 * Fitur:
 * - Form validation
 * - AJAX form submission
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 * - validationHelpers.js
 * - Bootstrap
 */

(function () {
    "use strict";

    const BASE_URL = $('meta[name="base-url"]').attr("content") || "/applied-informatics";

    $(document).ready(function () {
        // ========================================
        // Form Submission
        // ========================================

        $('#btnSubmit').on('click', function (e) {
            e.preventDefault();

            // Clear previous errors
            jQueryHelpers.clearAllErrors('formEditAsistenLab');

            // Get form data
            const formData = {
                id: $('#formEditAsistenLab input[name="id"]').val(),
                nim: $('#nim').val().trim(),
                nama: $('#nama').val().trim(),
                email: $('#email').val().trim(),
                no_hp: $('#no_hp').val().trim(),
                semester: $('#semester').val(),
                link_github: $('#link_github').val().trim(),
                jabatan_lab: $('#jabatan_lab').val(),
                status_aktif: $('#status_aktif').val(),
                csrf_token: $('input[name="csrf_token"]').val()
            };

            // Validation
            let isValid = true;

            // Validate NIM
            if (!formData.nim) {
                jQueryHelpers.showError('nim', 'nimError', 'NIM wajib diisi');
                isValid = false;
            } else {
                jQueryHelpers.clearError('nim', 'nimError');
            }

            // Validate Nama
            if (!formData.nama) {
                jQueryHelpers.showError('nama', 'namaError', 'Nama wajib diisi');
                isValid = false;
            } else if (formData.nama.length < 3) {
                jQueryHelpers.showError('nama', 'namaError', 'Nama minimal 3 karakter');
                isValid = false;
            } else {
                jQueryHelpers.clearError('nama', 'namaError');
            }

            // Validate Email
            const emailValidation = validationHelpers.validateEmail(formData.email, true);
            if (!emailValidation.valid) {
                jQueryHelpers.showError('email', 'emailError', emailValidation.message);
                isValid = false;
            } else {
                jQueryHelpers.clearError('email', 'emailError');
            }

            // Validate Semester
            if (!formData.semester) {
                jQueryHelpers.showError('semester', 'semesterError', 'Semester wajib dipilih');
                isValid = false;
            } else {
                jQueryHelpers.clearError('semester', 'semesterError');
            }

            // Validate Link Github (optional, but if filled must be valid URL)
            if (formData.link_github) {
                const urlPattern = /^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)$/;
                if (!urlPattern.test(formData.link_github)) {
                    jQueryHelpers.showError('link_github', 'linkGithubError', 'Format URL tidak valid. Gunakan format: https://github.com/username');
                    isValid = false;
                } else {
                    jQueryHelpers.clearError('link_github', 'linkGithubError');
                }
            } else {
                jQueryHelpers.clearError('link_github', 'linkGithubError');
            }

            // Validate Jabatan Lab
            if (!formData.jabatan_lab) {
                jQueryHelpers.showError('jabatan_lab', 'jabatanLabError', 'Jabatan Lab wajib dipilih');
                isValid = false;
            } else {
                jQueryHelpers.clearError('jabatan_lab', 'jabatanLabError');
            }

            // Validate Status Aktif
            if (!formData.status_aktif && formData.status_aktif !== '0') {
                jQueryHelpers.showError('status_aktif', 'statusAktifError', 'Status wajib dipilih');
                isValid = false;
            } else {
                jQueryHelpers.clearError('status_aktif', 'statusAktifError');
            }

            if (!isValid) {
                jQueryHelpers.showAlert('Mohon periksa kembali form Anda', 'danger', 3000);
                return;
            }

            // Disable submit button
            const submitBtn = $('#btnSubmit');
            const originalButtonHtml = submitBtn.html();
            submitBtn.prop('disabled', true);
            submitBtn.html(`
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Menyimpan...
            `);

            // Submit via AJAX
            jQueryHelpers.makeAjaxRequest({
                url: `${BASE_URL}/admin/asisten-lab/update`,
                method: 'POST',
                data: formData,
                onSuccess: (response) => {
                    if (response.success) {
                        jQueryHelpers.showAlert(response.message || 'Data berhasil disimpan!', 'success', 2000);

                        // Redirect to detail page after 1 second
                        setTimeout(() => {
                            window.location.href = `${BASE_URL}/admin/asisten-lab`;
                        }, 1000);
                    } else {
                        jQueryHelpers.showAlert(response.message || 'Gagal menyimpan data', 'danger', 5000);
                        submitBtn.prop('disabled', false);
                        submitBtn.html(originalButtonHtml);
                    }
                },
                onError: (errorMessage) => {
                    jQueryHelpers.showAlert('Terjadi kesalahan: ' + errorMessage, 'danger', 5000);
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalButtonHtml);
                },
            });
        });

        // ========================================
        // Input Sanitization
        // ========================================

        // Trim input on blur
        $('input[type="text"], input[type="email"]').on('blur', function () {
            $(this).val($(this).val().trim());
        });
    });

})();
