(function () {
  "use strict";

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
  // MODUL UPDATE AKTIVITAS
  // ============================================================

  const FormUpdateAktivitas = {
    init: function () {
      $("#formUpdateAktivitas").on("submit", (e) => {
        e.preventDefault();
        this.handleSubmit();
      });
    },

    handleSubmit: function () {
      const formData = this.getFormData();
      const validationErrors = this.validateFormData(formData);

      jQueryHelpers.clearAllErrors("formUpdateAktivitas");

      if (validationErrors.length > 0) {
        validationErrors.forEach((error) => {
          jQueryHelpers.showError(error.fieldId, error.errorId, error.message);
        });
        return;
      }

      const submitData = this.prepareFormData(formData);

      // Disable submit button
      const submitButton = $("#formUpdateAktivitas").find('button[type="submit"]');
      submitButton.prop("disabled", true);
      const originalButtonHtml = submitButton.html();
      submitButton.html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');

      jQueryHelpers.makeAjaxRequest({
        url: "/applied-informatics/aktivitas-lab/update",
        method: "POST",
        data: submitData,
        processData: false,
        contentType: false,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert(
              "Data aktivitas berhasil diupdate!",
              "success"
            );
            setTimeout(() => {
              window.location.href = "/applied-informatics/aktivitas-lab";
            }, 500);
          } else {
            jQueryHelpers.showAlert(
              "Gagal: " + response.message,
              "danger",
              5000
            );
            submitButton.prop("disabled", false);
            submitButton.html(originalButtonHtml);
          }
        },
        onError: (errorMessage) => {
          jQueryHelpers.showAlert("Error: " + errorMessage, "danger");
          submitButton.prop("disabled", false);
          submitButton.html(originalButtonHtml);
        },
      });
    },

    getFormData: () => {
      return {
        id: $('input[name="id"]').val(),
        judul: $("#judul_aktivitas").val().trim(),
        deskripsi: $("#deskripsi").val().trim(),
        foto_aktivitas: $("#foto_aktivitas")[0].files[0],
        tanggal_kegiatan: $("#tanggal_kegiatan").val().trim(),
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
      if (!data.deskripsi || data.deskripsi.length < 10) {
        errors.push({
          fieldId: "deskripsi",
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

      formData.append("id", data.id);
      formData.append("judul", data.judul);
      formData.append("deskripsi", data.deskripsi);
      formData.append("tanggal_kegiatan", data.tanggal_kegiatan);

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
    FormUpdateAktivitas.init();
  });
})();
