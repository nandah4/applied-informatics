/**
 * File: pages/recruitment/read.js
 * Deskripsi: Script untuk halaman detail recruitment
 *
 * Fitur:
 * - Delete recruitment dengan konfirmasi dari detail page
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 */

(function() {
    'use strict';

    // ============================================================
    // DELETE MODULE
    // ============================================================

    const DeleteModule = {
        /**
         * Konfirmasi dan hapus recruitment
         * Dipanggil dari tombol delete di halaman detail
         */
        confirmDelete: function() {
            // Ambil ID dari URL atau dari element tersembunyi
            const pathArray = window.location.pathname.split('/');
            const id = pathArray[pathArray.length - 1];

            if (!id || isNaN(id)) {
                alert('ID recruitment tidak valid');
                return;
            }

            // Tampilkan konfirmasi
            if (!confirm('Apakah Anda yakin ingin menghapus data recruitment ini?\n\nData yang dihapus tidak dapat dikembalikan.')) {
                return;
            }

            // Proses delete
            this.deleteRecruitment(id);
        },

        /**
         * Proses delete recruitment via AJAX
         * @param {number} id - ID recruitment yang akan dihapus
         */
        deleteRecruitment: function(id) {
            // Disable tombol delete
            const deleteBtn = $('.btn-danger-custom');
            deleteBtn.prop('disabled', true);

            // Request AJAX menggunakan jQueryHelpers
            jQueryHelpers.makeAjaxRequest({
                url: `/applied-informatics/recruitment/delete/${id}`,
                method: 'POST',
                data: {},
                onSuccess: (response) => {
                    if (response.success) {
                        // Tampilkan notifikasi success
                        alert('Data recruitment berhasil dihapus!');

                        // Redirect ke halaman list
                        window.location.href = '/applied-informatics/recruitment';
                    } else {
                        // Tampilkan pesan error
                        alert(response.message || 'Gagal menghapus data recruitment');

                        // Re-enable tombol delete
                        deleteBtn.prop('disabled', false);
                    }
                },
                onError: (errorMessage) => {
                    // Tampilkan error
                    alert('Terjadi kesalahan sistem. Silakan coba lagi.');

                    // Log error untuk debugging
                    console.error('Delete recruitment error:', errorMessage);

                    // Re-enable tombol delete
                    deleteBtn.prop('disabled', false);
                }
            });
        }
    };

    // ============================================================
    // INISIALISASI
    // ============================================================

    $(document).ready(function() {
        // Expose confirmDelete ke global scope
        window.confirmDelete = function() {
            DeleteModule.confirmDelete();
        };
    });

})();
