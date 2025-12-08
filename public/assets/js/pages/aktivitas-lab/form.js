/**
 * File: pages/aktivitas-lab/form.js
 * Deskripsi: Script untuk halaman create aktivitas laboratorium
 *
 * Fitur:
 * - File upload dengan preview
 * - Validasi form (judul, deskripsi, tanggal, file)
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
      const fileInput = $("#foto_aktivitas");
      const imagePreview = $("#imagePreview");
      const previewImg = $("#previewImg");

      if (!fileUploadWrapper || !fileInput) return;

      // Klik untuk upload
      fileUploadWrapper.on("click", () => {
        fileInput.click();
      });

      // Event perubahan file input (untuk menampilkan preview image after pick)
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
     * @param {HTMLElement} fileUploadWrapper - Element wrapper upload
     * @param {HTMLElement} fileInput - Element input file
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

  const FormCreateAktivitas = {
    init: function () {
      $("#btn-submit-create-aktivitas").on("click", (e) => {
        e.preventDefault();
        this.handleSubmit();
      });
    },

    handleSubmit: function () {
      const formData = this.getFormData();

      const validationErrors = this.validateFormData(formData);

      jQueryHelpers.clearAllErrors("formCreateAktivitas");

      if (validationErrors.length > 0) {
        validationErrors.forEach((error) => {
          jQueryHelpers.showError(error.fieldId, error.errorId, error.message);
        });
        return;
      }

      const submitData = this.prepareFormData(formData);
      
      const buttonState = jQueryHelpers.disableButton(
        "btn-submit-create-aktivitas",
        "Menyimpan ..."
      );



      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/aktivitas-lab/create`,
        method: "POST",
        data: submitData,
        processData: false,
        contentType: false,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert(
              "Data aktivitas berhasil ditambahkan!",
              "success",
              2000
            );
            setTimeout(() => {
              window.location.href = `${BASE_URL}/admin/aktivitas-lab`;
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
        judul: $("#judul_aktivitas").val().trim(),
        deskripsi: $(quill.root).html(),
        foto_aktivitas: $("#foto_aktivitas")[0].files[0],
        tanggal_kegiatan: $("#tanggal_kegiatan").val().trim(),
        csrf_token: $("input[name='csrf_token']").val(),
      };
    },

    validateFormData: (data) => {
      const errors = [];

      // Validasi judul
      const judulValidation = validationHelpers.validateName(data.judul, 5, 255);
      if (!judulValidation.valid) {
        errors.push({
          fieldId: "judul_aktivitas",
          errorId: "judulAktivitasError",
          message: judulValidation.message,
        });
      }

      // Validasi deskripsi
      const textDeskripsi = quill.getText().trim();
      if (!textDeskripsi || textDeskripsi.length < 10) {
        console.log(textDeskripsi)
        errors.push({
          fieldId: "",
          errorId: "deskripsiError",
          message: "Deskripsi minimal 10 karakter",
        });
      }

      // Validasi tanggal kegiatan
      if (!data.tanggal_kegiatan || data.tanggal_kegiatan.length < 1) {
        errors.push({
          fieldId: "tanggal_kegiatan",
          errorId: "tanggalKegiatanError",
          message: "Tanggal Kegiatan wajib diisi",
        });
      }

      // Validasi foto (opsional, tapi jika ada harus valid)
      if (data.foto_aktivitas) {
        const sizeValidation = validationHelpers.validateFileSize(
          data.foto_aktivitas,
          2
        );

        if (!sizeValidation.valid) {
          errors.push({
            fieldId: "foto_aktivitas",
            errorId: "fotoAktivitasError",
            message: sizeValidation.message,
          });
        }

        const typeValidation = validationHelpers.validateFileType(
          data.foto_aktivitas,
          ["image/jpeg", "image/jpg", "image/png"]
        );
        if (!typeValidation.valid) {
          errors.push({
            fieldId: "foto_aktivitas",
            errorId: "fotoAktivitasError",
            message: typeValidation.message,
          });
        }
      }

      return errors;
    },

    prepareFormData: (data) => {
      const formData = new FormData();

      formData.append("judul", data.judul);
      formData.append("deskripsi", data.deskripsi);
      formData.append("tanggal_kegiatan", data.tanggal_kegiatan);
      formData.append("csrf_token", data.csrf_token);

      if (data.foto_aktivitas) {
        formData.append("foto_aktivitas", data.foto_aktivitas);
      }

      return formData;
    },
  };

  // ============================================================
  // INISIALISASI SEMUA MODUL
  // ============================================================

  $(document).ready(function () {
    FileUploadModule.init();
    FormCreateAktivitas.init();
  });
})();
