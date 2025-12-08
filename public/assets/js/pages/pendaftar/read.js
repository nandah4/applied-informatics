/**
 * File: js/pages/pendaftar/read.js
 * Deskripsi: JavaScript untuk halaman detail pendaftar (read.php)
 *
 * Fitur:
 * - Update status seleksi pendaftar (Diterima/Ditolak)
 * - Rich text feedback editor untuk penolakan (Quill)
 * - Delete pendaftar dengan konfirmasi
 * - Kirim email notifikasi otomatis
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
        
        if (document.getElementById('feedback-editor')) {
            quillEditor = new Quill('#feedback-editor', {
                theme: 'snow',
                placeholder: 'Masukkan alasan penolakan...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered' }]
                    ]
                }
            });
        }

        // ========================================
        // Toggle Feedback Editor Visibility
        // ========================================

        const statusSelect = $('#selectStatusSeleksi');
        const feedbackContainer = $('#feedbackContainer');

        function toggleFeedbackEditor() {
            const selectedStatus = statusSelect.val();
            if (selectedStatus === 'Ditolak') {
                feedbackContainer.addClass('show');
            } else {
                feedbackContainer.removeClass('show');
                // Clear editor content when not Ditolak
                if (quillEditor) {
                    quillEditor.setContents([]);
                }
            }
        }

        // Initial check
        toggleFeedbackEditor();

        // On change
        statusSelect.on('change', toggleFeedbackEditor);

        // ========================================
        // Form Update Status Seleksi
        // ========================================

        $('#formUpdateStatus').on('submit', function (e) {
            e.preventDefault();

            const form = $(this);
            const submitBtn = $('#btnUpdateStatus');
            const statusSeleksi = statusSelect.val();

            // Validasi
            if (!statusSeleksi) {
                jQueryHelpers.showAlert('Pilih status seleksi terlebih dahulu', 'danger', 3000);
                return;
            }

            // Get deskripsi from Quill editor (only for Ditolak)
            let deskripsi = '';
            if (statusSeleksi === 'Ditolak' && quillEditor) {
                const quillContent = quillEditor.root.innerHTML;
                // Check if editor has actual content (not just empty paragraph)
                const textContent = quillEditor.getText().trim();
                if (textContent.length === 0) {
                    jQueryHelpers.showAlert('Mohon isi alasan penolakan', 'danger', 3000);
                    return;
                }
                deskripsi = quillContent;
                $('#deskripsiInput').val(deskripsi);
            }

            // Konfirmasi
            let confirmMessage = `Apakah Anda yakin ingin mengubah status menjadi "Status"?\n\n` +
                `Email notifikasi akan otomatis dikirim ke pendaftar.`;

            if(statusSeleksi === 'Pending') {
                confirmMessage = `Status saat ini PENDING dan Anda tidak bisa mengubah dari DITERIMA atau DITOLAK ke PENDING`
            } else if (statusSeleksi === 'Diterima') {
                confirmMessage = `Apakah Anda yakin ingin mengubah status menjadi "DITERIMA"?\n\n` +
                `Email notifikasi akan otomatis dikirim ke pendaftar.`
            } else {
                confirmMessage = `Apakah Anda yakin ingin mengubah status menjadi "DITOLAK"?\n\n` +
                `Feedback penolakan akan dikirim ke email pendaftar.`
            }
            
            if (!confirm(confirmMessage)) {
                return;
            }

            // Disable button & show loading
            submitBtn.prop('disabled', true);
            const originalButtonHtml = submitBtn.html();
            submitBtn.html(`
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Memproses...
            `);

            // Get CSRF token
            const csrfToken = $('input[name="csrf_token"]').val();

            // Prepare data
            const requestData = {
                pendaftar_id: form.find('input[name="pendaftar_id"]').val(),
                status_seleksi: statusSeleksi,
                csrf_token: csrfToken
            };

            // Add deskripsi only if Ditolak
            if (statusSeleksi === 'Ditolak') {
                requestData.deskripsi = deskripsi;
            }

            // AJAX Request
            jQueryHelpers.makeAjaxRequest({
                url: `${BASE_URL}/admin/daftar-pendaftar/update-status`,
                method: 'POST',
                data: requestData,
                onSuccess: (response) => {
                    if (response.success) {
                        jQueryHelpers.showAlert(response.message, 'success', 2000);

                        // Redirect ke list setelah 2 detik
                        setTimeout(() => {
                            window.location.href = `${BASE_URL}/admin/daftar-pendaftar`;
                        }, 2000);
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
    // DELETE PENDAFTAR
    // ========================================

    /**
     * Proses delete pendaftar via AJAX
     *
     * @param {number} id - ID pendaftar yang akan dihapus
     */
    window.confirmDelete = function (id) {
        if (!confirm('Apakah Anda yakin ingin menghapus data pendaftar ini?\n\nFile CV dan KHS juga akan terhapus.')) {
            return;
        }

        const csrfToken = $('input[name="csrf_token"]').val();
        const deleteBtn = $(`button[onclick="confirmDelete(${id})"]`);
        deleteBtn.prop("disabled", true);

        jQueryHelpers.makeAjaxRequest({
            url: `${BASE_URL}/admin/daftar-pendaftar/delete/${id}`,
            method: 'POST',
            data: { csrf_token: csrfToken },
            onSuccess: (response) => {
                if (response.success) {
                    jQueryHelpers.showAlert(response.message || 'Data pendaftar berhasil dihapus!', 'success', 2000);
                    setTimeout(() => {
                        window.location.href = `${BASE_URL}/admin/daftar-pendaftar`;
                    }, 1000);
                } else {
                    jQueryHelpers.showAlert(response.message || 'Gagal menghapus data pendaftar', 'danger', 5000);
                    deleteBtn.prop("disabled", false);
                }
            },
            onError: (errorMessage) => {
                console.error('Delete pendaftar error:', errorMessage);
                jQueryHelpers.showAlert('Terjadi kesalahan saat menghapus data', 'danger', 5000);
                deleteBtn.prop("disabled", false);
            },
        });
    };

})();
