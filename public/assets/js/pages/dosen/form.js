/**
 * File: pages/dosen/form.js
 * Deskripsi: Menangani interaksi form dosen (create & edit)
 *
 * Dependencies:
 * - jQuery
 * - Bootstrap 5
 * - jQueryHelpers.js
 * - validationHelpers.js
 *
 * Fitur:
 * - Upload file dengan preview (dukungan drag & drop)
 * - Custom dropdown untuk Jabatan (single selection)
 * - Custom dropdown untuk Keahlian (multiple selection dengan badges)
 * - Validasi dan submit form
 * - Operasi CRUD untuk Jabatan dan Keahlian
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
      const fileInput = document.getElementById("photo_profile");
      const imagePreview = document.getElementById("imagePreview");
      const previewImg = document.getElementById("previewImg");

      if (!fileUploadWrapper || !fileInput) return;

      // Klik untuk upload
      fileUploadWrapper.addEventListener("click", () => {
        fileInput.click();
      });

      // Event perubahan file input
      fileInput.addEventListener("change", (e) => {
        this.handleFileSelect(e.target.files[0], previewImg, imagePreview);
      });

      // Event drag and drop
      this.setupDragAndDrop(fileUploadWrapper, fileInput);
    },

    /**
     * Handle pemilihan file dan preview
     *
     * @param {File} file - File yang dipilih
     * @param {HTMLElement} previewImg - Element preview gambar
     * @param {HTMLElement} imagePreview - Element container preview
     */
    handleFileSelect: function (file, previewImg, imagePreview) {
      if (!file) return;

      // Validasi ukuran file (2MB)
      const sizeValidation = validationHelpers.validateFileSize(file, 2);
      if (!sizeValidation.valid) {
        alert(sizeValidation.message);
        document.getElementById("photo_profile").value = "";
        return;
      }

      // Validasi tipe file
      const typeValidation = validationHelpers.validateFileType(file, [
        "image/jpeg",
        "image/jpg",
        "image/png",
      ]);
      if (!typeValidation.valid) {
        alert(typeValidation.message);
        document.getElementById("photo_profile").value = "";
        return;
      }

      // Tampilkan preview
      const reader = new FileReader();
      reader.onload = (e) => {
        previewImg.src = e.target.result;
        imagePreview.style.display = "block";
      };
      reader.readAsDataURL(file);
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
  // MODUL CUSTOM DROPDOWN (Single Selection - Jabatan)
  // ============================================================

  const SingleDropdownModule = {
    /**
     * Inisialisasi dropdown single selection untuk Jabatan
     */
    init: function () {
      const dropdown = document.getElementById("customDropdownJabatan");
      const trigger = document.getElementById("jabatanTrigger");
      const menu = document.getElementById("jabatanMenu");
      const textElement = document.getElementById("jabatanText");
      const hiddenInput = document.getElementById("jabatan");

      if (!dropdown || !trigger || !menu) return;

      // Toggle dropdown
      trigger.addEventListener("click", (e) => {
        e.stopPropagation();
        dropdown.classList.toggle("active");
      });

      // Tutup dropdown saat klik di luar
      document.addEventListener("click", (e) => {
        if (!dropdown.contains(e.target)) {
          dropdown.classList.remove("active");
        }
      });

      // Pilih item
      menu.addEventListener("click", (e) => {
        this.handleItemClick(e, menu, hiddenInput, textElement, dropdown);
      });

      // Hapus jabatan
      menu.addEventListener("click", (e) => {
        this.handleDeleteClick(e, hiddenInput, textElement, menu);
      });
    },

    /**
     * Handle klik item (selection)
     *
     * @param {Event} e - Event klik
     * @param {HTMLElement} menu - Menu dropdown
     * @param {HTMLElement} hiddenInput - Hidden input untuk form submission
     * @param {HTMLElement} textElement - Element text display
     * @param {HTMLElement} dropdown - Container dropdown
     */
    handleItemClick: function (e, menu, hiddenInput, textElement, dropdown) {
      const item = e.target.closest(".custom-dropdown-item");

      // Jika klik pada item (bukan tombol delete)
      if (item && !e.target.closest(".item-delete-btn")) {
        const value = item.getAttribute("data-value");
        const text = item.querySelector(".item-text").textContent;

        // Update hidden input
        hiddenInput.value = value;

        // Update display text
        textElement.textContent = text;

        // Update status selected
        menu.querySelectorAll(".custom-dropdown-item").forEach((i) => {
          i.classList.remove("selected");
        });
        item.classList.add("selected");

        // Tutup dropdown
        dropdown.classList.remove("active");
      }
    },

    /**
     * Handle klik tombol delete
     *
     * @param {Event} e - Event klik
     * @param {HTMLElement} hiddenInput - Hidden input
     * @param {HTMLElement} textElement - Element text display
     * @param {HTMLElement} menu - Menu dropdown
     */
    handleDeleteClick: function (e, hiddenInput, textElement, menu) {
      const deleteBtn = e.target.closest(".item-delete-btn");

      if (deleteBtn) {
        e.stopPropagation();

        const id = deleteBtn.getAttribute("data-id");
        const name = deleteBtn.getAttribute("data-name");

        if (confirm(`Apakah Anda yakin ingin menghapus "${name}"?`)) {
          deleteBtn.disabled = true;

          jQueryHelpers.makeAjaxRequest({
            url: "/applied-informatics/dosen/delete-jabatan",
            method: "POST",
            data: { id: id },
            onSuccess: (response) => {
              if (response.success) {
                // Hapus item dari DOM
                const item = deleteBtn.closest(".custom-dropdown-item");
                item.remove();

                // Reset selection jika item yang dihapus sedang dipilih
                if (hiddenInput.value == id) {
                  hiddenInput.value = "";
                  textElement.textContent = "Pilih Jabatan";
                }

                // Tampilkan empty state jika tidak ada item tersisa
                if (
                  menu.querySelectorAll(".custom-dropdown-item").length === 0
                ) {
                  menu.innerHTML =
                    '<div class="custom-dropdown-empty">Belum ada jabatan</div>';
                }
              } else {
                alert(response.message || "Gagal menghapus jabatan");
                deleteBtn.disabled = false;
              }
            },
            onError: (errorMessage) => {
              alert("Error menghapus jabatan: " + errorMessage);
              deleteBtn.disabled = false;
            },
          });
        }
      }
    },

    /**
     * Tambah item jabatan baru ke dropdown
     *
     * @param {Object} data - Data jabatan {id, jabatan}
     */
    addItemToDropdown: function (data) {
      const menu = document.getElementById("jabatanMenu");
      const emptyState = menu.querySelector(".custom-dropdown-empty");

      // Hapus empty state jika ada
      if (emptyState) {
        emptyState.remove();
      }

      // Buat item baru
      const newItem = document.createElement("div");
      newItem.className = "custom-dropdown-item";
      newItem.setAttribute("data-value", data.id);
      newItem.setAttribute("data-id", data.id);
      newItem.innerHTML = `
                <span class="item-text">${data.jabatan}</span>
                <button type="button" class="item-delete-btn" data-id="${data.id}" data-name="${data.jabatan}" title="Hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </button>
            `;
      menu.appendChild(newItem);
    },
  };

  // Lanjutan di command berikutnya...

  // ============================================================
  // MODUL CUSTOM DROPDOWN (Multiple Selection - Keahlian)
  // ============================================================

  const MultipleDropdownModule = {
    selectedItems: [], // Simpan keahlian yang dipilih [{id, name}]

    /**
     * Inisialisasi dropdown multiple selection untuk Keahlian
     */
    init: function () {
      const dropdown = document.getElementById("customDropdownKeahlian");
      const trigger = document.getElementById("keahlianTrigger");
      const menu = document.getElementById("keahlianMenu");
      const textElement = document.getElementById("keahlianText");
      const hiddenInput = document.getElementById("keahlian");
      const badgesContainer = document.getElementById("selectedKeahlianBadges");

      if (!dropdown || !trigger || !menu) return;

      // Toggle dropdown
      trigger.addEventListener("click", (e) => {
        e.stopPropagation();
        dropdown.classList.toggle("active");
      });

      // Tutup dropdown saat klik di luar
      document.addEventListener("click", (e) => {
        if (!dropdown.contains(e.target)) {
          dropdown.classList.remove("active");
        }
      });

      // Pilih/batalkan pilih item
      menu.addEventListener("click", (e) => {
        this.handleItemClick(
          e,
          menu,
          hiddenInput,
          textElement,
          badgesContainer
        );
      });

      // Hapus keahlian dari database
      menu.addEventListener("click", (e) => {
        this.handleDeleteClick(
          e,
          hiddenInput,
          textElement,
          badgesContainer,
          menu
        );
      });

      // Hapus badge
      badgesContainer.addEventListener("click", (e) => {
        this.handleBadgeRemove(
          e,
          menu,
          hiddenInput,
          textElement,
          badgesContainer
        );
      });
    },

    /**
     * Handle klik item (toggle selection)
     */
    handleItemClick: function (
      e,
      menu,
      hiddenInput,
      textElement,
      badgesContainer
    ) {
      const item = e.target.closest(".custom-dropdown-item");

      if (item && !e.target.closest(".item-delete-btn")) {
        const value = item.getAttribute("data-value");
        const text = item.querySelector(".item-text").textContent;

        // Cek apakah sudah dipilih
        const index = this.selectedItems.findIndex((k) => k.id === value);

        if (index === -1) {
          // Tambah ke selection
          this.selectedItems.push({ id: value, name: text });
          item.classList.add("selected");
        } else {
          // Hapus dari selection
          this.selectedItems.splice(index, 1);
          item.classList.remove("selected");
        }

        // Update display
        this.updateDisplay(hiddenInput, textElement, badgesContainer);
      }
    },

    /**
     * Handle klik tombol delete (hapus dari database)
     */
    handleDeleteClick: function (
      e,
      hiddenInput,
      textElement,
      badgesContainer,
      menu
    ) {
      const deleteBtn = e.target.closest(".item-delete-btn");

      if (deleteBtn) {
        e.stopPropagation();

        const id = deleteBtn.getAttribute("data-id");
        const name = deleteBtn.getAttribute("data-name");

        if (confirm(`Apakah Anda yakin ingin menghapus "${name}"?`)) {
          deleteBtn.disabled = true;

          jQueryHelpers.makeAjaxRequest({
            url: "/applied-informatics/dosen/delete-keahlian",
            method: "POST",
            data: { id: id },
            onSuccess: (response) => {
              alert(response.message)
              if (response.success) {
                const item = deleteBtn.closest(".custom-dropdown-item");
                item.remove();

                const index = this.selectedItems.findIndex((k) => k.id === id);
                if (index !== -1) {
                  this.selectedItems.splice(index, 1);
                  this.updateDisplay(hiddenInput, textElement, badgesContainer);
                }

                if (
                  menu.querySelectorAll(".custom-dropdown-item").length === 0
                ) {
                  menu.innerHTML =
                    '<div class="custom-dropdown-empty">Belum ada keahlian</div>';
                }
              } else {
                alert(response.message || "Gagal menghapus keahlian");
                deleteBtn.disabled = false;
              }
            },
            onError: (errorMessage) => {
              alert("Error menghapus keahlian: " + errorMessage);
              deleteBtn.disabled = false;
            },
          });
        }
      }
    },

    /**
     * Handle klik tombol hapus badge
     */
    handleBadgeRemove: function (
      e,
      menu,
      hiddenInput,
      textElement,
      badgesContainer
    ) {
      const removeBtn = e.target.closest(".badge-remove-btn");

      if (removeBtn) {
        const id = removeBtn.getAttribute("data-id");

        const index = this.selectedItems.findIndex((k) => k.id === id);
        if (index !== -1) {
          this.selectedItems.splice(index, 1);
        }

        const item = menu.querySelector(`[data-value="${id}"]`);
        if (item) {
          item.classList.remove("selected");
        }

        this.updateDisplay(hiddenInput, textElement, badgesContainer);
      }
    },

    /**
     * Update tampilan (badges, hidden input, trigger text)
     */
    updateDisplay: function (hiddenInput, textElement, badgesContainer) {
      badgesContainer.innerHTML = "";
      this.selectedItems.forEach((skill) => {
        const badge = document.createElement("div");
        badge.className = "selected-badge";
        badge.innerHTML = `
                    <span>${skill.name}</span>
                    <button type="button" class="badge-remove-btn" data-id="${skill.id}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                `;
        badgesContainer.appendChild(badge);
      });

      hiddenInput.value = this.selectedItems.map((k) => k.id).join(",");

      if (this.selectedItems.length === 0) {
        textElement.textContent = "Pilih Keahlian";
      } else {
        textElement.textContent = `${this.selectedItems.length} keahlian dipilih`;
      }
    },

    /**
     * Tambah item keahlian baru ke dropdown
     */
    addItemToDropdown: function (data) {
      const menu = document.getElementById("keahlianMenu");
      const emptyState = menu.querySelector(".custom-dropdown-empty");

      if (emptyState) {
        emptyState.remove();
      }

      const newItem = document.createElement("div");
      newItem.className = "custom-dropdown-item";
      newItem.setAttribute("data-value", data.id);
      newItem.setAttribute("data-id", data.id);
      newItem.innerHTML = `
                <span class="item-text">${data.keahlian}</span>
                <button type="button" class="item-delete-btn" data-id="${data.id}" data-name="${data.keahlian}" title="Hapus">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </button>
            `;
      menu.appendChild(newItem);
    },
  };

  // ============================================================
  // MODUL TAMBAH JABATAN
  // ============================================================

  const AddJabatanModule = {
    init: function () {
      $("#btn-add-new-jabatan").on("click", (e) => {
        e.preventDefault();
        this.handleSubmit();
      });
    },

    handleSubmit: function () {
      const jabatan = $("#newJabatan").val().trim();

      const validation = validationHelpers.validateName(jabatan, 2, 255);
      if (!validation.valid) {
        $("#jabatanError").text(validation.message).show();
        return;
      }

      $("#jabatanError").text("").hide();
      const buttonState = jQueryHelpers.disableButton(
        "btn-add-new-jabatan",
        "Menyimpan..."
      );

      jQueryHelpers.makeAjaxRequest({
        url: "/applied-informatics/dosen/create-jabatan",
        method: "POST",
        data: { jabatan: jabatan },
        onSuccess: (response) => {
          if (response.success) {
            SingleDropdownModule.addItemToDropdown(response.data);
            $("#newJabatan").val("");
            bootstrap.Modal.getInstance($("#modalAddJabatan")).hide();
          } else {
            $("#jabatanError")
              .text(response.message || "Gagal menambahkan jabatan")
              .show();
          }
        },
        onError: (errorMessage) => {
          $("#jabatanError")
            .text("Error: " + errorMessage)
            .show();
        },
        onComplete: () => {
          buttonState.enable();
        },
      });
    },
  };

  // ============================================================
  // MODUL TAMBAH KEAHLIAN
  // ============================================================

  const AddKeahlianModule = {
    init: function () {
      $("#btn-add-new-keahlian").on("click", (e) => {
        e.preventDefault();
        this.handleSubmit();
      });
    },

    handleSubmit: function () {
      const keahlian = $("#newKeahlian").val().trim();

      const validation = validationHelpers.validateName(keahlian, 1, 255);
      if (!validation.valid) {
        $("#keahlianError").text(validation.message).show();
        return;
      }

      $("#keahlianError").text("").hide();
      const buttonState = jQueryHelpers.disableButton(
        "btn-add-new-keahlian",
        "Menyimpan..."
      );

      jQueryHelpers.makeAjaxRequest({
        url: "/applied-informatics/dosen/create-keahlian",
        method: "POST",
        data: { keahlian: keahlian },
        onSuccess: (response) => {
          if (response.success) {
            MultipleDropdownModule.addItemToDropdown(response.data);
            $("#newKeahlian").val("");
            bootstrap.Modal.getInstance($("#modalAddKeahlian")).hide();
          } else {
            $("#keahlianError")
              .text(response.message || "Gagal menambahkan keahlian")
              .show();
          }
        },
        onError: (errorMessage) => {
          $("#keahlianError")
            .text("Error: " + errorMessage)
            .show();
        },
        onComplete: () => {
          buttonState.enable();
        },
      });
    },
  };

  // ============================================================
  // MODUL SUBMIT FORM
  // ============================================================

  const FormSubmissionModule = {
    init: function () {
      $("#btn-submit-create-dosen").on("click", (e) => {
        e.preventDefault();
        this.handleSubmit();
      });
    },

    handleSubmit: function () {
      const formData = this.getFormData();
      const validationErrors = this.validateFormData(formData);

      jQueryHelpers.clearAllErrors("formDosen");

      if (validationErrors.length > 0) {
        validationErrors.forEach((error) => {
          jQueryHelpers.showError(error.fieldId, error.errorId, error.message);
        });
        return;
      }

      const submitData = this.prepareFormData(formData);
      const buttonState = jQueryHelpers.disableButton(
        "btn-submit-create-dosen",
        "Menyimpan..."
      );

      jQueryHelpers.makeAjaxRequest({
        url: "/applied-informatics/dosen/create",
        method: "POST",
        data: submitData,
        processData: false,
        contentType: false,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert(
              "Data dosen berhasil ditambahkan!",
              "success"
            );
            setTimeout(() => {
              window.location.href = "/applied-informatics/dosen";
            }, 500);
          } else {
            jQueryHelpers.showAlert("Gagal: " + response.message, "danger");
            buttonState.enable();
          }
        },
        onError: (errorMessage) => {
          jQueryHelpers.showAlert("Error: " + errorMessage, "danger");
          buttonState.enable();
        },
      });
    },

    getFormData: function () {
      return {
        full_name: $("#full_name").val().trim(),
        email: $("#email").val().trim(),
        nidn: $("#nidn").val().trim(),
        jabatan_id: $("#jabatan").val().trim(),
        keahlian_ids: $("#keahlian").val().trim(),
        deskripsi: $("#deskripsi").val().trim(),
        foto_profil: $("#photo_profile")[0].files[0],
      };
    },

    validateFormData: function (data) {
      const errors = [];

      const nameValidation = validationHelpers.validateName(
        data.full_name,
        1,
        255
      );
      if (!nameValidation.valid) {
        errors.push({
          fieldId: "full_name",
          errorId: "fullNameError",
          message: nameValidation.message,
        });
      }

      const emailValidation = validationHelpers.validateEmail(data.email);
      if (!emailValidation.valid) {
        errors.push({
          fieldId: "email",
          errorId: "emailError",
          message: emailValidation.message,
        });
      }

      const nidnValidation = validationHelpers.validateNIDN(data.nidn, true);
      if (!nidnValidation.valid) {
        errors.push({
          fieldId: "nidn",
          errorId: "nidnError",
          message: nidnValidation.message,
        });
      }

      const jabatanValidation = validationHelpers.validateRequired(
        data.jabatan_id,
        "Jabatan"
      );
      if (!jabatanValidation.valid) {
        errors.push({
          fieldId: "jabatan",
          errorId: "jabatanError",
          message: jabatanValidation.message,
        });
      }

      const keahlianValidation = validationHelpers.validateMultipleSelection(
        data.keahlian_ids,
        "keahlian"
      );
      if (!keahlianValidation.valid) {
        errors.push({
          fieldId: "keahlian",
          errorId: "keahlianError",
          message: keahlianValidation.message,
        });
      }

      if (data.foto_profil) {
        const sizeValidation = validationHelpers.validateFileSize(
          data.foto_profil,
          2
        );
        if (!sizeValidation.valid) {
          errors.push({
            fieldId: "photo_profile",
            errorId: "photoError",
            message: sizeValidation.message,
          });
        }

        const typeValidation = validationHelpers.validateFileType(
          data.foto_profil,
          ["image/jpeg", "image/jpg", "image/png"]
        );
        if (!typeValidation.valid) {
          errors.push({
            fieldId: "photo_profile",
            errorId: "photoError",
            message: typeValidation.message,
          });
        }
      }

      return errors;
    },

    prepareFormData: function (data) {
      const formData = new FormData();
      formData.append("full_name", data.full_name);
      formData.append("email", data.email);
      formData.append("nidn", data.nidn);
      formData.append("jabatan_id", data.jabatan_id);
      formData.append("keahlian_ids", data.keahlian_ids);
      formData.append("deskripsi", data.deskripsi);

      if (data.foto_profil) {
        formData.append("foto_profil", data.foto_profil);
      }

      return formData;
    },
  };

  // ============================================================
  // INISIALISASI SEMUA MODUL
  // ============================================================

  $(document).ready(function () {
    FileUploadModule.init();
    SingleDropdownModule.init();
    MultipleDropdownModule.init();
    AddJabatanModule.init();
    AddKeahlianModule.init();
    FormSubmissionModule.init();
  });
})();
