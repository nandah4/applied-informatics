<?php

namespace App\Middleware;

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
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('/'));
            exit;
        }

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo '<h1>403 - Akses Ditolak</h1>';
            exit;
        }
    }
}
