/**
 * File: helpers/jQueryHelpers.js
 * Deskripsi: Fungsi-fungsi utility jQuery yang dapat digunakan kembali untuk operasi umum
 *
 * Fungsi:
 * - showError(): Tampilkan pesan error untuk form field
 * - clearError(): Hapus pesan error dari form field
 * - makeAjaxRequest(): Buat AJAX request dengan error handling terstandar
 * - showAlert(): Tampilkan notifikasi Bootstrap alert
 */

const jQueryHelpers = {
  /**
   * Tampilkan pesan error untuk sebuah form field
   *
   * @param {string} fieldId - ID dari input field
   * @param {string} errorId - ID dari container pesan error
   * @param {string} message - Pesan error yang akan ditampilkan
   */
  showError: function (fieldId, errorId, message) {
    $(`#${fieldId}`).addClass("is-invalid");
    $(`#${errorId}`).text(message).show();
  },

  /**
   * Hapus pesan error dari sebuah form field
   *
   * @param {string} fieldId - ID dari input field
   * @param {string} errorId - ID dari container pesan error
   */
  clearError: function (fieldId, errorId) {
    $(`#${fieldId}`).removeClass("is-invalid");
    $(`#${errorId}`).text("").hide();
  },

  /**
   * Hapus semua error dari sebuah form
   *
   * @param {string} formId - ID dari form
   */
  clearAllErrors: function (formId) {
    $(`#${formId} .is-invalid`).removeClass("is-invalid");
    $(`#${formId} .invalid-feedback`).text("").hide();
  },

  /**
   * Buat AJAX request dengan konfigurasi dan error handling terstandar
   *
   * @param {Object} config - Konfigurasi AJAX
   * @param {string} config.url - URL request
   * @param {string} config.method - HTTP method (GET, POST, dll)
   * @param {Object|FormData} config.data - Data request
   * @param {Function} config.onSuccess - Fungsi callback untuk success
   * @param {Function} config.onError - Fungsi callback untuk error (opsional)
   * @param {Function} config.onComplete - Fungsi callback untuk complete (opsional)
   * @param {boolean} config.processData - Process data (default: true, set false untuk FormData)
   * @param {boolean} config.contentType - Content type (default: application/x-www-form-urlencoded)
   */
  makeAjaxRequest: function (config) {
    $.ajax({
      url: config.url,
      type: config.method || "POST",
      data: config.data || {},
      processData: config.processData !== undefined ? config.processData : true,
      contentType:
        config.contentType !== undefined
          ? config.contentType
          : "application/x-www-form-urlencoded; charset=UTF-8",
      dataType: "json",
      success: function (response) {
        if (typeof config.onSuccess === "function") {
          config.onSuccess(response);
        }
      },
      error: function (xhr, status, error) {
        console.error("AJAX Error:", xhr.responseText);

        let errorMessage = "Terjadi kesalahan";
        try {
          const response = JSON.parse(xhr.responseText);
          errorMessage = response.message || errorMessage;
        } catch (e) {
          errorMessage = error || errorMessage;
        }

        if (typeof config.onError === "function") {
          config.onError(errorMessage, xhr);
        } else {
          alert("Error: " + errorMessage);
        }
      },
      complete: function () {
        if (typeof config.onComplete === "function") {
          config.onComplete();
        }
      },
    });
  },

  /**
   * Tampilkan notifikasi Bootstrap alert
   *
   * @param {string} message - Pesan alert
   * @param {string} type - Tipe alert (success, danger, warning, info)
   * @param {number} duration - Durasi auto-dismiss dalam milliseconds (default: 3000)
   */
  showAlert: function (message, type = "success", duration = 1000) {
    const alertPlaceholder = document.getElementById("liveAlertPlaceholder");
    if (!alertPlaceholder) {
      console.warn("Alert placeholder tidak ditemukan");
      return;
    }

    const wrapper = document.createElement("div");
    wrapper.innerHTML = [
      `<div class="alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" role="alert" style="z-index: 9999; min-width: 300px;">`,
      `   <div>${message}</div>`,
      '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
      "</div>",
    ].join("");

    alertPlaceholder.append(wrapper);

    // Auto dismiss
    if (duration > 0) {
      setTimeout(() => {
        const alert = bootstrap.Alert.getOrCreateInstance(
          wrapper.querySelector(".alert")
        );
        alert.close();
      }, duration);
    }
  },

  /**
   * Disable button dan ubah text (berguna saat form submission)
   *
   * @param {string} buttonId - ID dari button
   * @param {string} text - Text yang akan ditampilkan (default: 'Loading...')
   * @returns {Object} - Object dengan method enable() untuk re-enable button
   */
  disableButton: function (buttonId, text = "Loading...") {
    const $button = $(`#${buttonId}`);
    const originalText = $button.text();

    $button.prop("disabled", true).text(text);

    return {
      enable: function () {
        $button.prop("disabled", false).text(originalText);
      },
      getOriginalText: function () {
        return originalText;
      },
    };
  },

  /**
   * Toggle visibility element
   *
   * @param {string} elementId - ID dari element
   * @param {boolean} show - True untuk show, false untuk hide
   */
  toggleVisibility: function (elementId, show) {
    if (show) {
      $(`#${elementId}`).show();
    } else {
      $(`#${elementId}`).hide();
    }
  },

  /**
   * Serialize form data menjadi object
   *
   * @param {string} formId - ID dari form
   * @returns {Object} - Form data sebagai object
   */
  serializeFormToObject: function (formId) {
    const formArray = $(`#${formId}`).serializeArray();
    const formObject = {};

    $.each(formArray, function () {
      if (formObject[this.name]) {
        if (!formObject[this.name].push) {
          formObject[this.name] = [formObject[this.name]];
        }
        formObject[this.name].push(this.value || "");
      } else {
        formObject[this.name] = this.value || "";
      }
    });

    return formObject;
  },
};

// Buat tersedia secara global
window.jQueryHelpers = jQueryHelpers;
