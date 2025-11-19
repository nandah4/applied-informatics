/**
 * File: pages/recruitment/index.js
 * Deskripsi: Script untuk halaman list data recruitment
 *
 * Fitur:
 * - Search/Filter data recruitment
 * - Delete recruitment dengan konfirmasi
 * - Notifikasi menggunakan Bootstrap Alert
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 */

(function() {
    'use strict';

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
    // MODUL DELETE RECRUITMENT
    // ============================================================

    const DeleteModule = {
        /**
         * Tampilkan konfirmasi dan hapus recruitment
         * Function ini dipanggil dari button delete di view
         *
         * @param {number} id - ID recruitment yang akan dihapus
         */
        confirmDelete: function(id) {
            // Tampilkan konfirmasi native browser
            if (!confirm('Apakah Anda yakin ingin menghapus data recruitment ini?\n\nData yang dihapus tidak dapat dikembalikan.')) {
                return; // User cancel, hentikan proses
            }

            // Proses delete
            this.deleteRecruitment(id);
        },

        /**
         * Proses delete recruitment via AJAX
         * Menggunakan jQueryHelpers untuk standardisasi AJAX call
         *
         * @param {number} id - ID recruitment yang akan dihapus
         */
        deleteRecruitment: function(id) {
            // Disable tombol delete untuk mencegah multiple clicks
            const deleteBtn = $(`button[onclick="confirmDelete(${id})"]`);
            deleteBtn.prop('disabled', true);

            // Request AJAX menggunakan jQueryHelpers
            jQueryHelpers.makeAjaxRequest({
                url: `/applied-informatics/recruitment/delete/${id}`,
                method: 'POST',
                data: {},
                onSuccess: (response) => {
                    if (response.success) {
                        // Tampilkan notifikasi success
                        jQueryHelpers.showAlert(
                            'Data recruitment berhasil dihapus!',
                            'success',
                            2000
                        );

                        // Reload page setelah 2 detik untuk refresh data
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        // Tampilkan pesan error dari server
                        jQueryHelpers.showAlert(
                            response.message || 'Gagal menghapus data recruitment',
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
                    console.error('Delete recruitment error:', errorMessage);

                    // Re-enable tombol delete
                    deleteBtn.prop('disabled', false);
                }
            });
        }
    };

    // ============================================================
    // MODUL MOBILE MENU (Optional - untuk future development)
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

        // Inisialisasi mobile menu
        MobileMenuModule.init();

        // Expose confirmDelete ke global scope untuk dipanggil dari HTML
        window.confirmDelete = function(id) {
            DeleteModule.confirmDelete(id);
        };
    });

})();
