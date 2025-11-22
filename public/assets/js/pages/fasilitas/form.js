/**
 * File: pages/fasilitas/form.js
 * Deskripsi: Menangani interaksi form fasilitas (create & edit)
 *
 * Dependencies:
 * - jQuery
 * - Bootstrap 5
 * - jQueryHelpers.js
 * - validationHelpers.js
 *
 * Fitur:
 * - Upload file dengan preview (dukungan drag & drop)
 * - Validasi dan submit form (create & edit)
 * - Error handling per-field
 * - CSRF Protection
 */

(function () {
  "use strict";

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

      fileUploadWrapper.addEventListener("click", () => {
        fileInput.click();
      });

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

      if (btnRemovePreview) {
        btnRemovePreview.addEventListener("click", (e) => {
          e.stopPropagation();
          this.removePreview(fileInput, imagePreview, fileUploadWrapper, currentImageWrapper);
        });
      }

      if (!btnRemovePreview && imagePreview) {
        imagePreview.addEventListener("click", () => {
          this.removePreview(fileInput, imagePreview, fileUploadWrapper, currentImageWrapper);
        });
      }

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

      const sizeValidation = validationHelpers.validateFileSize(file, 2);
      if (!sizeValidation.valid) {
        jQueryHelpers.showError("foto", "fotoError", sizeValidation.message);
        fileInput.value = "";
        return;
      }

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

      jQueryHelpers.clearError("foto", "fotoError");

      const reader = new FileReader();
      reader.onload = (e) => {
        previewImg.src = e.target.result;
        imagePreview.style.display = "block";
        fileUploadWrapper.style.display = "none";
        if (currentImageWrapper) {
          currentImageWrapper.style.display = "none";
        }
      };
      reader.readAsDataURL(file);
    },

    removePreview: function(fileInput, imagePreview, fileUploadWrapper, currentImageWrapper) {
      fileInput.value = "";
      imagePreview.style.display = "none";
      fileUploadWrapper.style.display = "flex";
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
  // MODUL SUBMIT FORM FASILITAS (Create & Edit)
  // ============================================================
  const FormSubmissionModule = {
    init: function () {
      $("#formFasilitas").on("submit", (e) => {
        e.preventDefault();
        this.handleSubmit(e.currentTarget);
      });
    },

    handleSubmit: function (formElement) {
      const $form = $(formElement);

      const ajaxUrl = $form.data("ajax-url");
      const redirectUrl = $form.data("redirect-url");
      const successMessage = $form.data("success-message");

      jQueryHelpers.clearAllErrors("formFasilitas");

      const formData = this.getFormData();
      
      const validationErrors = this.validateFormData(formData);

      if (validationErrors.length > 0) {
        validationErrors.forEach((error) => {
          jQueryHelpers.showError(error.fieldId, error.errorId, error.message);
        });
        return;
      }

      const submitData = this.prepareFormData(formData);
      
      const submitButton = $form.find('button[type="submit"]');
      const buttonState = jQueryHelpers.disableButton(submitButton.attr('id') || 'submit-btn', "Menyimpan...");

      jQueryHelpers.makeAjaxRequest({
        url: ajaxUrl,
        method: "POST",
        data: submitData,
        processData: false,
        contentType: false,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert(successMessage, "success", 1500);
            setTimeout(() => {
              window.location.href = redirectUrl;
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
        nama: $("#nama").val().trim(),
        deskripsi: $("#deskripsi").val().trim(),
        foto: $("#foto")[0].files[0] || null,
        id: $("#id").val() || null,
        // ✅ AMBIL CSRF TOKEN DARI HIDDEN FIELD
        csrf_token: $('input[name="csrf_token"]').val() || null,
      };
    },

    validateFormData: function (data) {
      const errors = [];
      const isCreateMode = data.id === null;

      // ✅ VALIDASI CSRF TOKEN
      if (!data.csrf_token) {
        errors.push({
          fieldId: "csrf_token",
          errorId: "csrfError",
          message: "Token keamanan tidak ditemukan. Silakan refresh halaman.",
        });
      }

      const nameValidation = validationHelpers.validateName(
        data.nama, 
        3, 
        150, 
        "Nama fasilitas"
      );
      if (!nameValidation.valid) {
        errors.push({
          fieldId: "nama",
          errorId: "namaError",
          message: nameValidation.message,
        });
      }

      const deskripsiValidation = validationHelpers.validateText(
        data.deskripsi, 
        255, 
        false
      );
      if (!deskripsiValidation.valid) {
        errors.push({
          fieldId: "deskripsi",
          errorId: "deskripsiError",
          message: deskripsiValidation.message,
        });
      }

      if (isCreateMode && !data.foto) {
        errors.push({
          fieldId: "foto",
          errorId: "fotoError",
          message: "Foto fasilitas wajib diisi",
        });
      }

      if (data.foto) {
        const sizeValidation = validationHelpers.validateFileSize(data.foto, 2);
        if (!sizeValidation.valid) {
          errors.push({
            fieldId: "foto",
            errorId: "fotoError",
            message: sizeValidation.message,
          });
        }

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
      
      // ✅ SERTAKAN CSRF TOKEN
      formData.append("csrf_token", data.csrf_token);
      formData.append("nama", data.nama);
      formData.append("deskripsi", data.deskripsi);

      if (data.foto) {
        formData.append("foto", data.foto);
      }

      if (data.id) {
        formData.append("id", data.id);
      }

      return formData;
    },
  };

  // ============================================================
  // INISIALISASI SEMUA MODUL
  // ============================================================
  $(document).ready(function () {
    FileUploadModule.init();
    FormSubmissionModule.init();
  });
})();