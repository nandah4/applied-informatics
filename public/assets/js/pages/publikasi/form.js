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

const BASE_URL =
    $('meta[name="base-url"]').attr("content") || "/applied-informatics";

  // ============================================================
  // MODUL SELECT2 INITIALIZATION
  // ============================================================

  const Select2Module = {
    init: function () {
      // Initialize Select2 untuk dropdown dosen
      $("#dosen_id").select2({
        theme: "bootstrap-5",
        placeholder: "Pilih Dosen",
        allowClear: true,
        width: "100%",
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
        url: `${BASE_URL}/admin/publikasi-akademik/create`,
        method: "POST",
        data: submitData,
        processData: false,
        contentType: false,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert(
              "Data publikasi berhasil ditambahkan!",
              "success",
              2000
            );
            setTimeout(() => {
              window.location.href = `${BASE_URL}/admin/publikasi-akademik`;
            }, 500);
          } else {
            jQueryHelpers.showAlert("Gagal: " + response.message, "danger", 5000);
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
        csrf_token: $("input[name='csrf_token']").val()
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

       const judulValidation = validationHelpers.validateText(
        data.judul,
        600,
        true,
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

      // Valiadate Tahun Publikasi not null
      const tahunPublikasi = validationHelpers.validateRequired(
        data.tahun_publikasi,
        "Tahun Publikasi"
      );
      if (!tahunPublikasi.valid) {
        errors.push({
          fieldId: "tahun_publikasi",
          errorId: "tahunPublikasiError",
          message: tahunPublikasi.message,
        });
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

      // Validate urlPublikasi
      const urlValiddation = validationHelpers.validateRequired(
        data.url_publikasi,
        "URL Publikasi"
      );
      if (!urlValiddation.valid) {
        errors.push({
          fieldId: "url_publikasi",
          errorId: "urlPublikasiError",
          message: urlValiddation.message,
        });
      }

      return errors;
    },

    prepareFormData: function (data) {
      const formData = new FormData();
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
  // INISIALISASI SEMUA MODUL
  // ============================================================

  $(document).ready(function () {
    Select2Module.init();
    FormSubmissionModule.init();
  });
})();
