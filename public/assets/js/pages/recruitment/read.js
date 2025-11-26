/**
 * File: pages/recruitment/read.js
 * Deskripsi: Script untuk halaman detail/read data recruitment
 *
 * Fitur:
 * - Delete recruitment dengan konfirmasi
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 */

(function () {
  "use strict";

  const BASE_URL = $('meta[name="base-url"]').attr("content") || "/applied-informatics";

  // ============================================================
  // MODUL DELETE RECRUITMENT (Detail Page)
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

      // Disable delete button dengan loading spinner
      $(".btn-danger-custom")
        .prop("disabled", true)
        .html(
          '<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...'
        );

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
              "Data recruitment berhasil dihapus",
              "success",
              1500
            );

            // Redirect ke list page setelah 0.5 detik
            setTimeout(function () {
              window.location.href = `${BASE_URL}/admin/recruitment`;
            }, 500);
          } else {
            jQueryHelpers.showAlert(
              response.message || "Gagal menghapus data recruitment",
              "danger",
              5000
            );

            // Re-enable button dengan icon delete
            $(".btn-danger-custom")
              .prop("disabled", false)
              .html(
                '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Hapus Data'
              );
          }
        },
        error: function (error) {
          jQueryHelpers.showAlert(
            "Terjadi kesalahan saat menghapus data",
            "danger",
            5000
          );

          // Re-enable button dengan icon delete
          $(".btn-danger-custom")
            .prop("disabled", false)
            .html(
              '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Hapus Data'
            );
        },
      });
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

    // Initialize feather icons
    if (typeof feather !== "undefined") {
      feather.replace();
    }
  });
})();