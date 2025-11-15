<?php

/**
 * ============================================================================
 * ROUTES CONFIGURATION
 * ============================================================================
 *
 * File: config/routes.php
 * Deskripsi: Konfigurasi semua route aplikasi Applied Informatics Lab
 *
 * Konvensi Penamaan:
 * - GET  : Menampilkan halaman/view
 * - POST : Handle form submission / AJAX request
 *
 * Middleware:
 * - AuthMiddleware::class : Memastikan user sudah login
 *
 * ============================================================================
 */

use App\Core\Router;
use App\Middleware\AuthMiddleware;

$router = new Router();

// ============================================================================
// PUBLIC ROUTES
// ============================================================================
// Routes yang bisa diakses tanpa login

/**
 * Homepage
 * URL: /
 */
$router->get('/', function () {
    require __DIR__ . '/../app/Views/home/index.php';
});

/**
 * Login Page
 * URL: /login
 */
$router->get('login', function () {
    require __DIR__ . '/../app/Views/auth/login.php';
    exit;
});

// ============================================================================
// AUTH ROUTES
// ============================================================================
// Routes untuk authentication (login, logout)

/**
 * Login - Handle Form Submit
 * URL: POST /login
 */
$router->post('login', function () {
    $controller = new AuthController();
    $controller->handleLogin();
});

/**
 * Logout
 * URL: GET /logout
 */
$router->get('logout', function () {
    session_unset();
    session_destroy();
    header("Location: " . base_url('/'));
    exit;
});

// ============================================================================
// ADMIN DASHBOARD
// ============================================================================
// Protected: Hanya admin yang bisa akses

/**
 * Admin Dashboard
 * URL: /dashboard
 */
$router->get('dashboard', function () {
    require __DIR__ . '/../app/Views/admin/index.php';
}, [AuthMiddleware::class]);

// ============================================================================
// DOSEN MANAGEMENT
// ============================================================================
// CRUD operations untuk data dosen

// ----------------------------------------
// READ Operations
// ----------------------------------------

/**
 * Dosen - List/Index (dengan Pagination)
 * URL: GET /dosen?page=1&per_page=10
 */
$router->get('dosen', function () {
    $controller = new DosenController();
    $result = $controller->getAllDosen();

    $listDosen = $result['data'];
    $pagination = $result['pagination'];

    require __DIR__ . '/../app/Views/admin/dosen/index.php';
}, [AuthMiddleware::class]);

/**
 * Dosen - Detail Page
 * URL: GET /dosen/detail/{id}
 */
$router->get('dosen/detail/(\d+)', function ($id) {
    $controller = new DosenController();

    // Get data dosen by ID
    $dosenData = $controller->getDosenById((int)$id);

    if (!$dosenData['success']) {
        header("Location: " . base_url('dosen'));
        exit;
    }

    $dosen = $dosenData['data'];

    // Get dropdown data
    $jabatanData = $controller->getAllJabatan();
    $listJabatan = [];
    foreach ($jabatanData['data'] ?? [] as $jab) {
        $listJabatan[$jab['id']] = $jab['jabatan'];
    }

    $keahlianData = $controller->getKeahlianByDosenID($id);
    $listKeahlian = $keahlianData['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/dosen/read.php';
}, [AuthMiddleware::class]);

// ----------------------------------------
// CREATE Operations
// ----------------------------------------

/**
 * Dosen - Create Page (Form)
 * URL: GET /dosen/create
 */
$router->get('dosen/create', function () {
    $controller = new DosenController();

    $jabatanData = $controller->getAllJabatan();
    $listJabatan = $jabatanData['data'] ?? [];

    $keahlianData = $controller->getAllKeahlian();
    $listKeahlian = $keahlianData['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/dosen/create.php';
}, [AuthMiddleware::class]);

/**
 * Dosen - Create (Handle Submit)
 * URL: POST /dosen/create
 */
$router->post('dosen/create', function () {
    $controller = new DosenController();
    $controller->createDosen();
}, [AuthMiddleware::class]);

// ----------------------------------------
// UPDATE Operations
// ----------------------------------------

/**
 * Dosen - Edit Page (Form)
 * URL: GET /dosen/edit/{id}
 */
$router->get('dosen/edit/(\d+)', function ($id) {
    $controller = new DosenController();

    // Get data dosen by ID
    $dosenData = $controller->getDosenById((int)$id);

    if (!$dosenData['success']) {
        header("Location: " . base_url('dosen'));
        exit;
    }

    $dosen = $dosenData['data'];

    // Get dropdown data
    $jabatanData = $controller->getAllJabatan();
    $listJabatan = $jabatanData['data'] ?? [];

    $keahlianData = $controller->getAllKeahlian();
    $listKeahlian = $keahlianData['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/dosen/edit.php';
}, [AuthMiddleware::class]);

/**
 * Dosen - Update (Handle Submit)
 * URL: POST /dosen/update
 */
$router->post('dosen/update', function () {
    $controller = new DosenController();
    $controller->updateDosen();
}, [AuthMiddleware::class]);

// ----------------------------------------
// DELETE Operations
// ----------------------------------------

/**
 * Dosen - Delete
 * URL: POST /dosen/delete/{id}
 */
$router->post('dosen/delete/(\d+)', function ($id) {
    $controller = new DosenController();
    $controller->deleteDosenByID($id);
}, [AuthMiddleware::class]);

// ----------------------------------------
// JABATAN Management (Sub-feature dari Dosen)
// ----------------------------------------

/**
 * Jabatan - Create
 * URL: POST /dosen/create-jabatan
 */
$router->post('dosen/create-jabatan', function () {
    $controller = new DosenController();
    $controller->createJabatan();
}, [AuthMiddleware::class]);

/**
 * Jabatan - Delete
 * URL: POST /dosen/delete-jabatan
 */
$router->post('dosen/delete-jabatan', function () {
    $controller = new DosenController();
    $controller->deleteJabatan();
}, [AuthMiddleware::class]);

// ----------------------------------------
// KEAHLIAN Management (Sub-feature dari Dosen)
// ----------------------------------------

/**
 * Keahlian - Create
 * URL: POST /dosen/create-keahlian
 */
$router->post('dosen/create-keahlian', function () {
    $controller = new DosenController();
    $controller->createKeahlian();
}, [AuthMiddleware::class]);

/**
 * Keahlian - Delete
 * URL: POST /dosen/delete-keahlian
 */
$router->post('dosen/delete-keahlian', function () {
    $controller = new DosenController();
    $controller->deleteKeahlian();
}, [AuthMiddleware::class]);

// ============================================================================
// FASILITAS MANAGEMENT
// ============================================================================
// CRUD operations untuk data fasilitas laboratorium

/**
 * Fasilitas - List/Index (dengan Pagination)
 * URL: GET /fasilitas?page=1&per_page=10
 * 
 */
$router->get('fasilitas', function () {
    $controller = new FasilitasController();
    $result = $controller->getAllFasilitas();

    $listFasilitas = $result['data'];      // Variable untuk view index.php
    $pagination = $result['pagination'];

    require __DIR__ . '/../app/Views/admin/fasilitas/index.php';
}, [AuthMiddleware::class]);

/**
 * Fasilitas - Create Page (Form)
 * URL: GET /fasilitas/create
 * 
 * Tidak perlu passing data, form kosong
 */
$router->get('fasilitas/create', function () {
    require __DIR__ . '/../app/Views/admin/fasilitas/create.php';
}, [AuthMiddleware::class]);

/**
 * Fasilitas - Create (Handle Submit)
 * URL: POST /fasilitas/create
 * 
 * Response JSON dari controller
 */
$router->post('fasilitas/create', function () {
    $controller = new FasilitasController();
    $controller->createFasilitas();
}, [AuthMiddleware::class]);

/**
 * Fasilitas - Detail Page
 * URL: GET /fasilitas/detail/{id}
 * 
 */
$router->get('fasilitas/detail/(\d+)', function ($id) {
    $controller = new FasilitasController();
    $result = $controller->getFasilitasById($id);

    // Jika data tidak ditemukan, redirect ke index
    if (!$result['success']) {
        header("Location: " . base_url('fasilitas'));
        exit;
    }

    $fasilitas = $result['data'];  // Variable untuk view read.php

    require __DIR__ . '/../app/Views/admin/fasilitas/read.php';
}, [AuthMiddleware::class]);

/**
 * Fasilitas - Edit Page (Form)
 * URL: GET /fasilitas/edit/{id}
 * 
 */
$router->get('fasilitas/edit/(\d+)', function ($id) {
    $controller = new FasilitasController();
    $result = $controller->getFasilitasById($id);

    // Jika data tidak ditemukan, redirect ke index
    if (!$result['success']) {
        header("Location: " . base_url('fasilitas'));
        exit;
    }

    $fasilitas = $result['data'];  // Variable untuk view edit.php

    require __DIR__ . '/../app/Views/admin/fasilitas/edit.php';
}, [AuthMiddleware::class]);

/**
 * Fasilitas - Update (Handle Submit)
 * URL: POST /fasilitas/update
 * 
 * Response JSON dari controller
 */
$router->post('fasilitas/update', function () {
    $controller = new FasilitasController();
    $controller->updateFasilitas();
}, [AuthMiddleware::class]);

/**
 * Fasilitas - Delete
 * URL: POST /fasilitas/delete/{id}
 * 
 * Response JSON dari controller
 */
$router->post('fasilitas/delete/(\d+)', function ($id) {
    $controller = new FasilitasController();
    $controller->deleteFasilitasById($id);
}, [AuthMiddleware::class]);

// ============================================================================
// PRODUK MANAGEMENT
// ============================================================================
// CRUD operations untuk data produk laboratorium

/**
 * Produk - List/Index
 * URL: GET /produk
 */
$router->get('produk', function () {
    require __DIR__ . '/../app/Views/admin/produk/index.php';
}, [AuthMiddleware::class]);

/**
 * Produk - Create Page
 * URL: GET /produk/create
 */
$router->get('produk/create', function () {
    require __DIR__ . '/../app/Views/admin/produk/create.php';
}, [AuthMiddleware::class]);

/**
 * Produk - Detail Page
 * URL: GET /produk/detail
 */
$router->get('produk/detail', function () {
    require __DIR__ . '/../app/Views/admin/produk/read.php';
}, [AuthMiddleware::class]);

/**
 * Produk - Edit Page
 * URL: GET /produk/edit
 */
$router->get('produk/edit', function () {
    require __DIR__ . '/../app/Views/admin/produk/edit.php';
}, [AuthMiddleware::class]);

// TODO: Tambahkan route POST untuk create, update, delete produk

// ============================================================================
// MITRA KERJASAMA MANAGEMENT
// ============================================================================
// CRUD operations untuk data mitra kerjasama

// ----------------------------------------
// READ Operations
// ----------------------------------------

/**
 * Mitra - List/Index dengan Pagination
 * URL: GET /mitra?page={number}&per_page={number}
 */
$router->get('mitra', function () {
    $controller = new MitraController();

    // Ambil parameter dari query string
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

    // Ambil data dengan pagination
    $result = $controller->getAllMitraWithPagination($page, $perPage);

    $listMitra = $result['data'] ?? [];
    $pagination = $result['pagination'] ?? null;

    require __DIR__ . '/../app/Views/admin/mitra/index.php';
}, [AuthMiddleware::class]);

/**
 * Mitra - Detail Page
 * URL: GET /mitra/detail/{id}
 */
$router->get('mitra/detail/(\d+)', function ($id) {
    $controller = new MitraController();

    // Get data mitra by ID
    $mitraData = $controller->getMitraById((int)$id);

    if (!$mitraData['success']) {
        header("Location: " . base_url('mitra'));
        exit;
    }

    $mitra = $mitraData['data'];

    require __DIR__ . '/../app/Views/admin/mitra/read.php';
}, [AuthMiddleware::class]);

// ----------------------------------------
// CREATE Operations
// ----------------------------------------

/**
 * Mitra - Create Page (Form)
 * URL: GET /mitra/create
 */
$router->get('mitra/create', function () {
    require __DIR__ . '/../app/Views/admin/mitra/create.php';
}, [AuthMiddleware::class]);

/**
 * Mitra - Create (Handle Submit)
 * URL: POST /mitra/create
 */
$router->post('mitra/create', function () {
    $controller = new MitraController();
    $controller->createMitra();
}, [AuthMiddleware::class]);

// ----------------------------------------
// UPDATE Operations
// ----------------------------------------

/**
 * Mitra - Edit Page (Form)
 * URL: GET /mitra/edit/{id}
 */
$router->get('mitra/edit/(\d+)', function ($id) {
    $controller = new MitraController();

    // Get data mitra by ID
    $mitraData = $controller->getMitraById((int)$id);

    if (!$mitraData['success']) {
        header("Location: " . base_url('mitra'));
        exit;
    }

    $mitra = $mitraData['data'];

    require __DIR__ . '/../app/Views/admin/mitra/edit.php';
}, [AuthMiddleware::class]);

/**
 * Mitra - Update (Handle Submit)
 * URL: POST /mitra/update
 */
$router->post('mitra/update', function () {
    $controller = new MitraController();
    $controller->updateMitra();
}, [AuthMiddleware::class]);

// ----------------------------------------
// DELETE Operations
// ----------------------------------------

/**
 * Mitra - Delete
 * URL: POST /mitra/delete/{id}
 */
$router->post('mitra/delete/(\d+)', function ($id) {
    $controller = new MitraController();
    $controller->deleteMitra($id);
}, [AuthMiddleware::class]);

// ============================================================================
// END OF ROUTES
// ============================================================================

return $router;
