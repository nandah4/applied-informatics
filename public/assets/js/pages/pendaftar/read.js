/**
 * File: js/pages/pendaftar/read.js
 * Deskripsi: JavaScript untuk halaman detail pendaftar (read.php)
 *
 * Fitur:
 * - Update status seleksi pendaftar (Diterima/Ditolak)
 * - Delete pendaftar dengan konfirmasi
 * - Kirim email notifikasi otomatis
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 * - Bootstrap
 */

(function () {
    "use strict";

    const BASE_URL = $('meta[name="base-url"]').attr("content") || "/applied-informatics";

    $(document).ready(function () {
        // ========================================
        // Form Update Status Seleksi
        // ========================================

        $('#formUpdateStatus').on('submit', function (e) {
            e.preventDefault();

            const form = $(this);
            const submitBtn = $('#btnUpdateStatus');
            const statusSeleksi = form.find('select[name="status_seleksi"]').val();

            // Validasi
            if (!statusSeleksi) {
                jQueryHelpers.showAlert('Pilih status seleksi terlebih dahulu', 'danger', 3000);
                return;
            }

            // Konfirmasi
            const statusText = statusSeleksi === 'Diterima' ? 'DITERIMA' : 'DITOLAK';
            const confirmMessage = `Apakah Anda yakin ingin mengubah status menjadi "${statusText}"?\n\n` +
                `Email notifikasi akan otomatis dikirim ke pendaftar.`;

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

            // AJAX Request
            jQueryHelpers.makeAjaxRequest({
                url: `${BASE_URL}/admin/daftar-pendaftar/update-status`,
                method: 'POST',
                data: {
                    pendaftar_id: form.find('input[name="pendaftar_id"]').val(),
                    status_seleksi: statusSeleksi,
                    csrf_token: csrfToken
                },
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
