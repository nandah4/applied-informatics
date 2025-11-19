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

  // ============================================================
  // EDIT MODE INITIALIZATION
  // ============================================================

  /**
   * Initialize edit mode
   * - Override form submission to use update endpoint
   */
  const EditModeModule = {
    init: function () {
      // Check if we're in edit mode
      if (!window.EDIT_MODE || !window.PUBLIKASI_DATA) {
        console.error("Edit mode data not found");
        return;
      }

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
        url: "/applied-informatics/publikasi/update",
        method: "POST",
        data: submitData,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert(
              "Data publikasi berhasil diperbarui!",
              "success"
            );
            setTimeout(() => {
              window.location.href = "/applied-informatics/publikasi";
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
        if (isNaN(tahun) || tahun < 1900 || tahun > currentYear + 10) {
          errors.push({
            fieldId: "tahun_publikasi",
            errorId: "tahunPublikasiError",
            message: `Tahun publikasi harus antara 1900 dan ${currentYear + 10}`,
          });
        }
      }

      // Validate url_publikasi (optional, but if provided must be URL)
      if (data.url_publikasi && data.url_publikasi.length > 0) {
        const urlPattern = /^https?:\/\/.+/i;
        if (!urlPattern.test(data.url_publikasi)) {
          errors.push({
            fieldId: "url_publikasi",
            errorId: "urlPublikasiError",
            message:
              "URL publikasi harus berupa URL yang valid (dimulai dengan http:// atau https://)",
          });
        }
      }

      return errors;
    },

    /**
     * Prepare form data for submission (includes ID)
     */
    prepareFormData: function (data) {
      return {
        id: data.id,
        dosen_id: data.dosen_id,
        judul: data.judul,
        tipe_publikasi: data.tipe_publikasi,
        tahun_publikasi: data.tahun_publikasi || null,
        url_publikasi: data.url_publikasi || null,
      };
    },
  };

  // ============================================================
  // INISIALISASI
  // ============================================================

  $(document).ready(function () {
    EditModeModule.init();
  });
})();
