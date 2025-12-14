/**
 * File: pages/aktivitas-lab/index.js
 * Deskripsi: Script untuk halaman index/list data aktivitas laboratorium
 *
 * Fitur:
 * - Delete aktivitas dengan konfirmasi
 * - Pagination controls
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 */

(function () {
  "use strict";

  const BASE_URL = $('meta[name="base-url"]').attr("content") || "/applied-informatics";

  // ============================================================
  // MODUL PAGINATION
  // ============================================================

  const PaginationModule = {
    /**
     * Inisialisasi pagination controls
     */
    init: function () {
      const perPageSelect = document.getElementById("perPageSelect");

      if (perPageSelect) {
        perPageSelect.addEventListener("change", this.handlePerPageChange);
      }
    },

    /**
     * Handle perubahan jumlah data per halaman
     * @param {Event} e - Event object
     */
    handlePerPageChange: function (e) {
      const perPage = e.target.value;
      const currentUrl = new URL(window.location.href);

      // Update parameter per_page dan reset ke page 1
      currentUrl.searchParams.set("per_page", perPage);
      currentUrl.searchParams.set("page", "1");

      // Redirect ke URL baru
      window.location.href = currentUrl.toString();
    },
  };

  // ============================================================
  // EXPOSE FUNCTION KE GLOBAL SCOPE
  // ============================================================

     /**
     * Konfirmasi dan proses hapus aktivitas
     *
     * @param {number} id - ID aktivitas yang akan dihapus
     * @param {string} judul - Judul aktivitas untuk konfirmasi
     */

  window.confirmDelete = function (id) {
    // Confirm dengan user
      if (!confirm(`Apakah Anda yakin ingin menghapus aktivitas ini?\n\nData yang dihapus tidak dapat dikembalikan.`)) {
        return;
      }

      // Disable semua delete buttons sementara
      $(".btn-delete").prop("disabled", true);


      // Ambil CSRF token
      const csrfToken = $('input[name="csrf_token"]').val();

      // AJAX delete request
      $.ajax({
        url: `${BASE_URL}/admin/aktivitas-lab/delete/${id}`,
        method: "POST",
        data: { csrf_token: csrfToken },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            jQueryHelpers.showAlert(
              response.message || "Data aktivitas berhasil dihapus",
              "success"
            );

            // Reload page setelah 1 detik
            setTimeout(function () {
              window.location.reload();
            }, 500);
          } else {
            jQueryHelpers.showAlert(
              response.message || "Gagal menghapus data aktivitas",
              "danger",
              5000
            );
            $(".btn-delete").prop("disabled", false);
          }
        },
        error: function (xhr) {
          const response = xhr.responseJSON;
          jQueryHelpers.showAlert(
            response?.message || "Terjadi kesalahan saat menghapus data",
            "danger",
            5000
          );
          $(".btn-delete").prop("disabled", false);
        },
      });
  };

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
            window.location.href = `${BASE_URL}/admin/aktivitas-lab`;
        });

        // Select per page
        $('#perPageSelect').on('change', function() {
            const perPage = $(this).val();
            const currentUrl = new URL(window.location.href);
            const search = currentUrl.searchParams.get('search') || '';

            let url = `${BASE_URL}/admin/aktivitas-lab?per_page=${perPage}`;
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
                `${BASE_URL}/admin/aktivitas-lab?search=${encodeURIComponent(searchValue)}&per_page=${perPage}`;
        } else {
            window.location.href =
                `${BASE_URL}/admin/aktivitas-lab?per_page=${perPage}`;
        }
    }
};

  // ============================================================
  // INISIALISASI
  // ============================================================

  $(document).ready(function () {
    SearchModule.init();
    PaginationModule.init();

    // Initialize feather icons
    if (typeof feather !== "undefined") {
      feather.replace();
    }
  });
})();
