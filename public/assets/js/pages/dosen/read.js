/**
 * Data Dosen - Detail/Read Page Scripts
 */

(function () {
  "use strict";

  const BASE_URL = $('meta[name="base-url"]').attr("content") || "/applied-informatics";

  /**
   * Proses delete dosen via AJAX
   *
   * @param {number} id - ID dosen yang akan dihapus
   */
  window.confirmDelete = function (id) {
    if (confirm("Apakah Anda yakin ingin menghapus data dosen ini?")) {
      const deleteBtn = $(`button[onclick="confirmDelete(${id})"]`);
      deleteBtn.prop("disabled", true);

      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/dosen/delete/${id}`,
        method: "POST",
        data: {
          csrf_token: $('meta[name="csrf-token"]').attr('content')
        },
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert("Data dosen berhasil dihapus!", "success", 2000);
            setTimeout(() => {
              window.location.href = `${BASE_URL}/admin/dosen`;
            }, 500);
          } else {
            jQueryHelpers.showAlert(response.message || "Gagal menghapus data dosen", "danger", 5000);
            deleteBtn.prop("disabled", false);
          }
        },
        onError: (errorMessage) => {
          jQueryHelpers.showAlert("Terjadi kesalahan sistem. Silakan coba lagi.", "danger", 5000);
          console.error("Delete dosen error:", errorMessage);
          deleteBtn.prop("disabled", false);
        },
      });
    }
  };

  // ========================================
  // PROFIL PUBLIKASI CRUD
  // ========================================

  /**
   * Handle form submit untuk tambah profil publikasi
   */
  $(document).ready(function() {
    // Add Profil Form Submit
    $('#addProfilForm').on('submit', function(e) {
      e.preventDefault();

      const $form = $(this);
      const $submitBtn = $form.find('button[type="submit"]');
      $submitBtn.prop('disabled', true);

      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/dosen/${DOSEN_ID}/profil-publikasi/create`,
        method: 'POST',
        data: {
          csrf_token: $('meta[name="csrf-token"]').attr('content'),
          tipe: $('#tipe').val(),
          url_profil: $('#url_profil').val()
        },
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert('Profil publikasi berhasil ditambahkan!', 'success', 2000);
            $('#addProfilModal').modal('hide');
            setTimeout(() => window.location.reload(), 500);
          } else {
            jQueryHelpers.showAlert(response.message || 'Gagal menambahkan profil', 'danger', 5000);
            $submitBtn.prop('disabled', false);
          }
        },
        onError: (errorMessage) => {
          jQueryHelpers.showAlert('Terjadi kesalahan sistem.', 'danger', 5000);
          console.error('Add profil error:', errorMessage);
          $submitBtn.prop('disabled', false);
        }
      });
    });

    // Edit Profil Form Submit
    $('#editProfilForm').on('submit', function(e) {
      e.preventDefault();

      const $form = $(this);
      const $submitBtn = $form.find('button[type="submit"]');
      $submitBtn.prop('disabled', true);

      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/dosen/profil-publikasi/update`,
        method: 'POST',
        data: {
          csrf_token: $('meta[name="csrf-token"]').attr('content'),
          id: $('#edit_profil_id').val(),
          url_profil: $('#edit_url_profil').val()
        },
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert('Profil publikasi berhasil diupdate!', 'success', 2000);
            $('#editProfilModal').modal('hide');
            setTimeout(() => window.location.reload(), 500);
          } else {
            jQueryHelpers.showAlert(response.message || 'Gagal mengupdate profil', 'danger', 5000);
            $submitBtn.prop('disabled', false);
          }
        },
        onError: (errorMessage) => {
          jQueryHelpers.showAlert('Terjadi kesalahan sistem.', 'danger', 5000);
          console.error('Update profil error:', errorMessage);
          $submitBtn.prop('disabled', false);
        }
      });
    });

    // Reset form when modal is closed
    $('#addProfilModal').on('hidden.bs.modal', function() {
      $('#addProfilForm')[0].reset();
    });
  });

  /**
   * Open edit modal dengan data profil
   *
   * @param {number} id - ID profil publikasi
   * @param {string} url - URL profil saat ini
   */
  window.editProfil = function(id, url) {
    $('#edit_profil_id').val(id);
    $('#edit_url_profil').val(url);
    $('#editProfilModal').modal('show');
  };

  /**
   * Delete profil publikasi
   *
   * @param {number} id - ID profil publikasi
   */
  window.deleteProfil = function(id) {
    if (confirm('Apakah Anda yakin ingin menghapus profil publikasi ini?')) {
      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/dosen/profil-publikasi/delete/${id}`,
        method: 'POST',
        data: {
          csrf_token: $('meta[name="csrf-token"]').attr('content')
        },
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert('Profil publikasi berhasil dihapus!', 'success', 2000);
            setTimeout(() => window.location.reload(), 500);
          } else {
            jQueryHelpers.showAlert(response.message || 'Gagal menghapus profil', 'danger', 5000);
          }
        },
        onError: (errorMessage) => {
          jQueryHelpers.showAlert('Terjadi kesalahan sistem.', 'danger', 5000);
          console.error('Delete profil error:', errorMessage);
        }
      });
    }
  };
})();
