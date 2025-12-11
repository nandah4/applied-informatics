/**
 * File: pages/asisten-lab/edit.js
 * Deskripsi: Script untuk halaman edit data asisten lab
 *
 * Fitur:
 * - Validasi form (NIM, nama, email, semester, dll)
 * - Submit form update via AJAX
 * - Input sanitization (trim whitespace)
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 * - validationHelpers.js
 */

(function () {
    "use strict";

    /** @type {string} Base URL aplikasi */
    const BASE_URL = $('meta[name="base-url"]').attr("content") || "/applied-informatics";

    $(document).ready(function () {
        // ========================================
        // Form Submission
        // ========================================

        /**
         * Event listener untuk tombol submit form
         * Melakukan validasi dan mengirim data ke server
         * @listens click
         */
        $('#btnSubmit').on('click', function (e) {
            e.preventDefault();

            // Clear previous errors
            jQueryHelpers.clearAllErrors('formEditAsistenLab');

            /** @type {AsistenLabFormData} */
            const formData = {
                id: $('#formEditAsistenLab input[name="id"]').val(),
                nim: $('#nim').val().trim(),
                nama: $('#nama').val().trim(),
                email: $('#email').val().trim(),
                no_hp: $('#no_hp').val().trim(),
                semester: $('#semester').val(),
                link_github: $('#link_github').val().trim(),
                tipe_anggota: $('#tipe_anggota').val(),
                periode_aktif: $('#periode_aktif').val().trim(),
                tanggal_selesai: $('#tanggal_selesai').val(),
                status_aktif: $('#status_aktif').val(),
                csrf_token: $('input[name="csrf_token"]').val()
            };

            // Validation
            const isValid = validateForm(formData);

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
            submitFormData(formData, submitBtn, originalButtonHtml);
        });

        /**
         * Memvalidasi seluruh field form
         * @function validateForm
         * @param {AsistenLabFormData} formData - Data form yang akan divalidasi
         * @returns {boolean} True jika semua validasi lolos
         */
        function validateForm(formData) {
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

            // Validate Tipe Anggota
            if (!formData.tipe_anggota) {
                jQueryHelpers.showError('tipe_anggota', 'tipeAnggotaError', 'Tipe Anggota wajib dipilih');
                isValid = false;
            } else {
                jQueryHelpers.clearError('tipe_anggota', 'tipeAnggotaError');
            }

            // Validate Status Aktif
            if (!formData.status_aktif && formData.status_aktif !== '0') {
                jQueryHelpers.showError('status_aktif', 'statusAktifError', 'Status wajib dipilih');
                isValid = false;
            } else {
                jQueryHelpers.clearError('status_aktif', 'statusAktifError');
            }

            return isValid;
        }

        /**
         * Mengirim data form ke server via AJAX
         * @function submitFormData
         * @param {AsistenLabFormData} formData - Data form yang akan dikirim
         * @param {jQuery} submitBtn - jQuery element tombol submit
         * @param {string} originalButtonHtml - HTML asli tombol submit untuk restore
         * @returns {void}
         */
        function submitFormData(formData, submitBtn, originalButtonHtml) {
            jQueryHelpers.makeAjaxRequest({
                url: `${BASE_URL}/admin/asisten-lab/update`,
                method: 'POST',
                data: formData,
                onSuccess: (response) => {
                    if (response.success) {
                        jQueryHelpers.showAlert(response.message || 'Data berhasil disimpan!', 'success', 2000);

                        // Redirect to list page after 1 second
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
        }

        // ========================================
        // Input Sanitization
        // ========================================

        /**
         * Event listener untuk trim whitespace pada blur
         * @listens blur
         */
        $('input[type="text"], input[type="email"]').on('blur', function () {
            $(this).val($(this).val().trim());
        });
    });

})();

