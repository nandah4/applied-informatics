/**
 * File: js/pages/asisten-lab/read.js
 * Deskripsi: JavaScript untuk halaman detail asisten lab (read.php)
 *
 * Fitur:
 * - Delete asisten lab dengan konfirmasi
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 * - Bootstrap
 */

(function () {
    "use strict";

    const BASE_URL = $('meta[name="base-url"]').attr("content") || "/applied-informatics";

    // ========================================
    // DELETE ASISTEN LAB
    // ========================================

    /**
     * Proses delete asisten lab via AJAX
     *
     * @param {number} id - ID asisten lab yang akan dihapus
     */
    window.confirmDelete = function (id) {
        if (!confirm('Apakah Anda yakin ingin menghapus data asisten lab ini?\n\nData yang sudah dihapus tidak dapat dikembalikan.')) {
            return;
        }

        const csrfToken = $('input[name="csrf_token"]').val();
        const deleteBtn = $(`button[onclick="confirmDelete(${id})"]`);
        deleteBtn.prop("disabled", true);

        jQueryHelpers.makeAjaxRequest({
            url: `${BASE_URL}/admin/asisten-lab/delete/${id}`,
            method: 'POST',
            data: { csrf_token: csrfToken },
            onSuccess: (response) => {
                if (response.success) {
                    jQueryHelpers.showAlert(response.message || 'Data asisten lab berhasil dihapus!', 'success', 2000);
                    setTimeout(() => {
                        window.location.href = `${BASE_URL}/admin/asisten-lab`;
                    }, 1000);
                } else {
                    jQueryHelpers.showAlert(response.message || 'Gagal menghapus data asisten lab', 'danger', 5000);
                    deleteBtn.prop("disabled", false);
                }
            },
            onError: (errorMessage) => {
                console.error('Delete asisten lab error:', errorMessage);
                jQueryHelpers.showAlert('Terjadi kesalahan saat menghapus data', 'danger', 5000);
                deleteBtn.prop("disabled", false);
            },
        });
    };

})();
