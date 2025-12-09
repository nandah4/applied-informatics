<?php

/**
 * File: Helpers/ValidationHelper.php
 * Deskripsi: Helper untuk validasi input data
 *
 * Fungsi:
 * - Validasi format email
 * - Validasi NIDN
 * - Validasi panjang string
 * - Validasi required field
 * - Dan validasi umum lainnya
 */

class ValidationHelper
{
    /**
     * Validasi email
     *
     * @param string $email - Email yang akan divalidasi
     * @return array - ['valid' => bool, 'message' => string]
     */
    public static function validateEmail($email)
    {
        // Cek apakah kosong
        if (empty($email)) {
            return [
                'valid' => false,
                'message' => 'Email tidak boleh kosong'
            ];
        }

        // Validasi format email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'valid' => false,
                'message' => 'Format email tidak valid'
            ];
        }

        // Validasi panjang maksimal
        if (strlen($email) > 150) {
            return [
                'valid' => false,
                'message' => 'Email maksimal 150 karakter'
            ];
        }

        return [
            'valid' => true,
            'message' => ''
        ];
    }

        /**
     * Validasi URL
     *
     * @param string $url - URL yang akan divalidasi
     * @param bool $required - Apakah field wajib diisi (default: false)
     * @return array - ['valid' => bool, 'message' => string]
     */
    public static function validateUrl($url, $required = false)
    {
        // Jika tidak required dan kosong, anggap valid
        if (!$required && empty($url)) {
            return [
                'valid' => true,
                'message' => ''
            ];
        }

        // Cek apakah kosong (jika required)
        if ($required && empty($url)) {
            return [
                'valid' => false,
                'message' => 'URL tidak boleh kosong'
            ];
        }

        // Validasi format URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return [
                'valid' => false,
                'message' => 'Format URL tidak valid'
            ];
        }

        return [
            'valid' => true,
            'message' => ''
        ];
    }

    /**
     * Validasi NIDN (Nomor Induk Dosen Nasional)
     * NIDN harus 10 digit angka
     *
     * @param string $nidn - NIDN yang akan divalidasi
     * @param bool $required - Apakah field wajib diisi (default: true)
     * @return array - ['valid' => bool, 'message' => string]
     */
    public static function validateNIDN($nidn, $required = true)
    {
        // Jika tidak required dan kosong, anggap valid
        if (!$required && empty($nidn)) {
            return [
                'valid' => true,
                'message' => ''
            ];
        }

        // Cek apakah kosong (jika required)
        if ($required && empty($nidn)) {
            return [
                'valid' => false,
                'message' => 'NIDN tidak boleh kosong'
            ];
        }

        // Validasi hanya angka
        if (!ctype_digit($nidn)) {
            return [
                'valid' => false,
                'message' => 'NIDN harus berisi angka saja'
            ];
        }

        // Validasi panjang harus lebih 10 digit
        if (strlen($nidn) < 10) {
            return [
                'valid' => false,
                'message' => 'NIDN minimal 10 digit'
            ];
        }

        return [
            'valid' => true,
            'message' => ''
        ];
    }

        /**
     * Validasi NIP (Nomor Induk Pegawai)
     * NIP harus 18 digit angka
     *
     * @param string $nip - NIP yang akan divalidasi
     * @param bool $required - Apakah field wajib diisi (default: false)
     * @return array - ['valid' => bool, 'message' => string]
     */
    public static function validateNIP($nip, $required = false)
    {
        // Jika tidak required dan kosong, anggap valid
        if (!$required && empty($nip)) {
            return [
                'valid' => true,
                'message' => ''
            ];
        }

        // Cek apakah kosong (jika required)
        if ($required && empty($nip)) {
            return [
                'valid' => false,
                'message' => 'NIP tidak boleh kosong'
            ];
        }

        // Validasi hanya angka
        if (!ctype_digit($nip)) {
            return [
                'valid' => false,
                'message' => 'NIP harus berisi angka saja'
            ];
        }

        // Validasi panjang harus 18 digit
        if (strlen($nip) < 18) {
            return [
                'valid' => false,
                'message' => 'NIP minimal harus 18 digit'
            ];
        }

        return [
            'valid' => true,
            'message' => ''
        ];
    }

    /**
     * Validasi nama lengkap
     *
     * @param string $name - Nama yang akan divalidasi
     * @param int $minLength - Panjang minimal (default: 3)
     * @param int $maxLength - Panjang maksimal (default: 255)
     * @return array - ['valid' => bool, 'message' => string]
     */
    public static function validateName($name, $minLength = 3, $maxLength = 255)
    {
        // Cek apakah kosong
        if (empty($name)) {
            return [
                'valid' => false,
                'message' => 'Nama tidak boleh kosong'
            ];
        }

        // Cek panjang minimal
        if (strlen($name) < $minLength) {
            return [
                'valid' => false,
                'message' => "Nama minimal {$minLength} karakter"
            ];
        }

        // Cek panjang maksimal
        if (strlen($name) > $maxLength) {
            return [
                'valid' => false,
                'message' => "Nama maksimal {$maxLength} karakter"
            ];
        }

        return [
            'valid' => true,
            'message' => ''
        ];
    }

    /**
     * Validasi field required (tidak boleh kosong)
     *
     * @param mixed $value - Nilai yang akan divalidasi
     * @param string $fieldName - Nama field (untuk pesan error)
     * @return array - ['valid' => bool, 'message' => string]
     */
    public static function validateRequired($value, $fieldName = 'Field')
    {
        if (empty($value) && $value !== '0') {
            return [
                'valid' => false,
                'message' => "{$fieldName} tidak boleh kosong"
            ];
        }

        return [
            'valid' => true,
            'message' => ''
        ];
    }

    /**
     * Validasi ID (harus numeric dan > 0)
     *
     * @param mixed $id - ID yang akan divalidasi
     * @param string $fieldName - Nama field (untuk pesan error)
     * @return array - ['valid' => bool, 'message' => string]
     */
    public static function validateId($id, $fieldName = 'ID')
    {
        if (empty($id) || !is_numeric($id) || $id <= 0) {
            return [
                'valid' => false,
                'message' => "{$fieldName} tidak valid"
            ];
        }

        return [
            'valid' => true,
            'message' => ''
        ];
    }

    /**
     * Validasi array IDs (comma-separated string atau array)
     * Contoh: "1,3,5" atau [1, 3, 5]
     *
     * @param mixed $ids - IDs dalam format string atau array
     * @param string $fieldName - Nama field (untuk pesan error)
     * @param bool $required - Apakah wajib diisi
     * @return array - ['valid' => bool, 'message' => string, 'data' => array]
     */
    public static function validateIds($ids, $fieldName = 'IDs', $required = true)
    {
        // Jika tidak required dan kosong, anggap valid
        if (!$required && empty($ids)) {
            return [
                'valid' => true,
                'message' => '',
                'data' => []
            ];
        }

        // Cek apakah kosong (jika required)
        if ($required && empty($ids)) {
            return [
                'valid' => false,
                'message' => "{$fieldName} tidak boleh kosong",
                'data' => []
            ];
        }

        // Convert string ke array jika perlu
        if (is_string($ids)) {
            $idsArray = explode(',', $ids);
        } else if (is_array($ids)) {
            $idsArray = $ids;
        } else {
            return [
                'valid' => false,
                'message' => "{$fieldName} format tidak valid",
                'data' => []
            ];
        }

        // Validasi setiap ID harus numeric
        $validIds = [];
        foreach ($idsArray as $id) {
            $id = trim($id);
            if (!is_numeric($id) || $id <= 0) {
                return [
                    'valid' => false,
                    'message' => "{$fieldName} mengandung ID yang tidak valid",
                    'data' => []
                ];
            }
            $validIds[] = (int)$id;
        }

        return [
            'valid' => true,
            'message' => '',
            'data' => $validIds
        ];
    }

    /**
     * Validasi text/deskripsi
     *
     * @param string $text - Text yang akan divalidasi
     * @param int $maxLength - Panjang maksimal (default: 5000)
     * @param bool $required - Apakah wajib diisi (default: false)
     * @return array - ['valid' => bool, 'message' => string]
     */
    public static function validateText($text, $maxLength = 5000, $required = false)
    {
        // Jika tidak required dan kosong, anggap valid
        if (!$required && empty($text)) {
            return [
                'valid' => true,
                'message' => ''
            ];
        }

        // Cek apakah kosong (jika required)
        if ($required && empty($text)) {
            return [
                'valid' => false,
                'message' => 'Text tidak boleh kosong'
            ];
        }

        // Cek panjang maksimal
        if (strlen($text) > $maxLength) {
            return [
                'valid' => false,
                'message' => "Text maksimal {$maxLength} karakter"
            ];
        }

        return [
            'valid' => true,
            'message' => ''
        ];
    }

    /**
     * Sanitize input untuk mencegah XSS
     *
     * @param string $input - Input yang akan di-sanitize
     * @return string - Input yang sudah di-sanitize
     */
    // public static function sanitize($input)
    // {
    //     if (is_array($input)) {
    //         return array_map([self::class, 'sanitize'], $input);
    //     }

    //     $input = trim($input);
    //     $input = stripslashes($input);
    //     $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

    //     return $input;
    // }
}
