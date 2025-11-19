/**
 * File: pages/produk/read.js
 * Deskripsi: Script untuk halaman detail produk
 *
 * Fitur:
 * - Delete produk dengan konfirmasi (AJAX)
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 */

(function() {
    'use strict';

    // ============================================================
    // MODUL DELETE PRODUK
    // ============================================================
    const DeleteModule = {
        /**
         * Tampilkan konfirmasi dan hapus produk
         * Function ini dipanggil dari button delete di view
         *
         * @param {number} id - ID produk yang akan dihapus
         * @param {string} deleteUrl - URL endpoint delete
         */
        confirmDelete: function(id, deleteUrl) {
            // Tampilkan konfirmasi native browser
            if (!confirm('Apakah Anda yakin ingin menghapus data produk ini?\n\nData yang dihapus tidak dapat dikembalikan.')) {
                return; // User cancel, hentikan proses
            }

            // Proses delete
            this.deleteProduk(id, deleteUrl);
        },

        /**
         * Proses delete produk via AJAX
         * Menggunakan jQueryHelpers untuk standardisasi AJAX call
         *
         * @param {number} id - ID produk yang akan dihapus
         * @param {string} deleteUrl - URL endpoint delete
         */
        deleteProduk: function(id, deleteUrl) {
            // Disable tombol delete untuk mencegah multiple clicks
            // Gunakan selector yang lebih spesifik dengan data attribute
            const deleteBtn = $(`button[data-produk-id="${id}"]`);
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
                            'Data produk berhasil dihapus!',
                            'success',
                            2000
                        );

                        // Redirect ke halaman index setelah 2 detik
                        setTimeout(() => {
                            // Redirect ke index, bukan reload
                            window.location.href = '/applied-informatics/produk';
                        }, 500);
                    } else {
                        // Tampilkan pesan error dari server
                        jQueryHelpers.showAlert(
                            response.message || 'Gagal menghapus data produk',
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
                    console.error('Delete produk error:', errorMessage);

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