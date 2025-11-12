/**
 * File: helpers/validationHelpers.js
 * Deskripsi: Helper validasi client-side (mirror dari ValidationHelper.php di server-side)
 *
 * Fungsi:
 * - validateEmail(): Validasi format email
 * - validateNIDN(): Validasi NIDN (10 digit)
 * - validateName(): Validasi panjang nama
 * - validateRequired(): Validasi field wajib diisi
 * - validateFileSize(): Validasi ukuran file
 * - validateFileType(): Validasi tipe file
 */

const validationHelpers = {
    /**
     * Validasi format email
     *
     * @param {string} email - Email yang akan divalidasi
     * @returns {Object} - {valid: boolean, message: string}
     */
    validateEmail: function(email) {
        if (!email || email.trim().length === 0) {
            return {
                valid: false,
                message: 'Email wajib diisi'
            };
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            return {
                valid: false,
                message: 'Format email tidak valid'
            };
        }

        if (email.length > 150) {
            return {
                valid: false,
                message: 'Email maksimal 150 karakter'
            };
        }

        return {
            valid: true,
            message: ''
        };
    },

    /**
     * Validasi NIDN (Nomor Induk Dosen Nasional)
     * Harus minimal 10 digit
     *
     * @param {string} nidn - NIDN yang akan divalidasi
     * @param {boolean} required - Apakah field ini wajib diisi (default: true)
     * @returns {Object} - {valid: boolean, message: string}
     */
    validateNIDN: function(nidn, required = true) {
        // Jika tidak wajib dan kosong, anggap valid
        if (!required && (!nidn || nidn.trim().length === 0)) {
            return {
                valid: true,
                message: ''
            };
        }

        // Cek apakah kosong (jika wajib)
        if (required && (!nidn || nidn.trim().length === 0)) {
            return {
                valid: false,
                message: 'NIDN wajib diisi'
            };
        }

        // Cek apakah hanya angka
        if (!/^\d+$/.test(nidn)) {
            return {
                valid: false,
                message: 'NIDN harus berisi angka saja'
            };
        }

        // Cek panjang (minimal 10 digit)
        if (nidn.length < 10) {
            return {
                valid: false,
                message: 'NIDN minimal 10 digit'
            };
        }

        return {
            valid: true,
            message: ''
        };
    },

    /**
     * Validasi panjang nama
     *
     * @param {string} name - Nama yang akan divalidasi
     * @param {number} minLength - Panjang minimal (default: 3)
     * @param {number} maxLength - Panjang maksimal (default: 255)
     * @returns {Object} - {valid: boolean, message: string}
     */
    validateName: function(name, minLength = 3, maxLength = 255) {
        if (!name || name.trim().length === 0) {
            return {
                valid: false,
                message: 'Nama wajib diisi'
            };
        }

        if (name.length < minLength) {
            return {
                valid: false,
                message: `Nama minimal ${minLength} karakter`
            };
        }

        if (name.length > maxLength) {
            return {
                valid: false,
                message: `Nama maksimal ${maxLength} karakter`
            };
        }

        return {
            valid: true,
            message: ''
        };
    },

    /**
     * Validasi field wajib diisi
     *
     * @param {*} value - Nilai yang akan divalidasi
     * @param {string} fieldName - Nama field untuk pesan error
     * @returns {Object} - {valid: boolean, message: string}
     */
    validateRequired: function(value, fieldName = 'Field') {
        if (!value || (typeof value === 'string' && value.trim().length === 0)) {
            return {
                valid: false,
                message: `${fieldName} wajib diisi`
            };
        }

        return {
            valid: true,
            message: ''
        };
    },

    /**
     * Validasi ukuran file
     *
     * @param {File} file - Object file
     * @param {number} maxSizeInMB - Ukuran maksimal dalam MB (default: 2)
     * @returns {Object} - {valid: boolean, message: string}
     */
    validateFileSize: function(file, maxSizeInMB = 2) {
        if (!file) {
            return {
                valid: true,
                message: ''
            };
        }

        const maxSizeInBytes = maxSizeInMB * 1024 * 1024;

        if (file.size > maxSizeInBytes) {
            return {
                valid: false,
                message: `Ukuran file maksimal ${maxSizeInMB}MB`
            };
        }

        return {
            valid: true,
            message: ''
        };
    },

    /**
     * Validasi tipe file
     *
     * @param {File} file - Object file
     * @param {Array} allowedTypes - Array tipe MIME atau ekstensi yang diizinkan
     * @returns {Object} - {valid: boolean, message: string}
     */
    validateFileType: function(file, allowedTypes = ['image/jpeg', 'image/jpg', 'image/png']) {
        if (!file) {
            return {
                valid: true,
                message: ''
            };
        }

        const fileType = file.type.toLowerCase();
        const fileName = file.name.toLowerCase();
        const fileExtension = fileName.substring(fileName.lastIndexOf('.'));

        // Cek tipe MIME atau ekstensi
        const isValid = allowedTypes.some(type => {
            if (type.startsWith('.')) {
                return fileExtension === type;
            } else {
                return fileType === type;
            }
        });

        if (!isValid) {
            return {
                valid: false,
                message: `Tipe file tidak diizinkan. Diizinkan: ${allowedTypes.join(', ')}`
            };
        }

        return {
            valid: true,
            message: ''
        };
    },

    /**
     * Validasi panjang text
     *
     * @param {string} text - Text yang akan divalidasi
     * @param {number} maxLength - Panjang maksimal (default: 5000)
     * @param {boolean} required - Apakah wajib diisi (default: false)
     * @returns {Object} - {valid: boolean, message: string}
     */
    validateText: function(text, maxLength = 5000, required = false) {
        // Jika tidak wajib dan kosong, anggap valid
        if (!required && (!text || text.trim().length === 0)) {
            return {
                valid: true,
                message: ''
            };
        }

        // Cek apakah kosong (jika wajib)
        if (required && (!text || text.trim().length === 0)) {
            return {
                valid: false,
                message: 'Text wajib diisi'
            };
        }

        // Cek panjang maksimal
        if (text.length > maxLength) {
            return {
                valid: false,
                message: `Text maksimal ${maxLength} karakter`
            };
        }

        return {
            valid: true,
            message: ''
        };
    },

    /**
     * Validasi multiple selection (minimal satu harus dipilih)
     *
     * @param {string|Array} values - String comma-separated atau array nilai
     * @param {string} fieldName - Nama field untuk pesan error
     * @returns {Object} - {valid: boolean, message: string}
     */
    validateMultipleSelection: function(values, fieldName = 'Field') {
        if (!values) {
            return {
                valid: false,
                message: `Minimal satu ${fieldName} harus dipilih`
            };
        }

        // Jika string, split dengan koma
        if (typeof values === 'string') {
            values = values.split(',').filter(v => v.trim().length > 0);
        }

        if (!Array.isArray(values) || values.length === 0) {
            return {
                valid: false,
                message: `Minimal satu ${fieldName} harus dipilih`
            };
        }

        return {
            valid: true,
            message: ''
        };
    }
};

// Buat tersedia secara global
window.validationHelpers = validationHelpers;
