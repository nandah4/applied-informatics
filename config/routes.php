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
    require __DIR__ . '/../app/Views/client/index.php';
});

/**
 * Tentang Kami
 * URL: /tentang-kami
 */
$router->get('tentang-kami', function () {
    require __DIR__ . '/../app/Views/client/tentang_kami.php';
});


/**
 * Produk Lab
 * URL: /produk-lab
 */
$router->get('produk-lab', function () {
    require __DIR__ . '/../app/Views/client/produk_lab.php';
});

/**
 * Aktivitas Lab
 * URL: /aktivitas-laboratorium
 */
$router->get('aktivitas-laboratorium', function () {
    require __DIR__ . '/../app/Views/client/aktivitas_lab.php';
});

/**
 * Publikasi Dosen
 * URL: /publikasi-dosen
 */
$router->get('publikasi-dosen', function () {
    require __DIR__ . '/../app/Views/client/publikasi_dosen.php';
});


// ============================================================================
// AUTH ROUTES
// ============================================================================
// Routes untuk authentication (login, logout)

/**
 * Login Page
 * URL: /login
 * Redirect ke dashboard jika sudah login
 */
$router->get('admin/login', function () {
    // Jika sudah login, redirect ke dashboard
    if (isset($_SESSION['user_id']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        header("Location: " . base_url('admin/dashboard'));
        exit;
    }

    require __DIR__ . '/../app/Views/auth/login.php';
    exit;
});

/**
 * Login - Handle Form Submit
 * URL: POST /login
 */
$router->post('admin/login', function () {
    $controller = new AuthController();
    $controller->handleLogin();
});

/**
 * Logout
 * URL: GET /logout
 *
 * Note: Idealnya logout menggunakan POST untuk keamanan,
 * tapi untuk kemudahan navigasi tetap menggunakan GET.
 */
$router->get('admin/logout', function () {
    // Hapus semua data session
    $_SESSION = [];

    // Hapus session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"],
            $params["httponly"]
        );
    }

    // Destroy session
    session_destroy();

    // Redirect ke homepage
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
$router->get('admin/dashboard', function () {
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
$router->get('admin/dosen', function () {
    $controller = new DosenController();
    $result = $controller->getAllDosen();

    $listDosen = $result['data'];
    $pagination = $result['pagination'];

    require __DIR__ . '/../app/Views/admin/dosen/index.php';
}, [AuthMiddleware::class]);

/**
 * Dosen - Detail Page
 * URL: GET /admin/dosen/detail/{id}
 */
$router->get('admin/dosen/detail/(\d+)', function ($id) {
    $controller = new DosenController();

    // Get data dosen by ID
    $response = $controller->getDosenById((int)$id);
    $dosenData = $response["data"];

    if (!$response['success']) {
        header("Location: " . base_url('admin/dosen'));
        exit;
    }

    // Get profil publikasi dosen
    $profilPublikasiResponse = $controller->getProfilPublikasi((int)$id);
    $profilPublikasi = $profilPublikasiResponse['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/dosen/read.php';
}, [AuthMiddleware::class]);

// ----------------------------------------
// CREATE Operations
// ----------------------------------------

/**
 * Dosen - Create Page (Form)
 * URL: GET /admin/dosen/create
 */
$router->get('admin/dosen/create', function () {
    $controller = new DosenController();
    $jabatanController = new JabatanController();
    $keahlianController = new KeahlianController();

    $jabatanData = $jabatanController->getAllJabatan();
    $listJabatan = $jabatanData['data'] ?? [];

    $keahlianData = $keahlianController->getAllKeahlian();
    $listKeahlian = $keahlianData['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/dosen/create.php';
}, [AuthMiddleware::class]);

/**
 * Dosen - Create (Handle Submit)
 * URL: POST /admin/dosen/create
 */
$router->post('admin/dosen/create', function () {
    $controller = new DosenController();
    $controller->createDosen();
}, [AuthMiddleware::class]);

// ----------------------------------------
// UPDATE Operations
// ----------------------------------------

/**
 * Dosen - Edit Page (Form)
 * URL: GET /admin/dosen/edit/{id}
 */
$router->get('admin/dosen/edit/(\d+)', function ($id) {
    $controller = new DosenController();
    $keahlianController = new KeahlianController();
    $jabatanController = new JabatanController();

    // Get data dosen by ID
    $dosenData = $controller->getDosenById((int)$id);

    if (!$dosenData['success']) {
        header("Location: " . base_url('admin/dosen'));
        exit;
    }

    $dosen = $dosenData['data'];

    // Get dropdown data
    $jabatanData = $jabatanController->getAllJabatan();
    $listJabatan = $jabatanData['data'] ?? [];

    $keahlianData = $keahlianController->getAllKeahlian();
    $listKeahlian = $keahlianData['data'] ?? [];

    // Get profil publikasi dosen
    $profilPublikasiData = $controller->getProfilPublikasi((int)$id);
    $listProfilPublikasi = $profilPublikasiData['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/dosen/edit.php';
}, [AuthMiddleware::class]);

/**
 * Dosen - Update (Handle Submit)
 * URL: POST /admin/dosen/update
 */
$router->post('admin/dosen/update', function () {
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
$router->post('admin/dosen/delete/(\d+)', function ($id) {
    $controller = new DosenController();
    $controller->deleteDosenByID($id);
}, [AuthMiddleware::class]);

// ----------------------------------------
// JABATAN Management (Sub-feature dari Dosen)
// ----------------------------------------

/**
 * Jabatan - Create
 * URL: POST /admin/dosen/create-jabatan
 */
$router->post('admin/dosen/create-jabatan', function () {
    $controller = new JabatanController();
    $controller->createJabatan();
}, [AuthMiddleware::class]);

/**
 * Jabatan - Delete
 * URL: POST /admin/dosen/delete-jabatan
 */
$router->post('admin/dosen/delete-jabatan', function () {
    $controller = new JabatanController();
    $controller->deleteJabatan();
}, [AuthMiddleware::class]);

// ----------------------------------------
// KEAHLIAN Management (Sub-feature dari Dosen)
// ----------------------------------------

/**
 * Keahlian - Create
 * URL: POST /admin/dosen/create-keahlian
 */
$router->post('admin/dosen/create-keahlian', function () {
    $controller = new KeahlianController();
    $controller->createKeahlian();
}, [AuthMiddleware::class]);

/**
 * Keahlian - Delete
 * URL: POST /admin/dosen/delete-keahlian
 */
$router->post('admin/dosen/delete-keahlian', function () {
    $controller = new KeahlianController();
    $controller->deleteKeahlian();
}, [AuthMiddleware::class]);

// ----------------------------------------
// PROFIL PUBLIKASI Management (Sub-feature dari Dosen)
// ----------------------------------------

/**
 * Profil Publikasi - Create
 * URL: POST /admin/dosen/{id}/profil-publikasi/create
 */
$router->post('admin/dosen/(\d+)/profil-publikasi/create', function ($dosen_id) {
    $controller = new DosenController();
    $controller->createProfilPublikasi($dosen_id);
}, [AuthMiddleware::class]);

/**
 * Profil Publikasi - Update
 * URL: POST /admin/dosen/profil-publikasi/update
 */
$router->post('admin/dosen/profil-publikasi/update', function () {
    $controller = new DosenController();
    $controller->updateProfilPublikasi();
}, [AuthMiddleware::class]);

/**
 * Profil Publikasi - Delete
 * URL: POST /admin/dosen/profil-publikasi/delete/{id}
 */
$router->post('admin/dosen/profil-publikasi/delete/(\d+)', function ($id) {
    $controller = new DosenController();
    $controller->deleteProfilPublikasi($id);
}, [AuthMiddleware::class]);

/**
 * Profil Publikasi - Get by Dosen ID
 * URL: GET /admin/dosen/{id}/profil-publikasi
 */
$router->get('admin/dosen/(\d+)/profil-publikasi', function ($dosen_id) {
    $controller = new DosenController();
    $result = $controller->getProfilPublikasi($dosen_id);
    ResponseHelper::success('Data profil publikasi', $result['data'] ?? []);
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
$router->get('admin/fasilitas', function () {
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
$router->get('admin/fasilitas/create', function () {
    require __DIR__ . '/../app/Views/admin/fasilitas/create.php';
}, [AuthMiddleware::class]);

/**
 * Fasilitas - Create (Handle Submit)
 * URL: POST /fasilitas/create
 * 
 * Response JSON dari controller
 */
$router->post('admin/fasilitas/create', function () {
    $controller = new FasilitasController();
    $controller->createFasilitas();
}, [AuthMiddleware::class]);

/**
 * Fasilitas - Detail Page
 * URL: GET /fasilitas/detail/{id}
 * 
 */
$router->get('admin/fasilitas/detail/(\d+)', function ($id) {
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
$router->get('admin/fasilitas/edit/(\d+)', function ($id) {
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
$router->post('admin/fasilitas/update', function () {
    $controller = new FasilitasController();
    $controller->updateFasilitas();
}, [AuthMiddleware::class]);

/**
 * Fasilitas - Delete
 * URL: POST /fasilitas/delete/{id}
 * 
 * Response JSON dari controller
 */
$router->post('admin/fasilitas/delete/(\d+)', function ($id) {
    $controller = new FasilitasController();
    $controller->deleteFasilitasById($id);
}, [AuthMiddleware::class]);

// ============================================================================
// PRODUK MANAGEMENT
// ============================================================================
// CRUD operations untuk data produk laboratorium

// ----------------------------------------
// READ Operations
// ----------------------------------------

/**
 * Produk - List/Index (dengan Pagination)
 * URL: GET /produk?page=1&per_page=10
 */
$router->get('produk', function () {
    $controller = new ProdukController();
    $result = $controller->getAllProduk();

    $listProduk = $result['data'];      // Variable untuk view index.php
    $pagination = $result['pagination'];

    require __DIR__ . '/../app/Views/admin/produk/index.php';
}, [AuthMiddleware::class]);

/**
 * Produk - Detail Page
 * URL: GET /produk/detail/{id}
 */
$router->get('produk/detail/(\d+)', function ($id) {
    $controller = new ProdukController();
    $result = $controller->getProdukById($id);

    // Jika data tidak ditemukan, redirect ke index
    if (!$result['success']) {
        header("Location: " . base_url('produk'));
        exit;
    }

    $produk = $result['data'];  // Variable untuk view read.php

    require __DIR__ . '/../app/Views/admin/produk/read.php';
}, [AuthMiddleware::class]);

// ----------------------------------------
// CREATE Operations
// ----------------------------------------

/**
 * Produk - Create Page (Form)
 * URL: GET /produk/create
 */
$router->get('produk/create', function () {
    $controller = new ProdukController();

    // Get list dosen untuk dropdown
    $dosenData = $controller->getAllDosen();
    $listDosen = $dosenData['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/produk/create.php';
}, [AuthMiddleware::class]);

/**
 * Produk - Create (Handle Submit)
 * URL: POST /produk/create
 * 
 * Response JSON dari controller
 */
$router->post('produk/create', function () {
    $controller = new ProdukController();
    $controller->createProduk();
}, [AuthMiddleware::class]);

// ----------------------------------------
// UPDATE Operations
// ----------------------------------------

/**
 * Produk - Edit Page (Form)
 * URL: GET /produk/edit/{id}
 */
$router->get('produk/edit/(\d+)', function ($id) {
    $controller = new ProdukController();
    $result = $controller->getProdukById($id);

    // Jika data tidak ditemukan, redirect ke index
    if (!$result['success']) {
        header("Location: " . base_url('produk'));
        exit;
    }

    $produk = $result['data'];  // Variable untuk view edit.php

    // Get list dosen untuk dropdown
    $dosenData = $controller->getAllDosen();
    $listDosen = $dosenData['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/produk/edit.php';
}, [AuthMiddleware::class]);

/**
 * Produk - Update (Handle Submit)
 * URL: POST /produk/update
 * 
 * Response JSON dari controller
 */
$router->post('produk/update', function () {
    $controller = new ProdukController();
    $controller->updateProduk();
}, [AuthMiddleware::class]);

// ----------------------------------------
// DELETE Operations
// ----------------------------------------

/**
 * Produk - Delete
 * URL: POST /produk/delete/{id}
 * 
 * Response JSON dari controller
 */
$router->post('produk/delete/(\d+)', function ($id) {
    $controller = new ProdukController();
    $controller->deleteProdukById($id);
}, [AuthMiddleware::class]);

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
// AKTIVITAS LABORATORIUM MANAGEMENT
// ============================================================================
// CRUD operations untuk data aktivitas laboratorium

// ----------------------------------------
// READ Operations
// ----------------------------------------

/**
 * Aktivitas - List/Index dengan Pagination
 * URL: GET /aktivitas?page={number}&per_page={number}
 */
$router->get('aktivitas-lab', function () {
    $controller = new AktivitasController();

    // Ambil parameter dari query string
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

    // Ambil data dengan pagination
    $result = $controller->getAllAktivitasWithPagination($page, $perPage);

    $listAktivitas = $result['data'] ?? [];
    $pagination = $result['pagination'] ?? null;

    require __DIR__ . '/../app/Views/admin/aktivitas-lab/index.php';
}, [AuthMiddleware::class]);

/**
 * Aktivitas - Detail Page
 * URL: GET /aktivitas/detail/{id}
 */
$router->get('aktivitas-lab/detail/(\\d+)', function ($id) {
    $controller = new AktivitasController();

    // Get data aktivitas by ID
    $aktivitasData = $controller->getAktivitasById((int)$id);

    if (!$aktivitasData['success']) {
        header("Location: " . base_url('aktivitas-lab'));
        exit;
    }

    $aktivitas = $aktivitasData['data'];

    require __DIR__ . '/../app/Views/admin/aktivitas-lab/read.php';
}, [AuthMiddleware::class]);

// ----------------------------------------
// CREATE Operations
// ----------------------------------------

/**
 * Aktivitas - Create Page (Form)
 * URL: GET /aktivitas/create
 */
$router->get('aktivitas-lab/create', function () {
    require __DIR__ . '/../app/Views/admin/aktivitas-lab/create.php';
}, [AuthMiddleware::class]);

/**
 * Aktivitas - Create (Handle Submit)
 * URL: POST /aktivitas/create
 */
$router->post('aktivitas-lab/create', function () {
    $controller = new AktivitasController();
    $controller->createAktivitas();
}, [AuthMiddleware::class]);

// ----------------------------------------
// UPDATE Operations
// ----------------------------------------

/**
 * Aktivitas - Edit Page (Form)
 * URL: GET /aktivitas/edit/{id}
 */
$router->get('aktivitas-lab/edit/(\\d+)', function ($id) {
    $controller = new AktivitasController();

    // Get data aktivitas by ID
    $aktivitasData = $controller->getAktivitasById((int)$id);

    if (!$aktivitasData['success']) {
        header("Location: " . base_url('aktivitas-lab'));
        exit;
    }

    $aktivitas = $aktivitasData['data'];

    require __DIR__ . '/../app/Views/admin/aktivitas-lab/edit.php';
}, [AuthMiddleware::class]);

/**
 * Aktivitas - Update (Handle Submit)
 * URL: POST /aktivitas/update
 */
$router->post('aktivitas-lab/update', function () {
    $controller = new AktivitasController();
    $controller->updateAktivitas();
}, [AuthMiddleware::class]);

// ----------------------------------------
// DELETE Operations
// ----------------------------------------

/**
 * Aktivitas - Delete
 * URL: POST /aktivitas/delete/{id}
 */
$router->post('aktivitas-lab/delete/(\\d+)', function ($id) {
    $controller = new AktivitasController();
    $controller->deleteAktivitas($id);
}, [AuthMiddleware::class]);

// ============================================================================
// RECRUITMENT MANAGEMENT
// ============================================================================
// CRUD operations untuk data recruitment/informasi rekrutment

// ----------------------------------------
// READ Operations
// ----------------------------------------

/**
 * Recruitment - List/Index dengan Pagination
 * URL: GET /recruitment?page={number}&per_page={number}
 */
$router->get('recruitment', function () {
    // $controller = new RecruitmentController();

    // // Ambil parameter dari query string
    // $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    // $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

    // // Ambil data dengan pagination
    // $result = $controller->getAllRecruitmentWithPagination($page, $perPage);

    // $listRecruitment = $result['data'] ?? [];
    // $pagination = $result['pagination'] ?? null;

    require __DIR__ . '/../app/Views/admin/recruitment/index.php';
}, [AuthMiddleware::class]);

/**
 * Recruitment - Detail Page
 * URL: GET /recruitment/detail/{id}
 */
$router->get('recruitment/detail/(\\d+)', function ($id) {
    // $controller = new RecruitmentController();

    // // Get data recruitment by ID
    // $recruitmentData = $controller->getRecruitmentById((int)$id);

    // if (!$recruitmentData['success']) {
    //     header("Location: " . base_url('recruitment'));
    //     exit;
    // }

    // $recruitment = $recruitmentData['data'];

    require __DIR__ . '/../app/Views/admin/recruitment/read.php';
}, [AuthMiddleware::class]);

// ----------------------------------------
// CREATE Operations
// ----------------------------------------

/**
 * Recruitment - Create Page (Form)
 * URL: GET /recruitment/create
 */
$router->get('recruitment/create', function () {
    require __DIR__ . '/../app/Views/admin/recruitment/create.php';
}, [AuthMiddleware::class]);

/**
 * Recruitment - Create (Handle Submit)
 * URL: POST /recruitment/create
 */
$router->post('recruitment/create', function () {
    // $controller = new RecruitmentController();
    // $controller->createRecruitment();
}, [AuthMiddleware::class]);

// ----------------------------------------
// UPDATE Operations
// ----------------------------------------

/**
 * Recruitment - Edit Page (Form)
 * URL: GET /recruitment/edit/{id}
 */
$router->get('recruitment/edit/(\\d+)', function ($id) {
    // $controller = new RecruitmentController();

    // // Get data recruitment by ID
    // $recruitmentData = $controller->getRecruitmentById((int)$id);

    // if (!$recruitmentData['success']) {
    //     header("Location: " . base_url('recruitment'));
    //     exit;
    // }

    // $recruitment = $recruitmentData['data'];

    require __DIR__ . '/../app/Views/admin/recruitment/edit.php';
}, [AuthMiddleware::class]);

/**
 * Recruitment - Update (Handle Submit)
 * URL: POST /recruitment/update
 */
$router->post('recruitment/update', function () {
    // $controller = new RecruitmentController();
    // $controller->updateRecruitment();
}, [AuthMiddleware::class]);

// ----------------------------------------
// DELETE Operations
// ----------------------------------------

/**
 * Recruitment - Delete
 * URL: POST /recruitment/delete/{id}
 */
$router->post('recruitment/delete/(\\d+)', function ($id) {
    // $controller = new RecruitmentController();
    // $controller->deleteRecruitment($id);
}, [AuthMiddleware::class]);

// ============================================================================
// PUBLIKASI MANAGEMENT
// ============================================================================
// CRUD operations untuk data publikasi dosen

// ----------------------------------------
// READ Operations
// ----------------------------------------

/**
 * Publikasi - List/Index dengan Pagination
 * URL: GET /publikasi?page={number}&per_page={number}
 */
$router->get('publikasi', function () {
    // $controller = new PublikasiController();

    // // Ambil parameter dari query string
    // $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    // $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

    // // Ambil data dengan pagination
    // $result = $controller->getAllPublikasiWithPagination($page, $perPage);

    // $listPublikasi = $result['data'] ?? [];
    // $pagination = $result['pagination'] ?? null;

    // // Ambil data dosen untuk dropdown
    // $dosenController = new DosenController();
    // $dosenResult = $dosenController->getAllDosen();
    // $listDosen = $dosenResult['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/publikasi/index.php';
}, [AuthMiddleware::class]);

/**
 * Publikasi - Detail Page
 * URL: GET /publikasi/detail/{id}
 */
$router->get('publikasi/detail/(\\d+)', function ($id) {
    // $controller = new PublikasiController();

    // // Get data publikasi by ID
    // $publikasiData = $controller->getPublikasiById((int)$id);

    // if (!$publikasiData['success']) {
    //     header("Location: " . base_url('publikasi'));
    //     exit;
    // }

    // $publikasi = $publikasiData['data'];

    require __DIR__ . '/../app/Views/admin/publikasi/read.php';
}, [AuthMiddleware::class]);

// ----------------------------------------
// CREATE Operations
// ----------------------------------------

/**
 * Publikasi - Create Page (Form)
 * URL: GET /publikasi/create
 */
$router->get('publikasi/create', function () {
    // // Get list dosen untuk dropdown
    // $dosenController = new DosenController();
    // $dosenResult = $dosenController->getAllDosen();
    // $listDosen = $dosenResult['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/publikasi/create.php';
}, [AuthMiddleware::class]);

/**
 * Publikasi - Create (Handle Submit)
 * URL: POST /publikasi/create
 */
$router->post('publikasi/create', function () {
    // $controller = new PublikasiController();
    // $controller->createPublikasi();
}, [AuthMiddleware::class]);

// ----------------------------------------
// UPDATE Operations
// ----------------------------------------

/**
 * Publikasi - Edit Page (Form)
 * URL: GET /publikasi/edit/{id}
 */
$router->get('publikasi/edit/(\\d+)', function ($id) {
    // $controller = new PublikasiController();

    // // Get data publikasi by ID
    // $publikasiData = $controller->getPublikasiById((int)$id);

    // if (!$publikasiData['success']) {
    //     header("Location: " . base_url('publikasi'));
    //     exit;
    // }

    // $publikasi = $publikasiData['data'];

    // // Get list dosen untuk dropdown
    // $dosenController = new DosenController();
    // $dosenResult = $dosenController->getAllDosen();
    // $listDosen = $dosenResult['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/publikasi/edit.php';
}, [AuthMiddleware::class]);

/**
 * Publikasi - Update (Handle Submit)
 * URL: POST /publikasi/update
 */
$router->post('publikasi/update', function () {
    // $controller = new PublikasiController();
    // $controller->updatePublikasi();
}, [AuthMiddleware::class]);

// ----------------------------------------
// DELETE Operations
// ----------------------------------------

/**
 * Publikasi - Delete
 * URL: POST /publikasi/delete/{id}
 */
$router->post('publikasi/delete/(\\d+)', function ($id) {
    // $controller = new PublikasiController();
    // $controller->deletePublikasi($id);
}, [AuthMiddleware::class]);

// ============================================================================
// END OF ROUTES
// ============================================================================

return $router;
