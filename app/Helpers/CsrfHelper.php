<?php

/**
 * File: Helpers/CsrfHelper.php
 * Description: Helper untuk CSRF (Cross-Site Request Forgery) protection
 */

class CsrfHelper
{
    /**
     * Generate CSRF token dan simpan di session
     * @return string
     */
    public static function generateToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Validasi CSRF token
     * @param string $token - Token dari form/request
     * @return bool
     */
    public static function validateToken($token)
    {
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }

        // Gunakan hash_equals untuk mencegah timing attack
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Regenerate CSRF token (gunakan setelah successful action)
     * @return string
     */
    public static function regenerateToken()
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }

    /**
     * Generate hidden input field dengan CSRF token
     * @return string
     */
    public static function tokenField()
    {
        $token = self::generateToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}
