/**
 * File: pages/mitra/index.js
 * Deskripsi: Script untuk halaman index/list data mitra
 *
 * Fitur:
 * - Delete mitra dengan konfirmasi
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
  // MODUL DELETE MITRA
  // ============================================================

  const DeleteMitraModule = {
    init: function () {
      // Function sudah dipanggil dari HTML onclick
      // Tidak perlu event binding di sini
    },

    /**
     * Konfirmasi dan proses hapus mitra
     *
     * @param {number} id - ID mitra yang akan dihapus
     * @param {string} name - Nama mitra untuk konfirmasi
     */
    confirmDelete: function (id, name) {
      // Confirm dengan user
      if (
        !confirm(
          `Apakah Anda yakin ingin menghapus mitra ini?\n\nData yang dihapus tidak dapat dikembalikan.`
        )
      ) {
        return;
      }

      // Disable semua delete buttons sementara
      $(".btn-delete").prop("disabled", true);

      // Ambil CSRF token
      const csrfToken = $('input[name="csrf_token"]').val();

      // AJAX delete request
      $.ajax({
        url: `${BASE_URL}/admin/mitra/delete/${id}`,
        method: "POST",
        data: { csrf_token: csrfToken },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            jQueryHelpers.showAlert(
              response.message || "Data mitra berhasil dihapus",
              "success"
            );

            // Reload page setelah 1 detik
            setTimeout(function () {
              window.location.reload();
            }, 500);
          } else {
            jQueryHelpers.showAlert(
              response.message || "Gagal menghapus data mitra",
              "danger",
              5000
            );
            $(".btn-delete").prop("disabled", false);
          }
        },
        error: function (error) {
          jQueryHelpers.showAlert(
            "Terjadi kesalahan saat menghapus data",
            "danger",
            5000
          );
          $(".btn-delete").prop("disabled", false);
        },
      });
    },
  };

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

  // Agar bisa dipanggil dari HTML onclick
  window.confirmDelete = function (id, name) {
    DeleteMitraModule.confirmDelete(id, name);
  };

  // ============================================================
  // INISIALISASI
  // ============================================================

  $(document).ready(function () {
    SearchModule.init();
    DeleteMitraModule.init();
    PaginationModule.init();

    // Initialize feather icons
    if (typeof feather !== "undefined") {
      feather.replace();
    }
  });
})();
