<?php

namespace App\Middleware;
use App\Helpers\SessionHelper;

/**
 * Class AuthMiddleware
 * 
 * Middleware untuk memastikan user sudah login
 * 
 * Fungsi:
 * - Cek apakah session 'user_id' ada
 * - Jika TIDAK ada → redirect ke halaman beranda
 * - Jika ada → lanjutkan ke controller
 * 
 * Cara pakai:
 * $router->get('dashboard', ..., [AuthMiddleware::class]);
 */

class AuthMiddleware
{

    /**
     * Handle middleware logic
     */
    public function handle()
    {
        // 1. Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('/'));
            exit;
        }

        // 2. Check if user has admin role
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo '<h1>403 - Akses Ditolak</h1>';
            exit;
        }

        // 3. Check session timeout
        $timeoutCheck = SessionHelper::isSessionExpired();

        if ($timeoutCheck['expired']) {
            // Session expired, destroy session and redirect to login
            SessionHelper::destroySession();

            // Set flash message untuk informasi user
            session_start(); // Start new session untuk flash message
            $_SESSION['timeout_reason'] = $timeoutCheck['reason'];
            $_SESSION['timeout_message'] = $this->getTimeoutMessage($timeoutCheck);

            header('Location: ' . base_url('/'));
            exit;
        }

        // 4. Update last activity time
        SessionHelper::updateLastActivity();

        // 5. Regenerate session ID periodically untuk security
        SessionHelper::regenerateSessionIfNeeded();
    }

    /**
     * Get user-friendly timeout message
     *
     * @param array $timeoutCheck
     * @return string
     */
    private function getTimeoutMessage($timeoutCheck)
    {
        switch ($timeoutCheck['reason']) {
            case 'idle_timeout':
                $minutes = $timeoutCheck['idle_minutes'] ?? 0;
                return "Sesi Anda telah berakhir karena tidak ada aktivitas selama {$minutes} menit. Silakan login kembali.";

            case 'absolute_timeout':
                $hours = $timeoutCheck['login_hours'] ?? 0;
                return "Sesi Anda telah berakhir karena sudah login selama {$hours} jam. Silakan login kembali untuk keamanan.";

            default:
                return "Sesi Anda telah berakhir. Silakan login kembali.";
        }
    }
}
