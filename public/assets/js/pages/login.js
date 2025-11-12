/**
 * Login Page Scripts
 * Requires: helpers/validation.js (loaded before this script)
 */

$(document).ready( function(){
    setVisiblePassword();
    initFormSubmit();
})

// Ubah visibilitas input password 
function setVisiblePassword() {
    $('#btn-visible-pw').on('click', function() {
        const input =  $('#inputPassword')
        const icon = $(this).find('svg')

        const isPassword = input.attr('type') === 'password';
        input.attr('type', isPassword ? 'text' : 'password');

        // Ubah icon html dengan tag i
        const newIcon = isPassword ? 'eye-off' : 'eye';
        icon.html(`<i data-feather="${newIcon}"></i>`)
    feather.replace();
    })
}

  // ========== VALIDASI FUNCTIONS ==========


/**
 * Validasi password
 * @param {string} password - Password yang akan divalidasi
 * @returns {object} - {valid: boolean, message: string}
 */

function validatePassword(password) {
    if(password === '') {
        return {
            valid: false,
            message: 'Password tidak boleh kosong!'
        }
    }

    if(password.length < 8) {
        return {
            valid: false,
            message: 'Password minimal 8 karakter!'
        }
    }

    return {
            valid: true,
            message: ''
    }
}


  // ========== SHOW % CLEAR ERROR ==========

/**
 * Tampilkan error message
 * @param {*} fieldId - ID input field
 * @param {*} errorId - ID div error mess
 * @param {*} message - Pesan error
 */
function showError(fieldId, errorId, message) {
    $(`#${fieldId}`).addClass('is-invalid')
    $(`#${errorId}`).text(message).show();
}

/**
 * Hapus error message
 * @param {*} fieldId - ID input field
 * @param {*} errorId - ID div error mess
 */
function clearError(fieldId, errorId, message) {
    $(`#${fieldId}`).removeClass('is-invalid')
    $(`#${errorId}`).text('').hide();
}

/**
 * Tampilkan Bootstrap alert
 * @param {string} message - Pesan alert
 * @param {string} type - Tipe alert (success, danger, warning, info)
 */
function showBootstrapAlert(message, type = 'success') {
    const alertPlaceholder = document.getElementById('liveAlertPlaceholder');
    const wrapper = document.createElement('div');
    wrapper.innerHTML = [
        `<div class="alert alert-${type} alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3" role="alert" style="z-index: 9999; min-width: 300px;">`,
        `   <div>${message}</div>`,
        '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
        '</div>'
    ].join('');

    alertPlaceholder.append(wrapper);

    // Auto dismiss after 3 seconds
    setTimeout(() => {
        const alert = bootstrap.Alert.getOrCreateInstance(wrapper.querySelector('.alert'));
        alert.close();
    }, 3000);
}

function initFormSubmit() {
    $('#btn-submit').on('click', function(e) {
        e.preventDefault();

        const email = $('#email').val().trim()
        const password =  $('#password').val().trim()

        // Clear semua error
        clearError('email', 'emailError')
        clearError('password', 'passwordError')

        const emailValidation = validateEmail(email);
        if(!emailValidation.valid) {
            showError('email', 'emailError', emailValidation.message)
            return
        }

        const passwordValidation = validatePassword(password);
        if(!passwordValidation.valid) {
            showError('password', 'passwordError', passwordValidation.message)
            return
        }

        // Disable button to prevent double submission
        const $button = $('#btn-submit');
        const originalText = $button.text();
        $button.prop('disabled', true).text('Loading...');

        // Submit via AJAX
        $.ajax({
            url: `/applied-informatics/login`,
            type: 'POST',
            data: {
                email: email,
                password: password
            },
            dataType: 'json',
            success: function(response) {
                if(response.success) {


                    // Redirect ke dashboard
                    setTimeout(function() {
                        if(response.data && response.data.redirect) {
                            window.location.href = response.data.redirect;
                        } else {
                            // Fallback redirect
                            window.location.href = '/dashboard';
                        }
                    }, 0);
                } else {
                    // Show error message dengan Bootstrap alert
                    showError('inputPassword', 'passwordError', response.message)
                    // showBootstrapAlert('Login gagal: ' + response.message, 'danger');
                    $button.prop('disabled', false).text(originalText);
                }
            },
            error: function(xhr, error) {
                console.error('Login error:', error);
                let errorMessage = 'Terjadi kesalahan sistem';

                if(xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                }

                showBootstrapAlert('Login gagal: ' + errorMessage, 'danger');
                $button.prop('disabled', false).text(originalText);
            }
        });
    })
}