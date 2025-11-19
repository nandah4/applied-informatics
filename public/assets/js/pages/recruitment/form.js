/**
 * File: pages/recruitment/form.js
 * Deskripsi: Menangani interaksi form recruitment (create & edit)
 *
 * Dependencies:
 * - jQuery
 * - Bootstrap 5
 * - jQueryHelpers.js
 * - validationHelpers.js
 *
 * Fitur:
 * - Upload file dengan preview (dukungan drag & drop)
 * - Validasi dan submit form
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
      const fileInput = document.getElementById("gambar_banner");
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
        document.getElementById("gambar_banner").value = "";
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
        document.getElementById("gambar_banner").value = "";
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
  // MODUL SUBMIT FORM
  // ============================================================

  const FormSubmissionModule = {
    init: function () {
      $("#btn-submit-create-recruitment").on("click", (e) => {
        e.preventDefault();
        this.handleSubmit();
      });
    },

    handleSubmit: function () {
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
        "btn-submit-create-recruitment",
        "Menyimpan..."
      );

      jQueryHelpers.makeAjaxRequest({
        url: "/applied-informatics/recruitment/create",
        method: "POST",
        data: submitData,
        processData: false,
        contentType: false,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert(
              "Data recruitment berhasil ditambahkan!",
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

    getFormData: function () {
      return {
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
            message: "Tanggal berakhir tidak boleh lebih awal dari tanggal mulai",
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
            message: "Link pendaftaran harus berupa URL yang valid (dimulai dengan http:// atau https://)",
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

    prepareFormData: function (data) {
      const formData = new FormData();
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
  // INISIALISASI SEMUA MODUL
  // ============================================================

  $(document).ready(function () {
    FileUploadModule.init();
    FormSubmissionModule.init();
  });
})();
