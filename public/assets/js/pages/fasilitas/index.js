/**
 * File: pages/fasilitas/index.js
 * Deskripsi: Script untuk halaman list data fasilitas
 *
 * Fitur:
 * - Search/Filter data fasilitas (client-side)
 * - Pagination controls (server-side)
 * - Delete fasilitas dengan konfirmasi (AJAX)
 * - Mobile menu toggle
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
                            'Data fasilitas berhasil dihapus!',
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