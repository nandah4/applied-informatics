/**
 * File: pages/dosen/index.js
 * Deskripsi: Script untuk halaman list data dosen
 *
 * Fitur:
 * - Search/Filter data dosen
 * - Delete dosen dengan konfirmasi
 * - Notifikasi menggunakan Bootstrap Alert
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 */

(function() {
    'use strict';


    const BASE_URL = $('meta[name="base-url"]').attr("content");

    // ============================================================
    // MODUL SEARCH/FILTER
    // ============================================================

   const SearchModule = {

    init: function() {
        this.handleSearch();
    },

    handleSearch: function() {
        const self = this;

        // Enter / keypress
        $('#searchInput').on('keypress', function(e) {
            if (e.which === 13) {
                self.performSearch();
            }
        });

        // Tombol cari
        $('#btnSearch').on('click', function() {
            self.performSearch();
        });

        // Tombol hapus search
        $('#btnClearSearch').on('click', function() {
            window.location.href = `${BASE_URL}/admin/dosen`;
        });

        // Select per page
        $('#perPageSelect').on('change', function() {
            const perPage = $(this).val();
            const currentUrl = new URL(window.location.href);
            const search = currentUrl.searchParams.get('search') || '';

            let url = `${BASE_URL}/admin/dosen?per_page=${perPage}`;
            if (search) {
                url += `&search=${encodeURIComponent(search)}`;
            }

            window.location.href = url;
        });
    },

    performSearch: function() {
        const searchValue = $('#searchInput').val().trim();
        const currentUrl = new URL(window.location.href);
        const perPage = currentUrl.searchParams.get('per_page') || '10';

        if (searchValue) {
            window.location.href =
                `${BASE_URL}/admin/dosen?search=${encodeURIComponent(searchValue)}&per_page=${perPage}`;
        } else {
            window.location.href =
                `${BASE_URL}/admin/dosen?per_page=${perPage}`;
        }
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
    // MODUL DELETE DOSEN
    // ============================================================

    const DeleteModule = {
        /**
         * Tampilkan konfirmasi dan hapus dosen
         * Function ini dipanggil dari button delete di view
         *
         * @param {number} id - ID dosen yang akan dihapus
         */
        confirmDelete: function(id) {
            // Tampilkan konfirmasi native browser
            if (!confirm('Apakah Anda yakin ingin menghapus data dosen ini?\n\nData yang dihapus tidak dapat dikembalikan.')) {
                return; // User cancel, hentikan proses
            }

            // Proses delete
            this.deleteDosen(id);
        },

        /**
         * Proses delete dosen via AJAX
         * Menggunakan jQueryHelpers untuk standardisasi AJAX call
         *
         * @param {number} id - ID dosen yang akan dihapus
         */
        deleteDosen: function(id) {
            // Disable tombol delete untuk mencegah multiple clicks
            const deleteBtn = $(`button[onclick="confirmDelete(${id})"]`);
            deleteBtn.prop('disabled', true);

            // Request AJAX menggunakan jQueryHelpers
            jQueryHelpers.makeAjaxRequest({
                url: `${BASE_URL}/admin/dosen/delete/${id}`,
                method: 'POST',
                data: {
                    csrf_token: $('input[name="csrf_token"]').val() || $('meta[name="csrf-token"]').attr('content')
                },
                onSuccess: (response) => {
                    if (response.success) {
                        // Tampilkan notifikasi success
                        jQueryHelpers.showAlert(
                            'Data dosen berhasil dihapus!',
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
                            response.message || 'Gagal menghapus data dosen',
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
                    console.error('Delete dosen error:', errorMessage);

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
