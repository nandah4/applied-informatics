<?php

/**
 * File: routes.php
 * Daftar semua route aplikasi
 * 
 * Format:
 * $router->get(path, controller, method, [middleware])
 * $router->post(path, controller, method, [middleware])
 */

use App\Core\Router;
use App\Middleware\AuthMiddleware;

$router = new Router();

// ========================================
// PUBLIC ROUTES (Tanpa Middleware)
// ========================================

// Beranda
$router->get('/', function () {
    require __DIR__ . '/../app/Views/home/index.php';
});

// Halaman login (GET - tampilkan form)
$router->get('login', function () {
    require __DIR__ . '/../app/Views/auth/login.php';
    exit;
});

// ========================================
// AUTH ROUTES (POST - Submit Form)
// ========================================

// Proses login (POST)
$router->post('login', function () {
    $controller = new AuthController();
    $controller->handleLogin();
});
$router->get('logout', function () {
    session_unset();
    session_destroy();
    header("Location: " . base_url('/'));
    exit;
});

// ========================================
// PROTECTED ROUTES (Hanya untuk ADMIN)
// ========================================

// Dashboard - hanya admin yang bisa akses
$router->get('dashboard', function () {
    require __DIR__ . '/../app/Views/admin/index.php';
}, [AuthMiddleware::class]);


// ========================================
// ROUTES DOSEN CRUD
// ========================================

// GET : untuk mengakses halaman list dosen
  $router->get('dosen', function () {
      $controller = new DosenController();
      $result = $controller->getAllDosen();

      $listDosen = $result['data'];
      $pagination = $result['pagination'];

      require __DIR__ . '/../app/Views/admin/dosen/index.php';
  }, [AuthMiddleware::class]);;

// GET : untuk mengakses halaman create dosen
$router->get('dosen/create', function () {
    $controller = new DosenController();
    $jabatanData = $controller->getAllJabatan();
    $listJabatan = $jabatanData['data'] ?? [];

    $keahlianData = $controller->getAllKeahlian();
    $listKeahlian = $keahlianData['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/dosen/create.php';
}, [AuthMiddleware::class]);

// GET: Show edit form dengan data dosen
$router->get('dosen/edit/(\d+)', function ($id) {
    $controller = new DosenController();

    // Get data dosen by ID
    $dosenData = $controller->getDosenById((int)$id);

    if (!$dosenData['success']) {
        // Jika dosen tidak ditemukan, redirect ke list
        header("Location: " . base_url('dosen'));
        exit;
    }

    $dosen = $dosenData['data'];

    // Get dropdown data untuk jabatan dan keahlian
    $jabatanData = $controller->getAllJabatan();
    $listJabatan = $jabatanData['data'] ?? [];

    $keahlianData = $controller->getAllKeahlian();
    $listKeahlian = $keahlianData['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/dosen/edit.php';
}, [AuthMiddleware::class]);

// GET: Menampilkan data dosen di halaman detail
$router->get('dosen/detail/(\d+)', function ($id) {

    $controller = new DosenController();

    // Get data dosen by ID
    $dosenData = $controller->getDosenById((int)$id);

    if (!$dosenData['success']) {
        // Jika dosen tidak ditemukan, redirect ke list
        header("Location: " . base_url('dosen'));
        exit;
    }

    // Berisi data detail dosen
    $dosen = $dosenData['data'];

    $jabatanData = $controller->getAllJabatan();
    $listJabatan = [];
    foreach ($jabatanData['data'] ?? [] as $jab) {
        $listJabatan[$jab['id']] = $jab['jabatan'];
    }

    $keahlianData = $controller->getKeahlianByDosenID($id);
    $listKeahlian = $keahlianData['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/dosen/read.php';
}, [AuthMiddleware::class]);


// POST: Handle form submit create dosen 
$router->post('dosen/create', function () {
    $controller = new DosenController();
    $controller->createDosen();
}, [AuthMiddleware::class]);

// POST: Handle update dosen
$router->post('dosen/update', function () {
    $controller = new DosenController();
    $controller->updateDosen();
}, [AuthMiddleware::class]);

// POST: Menghapus data dosen by id dosen
$router->post('dosen/delete/(\d+)', function ($id) {
    $controller = new DosenController();
    $controller->deleteDosenByID($id);
}, [AuthMiddleware::class]);


// Fasilitas - hanya admin yang bisa akses
$router->get('fasilitas', function () {
    require __DIR__ . '/../app/Views/admin/fasilitas/index.php';
}, [AuthMiddleware::class]);
$router->get('fasilitas/create', function () {
    require __DIR__ . '/../app/Views/admin/fasilitas/create.php';
}, [AuthMiddleware::class]);
$router->get('fasilitas/detail', function () {
    require __DIR__ . '/../app/Views/admin/fasilitas/read.php';
}, [AuthMiddleware::class]);
$router->get('fasilitas/edit', function () {
    require __DIR__ . '/../app/Views/admin/fasilitas/edit.php';
}, [AuthMiddleware::class]);

// Fasilitas - hanya admin yang bisa akses
$router->get('produk', function () {
    require __DIR__ . '/../app/Views/admin/produk/index.php';
}, [AuthMiddleware::class]);
$router->get('produk/create', function () {
    require __DIR__ . '/../app/Views/admin/produk/create.php';
}, [AuthMiddleware::class]);
$router->get('produk/detail', function () {
    require __DIR__ . '/../app/Views/admin/produk/read.php';
}, [AuthMiddleware::class]);
$router->get('produk/edit', function () {
    require __DIR__ . '/../app/Views/admin/produk/edit.php';
}, [AuthMiddleware::class]);

// Fasilitas - hanya admin yang bisa akses
$router->get('mitra', function () {
    require __DIR__ . '/../app/Views/admin/mitra/index.php';
}, [AuthMiddleware::class]);
$router->get('mitra/create', function () {
    require __DIR__ . '/../app/Views/admin/mitra/create.php';
}, [AuthMiddleware::class]);
$router->get('mitra/detail', function () {
    require __DIR__ . '/../app/Views/admin/mitra/read.php';
}, [AuthMiddleware::class]);
$router->get('mitra/edit', function () {
    require __DIR__ . '/../app/Views/admin/mitra/edit.php';
}, [AuthMiddleware::class]);

// ========================================
// ROUTES JABATAN
// ========================================

// Tambah jabatan
$router->post('dosen/create-jabatan', function () {
    $controller = new DosenController();
    $controller->createJabatan();
}, [AuthMiddleware::class]);

// Hapus jabatan
$router->post('dosen/delete-jabatan', function () {
    $controller = new DosenController();
    $controller->deleteJabatan();
}, [AuthMiddleware::class]);

// ========================================
// ROUTES KEAHLIAN
// ========================================
$router->post('dosen/create-keahlian', function () {
    $controller = new DosenController();
    $controller->createKeahlian();
}, [AuthMiddleware::class]);

// Hapus keahlian
$router->post('dosen/delete-keahlian', function () {
    $controller = new DosenController();
    $controller->deleteKeahlian();
}, [AuthMiddleware::class]);

return $router;
