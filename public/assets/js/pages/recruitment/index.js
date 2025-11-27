/**
 * File: pages/recruitment/index.js
 * Deskripsi: Script untuk halaman index/list data recruitment
 *
 * Fitur:
 * - Delete recruitment dengan konfirmasi
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
  // MODUL DELETE RECRUITMENT
  // ============================================================

  const DeleteRecruitmentModule = {
    init: function () {
      // Function sudah dipanggil dari HTML onclick
      // Tidak perlu event binding di sini
    },

    /**
     * Konfirmasi dan proses hapus recruitment
     *
     * @param {number} id - ID recruitment yang akan dihapus
     */
    confirmDelete: function (id) {
      // Confirm dengan user
      if (
        !confirm(
          `Apakah Anda yakin ingin menghapus recruitment ini?\n\nData yang dihapus tidak dapat dikembalikan.`
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
        url: `${BASE_URL}/admin/recruitment/delete/${id}`,
        method: "POST",
        data: { csrf_token: csrfToken },
        dataType: "json",
        success: function (response) {
          if (response.success) {
            jQueryHelpers.showAlert(
              response.message || "Data recruitment berhasil dihapus",
              "success"
            );

            // Reload page setelah 1 detik
            setTimeout(function () {
              window.location.reload();
            }, 500);
          } else {
            jQueryHelpers.showAlert(
              response.message || "Gagal menghapus data recruitment",
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
  window.confirmDelete = function (id) {
    DeleteRecruitmentModule.confirmDelete(id);
  };

  // ============================================================
  // INISIALISASI
  // ============================================================

  $(document).ready(function () {
    DeleteRecruitmentModule.init();
    PaginationModule.init();

    // Initialize feather icons
    if (typeof feather !== "undefined") {
      feather.replace();
    }
  });
})();