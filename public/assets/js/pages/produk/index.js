/**
 * File: pages/produk/index.js
 * Deskripsi: Script untuk halaman list data produk
 *
 * Fitur:
 * - Search/Filter data produk (server-side: nama produk & author)
 * - Pagination controls (server-side)
 * - Delete produk dengan konfirmasi (AJAX)
 * - Mobile menu toggle
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 */

(function() {
    'use strict';

    // ============================================================
    // MODUL SEARCH (SERVER-SIDE)
    // ============================================================

    const SearchModule = {
        /**
         * Inisialisasi fungsi search (server-side)
         */
        init: function () {
            const searchInput = document.getElementById('searchInput');
            const btnSearch = document.getElementById('btnSearch');
            const btnClear = document.getElementById('btnClearSearch');

            // Handle search button click
            if (btnSearch) {
                btnSearch.addEventListener('click', this.handleSearch);
            }

            // Handle Enter key pada search input
            if (searchInput) {
                searchInput.addEventListener('keypress', function (e) {
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
        handleSearch: function () {
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
        handleClear: function () {
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
         *
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
            // ✅ Ambil CSRF token
            const csrfToken = $('input[name="csrf_token"]').val();

            // Request AJAX menggunakan jQueryHelpers
            jQueryHelpers.makeAjaxRequest({
                url: deleteUrl,
                method: 'POST',
                data: { csrf_token: csrfToken },
                onSuccess: (response) => {
                    if (response.success) {
                        // Tampilkan notifikasi success
                        jQueryHelpers.showAlert(
                            'Data produk berhasil dihapus!',
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
    // MODUL MOBILE MENU
    // ============================================================
    const MobileMenuModule = {
        /**
         * Inisialisasi mobile menu toggle
         */
        init: function() {
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', this.toggleMenu);
            }
        },

        /**
         * Toggle mobile menu visibility
         */
        toggleMenu: function() {
            console.log('Mobile menu toggled');
            // TODO: Implementasi toggle menu untuk mobile
            // Contoh: document.querySelector('.sidebar').classList.toggle('active');
        }
    };

    // ============================================================
    // INISIALISASI
    // ============================================================
    $(document).ready(function() {
        // Inisialisasi modul search
        SearchModule.init();

        // Inisialisasi modul pagination
        PaginationModule.init();

        // Inisialisasi mobile menu
        MobileMenuModule.init();

        // Expose confirmDelete ke global scope untuk dipanggil dari HTML
        window.confirmDelete = function(id, deleteUrl) {
            DeleteModule.confirmDelete(id, deleteUrl);
        };
    });

})();