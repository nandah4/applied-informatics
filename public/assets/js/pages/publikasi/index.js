/**
 * File: pages/publikasi/index.js
 * Deskripsi: Script untuk halaman list data publikasi
 *
 * Fitur:
 * - Search/Filter data publikasi
 * - Delete publikasi dengan konfirmasi
 * - Notifikasi menggunakan Bootstrap Alert
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 */

(function() {
    'use strict';

    const BASE_URL = $('meta[name="base-url"]').attr('content') || '/applied-informatics';
    // ============================================================
    // MODUL SEARCH/FILTER
    // ============================================================

    const SearchModule = {
        /**
         * Inisialisasi fungsi search/filter
         */
        init: function() {
            const searchInput = document.querySelector('.search-input');

            if (searchInput) {
                searchInput.addEventListener('input', this.handleSearch);
            }
        },

        /**
         * Handle event input pada search box
         * Filter baris tabel berdasarkan keyword
         *
         * @param {Event} e - Event object
         */
        handleSearch: function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.table-custom tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();

                // Tampilkan baris jika mengandung keyword, sembunyikan jika tidak
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        }
    };

    // ============================================================
    // MODUL PAGINATION
    // ============================================================

    const PaginationModule = {
        /**
         * Inisialisasi pagination controls
         */
        init: function() {
            const perPageSelect = document.getElementById('perPageSelect');

            if (perPageSelect) {
                perPageSelect.addEventListener('change', this.handlePerPageChange);
            }
        },

        /**
         * Handle perubahan jumlah data per halaman
         * @param {Event} e - Event object
         */
        handlePerPageChange: function(e) {
            const perPage = e.target.value;
            const currentUrl = new URL(window.location.href);

            // Update parameter per_page dan reset ke page 1
            currentUrl.searchParams.set('per_page', perPage);
            currentUrl.searchParams.set('page', '1');

            // Redirect ke URL baru
            window.location.href = currentUrl.toString();
        }
    };

    // ============================================================
    // MODUL DELETE PUBLIKASI
    // ============================================================

    const DeleteModule = {
        /**
         * Tampilkan konfirmasi dan hapus publikasi
         * Function ini dipanggil dari button delete di view
         *
         * @param {number} id - ID publikasi yang akan dihapus
         */
        confirmDelete: function(id) {
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
            const deleteBtn = $(`button[onclick="confirmDelete(${id})"]`);
            deleteBtn.prop('disabled', true);

            // Ambil CSRF token
            const csrfToken = $('input[name="csrf_token"]').val();

            // Request AJAX menggunakan jQueryHelpers
            jQueryHelpers.makeAjaxRequest({
                url: `${BASE_URL}/admin/publikasi-akademik/delete/${id}`,
                method: 'POST',
                data: { csrf_token: csrfToken },
                onSuccess: (response) => {
                    if (response.success) {
                        // Tampilkan notifikasi success
                        jQueryHelpers.showAlert(
                            'Data publikasi berhasil dihapus!',
                            'success',
                            2000
                        );

                        // Reload page setelah 2 detik untuk refresh data
                        setTimeout(() => {
                            window.location.reload();
                        }, 500);
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
                        errorMessage || 'Terjadi kesalahan sistem. Silakan coba lagi.',
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
     * Jalankan semua modul saat document ready
     */
    $(document).ready(function() {
        // Inisialisasi modul search
        SearchModule.init();

        // Inisialisasi pagination
        PaginationModule.init();

        // Expose confirmDelete ke global scope untuk dipanggil dari HTML
        window.confirmDelete = function(id) {
            DeleteModule.confirmDelete(id);
        };
    });

})();
