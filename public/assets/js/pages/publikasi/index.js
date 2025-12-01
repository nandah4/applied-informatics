/**
 * File: pages/publikasi/index.js
 * Deskripsi: Script untuk halaman list data publikasi
 *
 * Fitur:
 * - Server-side search publikasi (dengan button trigger)
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
    // MODUL SEARCH (SERVER-SIDE)
    // ============================================================

    const SearchModule = {
        /**
         * Inisialisasi fungsi search (server-side)
         */
        init: function() {
            const searchInput = document.getElementById('searchInput');
            const btnSearch = document.getElementById('btnSearch');
            const btnClear = document.getElementById('btnClearSearch');

            // Handle search button click
            if (btnSearch) {
                btnSearch.addEventListener('click', this.handleSearch);
            }

            // Handle Enter key pada search input
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        SearchModule.handleSearch();
                    }
                });
            }

            // Handle clear button
            if (btnClear) {
                btnClear.addEventListener('click', this.handleClear);
            }
        },

        /**
         * Handle search - redirect ke URL dengan parameter search
         */
        handleSearch: function() {
            const searchInput = document.getElementById('searchInput');
            const searchValue = searchInput.value.trim();

            // Build URL dengan search parameter
            const currentUrl = new URL(window.location.href);

            if (searchValue) {
                currentUrl.searchParams.set('search', searchValue);
            } else {
                currentUrl.searchParams.delete('search');
            }

            // Reset ke page 1 saat search
            currentUrl.searchParams.set('page', '1');

            // Redirect
            window.location.href = currentUrl.toString();
        },

        /**
         * Handle clear search - hapus parameter search dan reload
         */
        handleClear: function() {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.delete('search');
            currentUrl.searchParams.set('page', '1');
            window.location.href = currentUrl.toString();
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
        // Inisialisasi modul search (server-side)
        SearchModule.init();

        // Inisialisasi pagination
        PaginationModule.init();

        // Expose confirmDelete ke global scope untuk dipanggil dari HTML
        window.confirmDelete = function(id) {
            DeleteModule.confirmDelete(id);
        };
    });

})();
