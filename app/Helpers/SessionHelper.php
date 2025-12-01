<?php

namespace App\Helpers;

/**
 * File: Helpers/SessionHelper.php
 * Deskripsi: Helper untuk menangani session management dan timeout
 *
 * Fitur:
 * - Idle timeout detection
 * - Absolute timeout detection
 * - Session regeneration untuk security
 * - Session destroy dengan proper cleanup
 */

class SessionHelper
{
    /**
     * Initialize session timestamps saat login
     * Set waktu login dan last activity
     *
     * @return void
     */
    public static function initSessionTimestamps()
    {
        $_SESSION['login_time'] = time();
        $_SESSION['last_activity'] = time();
        $_SESSION['last_regeneration'] = time();
    }

    /**
     * Update last activity timestamp
     * Dipanggil di setiap request untuk track aktivitas user
     *
     * @return void
     */
    public static function updateLastActivity()
    {
        $_SESSION['last_activity'] = time();
    }

    /**
     * Check apakah session sudah expired (idle atau absolute timeout)
     *
     * @return array ['expired' => bool, 'reason' => string]
     */
    public static function isSessionExpired()
    {
        // Jika tidak ada session data, anggap expired
        if (!isset($_SESSION['last_activity']) || !isset($_SESSION['login_time'])) {
            return [
                'expired' => true,
                'reason' => 'no_session'
            ];
        }

        $currentTime = time();

        // Check idle timeout
        $idleTime = $currentTime - $_SESSION['last_activity'];
        if ($idleTime > SESSION_IDLE_TIMEOUT) {
            return [
                'expired' => true,
                'reason' => 'idle_timeout',
                'idle_minutes' => round($idleTime / 60)
            ];
        }

        // Check absolute timeout
        $loginDuration = $currentTime - $_SESSION['login_time'];
        if ($loginDuration > SESSION_ABSOLUTE_TIMEOUT) {
            return [
                'expired' => true,
                'reason' => 'absolute_timeout',
                'login_hours' => round($loginDuration / 3600, 1)
            ];
        }

        // Session masih valid
        return [
            'expired' => false,
            'reason' => null
        ];
    }

    /**
     * Regenerate session ID untuk security
     * Mencegah session fixation attacks
     *
     * @return void
     */
    public static function regenerateSessionIfNeeded()
    {
        if (!isset($_SESSION['last_regeneration'])) {
            $_SESSION['last_regeneration'] = time();
            return;
        }

        $timeSinceRegeneration = time() - $_SESSION['last_regeneration'];

        if ($timeSinceRegeneration > SESSION_REGENERATION_INTERVAL) {
            // Regenerate session ID tapi keep data
            session_regenerate_id(true);
            $_SESSION['last_regeneration'] = time();
        }
    }

    /**
     * Destroy session dengan proper cleanup
     * Hapus semua session data dan cookie
     *
     * @return void
     */
    public static function destroySession()
    {
        // Unset all session variables
        $_SESSION = array();

        // Delete session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(
                session_name(),
                '',
                time() - 3600,
                '/'
            );
        }

        // Destroy session
        session_destroy();
    }

    /**
     * Get session info untuk debugging atau logging
     *
     * @return array
     */
    public static function getSessionInfo()
    {
        if (!isset($_SESSION['last_activity']) || !isset($_SESSION['login_time'])) {
            return [
                'active' => false
            ];
        }

        $currentTime = time();
        $idleTime = $currentTime - $_SESSION['last_activity'];
        $loginDuration = $currentTime - $_SESSION['login_time'];

        return [
            'active' => true,
            'user_id' => $_SESSION['user_id'] ?? null,
            'role' => $_SESSION['role'] ?? null,
            'login_time' => date('Y-m-d H:i:s', $_SESSION['login_time']),
            'last_activity' => date('Y-m-d H:i:s', $_SESSION['last_activity']),
            'idle_minutes' => round($idleTime / 60, 1),
            'login_hours' => round($loginDuration / 3600, 1),
            'idle_timeout_minutes' => SESSION_IDLE_TIMEOUT / 60,
            'absolute_timeout_hours' => SESSION_ABSOLUTE_TIMEOUT / 3600,
        ];
    }

    /**
     * Get remaining time sebelum idle timeout
     *
     * @return int Seconds remaining, atau 0 jika sudah timeout
     */
    public static function getRemainingIdleTime()
    {
        if (!isset($_SESSION['last_activity'])) {
            return 0;
        }

        $idleTime = time() - $_SESSION['last_activity'];
        $remaining = SESSION_IDLE_TIMEOUT - $idleTime;

        return max(0, $remaining);
    }

    /**
     * Get remaining time sebelum absolute timeout
     *
     * @return int Seconds remaining, atau 0 jika sudah timeout
     */
    public static function getRemainingAbsoluteTime()
    {
        if (!isset($_SESSION['login_time'])) {
            return 0;
        }

        $loginDuration = time() - $_SESSION['login_time'];
        $remaining = SESSION_ABSOLUTE_TIMEOUT - $loginDuration;

        return max(0, $remaining);
    }
}
