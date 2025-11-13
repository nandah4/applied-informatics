(function () {
  "use strict";

  // ============================================================
  // Initialize Flatpickr for date inputs
  // ============================================================

  const InitializeFlatpickr = {
    init: function () {
      this.tanggalAkhirPicker;
      this.tanggalMulaiPicker;
    },
    tanggalMulaiPicker: flatpickr("#tanggal_mulai", {
      dateFormat: "Y-m-d",
      locale: {
        firstDayOfWeek: 1,
        weekdays: {
          shorthand: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
          longhand: [
            "Minggu",
            "Senin",
            "Selasa",
            "Rabu",
            "Kamis",
            "Jumat",
            "Sabtu",
          ],
        },
        months: {
          shorthand: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "Mei",
            "Jun",
            "Jul",
            "Agu",
            "Sep",
            "Okt",
            "Nov",
            "Des",
          ],
          longhand: [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
          ],
        },
      },
      allowInput: true,
      onChange: function (selectedDates, dateStr, instance) {
        // Update min date for tanggal_akhir when tanggal_mulai changes
        if (selectedDates.length > 0) {
          tanggalAkhirPicker.set("minDate", selectedDates[0]);
        }
      },
    }),
    tanggalAkhirPicker: flatpickr("#tanggal_akhir", {
      dateFormat: "Y-m-d",
      locale: {
        firstDayOfWeek: 1,
        weekdays: {
          shorthand: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
          longhand: [
            "Minggu",
            "Senin",
            "Selasa",
            "Rabu",
            "Kamis",
            "Jumat",
            "Sabtu",
          ],
        },
        months: {
          shorthand: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "Mei",
            "Jun",
            "Jul",
            "Agu",
            "Sep",
            "Okt",
            "Nov",
            "Des",
          ],
          longhand: [
            "Januari",
            "Februari",
            "Maret",
            "April",
            "Mei",
            "Juni",
            "Juli",
            "Agustus",
            "September",
            "Oktober",
            "November",
            "Desember",
          ],
        },
      },
      allowInput: true,
    }),
  };

  // ============================================================
  // MODUL SUBMIT FORM
  // ============================================================

  const FormCreateMitra = {
    init: function () {
      $("#btn-submit-create-mitra").on("click", (e) => {
        e.preventDefault();
        this.handleSubmit();
      });
    },

    handleSubmit: function () {
      const formData = this.getFormData();
      const validationErrors = this.validateFormData(formData);

      jQueryHelpers.clearAllErrors("formMitra");

      if (validationErrors.length > 0) {
        validationErrors.forEach((error) => {
          jQueryHelpers.showError(error.fieldId, error.errorId, error.message);
        });
        return;
      }

      const submitData = this.prepareFormData(formData);
      const buttonState = jQueryHelpers.disableButton(
        "btn-submit-create-mitra",
        "Menyimpan ..."
      );

      jQueryHelpers.makeAjaxRequest({
        url: "/applied-informatics/mitra/create",
        method: "POST",
        data: submitData,
        processData: false,
        contentType: false,
        onSuccess: (response) => {
          if (response.success) {
            jQueryHelpers.showAlert(
              "Data mitra berhasil ditambahkan!",
              "success"
            );
            setTimeout(() => {
              window.location.href = "/applied-informatics/mitra";
            }, 500);
          } else {
            jQueryHelpers.showAlert("Gagal: " + response.message, "danger", 5000);
            buttonState.enable();
          }
        },
        onError: (errorMessage) => {
          jQueryHelpers.showAlert("Error: " + errorMessage, "danger");
          buttonState.enable();
        },
      });
    },
    getFormData: () => {
      return {
        nama: $("#nama_mitra").val().trim(),
        status: $("#status_mitra").val(), // Select tidak perlu trim
        logo_mitra: $("#logo_mitra")[0].files[0],
        tanggal_mulai: $("#tanggal_mulai").val().trim(),
        tanggal_akhir: $("#tanggal_akhir").val().trim(),
        deskripsi: $("#deskripsi").val().trim(),
      };
    },
    validateFormData: (data) => {
      const errors = [];

      const nameValidation = validationHelpers.validateName(data.nama, 1, 255);
      if (!nameValidation.valid) {
        errors.push({
          fieldId: "nama_mitra",
          errorId: "namaMitraError",
          message: nameValidation.message,
        });
      }

      if (!data.tanggal_mulai || data.tanggal_mulai.length < 1) {
        errors.push({
          fieldId: "tanggal_mulai",
          errorId: "tanggalMulaiError",
          message: "Tanggal Mulai Wajib Diisi",
        });
      }

      if (data.tanggal_akhir < data.tanggal_mulai) {
        errors.push({
          fieldId: "tanggal_akhir",
          errorId: "tanggalAkhirError",
          message: "Tanggal akhir tidak boleh lebih awal dari tanggal mulai",
        });
      }

      if (data.logo_mitra) {
        const sizeValidation = validationHelpers.validateFileSize(
          data.logo_mitra,
          2
        );

        if (!sizeValidation.valid) {
          errors.push({
            fieldId: "logo_mitra",
            errorId: "logoMitraError",
            message: sizeValidation.message,
          });
        }

        const typeValidation = validationHelpers.validateFileType(
          data.logo_mitra,
          ["image/jpeg", "image/jpg", "image/png"]
        );
        if (!typeValidation.valid) {
          errors.push({
            fieldId: "logo_mitra",
            errorId: "logoMitraError",
            message: typeValidation.message,
          });
        }
      }

      return errors;
    },
    prepareFormData: (data) => {
      const formData = new FormData();

      formData.append("nama", data.nama);
      formData.append("status", data.status);
      formData.append("tanggal_mulai", data.tanggal_mulai);
      formData.append("tanggal_akhir", data.tanggal_akhir);
      formData.append("deskripsi", data.deskripsi);

      if (data.logo_mitra) {
        formData.append("logo_mitra", data.logo_mitra);
      }

      return formData;
    },
  };

  // ============================================================
  // INISIALISASI SEMUA MODUL
  // ============================================================

  $(document).ready(function () {
    // FileUploadModule.init();
    // SingleDropdownModule.init();
    // MultipleDropdownModule.init();
    // AddJabatanModule.init();
    // AddKeahlianModule.init();
    InitializeFlatpickr.init();
    FormCreateMitra.init();
  });
})();
