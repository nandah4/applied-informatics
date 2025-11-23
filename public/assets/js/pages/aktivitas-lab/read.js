/**
 * File: pages/aktivitas-lab/read.js
 * Deskripsi: Script untuk halaman detail aktivitas laboratorium
 *
 * Fitur:
 * - Delete aktivitas dengan konfirmasi (AJAX)
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 */

(function () {
  "use strict";

  const BASE_URL = $('meta[name="base-url"]').attr("content") || "/applied-informatics";

  // ============================================================
  // MODUL DELETE AKTIVITAS
  // ============================================================

  const DeleteModule = {
    /**
     * Tampilkan konfirmasi dan hapus aktivitas
     * Function ini dipanggil dari button delete di view
     *
     * @param {number} id - ID aktivitas yang akan dihapus
     * @param {string} judul - Judul aktivitas untuk konfirmasi
     */
    confirmDelete: function (id, judul) {
      // Tampilkan konfirmasi native browser
      if (
        !confirm(
          `Apakah Anda yakin ingin menghapus aktivitas "${judul}"?\n\nData yang dihapus tidak dapat dikembalikan.`
        )
      ) {
        return; // User cancel, hentikan proses
      }

      // Proses delete
      this.deleteAktivitas(id);
    },

    /**
     * Proses delete aktivitas via AJAX
     * Menggunakan jQueryHelpers untuk standardisasi AJAX call
     *
     * @param {number} id - ID aktivitas yang akan dihapus
     */
    deleteAktivitas: function (id) {
      // Disable tombol delete untuk mencegah multiple clicks
      const deleteBtn = $(".btn-danger-custom");
      deleteBtn.prop("disabled", true);
      deleteBtn.html(
        '<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...'
      );

      // Ambil CSRF token
      const csrfToken = $('input[name="csrf_token"]').val();

      // Request AJAX menggunakan jQueryHelpers
      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/aktivitas-lab/delete/${id}`,
        method: "POST",
        data: { csrf_token: csrfToken },
        onSuccess: (response) => {
          if (response.success) {
            // Tampilkan notifikasi success
            jQueryHelpers.showAlert(
              "Data aktivitas berhasil dihapus!",
              "success",
              2000
            );

            // Redirect ke halaman index setelah delay
            setTimeout(() => {
              window.location.href = `${BASE_URL}/admin/aktivitas-lab`;
            }, 500);
          } else {
            // Tampilkan pesan error dari server
            jQueryHelpers.showAlert(
              response.message || "Gagal menghapus data aktivitas",
              "danger",
              5000
            );

            // Re-enable tombol delete
            deleteBtn.prop("disabled", false);
            deleteBtn.html(
              '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Hapus Data'
            );
          }
        },
        onError: (errorMessage) => {
          // Tampilkan error notifikasi
          jQueryHelpers.showAlert(
            "Terjadi kesalahan sistem. Silakan coba lagi.",
            "danger",
            5000
          );

          // Log error untuk debugging
          console.error("Delete aktivitas error:", errorMessage);

          // Re-enable tombol delete
          deleteBtn.prop("disabled", false);
          deleteBtn.html(
            '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg> Hapus Data'
          );
        },
      });
    },
  };

  // ============================================================
  // INISIALISASI
  // ============================================================

  $(document).ready(function () {
    // Expose confirmDelete ke global scope
    window.confirmDelete = function (id, judul) {
      DeleteModule.confirmDelete(id, judul);
    };

    // Initialize feather icons
    if (typeof feather !== "undefined") {
      feather.replace();
    }
  });
})();
