(function () {
  "use strict";

  // ============================================================
  // MODUL DELETE AKTIVITAS
  // ============================================================

  const DeleteAktivitasModule = {
    init: function () {
      // Function sudah dipanggil dari HTML onclick
      // Tidak perlu event binding di sini
    },

    confirmDelete: function (id, judul) {
      // Confirm dengan user
      if (
        !confirm(
          `Apakah Anda yakin ingin menghapus aktivitas "${judul}"?\n\nData yang dihapus tidak dapat dikembalikan.`
        )
      ) {
        return;
      }

      // Disable semua delete buttons sementara
      $(".btn-delete").prop("disabled", true);

      // AJAX delete request
      $.ajax({
        url: "/applied-informatics/aktivitas-lab/delete/" + id,
        method: "POST",
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
  window.confirmDelete = function (id, judul) {
    DeleteAktivitasModule.confirmDelete(id, judul);
  };

  // ============================================================
  // INISIALISASI
  // ============================================================

  $(document).ready(function () {
    DeleteAktivitasModule.init();
    PaginationModule.init();

    // Initialize feather icons
    if (typeof feather !== "undefined") {
      feather.replace();
    }
  });
})();
