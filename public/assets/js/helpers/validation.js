/**
 * Validasi email
 * @param {string} email - Email yang akan divalidasi
 * @returns {object} - {valid: boolean, message: string}
 */
function validateEmail(email) {
    if(email === '' || email.length < 1) {
        return {
            valid: false,
            message: 'Email tidak boleh kosong!'
        }
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if(!emailRegex.test(email)) {
        return {
            valid: false,
            message: 'Format email tidak valid!'
        }
    }

    return {
            valid: true,
            message: ''
    }
}
