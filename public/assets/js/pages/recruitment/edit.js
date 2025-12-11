/**
 * File: pages/recruitment/edit.js
 * Deskripsi: Script untuk halaman edit recruitment
 *
 * Fitur:
 * - Validasi form
 * - Submit form update via AJAX
 * - Image upload dengan preview
 *
 * Dependencies:
 * - jQuery
 * - jQueryHelpers.js
 * - validationHelpers.js
 */

(function () {
  "use strict";

  const BASE_URL = $('meta[name="base-url"]').attr("content") || "/applied-informatics";

  // ============================================================
  // MODUL IMAGE UPLOAD
  // ============================================================

  const ImageUpload = {
    init: function () {
      this.setupFileUpload();
      this.setupDragAndDrop();
    },

    setupFileUpload: function () {
      const fileInput = $("#banner_image");
      const uploadWrapper = $("#fileUploadWrapper");
      const imagePreview = $("#imagePreview");
      const previewImg = $("#previewImg");

      // Click wrapper to trigger file input
      uploadWrapper.on("click", function () {
        fileInput.click();
      });

      // Handle file selection
      fileInput.on("change", function (e) {
        const file = e.target.files[0];
        if (file) {
          ImageUpload.handleFileSelect(file, imagePreview, previewImg, uploadWrapper);
        }
      });

      // Click preview to remove
      imagePreview.on("click", function () {
        ImageUpload.removePreview(fileInput, imagePreview, uploadWrapper);
      });
    },

    setupDragAndDrop: function () {
      const uploadWrapper = $("#fileUploadWrapper");
      const fileInput = $("#banner_image");
      const imagePreview = $("#imagePreview");
      const previewImg = $("#previewImg");

      uploadWrapper.on("dragover", function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).addClass("dragover");
      });

      uploadWrapper.on("dragleave", function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass("dragover");
      });

      uploadWrapper.on("drop", function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).removeClass("dragover");

        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
          const file = files[0];
          if (file.type.startsWith("image/")) {
            // Set file to input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput[0].files = dataTransfer.files;

            ImageUpload.handleFileSelect(file, imagePreview, previewImg, uploadWrapper);
          } else {
            jQueryHelpers.showAlert("Hanya file gambar yang diperbolehkan", "danger", 3000);
          }
        }
      });
    },

    handleFileSelect: function (file, imagePreview, previewImg, uploadWrapper) {
      // Validate file type
      const allowedTypes = ["image/png", "image/jpg", "image/jpeg"];
      if (!allowedTypes.includes(file.type)) {
        jQueryHelpers.showAlert("Format file tidak valid. Gunakan PNG, JPG, atau JPEG", "danger", 3000);
        return;
      }

      // Validate file size (2MB)
      if (file.size > 2 * 1024 * 1024) {
        jQueryHelpers.showAlert("Ukuran file maksimal 2MB", "danger", 3000);
        return;
      }

      // Show preview
      const reader = new FileReader();
      reader.onload = function (e) {
        previewImg.attr("src", e.target.result);
        imagePreview.show();
        uploadWrapper.hide();
        // Hide current image wrapper if exists
        $("#currentImageWrapper").hide();
      };
      reader.readAsDataURL(file);
    },

    removePreview: function (fileInput, imagePreview, uploadWrapper) {
      fileInput.val("");
      imagePreview.hide();
      uploadWrapper.show();
      // Show current image wrapper again if it exists
      $("#currentImageWrapper").show();
    },
  };

  // ============================================================
  // MODUL UPDATE RECRUITMENT
  // ============================================================

  const FormUpdateRecruitment = {
    init: function () {
      $("#btn-update-recruitment").on("click", (e) => {
        e.preventDefault();
        this.handleSubmit();
      });
    },

    handleSubmit: function () {
      const formData = this.getFormData();
      const validationErrors = this.validateFormData(formData);

      jQueryHelpers.clearAllErrors("formUpdateRecruitment");

      if (validationErrors.length > 0) {
        validationErrors.forEach((error) => {
          jQueryHelpers.showError(error.fieldId, error.errorId, error.message);
        });
        return;
      }

      const submitData = this.prepareFormData(formData);

      // Disable submit button
      const submitButton = $("#formUpdateRecruitment").find('button[type="submit"]');
      submitButton.prop("disabled", true);
      const originalButtonHtml = submitButton.html();
      submitButton.html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');

      jQueryHelpers.makeAjaxRequest({
        url: `${BASE_URL}/admin/recruitment/update`,
        method: "POST",
        data: submitData,
        processData: false,
        contentType: false,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert(
              "Data recruitment berhasil diupdate!",
              "success"
            );
            setTimeout(() => {
              window.location.href = `${BASE_URL}/admin/recruitment`;
            }, 500);
          } else {
            jQueryHelpers.showAlert(
              "Gagal: " + response.message,
              "danger",
              5000
            );
            submitButton.prop("disabled", false);
            submitButton.html(originalButtonHtml);
          }
        },
        onError: (errorMessage) => {
          jQueryHelpers.showAlert("Error: " + errorMessage, "danger");
          submitButton.prop("disabled", false);
          submitButton.html(originalButtonHtml);
        },
      });
    },

    getFormData: () => {
      // Get deskripsi from Quill editor
      let deskripsi = "";
      if (typeof quill !== "undefined") {
        deskripsi = quill.root.innerHTML;
        // Clear if only contains empty paragraph
        if (deskripsi === "<p><br></p>") {
          deskripsi = "";
        }
      }

      return {
        id: $('input[name="id"]').val(),
        judul: ($("#judul").val() || "").trim(),
        status: $("#status").val() || "",
        kategori: $("#kategori").val() || "",
        periode: ($("#periode").val() || "").trim(),
        tanggal_buka: ($("#tanggal_buka").val() || "").trim(),
        tanggal_tutup: ($("#tanggal_tutup").val() || "").trim(),
        deskripsi: deskripsi,
        banner_image: $("#banner_image")[0]?.files[0] || null,
        old_banner_image: $("#old_banner_image").val() || "",
        csrf_token: $("input[name='csrf_token']").val() || "",
      };
    },

    validateFormData: (data) => {
      const errors = [];

      // Validasi judul
      const judulValidation = validationHelpers.validateName(data.judul, 1, 255);
      if (!judulValidation.valid) {
        errors.push({
          fieldId: "judul",
          errorId: "judulError",
          message: judulValidation.message,
        });
      }

      // Validasi status
      if (!data.status || data.status === "") {
        errors.push({
          fieldId: "status",
          errorId: "statusError",
          message: "Status recruitment wajib dipilih",
        });
      }

      // Validasi kategori
      if (!data.kategori || data.kategori === "") {
        errors.push({
          fieldId: "kategori",
          errorId: "kategoriError",
          message: "Kategori wajib dipilih",
        });
      }

      // Validasi periode
      if (!data.periode || data.periode.length < 1) {
        errors.push({
          fieldId: "periode",
          errorId: "periodeError",
          message: "Periode wajib diisi",
        });
      }

      // Validasi tanggal buka
      if (!data.tanggal_buka || data.tanggal_buka.length < 1) {
        errors.push({
          fieldId: "tanggal_buka",
          errorId: "tanggalBukaError",
          message: "Tanggal buka wajib diisi",
        });
      }

      // Validasi tanggal tutup
      if (!data.tanggal_tutup || data.tanggal_tutup.length < 1) {
        errors.push({
          fieldId: "tanggal_tutup",
          errorId: "tanggalTutupError",
          message: "Tanggal tutup wajib diisi",
        });
      }

      // Validasi tanggal tutup tidak lebih awal dari tanggal buka
      if (data.tanggal_tutup && data.tanggal_buka && data.tanggal_tutup < data.tanggal_buka) {
        errors.push({
          fieldId: "tanggal_tutup",
          errorId: "tanggalTutupError",
          message: "Tanggal tutup tidak boleh lebih awal dari tanggal buka",
        });
      }

      // Validasi deskripsi
      if (!data.deskripsi || data.deskripsi.length < 1) {
        errors.push({
          fieldId: "deskripsi",
          errorId: "deskripsiError",
          message: "Deskripsi wajib diisi",
        });
      }

      return errors;
    },

    prepareFormData: (data) => {
      const formData = new FormData();

      formData.append("id", data.id);
      formData.append("judul", data.judul);
      formData.append("status", data.status);
      formData.append("kategori", data.kategori);
      formData.append("periode", data.periode);
      formData.append("tanggal_buka", data.tanggal_buka);
      formData.append("tanggal_tutup", data.tanggal_tutup);
      formData.append("deskripsi", data.deskripsi);
      formData.append("old_banner_image", data.old_banner_image);
      formData.append("csrf_token", data.csrf_token);

      // Add new banner image if selected
      if (data.banner_image) {
        formData.append("banner_image", data.banner_image);
      }

      return formData;
    },
  };

  // ============================================================
  // INISIALISASI SEMUA MODUL
  // ============================================================

  $(document).ready(function () {
    ImageUpload.init();
    FormUpdateRecruitment.init();
  });
})();