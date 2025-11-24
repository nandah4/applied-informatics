/**
 * File: pages/produk/form.js
 * Deskripsi: Menangani interaksi form produk (create & edit) dengan multiple dosen
 * 
 * ✅ FIXED VERSION - Sesuai dengan referensi dosen/edit.js
 */

(function () {
  "use strict";

  // ============================================================
  // MODUL FILE UPLOAD
  // ============================================================
  const FileUploadModule = {
    init: function () {
      const fileUploadWrapper = document.getElementById("fileUploadWrapper");
      const fileInput = document.getElementById("foto_produk");
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
        jQueryHelpers.showError("foto_produk", "fotoProdukError", sizeValidation.message);
        fileInput.value = "";
        return;
      }

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

      jQueryHelpers.clearError("foto_produk", "fotoProdukError");

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

    removePreview: function (fileInput, imagePreview, fileUploadWrapper, currentImageWrapper) {
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
  // ✅ MODUL MULTIPLE DOSEN SELECTION - FIXED VERSION
  // ============================================================
  const DosenSelectionModule = {
    selectedDosen: [],

    init: function () {
      const $dropdown = $("#customDropdownDosen");
      const $trigger = $("#dosenTrigger");
      const $menu = $("#dosenMenu");
      const $text = $("#dosenText");
      const $hiddenInput = $("#dosen_ids");
      const $badgesContainer = $("#selectedDosenBadges");

      if ($dropdown.length === 0 || $trigger.length === 0) {
        console.warn("Dosen dropdown elements not found");
        return;
      }

      // ✅ FIX: Toggle dropdown dengan class 'active' sesuai CSS
      $trigger.on("click", (e) => {
        e.stopPropagation();
        $dropdown.toggleClass("active");
      });

      // Tutup dropdown saat klik di luar
      $(document).on("click", (e) => {
        if (!$dropdown.is(e.target) && $dropdown.has(e.target).length === 0) {
          $dropdown.removeClass("active");
        }
      });

      // ✅ Handle klik item - ADDITIVE ONLY (seperti dosen/edit.js)
      $menu.on("click", ".custom-dropdown-item", (e) => {
        // Jangan proses jika sudah selected/disabled
        const $item = $(e.target).closest(".custom-dropdown-item");
        if ($item.hasClass("selected") || $item.hasClass("disabled")) {
          return;
        }

        const value = $item.attr("data-value");
        const text = $item.find(".item-text").text();

        // Tambah ke selection (ADDITIVE)
        this.selectedDosen.push({ id: value, name: text });

        // Mark item sebagai selected dan disabled
        $item.addClass("selected disabled");

        // Update display
        this.updateDisplay($hiddenInput, $text, $badgesContainer);
      });

      // ✅ Handle hapus badge - SATU-SATUNYA cara untuk unselect
      $badgesContainer.on("click", ".badge-remove-btn", (e) => {
        e.stopPropagation();

        const $removeBtn = $(e.target).closest(".badge-remove-btn");
        const id = $removeBtn.attr("data-id");

        // Hapus dari selectedDosen
        const index = this.selectedDosen.findIndex((d) => d.id === id);
        if (index !== -1) {
          this.selectedDosen.splice(index, 1);
        }

        // Hapus badge dari DOM
        $removeBtn.closest(".selected-badge").remove();

        // Re-enable item di dropdown
        const $menuItem = $menu.find(`.custom-dropdown-item[data-value="${id}"]`);
        if ($menuItem.length > 0) {
          $menuItem.removeClass("selected disabled");
        }

        // Update display
        this.updateDisplay($hiddenInput, $text, $badgesContainer);
      });

      // Load selected dosen dari edit mode (jika ada)
      this.loadSelectedDosenFromEdit();

      console.log("Dosen selection module initialized");
    },

    loadSelectedDosenFromEdit: function () {
      const hiddenInput = document.getElementById("dosen_ids");
      if (!hiddenInput || !hiddenInput.value) return;

      const selectedIds = hiddenInput.value.split(",").map((id) => parseInt(id.trim()));
      const $menu = $("#dosenMenu");

      selectedIds.forEach((id) => {
        const item = document.querySelector(`.custom-dropdown-item[data-id="${id}"]`);
        if (item) {
          const name = item.querySelector(".item-text").textContent;
          this.selectedDosen.push({ id: id.toString(), name });
          
          // Mark dropdown item as selected and disabled
          $(item).addClass("selected disabled");
        }
      });

      this.renderSelectedBadges();
      this.updateHiddenInput();
      this.updateDropdownText();

      console.log("Loaded selected dosen from edit mode:", this.selectedDosen);
    },

    updateDisplay: function ($hiddenInput, $text, $badgesContainer) {
      this.updateHiddenInput();
      this.renderSelectedBadges();
      this.updateDropdownText();
    },

    renderSelectedBadges: function () {
      const badgesContainer = document.getElementById("selectedDosenBadges");
      if (!badgesContainer) return;

      if (this.selectedDosen.length === 0) {
        badgesContainer.innerHTML = "";
        badgesContainer.style.display = "none";
        return;
      }

      badgesContainer.style.display = "flex";
      badgesContainer.innerHTML = this.selectedDosen
        .map(
          (dosen) => `
        <span class="selected-badge">
          ${dosen.name}
          <button type="button" class="badge-remove-btn" data-id="${dosen.id}">
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        </span>
      `
        )
        .join("");
    },

    updateHiddenInput: function () {
      const hiddenInput = document.getElementById("dosen_ids");
      if (!hiddenInput) return;

      const ids = this.selectedDosen.map((d) => d.id).join(",");
      hiddenInput.value = ids;
    },

    updateDropdownText: function () {
      const dosenText = document.getElementById("dosenText");
      if (dosenText) {
        if (this.selectedDosen.length === 0) {
          dosenText.textContent = "Pilih Dosen";
        } else {
          dosenText.textContent = `${this.selectedDosen.length} dosen dipilih`;
        }
      }
    },
  };

  // ============================================================
  // MODUL AUTHOR TYPE TOGGLE
  // ============================================================
  const AuthorTypeModule = {
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

      this.toggleAuthorFields();
    },

    toggleAuthorFields: function () {
      const authorTypeDosen = document.getElementById("author_type_dosen");
      const authorTypeMahasiswa = document.getElementById("author_type_mahasiswa");
      const authorTypeKolaborasi = document.getElementById("author_type_kolaborasi");
      const authorDosenWrapper = document.getElementById("author_dosen_wrapper");
      const authorMahasiswaWrapper = document.getElementById("author_mahasiswa_wrapper");
      const dosenIdsInput = document.getElementById("dosen_ids");
      const timMahasiswaInput = document.getElementById("tim_mahasiswa");

      if (authorTypeDosen && authorTypeDosen.checked) {
        authorDosenWrapper.style.display = "block";
        authorMahasiswaWrapper.style.display = "none";
        dosenIdsInput.required = true;
        timMahasiswaInput.required = false;
        timMahasiswaInput.value = "";
      } else if (authorTypeMahasiswa && authorTypeMahasiswa.checked) {
        authorDosenWrapper.style.display = "none";
        authorMahasiswaWrapper.style.display = "block";
        dosenIdsInput.required = false;
        timMahasiswaInput.required = true;
        dosenIdsInput.value = "";
        DosenSelectionModule.selectedDosen = [];
        DosenSelectionModule.renderSelectedBadges();
        DosenSelectionModule.updateDropdownText();
      } else if (authorTypeKolaborasi && authorTypeKolaborasi.checked) {
        authorDosenWrapper.style.display = "block";
        authorMahasiswaWrapper.style.display = "block";
        dosenIdsInput.required = true;
        timMahasiswaInput.required = true;
      }
    },
  };

  // ============================================================
  // MODUL SUBMIT FORM PRODUK (Create & Edit)
  // ============================================================
  const FormSubmissionModule = {
    init: function () {
      $("#formProduk").on("submit", (e) => {
        e.preventDefault();
        this.handleSubmit(e.currentTarget);
      });
    },

    handleSubmit: function (formElement) {
      const $form = $(formElement);

      const ajaxUrl = $form.data("ajax-url");
      const redirectUrl = $form.data("redirect-url");
      const successMessage = $form.data("success-message");

      jQueryHelpers.clearAllErrors("formProduk");

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
      const buttonState = jQueryHelpers.disableButton(
        submitButton.attr("id") || "submit-btn",
        "Menyimpan..."
      );

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
        nama_produk: $("#nama_produk").val().trim(),
        deskripsi: $("#deskripsi").val().trim(),
        link_produk: $("#link_produk").val().trim(),
        foto_produk: $("#foto_produk")[0].files[0] || null,
        author_type: $('input[name="author_type"]:checked').val(),
        dosen_ids: $("#dosen_ids").val() || null,
        tim_mahasiswa: $("#tim_mahasiswa").val().trim() || null,
        id: $("#id").val() || null,
        csrf_token: $('input[name="csrf_token"]').val() || null,
      };
    },

    validateFormData: function (data) {
      const errors = [];
      const isCreateMode = data.id === null;

      if (!data.csrf_token) {
        errors.push({
          fieldId: "csrf_token",
          errorId: "csrfError",
          message: "Token keamanan tidak ditemukan. Silakan refresh halaman.",
        });
      }

      const nameValidation = validationHelpers.validateName(data.nama_produk, 3, 255, "Nama produk");
      if (!nameValidation.valid) {
        errors.push({
          fieldId: "nama_produk",
          errorId: "namaProdukError",
          message: nameValidation.message,
        });
      }

      const deskripsiValidation = validationHelpers.validateText(data.deskripsi, 255, false);
      if (!deskripsiValidation.valid) {
        errors.push({
          fieldId: "deskripsi",
          errorId: "deskripsiError",
          message: deskripsiValidation.message,
        });
      }

      if (data.link_produk) {
        if (data.link_produk.length > 255) {
          errors.push({
            fieldId: "link_produk",
            errorId: "linkProdukError",
            message: "Link produk maksimal 255 karakter",
          });
        } else {
          const linkValidation = validationHelpers.validateUrl(data.link_produk);
          if (!linkValidation.valid) {
            errors.push({
              fieldId: "link_produk",
              errorId: "linkProdukError",
              message: linkValidation.message,
            });
          }
        }
      }

      if (isCreateMode && !data.foto_produk) {
        errors.push({
          fieldId: "foto_produk",
          errorId: "fotoProdukError",
          message: "Foto produk wajib diisi",
        });
      }

      if (data.foto_produk) {
        const sizeValidation = validationHelpers.validateFileSize(data.foto_produk, 2);
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

      if (!data.author_type) {
        errors.push({
          fieldId: "author_type",
          errorId: "authorTypeError",
          message: "Tipe author harus dipilih",
        });
      }

      if (data.author_type === "dosen") {
        if (!data.dosen_ids) {
          errors.push({
            fieldId: "dosen_ids",
            errorId: "dosenError",
            message: "Minimal pilih satu dosen",
          });
        }
      } else if (data.author_type === "mahasiswa") {
        if (!data.tim_mahasiswa) {
          errors.push({
            fieldId: "tim_mahasiswa",
            errorId: "timMahasiswaError",
            message: "Tim mahasiswa harus diisi",
          });
        } else {
          const mahasiswaValidation = validationHelpers.validateName(
            data.tim_mahasiswa,
            3,
            255,
            "Tim mahasiswa"
          );
          if (!mahasiswaValidation.valid) {
            errors.push({
              fieldId: "tim_mahasiswa",
              errorId: "timMahasiswaError",
              message: mahasiswaValidation.message,
            });
          }
        }
      } else if (data.author_type === "kolaborasi") {
        if (!data.dosen_ids) {
          errors.push({
            fieldId: "dosen_ids",
            errorId: "dosenError",
            message: "Minimal pilih satu dosen untuk kolaborasi",
          });
        }
        if (!data.tim_mahasiswa) {
          errors.push({
            fieldId: "tim_mahasiswa",
            errorId: "timMahasiswaError",
            message: "Tim mahasiswa harus diisi untuk kolaborasi",
          });
        } else {
          const mahasiswaValidation = validationHelpers.validateName(
            data.tim_mahasiswa,
            3,
            255,
            "Tim mahasiswa"
          );
          if (!mahasiswaValidation.valid) {
            errors.push({
              fieldId: "tim_mahasiswa",
              errorId: "timMahasiswaError",
              message: mahasiswaValidation.message,
            });
          }
        }
      }

      return errors;
    },

    prepareFormData: function (data) {
      const formData = new FormData();

      formData.append("csrf_token", data.csrf_token);
      formData.append("nama_produk", data.nama_produk);
      formData.append("deskripsi", data.deskripsi);
      formData.append("link_produk", data.link_produk);
      formData.append("author_type", data.author_type);

      if (data.dosen_ids) {
        formData.append("dosen_ids", data.dosen_ids);
      }

      if (data.tim_mahasiswa) {
        formData.append("tim_mahasiswa", data.tim_mahasiswa);
      }

      if (data.foto_produk) {
        formData.append("foto_produk", data.foto_produk);
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
    DosenSelectionModule.init();
    AuthorTypeModule.init();
    FormSubmissionModule.init();
  });
})();