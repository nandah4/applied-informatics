/**
 * File: js/pages/contact/read.js
 * Deskripsi: JavaScript untuk halaman detail pesan (read.php)
 *
 * Fitur:
 * - Balas pesan dengan Quill rich text editor
 * - Kirim balasan via email otomatis
 * - Update catatan admin
 * - Delete pesan dengan konfirmasi
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 * - Bootstrap
 * - Quill.js
 */

(function () {
    "use strict";

    const BASE_URL = $('meta[name="base-url"]').attr("content") || "/applied-informatics";
    
    // Quill Editor Instance
    let quillEditor = null;

    $(document).ready(function () {
        // ========================================
        // Initialize Quill Editor
        // ========================================
        
        if (document.getElementById('balasan-editor')) {
            quillEditor = new Quill('#balasan-editor', {
                theme: 'snow',
                placeholder: 'Tulis balasan email di sini...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        ['link'],
                        ['clean']
                    ]
                }
            });
        }

        // ========================================
        // Form Balas Pesan
        // ========================================

        $('#formBalasPesan').on('submit', function (e) {
            e.preventDefault();

            const form = $(this);
            const submitBtn = $('#btnBalasPesan');

            // Get balasan from Quill editor
            if (!quillEditor) {
                jQueryHelpers.showAlert('Editor tidak tersedia', 'danger', 3000);
                return;
            }

            const quillContent = quillEditor.root.innerHTML;
            const textContent = quillEditor.getText().trim();

            // Validasi content tidak kosong
            if (textContent.length === 0) {
                jQueryHelpers.showAlert('Balasan email tidak boleh kosong', 'danger', 3000);
                return;
            }

            // Set balasan ke hidden input
            $('#balasanInput').val(quillContent);

            // Konfirmasi
            const confirmMessage = 'Apakah Anda yakin ingin mengirim balasan ini?\n\n' +
                'Email akan otomatis dikirim ke pengirim pesan.';
            
            if (!confirm(confirmMessage)) {
                return;
            }

            // Disable button & show loading
            submitBtn.prop('disabled', true);
            const originalButtonHtml = submitBtn.html();
            submitBtn.html(`
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Mengirim...
            `);

            // Get CSRF token
            const csrfToken = $('input[name="csrf_token"]').val();

            // Prepare data
            const requestData = {
                pesan_id: form.find('input[name="pesan_id"]').val(),
                balasan_email: quillContent,
                catatan_admin: form.find('textarea[name="catatan_admin"]').val(),
                csrf_token: csrfToken
            };

            // AJAX Request
            jQueryHelpers.makeAjaxRequest({
                url: `${BASE_URL}/admin/contact/balas`,
                method: 'POST',
                data: requestData,
                onSuccess: (response) => {
                    if (response.success) {
                        jQueryHelpers.showAlert(response.message, 'success', 3000);

                        // Redirect ke list setelah 3 detik
                        setTimeout(() => {
                            window.location.href = `${BASE_URL}/admin/contact`;
                        }, 3000);
                    } else {
                        jQueryHelpers.showAlert(response.message, 'danger', 5000);
                        // Re-enable button
                        submitBtn.prop('disabled', false);
                        submitBtn.html(originalButtonHtml);
                    }
                },
                onError: (errorMessage) => {
                    console.error('Error:', errorMessage);
                    jQueryHelpers.showAlert('Terjadi kesalahan saat memproses request', 'danger', 5000);

                    // Re-enable button
                    submitBtn.prop('disabled', false);
                    submitBtn.html(originalButtonHtml);
                },
            });
        });
    });

    // ========================================
    // DELETE PESAN
    // ========================================

    /**
     * Proses delete pesan via AJAX
     *
     * @param {number} id - ID pesan yang akan dihapus
     */
    window.confirmDelete = function (id) {
        if (!confirm('Apakah Anda yakin ingin menghapus pesan ini?\n\nTindakan ini tidak dapat dibatalkan.')) {
            return;
        }

        const csrfToken = $('input[name="csrf_token"]').val();
        const deleteBtn = $(`button[onclick="confirmDelete(${id})"]`);
        deleteBtn.prop("disabled", true);

        jQueryHelpers.makeAjaxRequest({
            url: `${BASE_URL}/admin/contact/delete/${id}`,
            method: 'POST',
            data: { csrf_token: csrfToken },
            onSuccess: (response) => {
                if (response.success) {
                    jQueryHelpers.showAlert(response.message || 'Pesan berhasil dihapus!', 'success', 2000);
                    setTimeout(() => {
                        window.location.href = `${BASE_URL}/admin/contact`;
                    }, 1000);
                } else {
                    jQueryHelpers.showAlert(response.message || 'Gagal menghapus pesan', 'danger', 5000);
                    deleteBtn.prop("disabled", false);
                }
            },
            onError: (errorMessage) => {
                console.error('Delete pesan error:', errorMessage);
                jQueryHelpers.showAlert('Terjadi kesalahan saat menghapus pesan', 'danger', 5000);
                deleteBtn.prop("disabled", false);
            },
        });
    };

})();