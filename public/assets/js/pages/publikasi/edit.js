/**
 * File: pages/publikasi/edit.js
 * Deskripsi: Logic khusus untuk edit mode publikasi
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 * - validationHelpers.js
 * - form.js (shared logic)
 *
 * Note: File ini di-load setelah form.js di halaman edit
 */

(function () {
  "use strict";

  const BASE_URL =
    $('meta[name="base-url"]').attr("content") || "/applied-informatics";

  // ============================================================
  // EDIT MODE INITIALIZATION
  // ============================================================

  /**
   * Initialize edit mode
   * - Override form submission to use update endpoint
   */
  const EditModeModule = {
    init: function () {

      // Override submit button handler for update
      this.setupUpdateHandler();
    },

    /**
     * Setup update handler (override create handler)
     */
    setupUpdateHandler: function () {
      // Remove create handler jika sudah di-attach oleh form.js
      $("#btn-submit-create-publikasi").off("click");

      // Ganti dengan ID yang sesuai untuk tombol update
      $("#btn-submit-update-publikasi").on("click", (e) => {
        e.preventDefault();
        this.handleUpdate();
      });
    },

    /**
     * Handle update submission
     */
    handleUpdate: function () {
      const formData = this.getFormData();
      const validationErrors = this.validateFormData(formData);

      jQueryHelpers.clearAllErrors("formPublikasi");

      if (validationErrors.length > 0) {
        validationErrors.forEach((error) => {
          jQueryHelpers.showError(error.fieldId, error.errorId, error.message);
        });
        return;
      }

      const submitData = this.prepareFormData(formData);
      const buttonState = jQueryHelpers.disableButton(
        "btn-submit-update-publikasi",
        "Menyimpan..."
      );

      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/publikasi-akademik/update`,
        method: "POST",
        processData: false,
        contentType: false,
        data: submitData,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert(
              "Data publikasi berhasil diperbarui!",
              "success"
            );
            setTimeout(() => {
              window.location.href = `${BASE_URL}/admin/publikasi-akademik`;
            }, 500);
          } else {
            jQueryHelpers.showAlert("Gagal: " + response.message, "danger");
            buttonState.enable();
          }
        },
        onError: (errorMessage) => {
          console.log(errorMessage);
          jQueryHelpers.showAlert("Error: " + errorMessage, "danger");
          buttonState.enable();
        },
      });
    },

    /**
     * Get form data (same as form.js but includes ID)
     */
    getFormData: function () {
      return {
        id: $("#publikasi_id").val(),
        dosen_id: $("#dosen_id").val(),
        judul: $("#judul").val().trim(),
        tipe_publikasi: $("#tipe_publikasi").val(),
        tahun_publikasi: $("#tahun_publikasi").val().trim(),
        url_publikasi: $("#url_publikasi").val().trim(),
        csrf_token: $("input[name='csrf_token']").val()
      };
    },

    /**
     * Validate form data (same validation as form.js)
     */
    validateFormData: function (data) {
      const errors = [];

      // Validate dosen_id
      const dosenValidation = validationHelpers.validateRequired(
        data.dosen_id,
        "Dosen"
      );
      if (!dosenValidation.valid) {
        errors.push({
          fieldId: "dosen_id",
          errorId: "dosenIdError",
          message: dosenValidation.message,
        });
      }

      // Validate judul
      const judulValidation = validationHelpers.validateRequired(
        data.judul,
        "Judul Publikasi"
      );
      if (!judulValidation.valid) {
        errors.push({
          fieldId: "judul",
          errorId: "judulError",
          message: judulValidation.message,
        });
      }

      // Validate tipe_publikasi
      const tipeValidation = validationHelpers.validateRequired(
        data.tipe_publikasi,
        "Tipe Publikasi"
      );
      if (!tipeValidation.valid) {
        errors.push({
          fieldId: "tipe_publikasi",
          errorId: "tipePublikasiError",
          message: tipeValidation.message,
        });
      }

      // Validate tahun_publikasi (optional, but if provided must be valid)
      if (data.tahun_publikasi && data.tahun_publikasi.length > 0) {
        const tahun = parseInt(data.tahun_publikasi);
        const currentYear = new Date().getFullYear();
        if (isNaN(tahun) || tahun < 1900 || tahun > (currentYear + 1)) {
          errors.push({
            fieldId: "tahun_publikasi",
            errorId: "tahunPublikasiError",
            message: `Tahun publikasi harus antara 1900 dan ${currentYear + 1}`,
          });
        }
      }

      // Validate url_publikasi
      if (data.url_publikasi && data.url_publikasi.length > 0) {
        const result = validationHelpers.validateUrl(data.url_publikasi, false);
        if (!result.valid) {
          errors.push({
            fieldId: "url_publikasi",
            errorId: "urlPublikasiError",
            message: result.message,
          });
        }
      }

      return errors;
    },

    /**
     * Prepare form data for submission (includes ID)
     */
    prepareFormData: function (data) {
      const formData = new FormData();
      formData.append("id", data.id);
      formData.append("dosen_id", data.dosen_id);
      formData.append("judul", data.judul);
      formData.append("tahun_publikasi", data.tahun_publikasi);
      formData.append("tipe_publikasi", data.tipe_publikasi);
      formData.append("url_publikasi", data.url_publikasi);
      formData.append("csrf_token", data.csrf_token);

      return formData;
    },
  };

  // ============================================================
  // INISIALISASI
  // ============================================================

  $(document).ready(function () {
    EditModeModule.init();
  });
})();
