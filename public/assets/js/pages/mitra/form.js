/**
 * File: pages/mitra/form.js
 * Deskripsi: Script untuk halaman create mitra
 *
 * Fitur:
 * - File upload dengan preview
 * - Validasi form (nama, kategori, tanggal, file)
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
  // MODUL FILE UPLOAD
  // ============================================================

  const FileUploadModule = {
    init: () => {
      $("#helper-text-preview").hide();

      const fileUploadWrapper = $("#fileUploadWrapper");
      const fileInput = $("#logo_mitra");
      const imagePreview = $("#imagePreview");
      const previewImg = $("#previewImg");

      if (!fileUploadWrapper || !fileInput) return;

      // Klik untuk upload
      fileUploadWrapper.on("click", () => {
        fileInput.click();
      });

      // CHANGE: Event perubahan file input (untuk menampilkan preview image after pick)
      fileInput.on("change", (e) => {
        FileUploadModule.handleFileSelect(
          e.target.files[0],
          previewImg,
          imagePreview,
          fileUploadWrapper,
          fileInput
        );
      });

      // CLICK: Remove preview
      previewImg.on("click", function () {
        fileInput.val("");
        imagePreview.css("display", "none");
        fileUploadWrapper.css("display", "block");
        $("#helper-text-preview").hide();
      });
    },

    /**
     * Handle pemilihan file dan preview
     *
     * @param {File} file - File yang dipilih
     * @param {HTMLElement} previewImg - Element preview gambar
     * @param {HTMLElement} imagePreview - Element container preview
     */
    handleFileSelect: (
      file,
      previewImg,
      imagePreview,
      fileUploadWrapper,
      fileInput
    ) => {
      if (!file) return;

      // Validasi ukuran file
      const sizeValidation = validationHelpers.validateFileSize(file, 2);
      if (!sizeValidation.valid) {
        alert(sizeValidation.message);
        fileInput.val("");
        return;
      }

      // Validasi tipe file
      const validateFileType = validationHelpers.validateFileType(file, [
        "image/jpeg",
        "image/jpg",
        "image/png",
      ]);

      if (!validateFileType.valid) {
        alert(validateFileType.message);
        fileInput.val("");
        return;
      }

      // Tampilkan preview
      const reader = new FileReader();

      reader.onload = (e) => {
        previewImg.attr("src", e.target.result);
        imagePreview.css("display", "block");
        fileUploadWrapper.css("display", "none");
        $("#helper-text-preview").show();
      };

      reader.readAsDataURL(file);
    },
  };

  // ============================================================
  // MODUL SUBMIT FORM
  // ============================================================

  const FormCreateMitra = {
    init: function () {
      $("#btn-submit-create-mitra").on("click", (e) => {
        e.preventDefault();
        this.handleSubmit();
      });
    },

    handleSubmit: function () {
      const formData = this.getFormData();
      const validationErrors = this.validateFormData(formData);

      jQueryHelpers.clearAllErrors("formMitra");

      if (validationErrors.length > 0) {
        validationErrors.forEach((error) => {
          jQueryHelpers.showError(error.fieldId, error.errorId, error.message);
        });
        return;
      }

      const submitData = this.prepareFormData(formData);
      const buttonState = jQueryHelpers.disableButton(
        "btn-submit-create-mitra",
        "Menyimpan ..."
      );

      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/mitra/create`,
        method: "POST",
        data: submitData,
        processData: false,
        contentType: false,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert(
              "Data mitra berhasil ditambahkan!",
              "success",
              2000
            );
            setTimeout(() => {
              window.location.href = `${BASE_URL}/admin/mitra`;
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
          jQueryHelpers.showAlert("Error: " + errorMessage, "danger", 5000);
          buttonState.enable();
        },
      });
    },
    getFormData: () => {
      return {
        nama: $("#nama_mitra").val().trim(),
        status: $("#status_mitra").val(),
        kategori_mitra: $("#kategori_mitra").val(),
        logo_mitra: $("#logo_mitra")[0].files[0],
        tanggal_mulai: $("#tanggal_mulai").val().trim(),
        tanggal_akhir: $("#tanggal_akhir").val().trim(),
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

      // Validasi nama mitra
      const nameValidation = validationHelpers.validateName(data.nama, 1, 255);
      if (!nameValidation.valid) {
        errors.push({
          fieldId: "nama_mitra",
          errorId: "namaMitraError",
          message: nameValidation.message,
        });
      }

      // Validasi kategori mitra
      if (!data.kategori_mitra || data.kategori_mitra === "") {
        errors.push({
          fieldId: "kategori_mitra",
          errorId: "kategoriMitraError",
          message: "Kategori mitra wajib dipilih",
        });
      }

      // Validasi tanggal mulai
      if (!data.tanggal_mulai || data.tanggal_mulai.length < 1) {
        errors.push({
          fieldId: "tanggal_mulai",
          errorId: "tanggalMulaiError",
          message: "Tanggal mulai wajib diisi",
        });
      }

      if (data.tanggal_akhir && data.tanggal_akhir < data.tanggal_mulai) {
        errors.push({
          fieldId: "tanggal_akhir",
          errorId: "tanggalAkhirError",
          message: "Tanggal akhir tidak boleh lebih awal dari tanggal mulai",
        });
      }

      if (data.logo_mitra) {
        const sizeValidation = validationHelpers.validateFileSize(
          data.logo_mitra,
          2
        );

        if (!sizeValidation.valid) {
          errors.push({
            fieldId: "logo_mitra",
            errorId: "logoMitraError",
            message: sizeValidation.message,
          });
        }

        const typeValidation = validationHelpers.validateFileType(
          data.logo_mitra,
          ["image/jpeg", "image/jpg", "image/png"]
        );
        if (!typeValidation.valid) {
          errors.push({
            fieldId: "logo_mitra",
            errorId: "logoMitraError",
            message: typeValidation.message,
          });
        }
      }

      return errors;
    },
    prepareFormData: (data) => {
      const formData = new FormData();

      formData.append("nama", data.nama);
      formData.append("status", data.status);
      formData.append("kategori_mitra", data.kategori_mitra);
      formData.append("tanggal_mulai", data.tanggal_mulai);
      formData.append("csrf_token", data.csrf_token)

      // Hanya append tanggal_akhir jika ada value
      if (data.tanggal_akhir && data.tanggal_akhir !== "") {
        formData.append("tanggal_akhir", data.tanggal_akhir);
      }

      if (data.logo_mitra) {
        formData.append("logo_mitra", data.logo_mitra);
      }

      return formData;
    },
  };

  // ============================================================
  // INISIALISASI SEMUA MODUL
  // ============================================================

  $(document).ready(function () {
    FileUploadModule.init();
    FormCreateMitra.init();
  });
})();
