/**
 * File: pages/recruitment/edit.js
 * Deskripsi: Logic khusus untuk edit mode recruitment
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 * - validationHelpers.js
 * - form.js (shared logic)
 *
 * Note: File ini di-load setelah form.js di halaman edit
 */

(function () {
  "use strict";

  // ============================================================
  // EDIT MODE INITIALIZATION
  // ============================================================

  /**
   * Initialize edit mode
   * - Pre-populate existing banner image preview if available
   * - Override form submission to use update endpoint
   */
  const EditModeModule = {
    init: function () {
      // Check if we're in edit mode
      if (!window.EDIT_MODE || !window.RECRUITMENT_DATA) {
        console.error("Edit mode data not found");
        return;
      }

      // Pre-populate banner preview if exists
      this.populateBannerPreview();

      // Override submit button handler for update
      this.setupUpdateHandler();
    },

    /**
     * Pre-populate banner preview if exists
     */
    populateBannerPreview: function () {
      const recruitmentData = window.RECRUITMENT_DATA;

      if (recruitmentData.gambar_banner) {
        const imagePreview = document.getElementById("imagePreview");
        const previewImg = document.getElementById("previewImg");

        if (imagePreview && previewImg) {
          // Set preview image src to existing banner
          previewImg.src = `/applied-informatics/public/uploads/recruitment/${recruitmentData.gambar_banner}`;
          imagePreview.style.display = "block";
        }
      }
    },

    /**
     * Setup update handler (override create handler)
     */
    setupUpdateHandler: function () {
      // Remove create handler jika sudah di-attach oleh form.js
      $("#btn-submit-create-recruitment").off("click");

      // Ganti dengan ID yang sesuai untuk tombol update
      $("#btn-submit-update-recruitment").on("click", (e) => {
        e.preventDefault();
        this.handleUpdate();
      });
    },

    /**
     * Handle update submission
     */
    handleUpdate: function () {
      const formData = this.getFormData();
      const validationErrors = this.validateFormData(formData);

      jQueryHelpers.clearAllErrors("formRecruitment");

      if (validationErrors.length > 0) {
        validationErrors.forEach((error) => {
          jQueryHelpers.showError(error.fieldId, error.errorId, error.message);
        });
        return;
      }

      const submitData = this.prepareFormData(formData);
      const buttonState = jQueryHelpers.disableButton(
        "btn-submit-update-recruitment",
        "Menyimpan..."
      );

      jQueryHelpers.makeAjaxRequest({
        url: "/applied-informatics/recruitment/update",
        method: "POST",
        data: submitData,
        processData: false,
        contentType: false,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert(
              "Data recruitment berhasil diperbarui!",
              "success"
            );
            setTimeout(() => {
              window.location.href = "/applied-informatics/recruitment";
            }, 500);
          } else {
            jQueryHelpers.showAlert("Gagal: " + response.message, "danger");
            buttonState.enable();
          }
        },
        onError: (errorMessage) => {
          console.log(errorMessage);
          jQueryHelpers.showAlert("Error: " + errorMessage, "danger");
          buttonState.enable();
        },
      });
    },

    /**
     * Get form data (same as form.js but includes ID)
     */
    getFormData: function () {
      return {
        id: $("#recruitment_id").val(),
        posisi: $("#posisi").val().trim(),
        deskripsi: $("#deskripsi").val().trim(),
        persyaratan: $("#persyaratan").val().trim(),
        tanggal_mulai: $("#tanggal_mulai").val().trim(),
        tanggal_berakhir: $("#tanggal_berakhir").val().trim(),
        status: $("#status").val().trim(),
        link_pendaftaran: $("#link_pendaftaran").val().trim(),
        gambar_banner: $("#gambar_banner")[0].files[0],
      };
    },

    /**
     * Validate form data (same validation as form.js)
     */
    validateFormData: function (data) {
      const errors = [];

      // Validate posisi
      const posisiValidation = validationHelpers.validateName(
        data.posisi,
        1,
        255
      );
      if (!posisiValidation.valid) {
        errors.push({
          fieldId: "posisi",
          errorId: "posisiError",
          message: posisiValidation.message,
        });
      }

      // Validate deskripsi
      const deskripsiValidation = validationHelpers.validateRequired(
        data.deskripsi,
        "Deskripsi"
      );
      if (!deskripsiValidation.valid) {
        errors.push({
          fieldId: "deskripsi",
          errorId: "deskripsiError",
          message: deskripsiValidation.message,
        });
      }

      // Validate tanggal_mulai
      const tanggalMulaiValidation = validationHelpers.validateRequired(
        data.tanggal_mulai,
        "Tanggal Mulai"
      );
      if (!tanggalMulaiValidation.valid) {
        errors.push({
          fieldId: "tanggal_mulai",
          errorId: "tanggalMulaiError",
          message: tanggalMulaiValidation.message,
        });
      }

      // Validate tanggal_berakhir
      const tanggalBerakhirValidation = validationHelpers.validateRequired(
        data.tanggal_berakhir,
        "Tanggal Berakhir"
      );
      if (!tanggalBerakhirValidation.valid) {
        errors.push({
          fieldId: "tanggal_berakhir",
          errorId: "tanggalBerakhirError",
          message: tanggalBerakhirValidation.message,
        });
      }

      // Validate tanggal berakhir tidak lebih awal dari tanggal mulai
      if (data.tanggal_mulai && data.tanggal_berakhir) {
        const mulai = new Date(data.tanggal_mulai);
        const berakhir = new Date(data.tanggal_berakhir);
        if (berakhir < mulai) {
          errors.push({
            fieldId: "tanggal_berakhir",
            errorId: "tanggalBerakhirError",
            message:
              "Tanggal berakhir tidak boleh lebih awal dari tanggal mulai",
          });
        }
      }

      // Validate status
      const statusValidation = validationHelpers.validateRequired(
        data.status,
        "Status"
      );
      if (!statusValidation.valid) {
        errors.push({
          fieldId: "status",
          errorId: "statusError",
          message: statusValidation.message,
        });
      }

      // Validate link_pendaftaran (optional, but if provided must be URL)
      if (data.link_pendaftaran && data.link_pendaftaran.length > 0) {
        const urlPattern = /^https?:\/\/.+/i;
        if (!urlPattern.test(data.link_pendaftaran)) {
          errors.push({
            fieldId: "link_pendaftaran",
            errorId: "linkPendaftaranError",
            message:
              "Link pendaftaran harus berupa URL yang valid (dimulai dengan http:// atau https://)",
          });
        }
      }

      // Validate gambar_banner (optional file upload)
      if (data.gambar_banner) {
        const sizeValidation = validationHelpers.validateFileSize(
          data.gambar_banner,
          2
        );
        if (!sizeValidation.valid) {
          errors.push({
            fieldId: "gambar_banner",
            errorId: "gambarBannerError",
            message: sizeValidation.message,
          });
        }

        const typeValidation = validationHelpers.validateFileType(
          data.gambar_banner,
          ["image/jpeg", "image/jpg", "image/png"]
        );
        if (!typeValidation.valid) {
          errors.push({
            fieldId: "gambar_banner",
            errorId: "gambarBannerError",
            message: typeValidation.message,
          });
        }
      }

      return errors;
    },

    /**
     * Prepare form data for submission (includes ID)
     */
    prepareFormData: function (data) {
      const formData = new FormData();
      formData.append("id", data.id);
      formData.append("posisi", data.posisi);
      formData.append("deskripsi", data.deskripsi);
      formData.append("persyaratan", data.persyaratan);
      formData.append("tanggal_mulai", data.tanggal_mulai);
      formData.append("tanggal_berakhir", data.tanggal_berakhir);
      formData.append("status", data.status);
      formData.append("link_pendaftaran", data.link_pendaftaran);

      if (data.gambar_banner) {
        formData.append("gambar_banner", data.gambar_banner);
      }

      return formData;
    },
  };

  // ============================================================
  // INISIALISASI
  // ============================================================

  $(document).ready(function () {
    EditModeModule.init();
  });
})();
