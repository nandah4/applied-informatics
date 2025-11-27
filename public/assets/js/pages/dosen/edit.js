/**
 * File: pages/dosen/edit.js
 * Deskripsi: Menangani logic edit dosen dan dropdown keahlian
 *
 * Dependencies:
 * - jQuery
 * - Bootstrap 5
 * - jQueryHelpers.js
 * - validationHelpers.js
 *
 * Fitur:
 * - Custom dropdown keahlian dengan multiple selection (additive)
 * - Pre-populate data dosen yang akan diedit
 * - Pre-select Jabatan dan Keahlian
 * - Preview foto profil existing
 * - Handle submit form untuk update data
 */

(function () {
  "use strict";

  const BASE_URL =
    $('meta[name="base-url"]').attr("content") || "/applied-informatics";

  // ============================================================
  // MODUL JABATAN DROPDOWN (Single Selection)
  // ============================================================

  const JabatanDropdown = {
    init: function () {
      const $dropdown = $("#customDropdownJabatan");
      const $trigger = $("#jabatanTrigger");
      const $menu = $("#jabatanMenu");
      const $text = $("#jabatanText");
      const $hidden = $("#jabatan");

      if ($dropdown.length === 0 || $trigger.length === 0) return;

      // Toggle dropdown
      $trigger.on("click", (e) => {
        e.stopPropagation();
        $dropdown.toggleClass("active");
      });

      // Tutup saat klik di luar
      $(document).on("click", (e) => {
        if (!$dropdown.is(e.target) && $dropdown.has(e.target).length === 0) {
          $dropdown.removeClass("active");
        }
      });

      // Pilih item
      $menu.on("click", ".custom-dropdown-item", (e) => {
        if (!$(e.target).closest(".item-delete-btn").length) {
          this.handleSelect(e, $menu, $hidden, $text, $dropdown);
        }
      });

      // Hapus jabatan
      $menu.on("click", ".item-delete-btn", (e) => {
        e.stopPropagation();
        this.handleDelete(e, $hidden, $text, $menu);
      });
    },

    handleSelect: function (e, $menu, $hidden, $text, $dropdown) {
      const $item = $(e.target).closest(".custom-dropdown-item");
      const value = $item.attr("data-value");
      const textContent = $item.find(".item-text").text();

      $hidden.val(value);
      $text.text(textContent);

      $menu.find(".custom-dropdown-item").removeClass("selected");
      $item.addClass("selected");

      $dropdown.removeClass("active");
    },

    handleDelete: function (e, $hidden, $text, $menu) {
      const $btn = $(e.currentTarget);
      const id = $btn.attr("data-id");
      const name = $btn.attr("data-name");

      if (!confirm(`Hapus jabatan "${name}"?`)) return;

      $btn.prop("disabled", true);

      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/dosen/delete-jabatan`,
        method: "POST",
        data: { id: id },
        onSuccess: (response) => {
          if (response.success) {
            const $item = $btn.closest(".custom-dropdown-item");
            $item.remove();

            if ($menu.find(".custom-dropdown-item").length === 0) {
              $menu.html(
                '<div class="custom-dropdown-empty">Belum ada jabatan</div>'
              );
            }
          } else {
            alert(response.message || "Gagal menghapus");
            $btn.prop("disabled", false);
          }
        },
        onError: (msg) => {
          alert("Error: " + msg);
          $btn.prop("disabled", false);
        },
      });
    },

    addItem: function (data) {
      const $menu = $("#jabatanMenu");
      const $empty = $menu.find(".custom-dropdown-empty");

      if ($empty.length) $empty.remove();

      const $item = $("<div>", {
        class: "custom-dropdown-item",
        "data-value": data.id,
        "data-id": data.id,
      }).html(`
        <span class="item-text">${data.nama_jabatan}</span>
        <button type="button" class="item-delete-btn" data-id="${data.id}" data-name="${data.nama_jabatan}" title="Hapus">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
        </button>
      `);

      $menu.append($item);
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

      $("#jabatanError").hide();
      const btnState = jQueryHelpers.disableButton(
        "btn-add-new-jabatan",
        "Menyimpan..."
      );

      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/dosen/create-jabatan`,
        method: "POST",
        data: { jabatan: jabatan },
        onSuccess: (response) => {
          if (response.success) {
            JabatanDropdown.addItem(response.data);
            $("#newJabatan").val("");
            bootstrap.Modal.getInstance($("#modalAddJabatan")[0]).hide();
            jQueryHelpers.showAlert(
              "Jabatan baru berhasil ditambahkan!",
              "success"
            );
          } else {
            $("#jabatanError")
              .text(response.message || "Gagal")
              .show();
          }
        },
        onError: (msg) => {
          $("#jabatanError")
            .text("Error: " + msg)
            .show();
        },
        onComplete: () => {
          btnState.enable();
        },
      });
    },
  };

  // ============================================================
  // MODUL KEAHLIAN DROPDOWN (Multiple Selection - Additive)
  // ============================================================

  const KeahlianDropdown = {
    selectedItems: [], // Array of {id, name}

    /**
     * Inisialisasi dropdown untuk edit mode
     */
    init: function () {
      const $dropdown = $("#customDropdownKeahlian");
      const $trigger = $("#keahlianTrigger");
      const $menu = $("#keahlianMenu");
      const $textElement = $("#keahlianText");
      const $hiddenInput = $("#keahlian");
      const $badgesContainer = $("#selectedKeahlianBadges");

      if (
        $dropdown.length === 0 ||
        $trigger.length === 0 ||
        $menu.length === 0
      ) {
        console.warn("Keahlian dropdown elements not found");
        return;
      }

      // Toggle dropdown
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

      // Handle klik item - ADDITIVE ONLY
      $menu.on("click", (e) => {
        this.handleItemClick(
          e,
          $menu,
          $hiddenInput,
          $textElement,
          $badgesContainer
        );
      });

      // Handle hapus badge
      $badgesContainer.on("click", (e) => {
        this.handleBadgeRemove(
          e,
          $menu,
          $hiddenInput,
          $textElement,
          $badgesContainer
        );
      });

      console.log("Keahlian dropdown initialized");
    },

    /**
     * Handle klik item - ADDITIVE ONLY
     */
    handleItemClick: function (
      e,
      $menu,
      $hiddenInput,
      $textElement,
      $badgesContainer
    ) {
      const $item = $(e.target).closest(".custom-dropdown-item");

      // Hanya proses jika klik pada item (bukan tombol delete) dan item belum dipilih
      if (
        $item.length > 0 &&
        $(e.target).closest(".item-delete-btn").length === 0
      ) {
        const value = $item.attr("data-value");
        const text = $item.find(".item-text").text();

        // Cek apakah item sudah dipilih
        const alreadySelected =
          this.selectedItems.findIndex((k) => k.id === value) !== -1;

        // Jika sudah dipilih, tidak perlu melakukan apa-apa
        if (alreadySelected) {
          return;
        }

        // Tambah ke selection (ADDITIVE)
        this.selectedItems.push({ id: value, name: text });

        // Mark item sebagai selected dan disable
        $item.addClass("selected disabled");

        // Update display
        this.updateDisplay($hiddenInput, $textElement, $badgesContainer);
      }
    },

    /**
     * Handle hapus badge - SATU-SATUNYA cara untuk unselect keahlian
     */
    handleBadgeRemove: function (
      e,
      $menu,
      $hiddenInput,
      $textElement,
      $badgesContainer
    ) {
      const $removeBtn = $(e.target).closest(".badge-remove-btn");

      if ($removeBtn.length > 0) {
        e.stopPropagation();

        const id = $removeBtn.attr("data-id");

        // Hapus dari selectedItems
        const index = this.selectedItems.findIndex((k) => k.id === id);
        if (index !== -1) {
          this.selectedItems.splice(index, 1);
        }

        // Hapus badge dari DOM
        $removeBtn.closest(".selected-badge").remove();

        // Re-enable item di dropdown
        const $menuItem = $menu.find(
          `.custom-dropdown-item[data-value="${id}"]`
        );
        if ($menuItem.length > 0) {
          $menuItem.removeClass("selected disabled");
        }

        // Update display
        this.updateDisplay($hiddenInput, $textElement, $badgesContainer);
      }
    },

    /**
     * Handle delete keahlian dari database (tombol trash di dropdown)
     */
    handleDeleteFromDatabase: function (
      id,
      name,
      $deleteBtn,
      $hiddenInput,
      $textElement,
      $badgesContainer,
      $menu
    ) {
      if (
        confirm(
          `Apakah Anda yakin ingin menghapus keahlian "${name}" dari database?\n\nCatatan: Ini akan menghapus keahlian dari sistem, bukan hanya dari dosen ini.`
        )
      ) {
        $deleteBtn.prop("disabled", true);

        jQueryHelpers.makeAjaxRequest({
          url: `${BASE_URL}/admin/dosen/delete-keahlian`,
          method: "POST",
          data: { id: id },
          onSuccess: (response) => {
            if (response.success) {
              // Hapus dari selectedItems jika sedang dipilih
              const index = this.selectedItems.findIndex((k) => k.id === id);
              if (index !== -1) {
                this.selectedItems.splice(index, 1);
              }

              // Hapus badge jika ada
              const $badge = $badgesContainer.find(
                `.badge-remove-btn[data-id="${id}"]`
              );
              if ($badge.length > 0) {
                $badge.closest(".selected-badge").remove();
              }

              // Hapus item dari dropdown
              const $item = $deleteBtn.closest(".custom-dropdown-item");
              $item.remove();

              // Tampilkan empty state jika tidak ada item tersisa
              if ($menu.find(".custom-dropdown-item").length === 0) {
                $menu.html(
                  '<div class="custom-dropdown-empty">Belum ada keahlian</div>'
                );
              }

              // Update display
              this.updateDisplay($hiddenInput, $textElement, $badgesContainer);

              jQueryHelpers.showAlert(
                "Keahlian berhasil dihapus dari database",
                "success",
                3000
              );
            } else {
              alert(response.message || "Gagal menghapus keahlian");
              $deleteBtn.prop("disabled", false);
            }
          },
          onError: (errorMessage) => {
            alert("Error menghapus keahlian: " + errorMessage);
            $deleteBtn.prop("disabled", false);
          },
        });
      }
    },

    /**
     * Update display (badges, hidden input, text)
     */
    updateDisplay: function ($hiddenInput, $textElement, $badgesContainer) {
      // Update hidden input
      const ids = this.selectedItems.map((k) => k.id);
      $hiddenInput.val(ids.join(","));

      // Update badges
      this.updateBadges($badgesContainer);
    },

    /**
     * Update badges display
     */
    updateBadges: function ($badgesContainer) {
      $badgesContainer.empty();

      this.selectedItems.forEach((item) => {
        const $badge = $("<div>", { class: "selected-badge" }).html(`
          <span>${item.name}</span>
          <button type="button" class="badge-remove-btn" data-id="${item.id}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        `);
        $badgesContainer.append($badge);
      });
    },

    /**
     * Pre-select keahlian dari data dosen
     * @param {Array} keahlianList - Array of {id, keahlian}
     */
    preselectKeahlian: function (keahlianList) {
      if (!keahlianList || keahlianList.length === 0) {
        return;
      }

      const $menu = $("#keahlianMenu");
      const $hiddenInput = $("#keahlian");
      const $textElement = $("#keahlianText");
      const $badgesContainer = $("#selectedKeahlianBadges");

      // Clear current selection
      this.selectedItems = [];

      // Populate keahlian dari data
      keahlianList.forEach((k) => {
        this.selectedItems.push({
          id: k.id.toString(),
          name: k.keahlian,
        });

        // Mark dropdown item as selected and disabled
        const $menuItem = $menu.find(
          `.custom-dropdown-item[data-value="${k.id}"]`
        );
        if ($menuItem.length > 0) {
          $menuItem.addClass("selected disabled");
        }
      });

      // Update display
      this.updateDisplay($hiddenInput, $textElement, $badgesContainer);

      console.log("Preselected keahlian:", this.selectedItems);
    },

    /**
     * Tambah keahlian baru ke dropdown (dari modal)
     * @param {Object} data - {id, keahlian}
     */
    addItemToDropdown: function (data) {
      const $menu = $("#keahlianMenu");
      const $emptyState = $menu.find(".custom-dropdown-empty");

      // Hapus empty state jika ada
      if ($emptyState.length > 0) {
        $emptyState.remove();
      }

      // Buat item baru
      const $newItem = $("<div>", {
        class: "custom-dropdown-item",
        "data-value": data.id,
        "data-id": data.id,
      }).html(`
        <span class="item-text">${data.nama_keahlian}</span>
        <button type="button" class="item-delete-btn" data-id="${data.id}" data-name="${data.nama_keahlian}" title="Hapus dari database">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
        </button>
      `);
      $menu.append($newItem);

      console.log("Added new keahlian to dropdown:", data);
    },
  };

  // ============================================================
  // MODUL FILE UPLOAD
  // ============================================================

  const FileUploadModule = {
    init: function () {
      const $wrapper = $("#fileUploadWrapper");
      const $input = $("#foto_profil");
      const $preview = $("#imagePreview");
      const $previewImg = $("#previewImg");

      if ($wrapper.length === 0 || $input.length === 0) return;

      // Klik untuk upload
      $wrapper.on("click", () => {
        $input.trigger("click");
      });

      // Event perubahan file
      $input.on("change", (e) => {
        this.handleFileSelect(e.target.files[0], $previewImg, $preview);
      });

      // Drag and drop
      this.setupDragAndDrop($wrapper, $input);
    },

    handleFileSelect: function (file, $previewImg, $preview) {
      if (!file) return;

      // Validasi ukuran (2MB)
      const sizeValidation = validationHelpers.validateFileSize(file, 2);
      if (!sizeValidation.valid) {
        alert(sizeValidation.message);
        $("#foto_profil").val("");
        return;
      }

      // Validasi tipe
      const typeValidation = validationHelpers.validateFileType(file, [
        "image/jpeg",
        "image/jpg",
        "image/png",
      ]);
      if (!typeValidation.valid) {
        alert(typeValidation.message);
        $("#foto_profil").val("");
        return;
      }

      // Preview
      const reader = new FileReader();
      reader.onload = (e) => {
        $previewImg.attr("src", e.target.result);
        $preview.show();
      };
      reader.readAsDataURL(file);
    },

    setupDragAndDrop: function ($wrapper, $input) {
      $wrapper.on("dragover", (e) => {
        e.preventDefault();
        $wrapper.addClass("dragover");
      });

      $wrapper.on("dragleave", () => {
        $wrapper.removeClass("dragover");
      });

      $wrapper.on("drop", (e) => {
        e.preventDefault();
        $wrapper.removeClass("dragover");

        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
          $input[0].files = files;
          $input.trigger("change");
        }
      });
    },
  };

  // ============================================================
  // MODUL EDIT MODE INITIALIZATION
  // ============================================================

  const EditModeModule = {
    /**
     * Inisialisasi edit mode
     */
    init: function () {
      JabatanDropdown.init();
      AddJabatanModule.init();
      KeahlianDropdown.init();
      FileUploadModule.init();
      this.preselectJabatan();
      this.preselectKeahlian();
      this.showExistingPhoto();
      this.setupFormSubmit();
      this.setupModalAddKeahlian();
      this.setupDeleteKeahlianHandlers();
      this.setUpModalAddProfilPublikasi();
    },

    /**
     * Pre-select jabatan berdasarkan data dosen
     */
    preselectJabatan: function () {
      const data = window.DOSEN_DATA;

      if (data.jabatan_id && data.jabatan_name) {
        $("#jabatanText").text(data.jabatan_name);

        // Mark dropdown item as selected
        $(
          `#jabatanMenu .custom-dropdown-item[data-value="${data.jabatan_id}"]`
        ).addClass("selected");
      }
    },

    /**
     * Pre-select keahlian berdasarkan data dosen
     * Mengkonversi keahlian_list (string) menjadi array untuk dropdown
     */
    preselectKeahlian: function () {
      const data = window.DOSEN_DATA;

      if (!data.keahlian_list || data.keahlian_list.trim() === "") {
        return;
      }

      // Parse keahlian_list string menjadi array (lowercase untuk matching)
      const keahlianNames = data.keahlian_list
        .split(", ")
        .map((name) => name.trim().toLowerCase());
      const $menu = $("#keahlianMenu");
      const keahlianArray = [];

      // Match nama keahlian dengan item di dropdown untuk mendapatkan ID
      $menu.find(".custom-dropdown-item").each(function () {
        const $item = $(this);
        const itemText = $item.find(".item-text").text().trim();

        if (keahlianNames.includes(itemText.toLowerCase())) {
          keahlianArray.push({
            id: $item.attr("data-value"),
            keahlian: itemText,
          });
        }
      });

      if (keahlianArray.length > 0) {
        KeahlianDropdown.preselectKeahlian(keahlianArray);
      }
    },

    /**
     * Tampilkan preview foto profil yang sudah ada
     */
    showExistingPhoto: function () {
      const data = window.DOSEN_DATA;

      if (data.foto_profil) {
        const $imagePreview = $("#imagePreview");
        const $previewImg = $("#previewImg");

        // Set src dengan base_url dari PHP
        const photoUrl = window.BASE_URL
          ? `${window.BASE_URL}/uploads/dosen/${data.foto_profil}`
          : `/applied-informatics/uploads/dosen/${data.foto_profil}`;

        $previewImg.attr("src", photoUrl);
        $imagePreview.show();
      }
    },

    /**
     * Setup form submit handler untuk edit mode
     */
    setupFormSubmit: function () {
      const self = this;

      // Override form submit untuk edit mode
      $("#btn-submit-update-dosen").on("click", function (e) {
        e.preventDefault();
        self.handleFormSubmit();
      });
    },

    /**
     * Handle submit form edit dosen
     */
    handleFormSubmit: function () {
      // Clear all errors
      jQueryHelpers.clearAllErrors("formDosen");

      // Get form data
      const formData = this.getFormData();

      // Validasi
      const errors = this.validateFormData(formData);

      // Show errors
      if (errors.length > 0) {
        errors.forEach((error) => {
          jQueryHelpers.showError(error.fieldId, error.errorId, error.message);
        });
        return;
      }

      // Prepare FormData untuk submit
      const submitData = this.prepareSubmitData(formData);

      // Disable button
      const buttonState = jQueryHelpers.disableButton(
        "btn-submit-update-dosen",
        "Menyimpan..."
      );

      // Submit AJAX
      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/dosen/update`,
        method: "POST",
        data: submitData,
        processData: false,
        contentType: false,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert("Data dosen berhasil diupdate!", "success");
            setTimeout(() => {
              window.location.href = `${BASE_URL}/admin/dosen`;
            }, 1500);
          } else {
            jQueryHelpers.showAlert(
              response.message || "Gagal update data",
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

    /**
     * Get data dari form
     */
    getFormData: function () {
      return {
        id: window.DOSEN_DATA.id,
        full_name: $("#full_name").val().trim(),
        email: $("#email").val().trim(),
        nidn: $("#nidn").val().trim(),
        jabatan_id: $("#jabatan").val().trim(),
        keahlian_ids: $("#keahlian").val().trim(),
        deskripsi: $("#deskripsi").val().trim(),
        foto_profil: $("#foto_profil")[0]?.files[0] || null,
      };
    },

    /**
     * Validasi data form
     */
    validateFormData: function (data) {
      const errors = [];

      // Validasi nama
      const nameValidation = validationHelpers.validateName(
        data.full_name,
        3,
        255
      );
      if (!nameValidation.valid) {
        errors.push({
          fieldId: "full_name",
          errorId: "fullNameError",
          message: nameValidation.message,
        });
      }

      // Validasi email
      const emailValidation = validationHelpers.validateEmail(data.email);
      if (!emailValidation.valid) {
        errors.push({
          fieldId: "email",
          errorId: "emailError",
          message: emailValidation.message,
        });
      }

      // Validasi NIDN
      const nidnValidation = validationHelpers.validateNIDN(data.nidn, true);
      if (!nidnValidation.valid) {
        errors.push({
          fieldId: "nidn",
          errorId: "nidnError",
          message: nidnValidation.message,
        });
      }

      // Validasi jabatan
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

      // Validasi keahlian
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

      // Validasi file (jika ada file baru)
      if (data.foto_profil) {
        const sizeValidation = validationHelpers.validateFileSize(
          data.foto_profil,
          2
        );
        if (!sizeValidation.valid) {
          errors.push({
            fieldId: "foto_profil",
            errorId: "fotoProfilError",
            message: sizeValidation.message,
          });
        }

        const typeValidation = validationHelpers.validateFileType(
          data.foto_profil,
          ["image/jpeg", "image/jpg", "image/png"]
        );
        if (!typeValidation.valid) {
          errors.push({
            fieldId: "foto_profil",
            errorId: "fotoProfilError",
            message: typeValidation.message,
          });
        }
      }

      return errors;
    },

    /**
     * Prepare data untuk submit
     */
    prepareSubmitData: function (data) {
      const formData = new FormData();

      // Tambahkan CSRF token
      const csrfToken = $('input[name="csrf_token"]').val();
      if (csrfToken) {
        formData.append("csrf_token", csrfToken);
      }

      formData.append("id", data.id);
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

    /**
     * Setup handlers untuk modal Tambah Keahlian Baru
     */
    setupModalAddKeahlian: function () {
      const $btnAddKeahlian = $("#btn-add-new-keahlian");
      const $newKeahlianInput = $("#newKeahlian");
      const $modalKeahlian = $("#modalAddKeahlian");

      if ($btnAddKeahlian.length > 0 && $newKeahlianInput.length > 0) {
        $btnAddKeahlian.on("click", () => {
          const keahlianName = $newKeahlianInput.val().trim();

          if (!keahlianName) {
            alert("Nama keahlian tidak boleh kosong");
            return;
          }

          // Disable button
          $btnAddKeahlian.prop("disabled", true).text("Menyimpan...");

          // Submit ke server
          jQueryHelpers.makeAjaxRequest({
            url: `${BASE_URL}/admin/dosen/create-keahlian`,
            method: "POST",
            data: { keahlian: keahlianName },
            onSuccess: (response) => {
              if (response.success) {
                // Tambahkan ke dropdown
                KeahlianDropdown.addItemToDropdown(response.data);

                // Reset input dan tutup modal
                $newKeahlianInput.val("");
                bootstrap.Modal.getInstance($modalKeahlian[0]).hide();

                jQueryHelpers.showAlert(
                  "Keahlian baru berhasil ditambahkan",
                  "success",
                  3000
                );
              } else {
                alert(response.message || "Gagal menambahkan keahlian");
              }

              // Re-enable button
              $btnAddKeahlian.prop("disabled", false).text("Tambah");
            },
            onError: (errorMessage) => {
              alert("Error: " + errorMessage);
              $btnAddKeahlian.prop("disabled", false).text("Tambah");
            },
          });
        });
      }
    },

    /**
     * Setup handlers untuk delete keahlian dari database
     */
    setupDeleteKeahlianHandlers: function () {
      const $menu = $("#keahlianMenu");
      const $hiddenInput = $("#keahlian");
      const $textElement = $("#keahlianText");
      const $badgesContainer = $("#selectedKeahlianBadges");

      // Delegated event untuk tombol delete keahlian
      $menu.on("click", ".item-delete-btn", (e) => {
        e.stopPropagation();

        const $deleteBtn = $(e.currentTarget);
        const id = $deleteBtn.attr("data-id");
        const name = $deleteBtn.attr("data-name");

        KeahlianDropdown.handleDeleteFromDatabase(
          id,
          name,
          $deleteBtn,
          $hiddenInput,
          $textElement,
          $badgesContainer,
          $menu
        );
      });
    },

    /**
     * Setup handlers untuk modal Tambah Profil Publikasi Baru
     */

    setUpModalAddProfilPublikasi: function () {
      const csrfToken = $('input[name="csrf_token"]').val();
      const id = window.DOSEN_DATA.id;

      const btnAddProfilPublikasi = $("#btn-add-new-profil-publikasi");
      const modalProfilPublikasi = $("#modalAddProfilPublikasi");
      const messageErrorUrl = $("#linkPublikasiError");
      const messageSelectedTipePublikasi = $("#selectedTipePublikasiError");

      // Reset state saat modal ditutup
      modalProfilPublikasi.on("hidden.bs.modal", function () {
        btnAddProfilPublikasi.prop("disabled", false).text("Tambah");
        $("#newProfilPublikasi").val("");
        $("#tipeProfilPublikasi").val("");
        messageErrorUrl.hide();
        messageSelectedTipePublikasi.hide();
      });

      btnAddProfilPublikasi.on("click", (e) => {
        const urlProfil = $("#newProfilPublikasi").val().trim();
        const selectedTipeProfil = $("#tipeProfilPublikasi").val().trim();

        const validateUrl = validationHelpers.validateUrl(urlProfil, true);
        if (!validateUrl.valid) {
          messageErrorUrl.text(validateUrl.message).show();
          return;
        }

        messageErrorUrl.text("").hide();

        if (selectedTipeProfil === "") {
          messageSelectedTipePublikasi
            .text("Pilih tipe profil publikasi!")
            .show();
          return;
        }

        messageSelectedTipePublikasi.hide();

        // Disable button
        btnAddProfilPublikasi.prop("disabled", true).text("Menyimpan...");

        // Submit ke server
        jQueryHelpers.makeAjaxRequest({
          url: `${BASE_URL}/admin/dosen/${id}/profil-publikasi/create`,
          method: "POST",
          data: {
            tipe: selectedTipeProfil,
            url_profil: urlProfil,
            csrf_token: csrfToken,
          },
          onSuccess: (response) => {
            // Re-enable button
            btnAddProfilPublikasi.prop("disabled", false).text("Tambah");

            if (response.success) {
              bootstrap.Modal.getInstance(modalProfilPublikasi[0]).hide();

              jQueryHelpers.showAlert(
                "Profil publikasi baru berhasil ditambahkan",
                "success",
                3000
              );

              setTimeout(() => {
                window.location.reload();
              }, 500);
            } else {
              jQueryHelpers.showAlert(
                response.message || "Gagal menambahkan keahlian",
                "danger",
                3000
              );
            }
          },
          onError: (errorMessage) => {
            alert("Error: " + errorMessage);
            btnAddProfilPublikasi.prop("disabled", false).text("Tambah");
          },
        });
      });
    },

    /**
     * Setup handlers untuk modal Edit Profil Publikasi
     */
    setUpModalEditProfilPublikasi: function () {
      const csrfToken = $('input[name="csrf_token"]').val();
      const btnUpdate = $("#btn-update-profil-publikasi");
      const modalEdit = $("#modalEditProfilPublikasi");
      const errorUrl = $("#editProfilUrlError");

      // Reset saat modal ditutup
      modalEdit.on("hidden.bs.modal", function () {
        btnUpdate.prop("disabled", false).text("Update");
        $("#editProfilUrl").val("");
        $("#editProfilId").val("");
        $("#editProfilTipe").val("");
        errorUrl.hide();
      });

      // Handle update
      btnUpdate.on("click", function () {
        const id = $("#editProfilId").val();
        const urlProfil = $("#editProfilUrl").val().trim();

        // Validasi URL
        const validateUrl = validationHelpers.validateUrl(urlProfil, true);
        if (!validateUrl.valid) {
          errorUrl.text(validateUrl.message).show();
          return;
        }
        errorUrl.hide();

        // Disable button
        btnUpdate.prop("disabled", true).text("Menyimpan...");

        // Submit ke server
        jQueryHelpers.makeAjaxRequest({
          url: `${BASE_URL}/admin/dosen/profil-publikasi/update`,
          method: "POST",
          data: {
            id: id,
            url_profil: urlProfil,
            csrf_token: csrfToken,
          },
          onSuccess: (response) => {
            btnUpdate.prop("disabled", false).text("Update");

            if (response.success) {
              bootstrap.Modal.getInstance(modalEdit[0]).hide();
              jQueryHelpers.showAlert(
                "Profil publikasi berhasil diupdate",
                "success",
                2000
              );
              setTimeout(() => window.location.reload(), 500);
            } else {
              jQueryHelpers.showAlert(
                response.message || "Gagal mengupdate profil publikasi",
                "danger",
                3000
              );
            }
          },
          onError: (errorMessage) => {
            btnUpdate.prop("disabled", false).text("Update");
            jQueryHelpers.showAlert("Terjadi kesalahan: " + errorMessage, "danger", 3000);
          },
        });
      });
    },
  };

  // ============================================================
  // GLOBAL FUNCTIONS - Edit & Delete Profil Publikasi
  // ============================================================

  /**
   * Buka modal edit profil publikasi
   * @param {number} id - ID profil publikasi
   * @param {string} url - URL profil saat ini
   * @param {string} tipe - Tipe profil (untuk display)
   */
  window.editProfilPublikasi = function (id, url, tipe) {
    $("#editProfilId").val(id);
    $("#editProfilUrl").val(url);
    $("#editProfilTipe").val(tipe);
    $("#modalEditProfilPublikasi").modal("show");
  };

  /**
   * Hapus profil publikasi
   * @param {number} id - ID profil publikasi
   */
  window.deleteProfilPublikasi = function (id) {
    if (!confirm("Apakah Anda yakin ingin menghapus profil publikasi ini?")) {
      return;
    }

    const csrfToken = $('input[name="csrf_token"]').val();

    jQueryHelpers.makeAjaxRequest({
      url: `${BASE_URL}/admin/dosen/profil-publikasi/delete/${id}`,
      method: "POST",
      data: { csrf_token: csrfToken },
      onSuccess: (response) => {
        if (response.success) {
          jQueryHelpers.showAlert(
            "Profil publikasi berhasil dihapus",
            "success",
            2000
          );
          setTimeout(() => window.location.reload(), 500);
        } else {
          jQueryHelpers.showAlert(
            response.message || "Gagal menghapus profil publikasi",
            "danger",
            3000
          );
        }
      },
      onError: (errorMessage) => {
        jQueryHelpers.showAlert(
          "Terjadi kesalahan: " + errorMessage,
          "danger",
          3000
        );
      },
    });
  };

  // ============================================================
  // INISIALISASI
  // ============================================================

  $(document).ready(function () {
    EditModeModule.init();
    EditModeModule.setUpModalEditProfilPublikasi();
  });
})();
