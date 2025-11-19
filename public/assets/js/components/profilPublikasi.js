/**
 * File: components/profilPublikasi.js
 * Deskripsi: Modul untuk mengelola profil publikasi dosen (shared untuk create & edit)
 *
 * Dependencies:
 * - jQuery
 *
 * Fitur:
 * - Tambah/hapus row profil publikasi secara dinamis
 * - Validasi duplikasi tipe profil
 * - Pre-populate profil untuk edit mode
 * - Get data profil untuk form submission
 */

(function () {
  "use strict";

  // ============================================================
  // MODUL PROFIL PUBLIKASI
  // ============================================================

  const ProfilPublikasiModule = {
    // Tipe profil yang tersedia (sesuai enum di database)
    tipeProfil: {
      SINTA: "SINTA",
      SCOPUS: "SCOPUS",
      GOOGLE_SCHOLAR: "Google Scholar",
      ORCID: "ORCID",
      RESEARCHGATE: "ResearchGate"
    },

    // Placeholder URL untuk setiap tipe
    placeholders: {
      SINTA: "https://sinta.kemdikbud.go.id/authors/profile/...",
      SCOPUS: "https://www.scopus.com/authid/detail.uri?authorId=...",
      GOOGLE_SCHOLAR: "https://scholar.google.com/citations?user=...",
      ORCID: "https://orcid.org/0000-0000-0000-0000",
      RESEARCHGATE: "https://www.researchgate.net/profile/..."
    },

    // Counter untuk unique ID setiap row
    rowCounter: 0,

    /**
     * Inisialisasi modul
     */
    init: function () {
      const $btnAdd = $('#btnAddProfilPublikasi');

      if ($btnAdd.length === 0) {
        console.warn('Button tambah profil publikasi not found');
        return;
      }

      // Handle click tombol tambah profil
      $btnAdd.on('click', () => {
        this.addRow();
      });

      console.log('Profil Publikasi module initialized');
    },

    /**
     * Tambah row profil publikasi baru
     * @param {string} tipe - Tipe profil (opsional, untuk pre-populate)
     * @param {string} url - URL profil (opsional, untuk pre-populate)
     * @returns {boolean} - true jika berhasil, false jika gagal (duplikat)
     */
    addRow: function (tipe = '', url = '') {
      const $container = $('#profilPublikasiContainer');
      this.rowCounter++;
      const rowId = `profil-row-${this.rowCounter}`;

      // Jika tipe sudah dipilih, cek duplikasi
      if (tipe && this.isDuplicate(tipe)) {
        alert(`Profil ${this.tipeProfil[tipe]} sudah ditambahkan`);
        return false;
      }

      // Buat row baru
      const $row = $('<div>', {
        class: 'profil-publikasi-row mb-1',
        id: rowId,
        'data-tipe': tipe
      });

      // Buat dropdown tipe profil
      const $dropdown = this.createDropdown(tipe, rowId);

      // Buat input URL
      const $inputUrl = $('<input>', {
        type: 'url',
        class: 'form-control profil-url-input',
        name: `profil_publikasi_url[]`,
        placeholder: tipe ? this.placeholders[tipe] : 'Masukkan URL profil publikasi',
        value: url,
        required: false
      });

      // Buat tombol hapus
      const $btnRemove = $('<button>', {
        type: 'button',
        class: 'btn-remove-profil',
        title: 'Hapus profil',
        html: `
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
          </svg>
        `
      });

      // Handle click hapus
      $btnRemove.on('click', () => {
        this.removeRow(rowId);
      });

      // Susun row
      const $rowContent = $('<div>', { class: 'row align--start' });

      const $colTipe = $('<div>', { class: 'col-md-4 mb-2' }).append($dropdown);
      const $colUrl = $('<div>', { class: 'col-md-7 mb-2' }).append($inputUrl);
      const $colAction = $('<div>', { class: 'col-md-1 mb-2' }).append($btnRemove);

      $rowContent.append($colTipe, $colUrl, $colAction);
      $row.append($rowContent);

      // Append ke container
      $container.append($row);

      return true;
    },

    /**
     * Buat dropdown tipe profil
     */
    createDropdown: function (selectedTipe, rowId) {
      const $dropdown = $('<select>', {
        class: 'form-select profil-tipe-select',
        name: 'profil_publikasi_tipe[]',
        required: false
      });

      // Default option
      $dropdown.append($('<option>', {
        value: '',
        text: 'Pilih Tipe Profil',
        disabled: true,
        selected: !selectedTipe
      }));

      // Options tipe profil
      Object.keys(this.tipeProfil).forEach(key => {
        const $option = $('<option>', {
          value: key,
          text: this.tipeProfil[key],
          selected: key === selectedTipe
        });

        // Disable jika tipe sudah dipilih di row lain
        if (this.isDuplicate(key) && key !== selectedTipe) {
          $option.prop('disabled', true);
        }

        $dropdown.append($option);
      });

      // Handle change dropdown
      $dropdown.on('change', (e) => {
        this.handleTipeChange(e, rowId);
      });

      return $dropdown;
    },

    /**
     * Handle perubahan tipe profil
     */
    handleTipeChange: function (e, rowId) {
      const $select = $(e.target);
      const newTipe = $select.val();
      const $row = $(`#${rowId}`);
      const oldTipe = $row.attr('data-tipe');

      // Cek duplikasi
      if (newTipe && this.isDuplicate(newTipe) && newTipe !== oldTipe) {
        alert(`Profil ${this.tipeProfil[newTipe]} sudah ditambahkan`);
        $select.val(oldTipe || '');
        return;
      }

      // Update data-tipe di row
      $row.attr('data-tipe', newTipe);

      // Update placeholder URL
      const $urlInput = $row.find('.profil-url-input');
      if (newTipe) {
        $urlInput.attr('placeholder', this.placeholders[newTipe]);
      } else {
        $urlInput.attr('placeholder', 'Masukkan URL profil publikasi');
      }

      // Refresh semua dropdown untuk update disabled state
      this.refreshDropdowns();
    },

    /**
     * Hapus row profil publikasi
     */
    removeRow: function (rowId) {
      $(`#${rowId}`).remove();

      // Refresh semua dropdown setelah hapus
      this.refreshDropdowns();
    },

    /**
     * Cek apakah tipe profil sudah ada
     */
    isDuplicate: function (tipe) {
      if (!tipe) return false;

      const existingTypes = [];
      $('.profil-publikasi-row').each(function () {
        const rowTipe = $(this).attr('data-tipe');
        if (rowTipe) {
          existingTypes.push(rowTipe);
        }
      });

      return existingTypes.filter(t => t === tipe).length > 0;
    },

    /**
     * Refresh semua dropdown untuk update disabled state
     */
    refreshDropdowns: function () {
      $('.profil-tipe-select').each((index, select) => {
        const $select = $(select);
        const selectedValue = $select.val();

        $select.find('option').each((i, option) => {
          const $option = $(option);
          const optionValue = $option.val();

          if (optionValue && optionValue !== selectedValue) {
            // Disable jika sudah dipilih di row lain
            if (this.isDuplicate(optionValue)) {
              $option.prop('disabled', true);
            } else {
              $option.prop('disabled', false);
            }
          }
        });
      });
    },

    /**
     * Get data profil publikasi untuk form submission
     * @returns {Array} - Array of {tipe, url}
     */
    getData: function () {
      const data = [];

      $('.profil-publikasi-row').each(function () {
        const tipe = $(this).find('.profil-tipe-select').val();
        const url = $(this).find('.profil-url-input').val().trim();

        // Hanya include jika tipe dan URL terisi
        if (tipe && url) {
          data.push({ tipe, url });
        }
      });

      return data;
    },

    /**
     * Load data profil publikasi (untuk edit mode)
     * @param {Array} profiles - Array of {tipe, url_ke_profil}
     */
    loadData: function (profiles) {
      if (!profiles || profiles.length === 0) {
        return;
      }

      profiles.forEach(profile => {
        this.addRow(profile.tipe, profile.url_ke_profil);
      });

      console.log('Loaded profil publikasi:', profiles);
    },

    /**
     * Clear semua row profil publikasi
     */
    clear: function () {
      $('#profilPublikasiContainer').empty();
      this.rowCounter = 0;
    }
  };

  // ============================================================
  // EXPOSE KE GLOBAL SCOPE
  // ============================================================
  window.ProfilPublikasiModule = ProfilPublikasiModule;

})();
