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

  // ============================================================
  // MODUL KEAHLIAN DROPDOWN (Multiple Selection - Additive)
  // ============================================================

  const KeahlianDropdown = {
    selectedItems: [], // Array of {id, name}

    /**
     * Inisialisasi dropdown untuk edit mode
     */
    init: function () {
      const $dropdown = $('#customDropdownKeahlian');
      const $trigger = $('#keahlianTrigger');
      const $menu = $('#keahlianMenu');
      const $textElement = $('#keahlianText');
      const $hiddenInput = $('#keahlian');
      const $badgesContainer = $('#selectedKeahlianBadges');

      if ($dropdown.length === 0 || $trigger.length === 0 || $menu.length === 0) {
        console.warn('Keahlian dropdown elements not found');
        return;
      }

      // Toggle dropdown
      $trigger.on('click', (e) => {
        e.stopPropagation();
        $dropdown.toggleClass('active');
      });

      // Tutup dropdown saat klik di luar
      $(document).on('click', (e) => {
        if (!$dropdown.is(e.target) && $dropdown.has(e.target).length === 0) {
          $dropdown.removeClass('active');
        }
      });

      // Handle klik item - ADDITIVE ONLY
      $menu.on('click', (e) => {
        this.handleItemClick(e, $menu, $hiddenInput, $textElement, $badgesContainer);
      });

      // Handle hapus badge
      $badgesContainer.on('click', (e) => {
        this.handleBadgeRemove(e, $menu, $hiddenInput, $textElement, $badgesContainer);
      });

      console.log('Keahlian dropdown initialized');
    },

    /**
     * Handle klik item - ADDITIVE ONLY
     */
    handleItemClick: function (e, $menu, $hiddenInput, $textElement, $badgesContainer) {
      const $item = $(e.target).closest('.custom-dropdown-item');

      // Hanya proses jika klik pada item (bukan tombol delete) dan item belum dipilih
      if ($item.length > 0 && $(e.target).closest('.item-delete-btn').length === 0) {
        const value = $item.attr('data-value');
        const text = $item.find('.item-text').text();

        // Cek apakah item sudah dipilih
        const alreadySelected = this.selectedItems.findIndex(k => k.id === value) !== -1;

        // Jika sudah dipilih, tidak perlu melakukan apa-apa
        if (alreadySelected) {
          return;
        }

        // Tambah ke selection (ADDITIVE)
        this.selectedItems.push({ id: value, name: text });

        // Mark item sebagai selected dan disable
        $item.addClass('selected disabled');

        // Update display
        this.updateDisplay($hiddenInput, $textElement, $badgesContainer);
      }
    },

    /**
     * Handle hapus badge - SATU-SATUNYA cara untuk unselect keahlian
     */
    handleBadgeRemove: function (e, $menu, $hiddenInput, $textElement, $badgesContainer) {
      const $removeBtn = $(e.target).closest('.badge-remove-btn');

      if ($removeBtn.length > 0) {
        e.stopPropagation();

        const id = $removeBtn.attr('data-id');

        // Hapus dari selectedItems
        const index = this.selectedItems.findIndex(k => k.id === id);
        if (index !== -1) {
          this.selectedItems.splice(index, 1);
        }

        // Hapus badge dari DOM
        $removeBtn.closest('.selected-badge').remove();

        // Re-enable item di dropdown
        const $menuItem = $menu.find(`.custom-dropdown-item[data-value="${id}"]`);
        if ($menuItem.length > 0) {
          $menuItem.removeClass('selected disabled');
        }

        // Update display
        this.updateDisplay($hiddenInput, $textElement, $badgesContainer);
      }
    },

    /**
     * Handle delete keahlian dari database (tombol trash di dropdown)
     */
    handleDeleteFromDatabase: function (id, name, $deleteBtn, $hiddenInput, $textElement, $badgesContainer, $menu) {
      if (confirm(`Apakah Anda yakin ingin menghapus keahlian "${name}" dari database?\n\nCatatan: Ini akan menghapus keahlian dari sistem, bukan hanya dari dosen ini.`)) {
        $deleteBtn.prop('disabled', true);

        jQueryHelpers.makeAjaxRequest({
          url: '/applied-informatics/dosen/delete-keahlian',
          method: 'POST',
          data: { id: id },
          onSuccess: (response) => {
            if (response.success) {
              // Hapus dari selectedItems jika sedang dipilih
              const index = this.selectedItems.findIndex(k => k.id === id);
              if (index !== -1) {
                this.selectedItems.splice(index, 1);
              }

              // Hapus badge jika ada
              const $badge = $badgesContainer.find(`.badge-remove-btn[data-id="${id}"]`);
              if ($badge.length > 0) {
                $badge.closest('.selected-badge').remove();
              }

              // Hapus item dari dropdown
              const $item = $deleteBtn.closest('.custom-dropdown-item');
              $item.remove();

              // Tampilkan empty state jika tidak ada item tersisa
              if ($menu.find('.custom-dropdown-item').length === 0) {
                $menu.html('<div class="custom-dropdown-empty">Belum ada keahlian</div>');
              }

              // Update display
              this.updateDisplay($hiddenInput, $textElement, $badgesContainer);

              jQueryHelpers.showAlert('Keahlian berhasil dihapus dari database', 'success', 3000);
            } else {
              alert(response.message || 'Gagal menghapus keahlian');
              $deleteBtn.prop('disabled', false);
            }
          },
          onError: (errorMessage) => {
            alert('Error menghapus keahlian: ' + errorMessage);
            $deleteBtn.prop('disabled', false);
          }
        });
      }
    },

    /**
     * Update display (badges, hidden input, text)
     */
    updateDisplay: function ($hiddenInput, $textElement, $badgesContainer) {
      // Update hidden input
      const ids = this.selectedItems.map(k => k.id);
      $hiddenInput.val(ids.join(','));

      // Update text
      if (this.selectedItems.length === 0) {
        $textElement.text('Pilih Keahlian').addClass('placeholder');
      } else {
        $textElement.text(`${this.selectedItems.length} keahlian dipilih`).removeClass('placeholder');
      }

      // Update badges
      this.updateBadges($badgesContainer);
    },

    /**
     * Update badges display
     */
    updateBadges: function ($badgesContainer) {
      $badgesContainer.empty();

      this.selectedItems.forEach(item => {
        const $badge = $('<div>', { class: 'selected-badge' }).html(`
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

      const $menu = $('#keahlianMenu');
      const $hiddenInput = $('#keahlian');
      const $textElement = $('#keahlianText');
      const $badgesContainer = $('#selectedKeahlianBadges');

      // Clear current selection
      this.selectedItems = [];

      // Populate keahlian dari data
      keahlianList.forEach(k => {
        this.selectedItems.push({
          id: k.id.toString(),
          name: k.keahlian
        });

        // Mark dropdown item as selected and disabled
        const $menuItem = $menu.find(`.custom-dropdown-item[data-value="${k.id}"]`);
        if ($menuItem.length > 0) {
          $menuItem.addClass('selected disabled');
        }
      });

      // Update display
      this.updateDisplay($hiddenInput, $textElement, $badgesContainer);

      console.log('Preselected keahlian:', this.selectedItems);
    },

    /**
     * Tambah keahlian baru ke dropdown (dari modal)
     * @param {Object} data - {id, keahlian}
     */
    addItemToDropdown: function (data) {
      const $menu = $('#keahlianMenu');
      const $emptyState = $menu.find('.custom-dropdown-empty');

      // Hapus empty state jika ada
      if ($emptyState.length > 0) {
        $emptyState.remove();
      }

      // Buat item baru
      const $newItem = $('<div>', {
        class: 'custom-dropdown-item',
        'data-value': data.id,
        'data-id': data.id
      }).html(`
        <span class="item-text">${data.keahlian}</span>
        <button type="button" class="item-delete-btn" data-id="${data.id}" data-name="${data.keahlian}" title="Hapus dari database">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
        </button>
      `);
      $menu.append($newItem);

      console.log('Added new keahlian to dropdown:', data);
    }
  };

  // ============================================================
  // MODUL EDIT MODE INITIALIZATION
  // ============================================================

  const EditModeModule = {
    /**
     * Inisialisasi edit mode
     */
    init: function () {
      KeahlianDropdown.init();
      this.preselectJabatan();
      this.preselectKeahlian();
      this.showExistingPhoto();
      this.loadProfilPublikasi();
      this.setupFormSubmit();
      this.setupModalHandlers();
      this.setupDeleteKeahlianHandlers();
    },

    /**
     * Pre-select jabatan berdasarkan data dosen
     */
    preselectJabatan: function () {
      const data = window.DOSEN_DATA;

      if (data.jabatan_id && data.jabatan_name) {
        $("#jabatan").val(data.jabatan_id);
        $("#jabatanText").text(data.jabatan_name).removeClass("placeholder");

        // Mark dropdown item as selected
        $(`#jabatanMenu .custom-dropdown-item[data-value="${data.jabatan_id}"]`).addClass("selected");
      }
    },

    /**
     * Pre-select keahlian berdasarkan data dosen
     */
    preselectKeahlian: function () {
      const data = window.DOSEN_DATA;

      if (!data.keahlian || data.keahlian.length === 0) {
        return;
      }

      KeahlianDropdown.preselectKeahlian(data.keahlian);
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
     * Load profil publikasi yang sudah ada (untuk edit mode)
     */
    loadProfilPublikasi: function () {
      const data = window.DOSEN_DATA;

      if (data.profil_publikasi && window.ProfilPublikasiModule) {
        window.ProfilPublikasiModule.init();
        window.ProfilPublikasiModule.loadData(data.profil_publikasi);
      } else if (window.ProfilPublikasiModule) {
        window.ProfilPublikasiModule.init();
      }
    },

    /**
     * Setup form submit handler untuk edit mode
     */
    setupFormSubmit: function () {
      const self = this;

      // Override form submit untuk edit mode
      $("#formDosen")
        .off("submit")
        .on("submit", function (e) {
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
        url: "/applied-informatics/dosen/update",
        method: "POST",
        data: submitData,
        processData: false,
        contentType: false,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert(
              "Data dosen berhasil diupdate!",
              "success"
            );
            setTimeout(() => {
              window.location.href = "/applied-informatics/dosen";
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
        foto_profil: $("#photo_profile")[0].files[0],
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

    /**
     * Prepare data untuk submit
     */
    prepareSubmitData: function (data) {
      const formData = new FormData();
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

      // Tambahkan profil publikasi jika ada
      if (window.ProfilPublikasiModule) {
        const profilPublikasi = window.ProfilPublikasiModule.getData();
        formData.append("profil_publikasi", JSON.stringify(profilPublikasi));
      }

      return formData;
    },

    /**
     * Setup handlers untuk modal Tambah Keahlian Baru
     */
    setupModalHandlers: function () {
      const $btnAddKeahlian = $('#btn-add-new-keahlian');
      const $newKeahlianInput = $('#newKeahlian');
      const $modalKeahlian = $('#modalAddKeahlian');

      if ($btnAddKeahlian.length > 0 && $newKeahlianInput.length > 0) {
        $btnAddKeahlian.on('click', () => {
          const keahlianName = $newKeahlianInput.val().trim();

          if (!keahlianName) {
            alert('Nama keahlian tidak boleh kosong');
            return;
          }

          // Disable button
          $btnAddKeahlian.prop('disabled', true).text('Menyimpan...');

          // Submit ke server
          jQueryHelpers.makeAjaxRequest({
            url: '/applied-informatics/dosen/create-keahlian',
            method: 'POST',
            data: { keahlian: keahlianName },
            onSuccess: (response) => {
              if (response.success) {
                // Tambahkan ke dropdown
                KeahlianDropdown.addItemToDropdown({
                  id: response.data.id,
                  keahlian: response.data.keahlian
                });

                // Reset input dan tutup modal
                $newKeahlianInput.val('');
                bootstrap.Modal.getInstance($modalKeahlian[0]).hide();

                jQueryHelpers.showAlert('Keahlian baru berhasil ditambahkan', 'success', 3000);
              } else {
                alert(response.message || 'Gagal menambahkan keahlian');
              }

              // Re-enable button
              $btnAddKeahlian.prop('disabled', false).text('Tambah');
            },
            onError: (errorMessage) => {
              alert('Error: ' + errorMessage);
              $btnAddKeahlian.prop('disabled', false).text('Tambah');
            }
          });
        });
      }
    },

    /**
     * Setup handlers untuk delete keahlian dari database
     */
    setupDeleteKeahlianHandlers: function () {
      const $menu = $('#keahlianMenu');
      const $hiddenInput = $('#keahlian');
      const $textElement = $('#keahlianText');
      const $badgesContainer = $('#selectedKeahlianBadges');

      // Delegated event untuk tombol delete keahlian
      $menu.on('click', '.item-delete-btn', (e) => {
        e.stopPropagation();

        const $deleteBtn = $(e.currentTarget);
        const id = $deleteBtn.attr('data-id');
        const name = $deleteBtn.attr('data-name');

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
    }
  };

  // ============================================================
  // INISIALISASI
  // ============================================================

  $(document).ready(function () {
    EditModeModule.init();
  });
})();
