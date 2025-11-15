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
 */

(function () {
  "use strict";

  // ============================================================
  // MODUL FILE UPLOAD
  // ============================================================
  const FileUploadModule = {
    /**
     * Inisialisasi fungsionalitas upload file
     */
    init: function () {
      const fileUploadWrapper = document.getElementById("fileUploadWrapper");
      const fileInput = document.getElementById("foto");
      const imagePreview = document.getElementById("imagePreview");
      const previewImg = document.getElementById("previewImg");
      const btnRemovePreview = document.getElementById("btnRemovePreview");
      const currentImageWrapper = document.querySelector(".current-image-wrapper");

      if (!fileUploadWrapper || !fileInput) return;

      // Klik untuk upload
      fileUploadWrapper.addEventListener("click", () => {
        fileInput.click();
      });

      // Event perubahan file input
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

      // Cek apakah btnRemovePreview ada (untuk edit mode)
      if (btnRemovePreview) {
        btnRemovePreview.addEventListener("click", (e) => {
          e.stopPropagation();
          this.removePreview(fileInput, imagePreview, fileUploadWrapper, currentImageWrapper);
        });
      }

      // Untuk create mode, hapus preview dengan klik pada gambar preview
      if (!btnRemovePreview && imagePreview) {
        imagePreview.addEventListener("click", () => {
          this.removePreview(fileInput, imagePreview, fileUploadWrapper, currentImageWrapper);
        });
      }

      // Event drag and drop
      this.setupDragAndDrop(fileUploadWrapper, fileInput);
    },

    /**
     * Handle pemilihan file dan preview
     *
     * @param {File} file - File yang dipilih
     * @param {HTMLElement} previewImg - Element preview gambar
     * @param {HTMLElement} imagePreview - Element container preview
     * @param {HTMLElement} fileInput - Input file element
     * @param {HTMLElement} fileUploadWrapper - Wrapper upload box
     * @param {HTMLElement} currentImageWrapper - Wrapper untuk gambar lama (edit mode)
     */
    handleFileSelect: function (
      file, 
      previewImg, 
      imagePreview, 
      fileInput, 
      fileUploadWrapper, 
      currentImageWrapper
    ) {
      if (!file) return;

      // Validasi ukuran file (2MB)
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

      // Clear error jika valid
      jQueryHelpers.clearError("foto", "fotoError");

      // Tampilkan preview
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

    /**
     * Extract logic remove preview ke fungsi terpisah
     * Hapus preview dan reset file input
     *
     * @param {HTMLElement} fileInput - Input file element
     * @param {HTMLElement} imagePreview - Element container preview
     * @param {HTMLElement} fileUploadWrapper - Wrapper upload box
     * @param {HTMLElement} currentImageWrapper - Wrapper untuk gambar lama (edit mode)
     */
    removePreview: function(fileInput, imagePreview, fileUploadWrapper, currentImageWrapper) {
      fileInput.value = "";
      imagePreview.style.display = "none";
      fileUploadWrapper.style.display = "flex";
      if (currentImageWrapper) {
        currentImageWrapper.style.display = "block";
      }
    },

    /**
     * Setup fungsionalitas drag and drop
     *
     * @param {HTMLElement} wrapper - Element wrapper upload
     * @param {HTMLElement} fileInput - Element file input
     */
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
    /**
     * Inisialisasi form submission
     */
    init: function () {
      // Binding ke 'submit' form, bukan 'click' tombol
      $("#formFasilitas").on("submit", (e) => {
        e.preventDefault();
        this.handleSubmit(e.currentTarget);
      });
    },

    /**
     * Handle submit form
     *
     * @param {HTMLFormElement} formElement - Element form yang disubmit
     */
    handleSubmit: function (formElement) {
      const $form = $(formElement);

      // Ambil data-* attributes dari form untuk URL & message dinamis
      const ajaxUrl = $form.data("ajax-url");
      const redirectUrl = $form.data("redirect-url");
      const successMessage = $form.data("success-message");

      // Clear semua error messages
      jQueryHelpers.clearAllErrors("formFasilitas");

      // Ambil data form
      const formData = this.getFormData();
      
      // Validasi form
      const validationErrors = this.validateFormData(formData);

      if (validationErrors.length > 0) {
        // Tampilkan error di bawah field yang sesuai
        validationErrors.forEach((error) => {
          jQueryHelpers.showError(error.fieldId, error.errorId, error.message);
        });
        // Tampilkan alert untuk error pertama
        jQueryHelpers.showAlert(validationErrors[0].message, "danger", 5000);
        return;
      }

      // Siapkan FormData untuk AJAX (support file upload)
      const submitData = this.prepareFormData(formData);
      
      // Disable tombol submit
      const submitButton = $form.find('button[type="submit"]');
      const originalText = submitButton.text();
      const buttonState = jQueryHelpers.disableButton(submitButton.attr('id') || 'submit-btn', "Menyimpan...");

      // Kirim AJAX request
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
            }, 1500);
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

    /**
     * Ambil data dari form
     *
     * @return {Object} Data form
     */
    getFormData: function () {
      return {
        nama: $("#nama").val().trim(),
        deskripsi: $("#deskripsi").val().trim(),
        foto: $("#foto")[0].files[0] || null,
        fasilitas_id: $("#fasilitas_id").val() || null, // Untuk edit mode
      };
    },

    /**
     * Validasi data form
     *
     * @param {Object} data - Data form yang akan divalidasi
     * @return {Array} Array of error objects
     */
    validateFormData: function (data) {
      const errors = [];
      const isCreateMode = data.fasilitas_id === null;

      // Validasi Nama (wajib, min 3 char, max 150 char)
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

      // Validasi Deskripsi (opsional, max 5000 char)
      const deskripsiValidation = validationHelpers.validateText(
        data.deskripsi, 
        5000, 
        false
      );
      if (!deskripsiValidation.valid) {
        errors.push({
          fieldId: "deskripsi",
          errorId: "deskripsiError",
          message: deskripsiValidation.message,
        });
      }

      // Validasi Foto (wajib di mode Create, opsional di mode Edit)
      if (isCreateMode && !data.foto) {
        errors.push({
          fieldId: "foto",
          errorId: "fotoError",
          message: "Foto fasilitas wajib diisi.",
        });
      }

      // Validasi Foto (jika file baru di-upload)
      if (data.foto) {
        const sizeValidation = validationHelpers.validateFileSize(data.foto, 2); // 2MB
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

    /**
     * Siapkan FormData untuk submit AJAX
     *
     * @param {Object} data - Data form
     * @return {FormData} FormData object
     */
    prepareFormData: function (data) {
      const formData = new FormData();
      
      formData.append("nama", data.nama);
      formData.append("deskripsi", data.deskripsi);

      // Tambahkan foto jika ada
      if (data.foto) {
        formData.append("foto", data.foto);
      }

      // Tambahkan ID jika ini mode edit
      if (data.fasilitas_id) {
        formData.append("fasilitas_id", data.fasilitas_id);
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