/**
 * File: pages/dosen/read.js
 * Deskripsi: Script untuk halaman detail/read data dosen
 *
 * Fitur:
 * - Delete dosen dengan konfirmasi
 * - CRUD profil publikasi dosen
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 * - validationHelpers.js
 * - Bootstrap Modal
 */

(function () {
  "use strict";

  const BASE_URL = $('meta[name="base-url"]').attr("content") || "/applied-informatics";

  // ========================================
  // DELETE DOSEN
  // ========================================

  /**
   * Proses delete dosen via AJAX
   *
   * @param {number} id - ID dosen yang akan dihapus
   */
  window.confirmDelete = function (id) {
    if (!confirm("Apakah Anda yakin ingin menghapus data dosen ini?")) {
      return;
    }

    const csrfToken = $('input[name="csrf_token"]').val();
    const deleteBtn = $(`button[onclick="confirmDelete(${id})"]`);
    deleteBtn.prop("disabled", true);

    jQueryHelpers.makeAjaxRequest({
      url: `${BASE_URL}/admin/dosen/delete/${id}`,
      method: "POST",
      data: { csrf_token: csrfToken },
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
        jQueryHelpers.showAlert("Terjadi kesalahan sistem.", "danger", 5000);
        console.error("Delete dosen error:", errorMessage);
        deleteBtn.prop("disabled", false);
      },
    });
  };

  // ========================================
  // PROFIL PUBLIKASI CRUD
  // ========================================

  /**
   * Setup handlers untuk modal tambah dan edit profil publikasi
   */
  $(document).ready(function () {
    const csrfToken = $('input[name="csrf_token"]').val();

    // ----------------------------------------
    // TAMBAH PROFIL PUBLIKASI
    // ----------------------------------------
    const btnAddProfil = $("#btn-add-profil-publikasi");
    const modalAdd = $("#addProfilModal");
    const errorTipe = $("#tipeError");
    const errorUrl = $("#urlProfilError");

    // Reset saat modal ditutup
    modalAdd.on("hidden.bs.modal", function () {
      btnAddProfil.prop("disabled", false).text("Simpan");
      $("#tipe").val("");
      $("#url_profil").val("");
      errorTipe.hide();
      errorUrl.hide();
    });

    // Handle tambah
    btnAddProfil.on("click", function () {
      const tipe = $("#tipe").val();
      const urlProfil = $("#url_profil").val().trim();

      // Validasi tipe
      if (!tipe || tipe === "") {
        errorTipe.text("Pilih tipe profil publikasi").show();
        return;
      }
      errorTipe.hide();

      // Validasi URL
      const validateUrl = validationHelpers.validateUrl(urlProfil, true);
      if (!validateUrl.valid) {
        errorUrl.text(validateUrl.message).show();
        return;
      }
      errorUrl.hide();

      // Disable button
      btnAddProfil.prop("disabled", true).text("Menyimpan...");

      // Submit ke server
      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/dosen/${DOSEN_ID}/profil-publikasi/create`,
        method: "POST",
        data: {
          csrf_token: csrfToken,
          tipe: tipe,
          url_profil: urlProfil,
        },
        onSuccess: (response) => {
          btnAddProfil.prop("disabled", false).text("Simpan");

          if (response.success) {
            bootstrap.Modal.getInstance(modalAdd[0]).hide();
            jQueryHelpers.showAlert("Profil publikasi berhasil ditambahkan", "success", 2000);
            setTimeout(() => window.location.reload(), 500);
          } else {
            jQueryHelpers.showAlert(response.message || "Gagal menambahkan profil publikasi", "danger", 3000);
          }
        },
        onError: (errorMessage) => {
          btnAddProfil.prop("disabled", false).text("Simpan");
          jQueryHelpers.showAlert("Terjadi kesalahan: " + errorMessage, "danger", 3000);
        },
      });
    });

    // ----------------------------------------
    // EDIT PROFIL PUBLIKASI
    // ----------------------------------------
    const btnUpdateProfil = $("#btn-update-profil-publikasi");
    const modalEdit = $("#editProfilModal");
    const errorEditUrl = $("#editUrlProfilError");

    // Reset saat modal ditutup
    modalEdit.on("hidden.bs.modal", function () {
      btnUpdateProfil.prop("disabled", false).text("Update");
      $("#edit_profil_id").val("");
      $("#edit_url_profil").val("");
      $("#edit_profil_tipe").val("");
      errorEditUrl.hide();
    });

    // Handle update
    btnUpdateProfil.on("click", function () {
      const id = $("#edit_profil_id").val();
      const urlProfil = $("#edit_url_profil").val().trim();

      // Validasi URL
      const validateUrl = validationHelpers.validateUrl(urlProfil, true);
      if (!validateUrl.valid) {
        errorEditUrl.text(validateUrl.message).show();
        return;
      }
      errorEditUrl.hide();

      // Disable button
      btnUpdateProfil.prop("disabled", true).text("Menyimpan...");

      // Submit ke server
      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/dosen/profil-publikasi/update`,
        method: "POST",
        data: {
          csrf_token: csrfToken,
          id: id,
          url_profil: urlProfil,
        },
        onSuccess: (response) => {
          btnUpdateProfil.prop("disabled", false).text("Update");

          if (response.success) {
            bootstrap.Modal.getInstance(modalEdit[0]).hide();
            jQueryHelpers.showAlert("Profil publikasi berhasil diupdate", "success", 2000);
            setTimeout(() => window.location.reload(), 500);
          } else {
            jQueryHelpers.showAlert(response.message || "Gagal mengupdate profil publikasi", "danger", 3000);
          }
        },
        onError: (errorMessage) => {
          btnUpdateProfil.prop("disabled", false).text("Update");
          jQueryHelpers.showAlert("Terjadi kesalahan: " + errorMessage, "danger", 3000);
        },
      });
    });
  });

  // ========================================
  // GLOBAL FUNCTIONS - Edit & Delete Profil Publikasi
  // ========================================

  /**
   * Buka modal edit profil publikasi
   *
   * @param {number} id - ID profil publikasi
   * @param {string} url - URL profil saat ini
   * @param {string} tipe - Tipe profil (untuk display)
   */
  window.editProfilPublikasi = function (id, url, tipe) {
    $("#edit_profil_id").val(id);
    $("#edit_url_profil").val(url);
    $("#edit_profil_tipe").val(tipe);
    $("#editProfilModal").modal("show");
  };

  /**
   * Hapus profil publikasi
   *
   * @param {number} id - ID profil publikasi
   */
  window.deleteProfilPublikasi = function (id) {
    if (!confirm("Apakah Anda yakin ingin menghapus profil publikasi ini?")) {
      return;
    }

    const csrfToken = $('input[name="csrf_token"]').val();

    jQueryHelpers.makeAjaxRequest({
      url: `${BASE_URL}/admin/dosen/profil-publikasi/delete/${id}`,
      method: "POST",
      data: { csrf_token: csrfToken },
      onSuccess: (response) => {
        if (response.success) {
          jQueryHelpers.showAlert("Profil publikasi berhasil dihapus", "success", 2000);
          setTimeout(() => window.location.reload(), 500);
        } else {
          jQueryHelpers.showAlert(response.message || "Gagal menghapus profil publikasi", "danger", 3000);
        }
      },
      onError: (errorMessage) => {
        jQueryHelpers.showAlert("Terjadi kesalahan: " + errorMessage, "danger", 3000);
      },
    });
  };
})();
