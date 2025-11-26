/**
 * File: pages/recruitment/form.js
 * Deskripsi: Script untuk halaman form recruitment (create)
 *
 * Fitur:
 * - Validasi form
 * - Submit form via AJAX
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 * - validationHelpers.js
 */

(function () {
  "use strict";

  const BASE_URL = $('meta[name="base-url"]').attr("content") || "/applied-informatics";

  // ============================================================
  // MODUL SUBMIT FORM
  // ============================================================

  const FormCreateRecruitment = {
    init: function () {
      $("#btn-submit-create-recruitment").on("click", (e) => {
        e.preventDefault();
        this.handleSubmit();
      });
    },

    handleSubmit: function () {
      const formData = this.getFormData();
      const validationErrors = this.validateFormData(formData);

      jQueryHelpers.clearAllErrors("formRecruitment");

      if (validationErrors.length > 0) {
        validationErrors.forEach((error) => {
          jQueryHelpers.showError(error.fieldId, error.errorId, error.message);
        });
        return;
      }

      const submitData = this.prepareFormData(formData);
      const buttonState = jQueryHelpers.disableButton(
        "btn-submit-create-recruitment",
        "Menyimpan ..."
      );

      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/recruitment/create`,
        method: "POST",
        data: submitData,
        processData: false,
        contentType: false,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert(
              "Data recruitment berhasil ditambahkan!",
              "success"
            );
            setTimeout(() => {
              window.location.href = `${BASE_URL}/admin/recruitment`;
            }, 500);
          } else {
            jQueryHelpers.showAlert(
              "Gagal: " + response.message,
              "danger",
              5000
            );
            buttonState.enable();
          }
        },
        onError: (errorMessage) => {
          jQueryHelpers.showAlert("Error: " + errorMessage, "danger");
          buttonState.enable();
        },
      });
    },

    getFormData: () => {
      return {
        judul: $("#judul").val().trim(),
        status: $("#status").val(),
        tanggal_buka: $("#tanggal_buka").val().trim(),
        tanggal_tutup: $("#tanggal_tutup").val().trim(),
        lokasi: $("#lokasi").val().trim(),
        deskripsi: $("#deskripsi").val().trim(),
        csrf_token: $("input[name='csrf_token']").val(),
      };
    },

    /**
     * Validasi data form sebelum submit
     *
     * @param {Object} data - Data form yang akan divalidasi
     * @returns {Array} - Array of error objects
     */
    validateFormData: (data) => {
      const errors = [];

      // Validasi judul
      const judulValidation = validationHelpers.validateName(data.judul, 1, 255);
      if (!judulValidation.valid) {
        errors.push({
          fieldId: "judul",
          errorId: "judulError",
          message: "Judul wajib diisi",
        });
      }

      // Validasi status
      if (!data.status || data.status === "") {
        errors.push({
          fieldId: "status",
          errorId: "statusError",
          message: "Status recruitment wajib dipilih",
        });
      }

      // Validasi tanggal buka
      if (!data.tanggal_buka || data.tanggal_buka.length < 1) {
        errors.push({
          fieldId: "tanggal_buka",
          errorId: "tanggalBukaError",
          message: "Tanggal buka wajib diisi",
        });
      }

      // Validasi tanggal tutup
      if (!data.tanggal_tutup || data.tanggal_tutup.length < 1) {
        errors.push({
          fieldId: "tanggal_tutup",
          errorId: "tanggalTutupError",
          message: "Tanggal tutup wajib diisi",
        });
      }

      // Validasi tanggal tutup tidak lebih awal dari tanggal buka
      if (data.tanggal_tutup && data.tanggal_buka && data.tanggal_tutup < data.tanggal_buka) {
        errors.push({
          fieldId: "tanggal_tutup",
          errorId: "tanggalTutupError",
          message: "Tanggal tutup tidak boleh lebih awal dari tanggal buka",
        });
      }

      // Validasi lokasi
      const lokasiValidation = validationHelpers.validateName(data.lokasi, 1, 255);
      if (!lokasiValidation.valid) {
        errors.push({
          fieldId: "lokasi",
          errorId: "lokasiError",
          message: "Lokasi wajib diisi",
        });
      }

      // Validasi deskripsi
      if (!data.deskripsi || data.deskripsi.length < 1) {
        errors.push({
          fieldId: "deskripsi",
          errorId: "deskripsiError",
          message: "Deskripsi wajib diisi",
        });
      }

      return errors;
    },

    prepareFormData: (data) => {
      const formData = new FormData();

      formData.append("judul", data.judul);
      formData.append("status", data.status);
      formData.append("tanggal_buka", data.tanggal_buka);
      formData.append("tanggal_tutup", data.tanggal_tutup);
      formData.append("lokasi", data.lokasi);
      formData.append("deskripsi", data.deskripsi);
      formData.append("csrf_token", data.csrf_token);

      return formData;
    },
  };

  // ============================================================
  // INISIALISASI SEMUA MODUL
  // ============================================================

  $(document).ready(function () {
    FormCreateRecruitment.init();
  });
})();