/**
 * File: pages/fasilitas/edit.js
 * Deskripsi: Script untuk halaman form EDIT fasilitas
 *
 * Fitur:
 * - Upload file dengan preview (dukungan drag & drop)
 * - Validasi dan submit form edit
 * - Preview foto lama dan foto baru
 * - Error handling per-field
 * - CSRF Protection
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
    init: function () {
      const fileUploadWrapper = document.getElementById("fileUploadWrapper");
      const fileInput = document.getElementById("foto");
      const imagePreview = document.getElementById("imagePreview");
      const previewImg = document.getElementById("previewImg");
      const btnRemovePreview = document.getElementById("btnRemovePreview");
      const currentImageWrapper = document.querySelector(".current-image-wrapper");

      if (!fileUploadWrapper || !fileInput) return;

      // Click to upload
      fileUploadWrapper.addEventListener("click", () => {
        fileInput.click();
      });

      // File change handler
      fileInput.addEventListener("change", (e) => {
        this.handleFileSelect(
          e.target.files[0],
          previewImg,
          imagePreview,
          fileInput,
          fileUploadWrapper,
          currentImageWrapper
        );
      });

      // Remove preview button
      if (btnRemovePreview) {
        btnRemovePreview.addEventListener("click", (e) => {
          e.stopPropagation();
          this.removePreview(fileInput, imagePreview, fileUploadWrapper, currentImageWrapper);
        });
      }

      // Setup drag and drop
      this.setupDragAndDrop(fileUploadWrapper, fileInput);
    },

    handleFileSelect: function (
      file,
      previewImg,
      imagePreview,
      fileInput,
      fileUploadWrapper,
      currentImageWrapper
    ) {
      if (!file) return;

      // Validasi ukuran file
      const sizeValidation = validationHelpers.validateFileSize(file, 2);
      if (!sizeValidation.valid) {
        jQueryHelpers.showError("foto", "fotoError", sizeValidation.message);
        fileInput.value = "";
        return;
      }

      // Validasi tipe file
      const typeValidation = validationHelpers.validateFileType(file, [
        "image/jpeg",
        "image/jpg",
        "image/png",
      ]);
      if (!typeValidation.valid) {
        jQueryHelpers.showError("foto", "fotoError", typeValidation.message);
        fileInput.value = "";
        return;
      }

      // Clear error jika ada
      jQueryHelpers.clearError("foto", "fotoError");

      // Show preview
      const reader = new FileReader();
      reader.onload = (e) => {
        previewImg.src = e.target.result;
        imagePreview.style.display = "block";
        fileUploadWrapper.style.display = "none";

        // Hide current image
        if (currentImageWrapper) {
          currentImageWrapper.style.display = "none";
        }
      };
      reader.readAsDataURL(file);
    },

    removePreview: function (fileInput, imagePreview, fileUploadWrapper, currentImageWrapper) {
      fileInput.value = "";
      imagePreview.style.display = "none";
      fileUploadWrapper.style.display = "flex";

      // Show current image again
      if (currentImageWrapper) {
        currentImageWrapper.style.display = "block";
      }
    },

    setupDragAndDrop: function (wrapper, fileInput) {
      wrapper.addEventListener("dragover", (e) => {
        e.preventDefault();
        wrapper.classList.add("dragover");
      });

      wrapper.addEventListener("dragleave", () => {
        wrapper.classList.remove("dragover");
      });

      wrapper.addEventListener("drop", (e) => {
        e.preventDefault();
        wrapper.classList.remove("dragover");

        const files = e.dataTransfer.files;
        if (files.length > 0) {
          fileInput.files = files;
          fileInput.dispatchEvent(new Event("change"));
        }
      });
    },
  };

  // ============================================================
  // MODUL SUBMIT FORM EDIT
  // ============================================================
  const FormEditModule = {
    init: function () {
      $("#btn-submit-update-fasilitas").on("click", (e) => {
        e.preventDefault();
        this.handleSubmit();
      });
    },

    handleSubmit: function () {
      // Clear all errors
      jQueryHelpers.clearAllErrors("formFasilitas");

      // Get form data
      const formData = this.getFormData();

      // Validate
      const validationErrors = this.validateFormData(formData);
      if (validationErrors.length > 0) {
        validationErrors.forEach((error) => {
          jQueryHelpers.showError(error.fieldId, error.errorId, error.message);
        });
        return;
      }

      // Prepare FormData
      const submitData = this.prepareFormData(formData);

      // Disable button
      const buttonState = jQueryHelpers.disableButton(
        "btn-submit-update-fasilitas",
        "Menyimpan..."
      );

      // Submit via AJAX
      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/fasilitas/update`,
        method: "POST",
        data: submitData,
        processData: false,
        contentType: false,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert(
              "Data fasilitas berhasil diupdate!",
              "success",
              1500
            );
            setTimeout(() => {
              window.location.href = `${BASE_URL}/admin/fasilitas`;
            }, 500);
          } else {
            jQueryHelpers.showAlert("Gagal: " + response.message, "danger", 5000);
            buttonState.enable();
          }
        },
        onError: (errorMessage) => {
          jQueryHelpers.showAlert("Error: " + errorMessage, "danger", 5000);
          buttonState.enable();
        },
      });
    },

    getFormData: function () {
      return {
        id: $("#id").val(),
        nama: $("#nama").val().trim(),
        deskripsi: $("#deskripsi").val().trim(),
        foto: $("#foto")[0].files[0] || null,
        csrf_token: $('input[name="csrf_token"]').val() || null,
      };
    },

    validateFormData: function (data) {
      const errors = [];

      // Validasi CSRF token
      if (!data.csrf_token) {
        errors.push({
          fieldId: "csrf_token",
          errorId: "csrfError",
          message: "Token keamanan tidak ditemukan. Silakan refresh halaman.",
        });
      }

      // Validasi ID
      if (!data.id) {
        errors.push({
          fieldId: "id",
          errorId: "idError",
          message: "ID fasilitas tidak valid.",
        });
      }

      // Validasi nama
      const nameValidation = validationHelpers.validateName(data.nama, 3, 150);
      if (!nameValidation.valid) {
        errors.push({
          fieldId: "nama",
          errorId: "namaError",
          message: nameValidation.message,
        });
      }

      // Validasi deskripsi (optional)
      if (data.deskripsi) {
        const deskripsiValidation = validationHelpers.validateText(data.deskripsi, 255, false);
        if (!deskripsiValidation.valid) {
          errors.push({
            fieldId: "deskripsi",
            errorId: "deskripsiError",
            message: deskripsiValidation.message,
          });
        }
      }

      // Validasi foto (optional untuk edit, tapi jika ada harus valid)
      if (data.foto) {
        // Validasi ukuran file
        const sizeValidation = validationHelpers.validateFileSize(data.foto, 2);
        if (!sizeValidation.valid) {
          errors.push({
            fieldId: "foto",
            errorId: "fotoError",
            message: sizeValidation.message,
          });
        }

        // Validasi tipe file
        const typeValidation = validationHelpers.validateFileType(data.foto, [
          "image/jpeg",
          "image/jpg",
          "image/png",
        ]);
        if (!typeValidation.valid) {
          errors.push({
            fieldId: "foto",
            errorId: "fotoError",
            message: typeValidation.message,
          });
        }
      }

      return errors;
    },

    prepareFormData: function (data) {
      const formData = new FormData();

      formData.append("csrf_token", data.csrf_token);
      formData.append("id", data.id);
      formData.append("nama", data.nama);
      formData.append("deskripsi", data.deskripsi);

      // Foto optional untuk edit
      if (data.foto) {
        formData.append("foto", data.foto);
      }

      return formData;
    },
  };

  // ============================================================
  // INISIALISASI
  // ============================================================
  $(document).ready(function () {
    FileUploadModule.init();
    FormEditModule.init();
  });
})();