/**
 * File: pages/dosen/form.js
 * Deskripsi: Menangani interaksi form create dosen
 *
 * Dependencies:
 * - jQuery
 * - Bootstrap 5
 * - jQueryHelpers.js
 * - validationHelpers.js
 *
 * Fitur:
 * - Upload file dengan preview (drag & drop)
 * - Custom dropdown Jabatan (single selection)
 * - Custom dropdown Keahlian (multiple selection)
 * - CRUD Jabatan dan Keahlian
 * - Validasi dan submit form
 */

(function () {
  "use strict";

  const BASE_URL = $('meta[name="base-url"]').attr("content");

  // ============================================================
  // MODUL FILE UPLOAD
  // ============================================================

  const FileUploadModule = {
    init: function () {
      const $wrapper = $("#fileUploadWrapper");
      const $input = $("#photo_profile");
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
        $("#photo_profile").val("");
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
        $("#photo_profile").val("");
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
  // MODUL DROPDOWN JABATAN (Single Selection)
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
      const textContent = $item.find(".item-text").text()

      $hidden.val(value);
      $text.text(textContent)

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
              $menu.html('<div class="custom-dropdown-empty">Belum ada jabatan</div>');
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
  // MODUL DROPDOWN KEAHLIAN (Multiple Selection)
  // ============================================================

  const KeahlianDropdown = {
    selectedItems: [],

    init: function () {
      const $dropdown = $("#customDropdownKeahlian");
      const $trigger = $("#keahlianTrigger");
      const $menu = $("#keahlianMenu");
      const $text = $("#keahlianText");
      const $hidden = $("#keahlian");
      const $badges = $("#selectedKeahlianBadges");

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
          this.handleSelect(e, $menu, $hidden, $text, $badges);
        }
      });

      // Hapus dari database
      $menu.on("click", ".item-delete-btn", (e) => {
        e.stopPropagation();
        this.handleDelete(e, $hidden, $text, $badges, $menu);
      });

      // Hapus badge
      $badges.on("click", ".badge-remove-btn", (e) => {
        this.handleBadgeRemove(e, $menu, $hidden, $text, $badges);
      });
    },

    handleSelect: function (e, $menu, $hidden, $text, $badges) {
      const $item = $(e.target).closest(".custom-dropdown-item");
      const value = $item.attr("data-value");
      const text = $item.find(".item-text").text();

      // Cek sudah dipilih
      if (this.selectedItems.find((k) => k.id === value)) return;

      this.selectedItems.push({ id: value, name: text });
      $item.addClass("selected disabled");

      this.updateDisplay($hidden, $text, $badges);
    },

    handleDelete: function (e, $hidden, $text, $badges, $menu) {
      const $btn = $(e.currentTarget);
      const id = $btn.attr("data-id");
      const name = $btn.attr("data-name");

      if (!confirm(`Hapus keahlian "${name}"?`)) return;

      $btn.prop("disabled", true);

      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/dosen/delete-keahlian`,
        method: "POST",
        data: { id: id },
        onSuccess: (response) => {
          if (response.success) {
            $btn.closest(".custom-dropdown-item").remove();

            const idx = this.selectedItems.findIndex((k) => k.id === id);
            if (idx !== -1) {
              this.selectedItems.splice(idx, 1);
              this.updateDisplay($hidden, $text, $badges);
            }

            if ($menu.find(".custom-dropdown-item").length === 0) {
              $menu.html('<div class="custom-dropdown-empty">Belum ada keahlian</div>');
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

    handleBadgeRemove: function (e, $menu, $hidden, $text, $badges) {
      const $btn = $(e.target).closest(".badge-remove-btn");
      const id = $btn.attr("data-id");

      const idx = this.selectedItems.findIndex((k) => k.id === id);
      if (idx !== -1) this.selectedItems.splice(idx, 1);

      const $item = $menu.find(`[data-value="${id}"]`);
      $item.removeClass("selected disabled");

      this.updateDisplay($hidden, $text, $badges);
    },

    updateDisplay: function ($hidden, $text, $badges) {
      $badges.empty();

      this.selectedItems.forEach((item) => {
        const $badge = $("<div>", { class: "selected-badge" }).html(`
          <span>${item.name}</span>
          <button type="button" class="badge-remove-btn" data-id="${item.id}">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <line x1="18" y1="6" x2="6" y2="18"></line>
              <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
          </button>
        `);
        $badges.append($badge);
      });

      $hidden.val(this.selectedItems.map((k) => k.id).join(","));
    },

    addItem: function (data) {
      const $menu = $("#keahlianMenu");
      const $empty = $menu.find(".custom-dropdown-empty");

      if ($empty.length) $empty.remove();

      const $item = $("<div>", {
        class: "custom-dropdown-item",
        "data-value": data.id,
        "data-id": data.id,
      }).html(`
        <span class="item-text">${data.nama_keahlian}</span>
        <button type="button" class="item-delete-btn" data-id="${data.id}" data-name="${data.nama_keahlian}" title="Hapus">
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
      const btnState = jQueryHelpers.disableButton("btn-add-new-jabatan", "Menyimpan...");

      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/dosen/create-jabatan`,
        method: "POST",
        data: { jabatan: jabatan },
        onSuccess: (response) => {
          if (response.success) {
            JabatanDropdown.addItem(response.data);
            $("#newJabatan").val("");
            bootstrap.Modal.getInstance($("#modalAddJabatan")[0]).hide();
          } else {
            $("#jabatanError").text(response.message || "Gagal").show();
          }
        },
        onError: (msg) => {
          $("#jabatanError").text("Error: " + msg).show();
        },
        onComplete: () => {
          btnState.enable();
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

      $("#keahlianError").hide();
      const btnState = jQueryHelpers.disableButton("btn-add-new-keahlian", "Menyimpan...");

      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/dosen/create-keahlian`,
        method: "POST",
        data: { keahlian: keahlian },
        onSuccess: (response) => {
          if (response.success) {
            KeahlianDropdown.addItem(response.data);
            $("#newKeahlian").val("");
            bootstrap.Modal.getInstance($("#modalAddKeahlian")[0]).hide();
          } else {
            $("#keahlianError").text(response.message || "Gagal").show();
          }
        },
        onError: (msg) => {
          $("#keahlianError").text("Error: " + msg).show();
        },
        onComplete: () => {
          btnState.enable();
        },
      });
    },
  };

  // ============================================================
  // MODUL SUBMIT FORM
  // ============================================================

  const FormSubmitModule = {
    init: function () {
      $("#btn-submit-create-dosen").on("click", (e) => {
        e.preventDefault();
        this.handleSubmit();
      });
    },

    handleSubmit: function () {
      jQueryHelpers.clearAllErrors("formDosen");

      const formData = this.getFormData();
      const errors = this.validate(formData);

      if (errors.length > 0) {
        errors.forEach((err) => {
          jQueryHelpers.showError(err.fieldId, err.errorId, err.message);
        });
        return;
      }

      const submitData = this.prepareData(formData);
      const btnState = jQueryHelpers.disableButton("btn-submit-create-dosen", "Menyimpan...");

      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/dosen/create`,
        method: "POST",
        data: submitData,
        processData: false,
        contentType: false,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert("Data dosen berhasil ditambahkan!", "success");
            setTimeout(() => {
              window.location.href = `${BASE_URL}/admin/dosen`;
            }, 500);
          } else {
            jQueryHelpers.showAlert("Gagal: " + response.message, "danger");
            btnState.enable();
          }
        },
        onError: (msg) => {
          jQueryHelpers.showAlert("Error: " + msg, "danger");
          btnState.enable();
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
        csrf_token: $('input[name="csrf_token"]').val(),
      };
    },

    validate: function (data) {
      const errors = [];

      const name = validationHelpers.validateName(data.full_name, 1, 255);
      if (!name.valid) {
        errors.push({ fieldId: "full_name", errorId: "fullNameError", message: name.message });
      }

      const email = validationHelpers.validateEmail(data.email);
      if (!email.valid) {
        errors.push({ fieldId: "email", errorId: "emailError", message: email.message });
      }

      const nidn = validationHelpers.validateNIDN(data.nidn, true);
      if (!nidn.valid) {
        errors.push({ fieldId: "nidn", errorId: "nidnError", message: nidn.message });
      }

      const jabatan = validationHelpers.validateRequired(data.jabatan_id, "Jabatan");
      if (!jabatan.valid) {
        errors.push({ fieldId: "jabatan", errorId: "jabatanError", message: jabatan.message });
      }

      const keahlian = validationHelpers.validateMultipleSelection(data.keahlian_ids, "keahlian");
      if (!keahlian.valid) {
        errors.push({ fieldId: "keahlian", errorId: "keahlianError", message: keahlian.message });
      }

      if (data.foto_profil) {
        const size = validationHelpers.validateFileSize(data.foto_profil, 2);
        if (!size.valid) {
          errors.push({ fieldId: "photo_profile", errorId: "photoError", message: size.message });
        }

        const type = validationHelpers.validateFileType(data.foto_profil, ["image/jpeg", "image/jpg", "image/png"]);
        if (!type.valid) {
          errors.push({ fieldId: "photo_profile", errorId: "photoError", message: type.message });
        }
      }

      return errors;
    },

    prepareData: function (data) {
      const formData = new FormData();
      formData.append("full_name", data.full_name);
      formData.append("email", data.email);
      formData.append("nidn", data.nidn);
      formData.append("jabatan_id", data.jabatan_id);
      formData.append("keahlian_ids", data.keahlian_ids);
      formData.append("deskripsi", data.deskripsi);
      formData.append("csrf_token", data.csrf_token)

      if (data.foto_profil) {
        formData.append("foto_profil", data.foto_profil);
      }

      return formData;
    },
  };

  // ============================================================
  // INISIALISASI
  // ============================================================

  $(document).ready(function () {
    FileUploadModule.init();
    JabatanDropdown.init();
    KeahlianDropdown.init();
    AddJabatanModule.init();
    AddKeahlianModule.init();
    FormSubmitModule.init();
  });
})();
