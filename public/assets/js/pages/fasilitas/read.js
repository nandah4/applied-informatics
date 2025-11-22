/**
 * File: pages/fasilitas/read.js
 * Deskripsi: Script untuk halaman detail fasilitas
 *
 * Fitur:
 * - Delete fasilitas dengan konfirmasi (AJAX)
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 */

(function() {
    'use strict';

    // ============================================================
    // MODUL DELETE FASILITAS
    // ============================================================
    const DeleteModule = {
        /**
         * Tampilkan konfirmasi dan hapus fasilitas
         * Function ini dipanggil dari button delete di view
         *
         * @param {number} id - ID fasilitas yang akan dihapus
         * @param {string} deleteUrl - URL endpoint delete
         */
        confirmDelete: function(id, deleteUrl) {
            // Tampilkan konfirmasi native browser
            if (!confirm('Apakah Anda yakin ingin menghapus data fasilitas ini?\n\nData yang dihapus tidak dapat dikembalikan.')) {
                return; // User cancel, hentikan proses
            }

            // Proses delete
            this.deleteFasilitas(id, deleteUrl);
        },

        /**
         * Proses delete fasilitas via AJAX
         * Menggunakan jQueryHelpers untuk standardisasi AJAX call
         *
         * @param {number} id - ID fasilitas yang akan dihapus
         * @param {string} deleteUrl - URL endpoint delete
         */
        deleteFasilitas: function(id, deleteUrl) {
            // Disable tombol delete untuk mencegah multiple clicks
            // Gunakan selector yang lebih spesifik dengan data attribute
            const deleteBtn = $(`button[data-fasilitas-id="${id}"]`);
            deleteBtn.prop('disabled', true);

            // Request AJAX menggunakan jQueryHelpers
            jQueryHelpers.makeAjaxRequest({
                url: deleteUrl,
                method: 'POST',
                data: {},
                onSuccess: (response) => {
                    if (response.success) {
                        // Tampilkan notifikasi success
                        jQueryHelpers.showAlert(
                            'Data fasilitas berhasil dihapus!',
                            'success',
                            2000
                        );

                        // Redirect ke halaman index setelah 2 detik
                        setTimeout(() => {
                            // Redirect ke index, bukan reload
                            window.location.href = '/applied-informatics/admin/fasilitas';
                        }, 500);
                    } else {
                        // Tampilkan pesan error dari server
                        jQueryHelpers.showAlert(
                            response.message || 'Gagal menghapus data fasilitas',
                            'danger',
                            5000
                        );

                        // Re-enable tombol delete
                        deleteBtn.prop('disabled', false);
                    }
                },
                onError: (errorMessage) => {
                    // Tampilkan error notifikasi
                    jQueryHelpers.showAlert(
                        'Terjadi kesalahan sistem. Silakan coba lagi.',
                        'danger',
                        5000
                    );

                    // Log error untuk debugging
                    console.error('Delete fasilitas error:', errorMessage);

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
        window.confirmDelete = function(id, deleteUrl) {
            DeleteModule.confirmDelete(id, deleteUrl);
        };
    });

})();