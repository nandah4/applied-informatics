/**
 * File: pages/publikasi/read.js
 * Deskripsi: Script untuk halaman detail publikasi
 *
 * Fitur:
 * - Delete publikasi dengan konfirmasi
 * - Notifikasi menggunakan Bootstrap Alert
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 */

(function() {
    'use strict';

    // ============================================================
    // MODUL DELETE PUBLIKASI
    // ============================================================

    const DeleteModule = {
        /**
         * Tampilkan konfirmasi dan hapus publikasi
         * Function ini dipanggil dari button delete di view
         */
        confirmDelete: function() {
            // Ambil ID dari URL (format: /publikasi/detail/{id})
            const pathArray = window.location.pathname.split('/');
            const id = pathArray[pathArray.length - 1];

            // Tampilkan konfirmasi native browser
            if (!confirm('Apakah Anda yakin ingin menghapus data publikasi ini?\n\nData yang dihapus tidak dapat dikembalikan.')) {
                return; // User cancel, hentikan proses
            }

            // Proses delete
            this.deletePublikasi(id);
        },

        /**
         * Proses delete publikasi via AJAX
         * Menggunakan jQueryHelpers untuk standardisasi AJAX call
         *
         * @param {number} id - ID publikasi yang akan dihapus
         */
        deletePublikasi: function(id) {
            // Disable tombol delete untuk mencegah multiple clicks
            const deleteBtn = $('#btn-delete-publikasi');
            deleteBtn.prop('disabled', true);

            // Request AJAX menggunakan jQueryHelpers
            jQueryHelpers.makeAjaxRequest({
                url: `/applied-informatics/publikasi/delete/${id}`,
                method: 'POST',
                data: {},
                onSuccess: (response) => {
                    if (response.success) {
                        // Tampilkan notifikasi success
                        jQueryHelpers.showAlert(
                            'Data publikasi berhasil dihapus!',
                            'success',
                            2000
                        );

                        // Redirect ke list page setelah 2 detik
                        setTimeout(() => {
                            window.location.href = '/applied-informatics/publikasi';
                        }, 2000);
                    } else {
                        // Tampilkan pesan error dari server
                        jQueryHelpers.showAlert(
                            response.message || 'Gagal menghapus data publikasi',
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
                    console.error('Delete publikasi error:', errorMessage);

                    // Re-enable tombol delete
                    deleteBtn.prop('disabled', false);
                }
            });
        }
    };

    // ============================================================
    // INISIALISASI
    // ============================================================

    /**
     * Jalankan modul saat document ready
     */
    $(document).ready(function() {
        // Expose confirmDelete ke global scope untuk dipanggil dari HTML
        window.confirmDeletePublikasi = function() {
            DeleteModule.confirmDelete();
        };
    });

})();
