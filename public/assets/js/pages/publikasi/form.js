/**
 * File: pages/publikasi/form.js
 * Deskripsi: Menangani interaksi form publikasi (create & edit)
 *
 * Dependencies:
 * - jQuery
 * - Bootstrap 5
 * - Select2
 * - jQueryHelpers.js
 * - validationHelpers.js
 *
 * Fitur:
 * - Dropdown dosen dengan Select2
 * - Validasi dan submit form
 */

(function () {
  "use strict";

  // ============================================================
  // MODUL SELECT2 INITIALIZATION
  // ============================================================

  const Select2Module = {
    /**
     * Inisialisasi Select2 untuk dropdown dosen
     */
    init: function () {
      // Initialize Select2 untuk dropdown dosen
      $("#dosen_id").select2({
        theme: "bootstrap-5",
        placeholder: "Pilih Dosen",
        allowClear: true,
        width: "100%",
      });

      // Initialize Select2 untuk dropdown tipe publikasi (optional, tapi lebih baik UX)
      $("#tipe_publikasi").select2({
        theme: "bootstrap-5",
        placeholder: "Pilih Tipe Publikasi",
        allowClear: true,
        width: "100%",
        minimumResultsForSearch: -1, // Disable search karena opsi sedikit
      });
    },
  };

  // ============================================================
  // MODUL SUBMIT FORM
  // ============================================================

  const FormSubmissionModule = {
    init: function () {
      $("#btn-submit-create-publikasi").on("click", (e) => {
        e.preventDefault();
        this.handleSubmit();
      });
    },

    handleSubmit: function () {
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
        "btn-submit-create-publikasi",
        "Menyimpan..."
      );

      jQueryHelpers.makeAjaxRequest({
        url: "/applied-informatics/publikasi/create",
        method: "POST",
        data: submitData,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert(
              "Data publikasi berhasil ditambahkan!",
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

    getFormData: function () {
      return {
        dosen_id: $("#dosen_id").val(),
        judul: $("#judul").val().trim(),
        tipe_publikasi: $("#tipe_publikasi").val(),
        tahun_publikasi: $("#tahun_publikasi").val().trim(),
        url_publikasi: $("#url_publikasi").val().trim(),
      };
    },

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

    prepareFormData: function (data) {
      return {
        dosen_id: data.dosen_id,
        judul: data.judul,
        tipe_publikasi: data.tipe_publikasi,
        tahun_publikasi: data.tahun_publikasi || null,
        url_publikasi: data.url_publikasi || null,
      };
    },
  };

  // ============================================================
  // INISIALISASI SEMUA MODUL
  // ============================================================

  $(document).ready(function () {
    Select2Module.init();
    FormSubmissionModule.init();
  });
})();
