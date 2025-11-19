/**
 * File: pages/produk/form.js
 * Deskripsi: Menangani interaksi form produk (create & edit)
 *
 * Dependencies:
 * - jQuery
 * - Bootstrap 5
 * - jQueryHelpers.js
 * - validationHelpers.js
 *
 * Fitur:
 * - Upload file dengan preview (dukungan drag & drop)
 * - Toggle author fields (Dosen/Mahasiswa/Kolaborasi)
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
      const fileInput = document.getElementById("foto_produk");
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
        jQueryHelpers.showError("foto_produk", "fotoProdukError", sizeValidation.message);
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
        jQueryHelpers.showError("foto_produk", "fotoProdukError", typeValidation.message);
        fileInput.value = "";
        return;
      }

      // Clear error jika valid
      jQueryHelpers.clearError("foto_produk", "fotoProdukError");

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
     * Hapus preview dan reset file input
     *
     * @param {HTMLElement} fileInput - Input file element
     * @param {HTMLElement} imagePreview - Element container preview
     * @param {HTMLElement} fileUploadWrapper - Wrapper upload box
     * @param {HTMLElement} currentImageWrapper - Wrapper untuk gambar lama (edit mode)
     */
    removePreview: function (fileInput, imagePreview, fileUploadWrapper, currentImageWrapper) {
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
  // MODUL AUTHOR TYPE TOGGLE
  // ============================================================
  const AuthorTypeModule = {
    /**
     * Inisialisasi toggle author fields
     */
    init: function () {
      const authorTypeDosen = document.getElementById("author_type_dosen");
      const authorTypeMahasiswa = document.getElementById("author_type_mahasiswa");
      const authorTypeKolaborasi = document.getElementById("author_type_kolaborasi");

      if (authorTypeDosen) {
        authorTypeDosen.addEventListener("change", this.toggleAuthorFields);
      }
      if (authorTypeMahasiswa) {
        authorTypeMahasiswa.addEventListener("change", this.toggleAuthorFields);
      }
      if (authorTypeKolaborasi) {
        authorTypeKolaborasi.addEventListener("change", this.toggleAuthorFields);
      }

      // Initial toggle saat load halaman
      this.toggleAuthorFields();
    },

    /**
     * Toggle visibility author fields berdasarkan tipe yang dipilih
     */
    toggleAuthorFields: function () {
      const authorTypeDosen = document.getElementById("author_type_dosen");
      const authorTypeMahasiswa = document.getElementById("author_type_mahasiswa");
      const authorTypeKolaborasi = document.getElementById("author_type_kolaborasi");
      const authorDosenWrapper = document.getElementById("author_dosen_wrapper");
      const authorMahasiswaWrapper = document.getElementById("author_mahasiswa_wrapper");
      const authorDosenSelect = document.getElementById("author_dosen_id");
      const authorMahasiswaInput = document.getElementById("author_mahasiswa_nama");

      if (authorTypeDosen.checked) {
        // Hanya Dosen
        authorDosenWrapper.style.display = "block";
        authorMahasiswaWrapper.style.display = "none";
        authorDosenSelect.required = true;
        authorMahasiswaInput.required = false;
        authorMahasiswaInput.value = ""; // Clear mahasiswa input
      } else if (authorTypeMahasiswa.checked) {
        // Hanya Mahasiswa
        authorDosenWrapper.style.display = "none";
        authorMahasiswaWrapper.style.display = "block";
        authorDosenSelect.required = false;
        authorMahasiswaInput.required = true;
        authorDosenSelect.value = ""; // Clear dosen select
      } else if (authorTypeKolaborasi.checked) {
        // Kolaborasi - tampilkan keduanya
        authorDosenWrapper.style.display = "block";
        authorMahasiswaWrapper.style.display = "block";
        authorDosenSelect.required = true;
        authorMahasiswaInput.required = true;
      }
    },
  };

  // ============================================================
  // MODUL SUBMIT FORM PRODUK (Create & Edit)
  // ============================================================
  const FormSubmissionModule = {
    /**
     * Inisialisasi form submission
     */
    init: function () {
      // Binding ke 'submit' form, bukan 'click' tombol
      $("#formProduk").on("submit", (e) => {
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

      // Get form action URL
      const ajaxUrl = $form.data("ajax-url");
      const redirectUrl = $form.data("redirect-url");
      const successMessage = $form.data("success-message");

      // Clear semua error messages
      jQueryHelpers.clearAllErrors("formProduk");

      // Ambil data form
      const formData = this.getFormData();

      // Validasi form
      const validationErrors = this.validateFormData(formData);

      if (validationErrors.length > 0) {
        // Tampilkan error di bawah field yang sesuai
        validationErrors.forEach((error) => {
          jQueryHelpers.showError(error.fieldId, error.errorId, error.message);
        });
        return;
      }

      // Siapkan FormData untuk AJAX (support file upload)
      const submitData = this.prepareFormData(formData);

      // Disable tombol submit
      const submitButton = $form.find('button[type="submit"]');
      const buttonState = jQueryHelpers.disableButton(
        submitButton.attr('id') || 'submit-btn', "Menyimpan...");

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

    /**
     * Ambil data dari form
     *
     * @return {Object} Data form
     */
    getFormData: function () {
      return {
        nama_produk: $("#nama_produk").val().trim(),
        deskripsi: $("#deskripsi").val().trim(),
        link_produk: $("#link_produk").val().trim(),
        foto_produk: $("#foto_produk")[0].files[0] || null,
        author_type: $('input[name="author_type"]:checked').val(),
        author_dosen_id: $("#author_dosen_id").val() || null,
        author_mahasiswa_nama: $("#author_mahasiswa_nama").val().trim() || null,
        id: $("#id").val() || null,
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
      const isCreateMode = data.id === null;

      // Validasi Nama Produk (wajib, min 3 char, max 255 char)
      const nameValidation = validationHelpers.validateName(data.nama_produk, 3, 255, "Nama produk");
      if (!nameValidation.valid) {
        errors.push({
          fieldId: "nama_produk",
          errorId: "namaProdukError",
          message: nameValidation.message,
        });
      }

      // Validasi Deskripsi (opsional, max 5000 char)
      const deskripsiValidation = validationHelpers.validateText(data.deskripsi, 5000, false);
      if (!deskripsiValidation.valid) {
        errors.push({
          fieldId: "deskripsi",
          errorId: "deskripsiError",
          message: deskripsiValidation.message,
        });
      }

      // Validasi Link Produk (opsional, jika diisi harus valid URL)
      if (data.link_produk) {
        const linkValidation = validationHelpers.validateUrl(data.link_produk);
        if (!linkValidation.valid) {
          errors.push({
            fieldId: "link_produk",
            errorId: "linkProdukError",
            message: linkValidation.message,
          });
        }
      }

      // Validasi Foto (wajib di mode Create, opsional di mode Edit)
      if (isCreateMode && !data.foto_produk) {
        errors.push({
          fieldId: "foto_produk",
          errorId: "fotoProdukError",
          message: "Foto produk wajib diisi",
        });
      }

      // Validasi Foto (jika file baru di-upload)
      if (data.foto_produk) {
        const sizeValidation = validationHelpers.validateFileSize(data.foto_produk, 2); // 2MB
        if (!sizeValidation.valid) {
          errors.push({
            fieldId: "foto_produk",
            errorId: "fotoProdukError",
            message: sizeValidation.message,
          });
        }

        const typeValidation = validationHelpers.validateFileType(data.foto_produk, [
          "image/jpeg",
          "image/jpg",
          "image/png",
        ]);
        if (!typeValidation.valid) {
          errors.push({
            fieldId: "foto_produk",
            errorId: "fotoProdukError",
            message: typeValidation.message,
          });
        }
      }

      // Validasi author_type (wajib)
      if (!data.author_type) {
        errors.push({
          fieldId: "author_type", // Bisa di salah satu radio button
          errorId: "authorTypeError",
          message: "Tipe author harus dipilih",
        });
      }

      // Validasi Author berdasarkan author_type
      if (data.author_type === "dosen") {
        if (!data.author_dosen_id) {
          errors.push({
            fieldId: "author_dosen_id",
            errorId: "authorDosenError",
            message: "Dosen harus dipilih",
          });
        }
      } else if (data.author_type === "mahasiswa") {
        if (!data.author_mahasiswa_nama) {
          errors.push({
            fieldId: "author_mahasiswa_nama",
            errorId: "authorMahasiswaError",
            message: "Nama mahasiswa harus diisi",
          });
        } else {
          const mahasiswaValidation = validationHelpers.validateName(
            data.author_mahasiswa_nama,
            3,
            255,
            "Nama mahasiswa"
          );
          if (!mahasiswaValidation.valid) {
            errors.push({
              fieldId: "author_mahasiswa_nama",
              errorId: "authorMahasiswaError",
              message: mahasiswaValidation.message,
            });
          }
        }
      } else if (data.author_type === "kolaborasi") {
        if (!data.author_dosen_id) {
          errors.push({
            fieldId: "author_dosen_id",
            errorId: "authorDosenError",
            message: "Dosen harus dipilih untuk kolaborasi",
          });
        }
        if (!data.author_mahasiswa_nama) {
          errors.push({
            fieldId: "author_mahasiswa_nama",
            errorId: "authorMahasiswaError",
            message: "Nama mahasiswa harus diisi untuk kolaborasi",
          });
        } else {
          const mahasiswaValidation = validationHelpers.validateName(
            data.author_mahasiswa_nama,
            3,
            255,
            "Nama mahasiswa"
          );
          if (!mahasiswaValidation.valid) {
            errors.push({
              fieldId: "author_mahasiswa_nama",
              errorId: "authorMahasiswaError",
              message: mahasiswaValidation.message,
            });
          }
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

      formData.append("nama_produk", data.nama_produk);
      formData.append("deskripsi", data.deskripsi);
      formData.append("link_produk", data.link_produk);
      formData.append("author_type", data.author_type);

      // Tambahkan author_dosen_id jika ada
      if (data.author_dosen_id) {
        formData.append("author_dosen_id", data.author_dosen_id);
      }

      // Tambahkan author_mahasiswa_nama jika ada
      if (data.author_mahasiswa_nama) {
        formData.append("author_mahasiswa_nama", data.author_mahasiswa_nama);
      }

      // Tambahkan foto jika ada
      if (data.foto_produk) {
        formData.append("foto_produk", data.foto_produk);
      }

      // Tambahkan ID jika ini mode edit
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
    AuthorTypeModule.init();
    FormSubmissionModule.init();
  });
})();