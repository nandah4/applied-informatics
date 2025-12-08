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
    $dashboardModel = new DashboardModel();
    $statisticLab = $dashboardModel->getHomeStatistic();
    $statisticData = $statisticLab['data'];

    $fasiltiasModel = new FasilitasModel();
    $listFasiltias = $fasiltiasModel->getAllFasilitas();
    $fasilitasData = $listFasiltias['data'];

    // Ambil data publikasi untuk section penelitian
    $publikasiModel = new PublikasiAkademikModel();
    $yearsResult = $publikasiModel->getDistinctYears();
    $publikasiYears = $yearsResult['success'] ? $yearsResult['data'] : [];

    // Ambil publikasi dari tahun pertama (terbaru) sebagai default
    $publikasiData = [];
    if (!empty($publikasiYears)) {
        $firstYear = $publikasiYears[0];
        $publikasiResult = $publikasiModel->getByYear($firstYear);
        $publikasiData = $publikasiResult['success'] ? $publikasiResult['data'] : [];
    }

    // Ambil data terbaru aktivitas
    $aktivitasModel = new AktivitasModel();
    $listAktivitas = $aktivitasModel->getAll();
    $aktivitasData = $listAktivitas['data'];

    require __DIR__ . '/../app/Views/client/index.php';
});

/**
 * AR Showcase - Augmented Reality Demo
 * URL: /ar-showcase
 */
$router->get('ar-showcase', function () {
    require __DIR__ . '/../app/Views/client/ar_showcase.php';
});

/**
 * API: Get publikasi by year (AJAX endpoint)
 * URL: /api/publikasi/year/{year}
 */
$router->get('api/publikasi/year/(\d+)', function ($year) {
    header('Content-Type: application/json');

    $publikasiModel = new PublikasiAkademikModel();
    $result = $publikasiModel->getByYear($year);

    echo json_encode($result);
});

/**
 * Anggota Laboratorium
 * URL: /anggota-laboratorium
 */
$router->get('anggota-laboratorium', function () {
    $dosenModel = new DosenModel();

    // Ambil data Kepala Laboratorium
    $leadershipResult = $dosenModel->getDosenByJabatan('Kepala Laboratorium');
    $leadership = $leadershipResult['success'] ? $leadershipResult['data'] : [];

    // Ambil data Dosen (anggota)
    $membersResult = $dosenModel->getDosenByJabatan('Anggota');
    $members = $membersResult['success'] ? $membersResult['data'] : [];

    require __DIR__ . '/../app/Views/client/anggota_lab.php';
});

/**
 * Detail Dosen
 * URL: /dosen/detail/{id}
 */
$router->get('dosen/detail/(\d+)', function ($id) {
    $dosenModel = new DosenModel();
    $profilPublikasiModel = new ProfilPublikasiModel();
    $publikasiModel = new PublikasiAkademikModel();

    // Get data dosen by ID
    $dosenResult = $dosenModel->getDosenById((int)$id);

    if (!$dosenResult['success']) {
        header("Location: " . base_url('anggota-laboratorium'));
        exit;
    }

    $dosenData = $dosenResult['data'];

    // Get profil publikasi dosen (SINTA, Scopus, dll)
    $profilPublikasiResult = $profilPublikasiModel->getByDosenId((int)$id);
    $profilPublikasi = $profilPublikasiResult['success'] ? $profilPublikasiResult['data'] : [];

    // Get publikasi akademik dosen (Riset, PPM, Kekayaan Intelektual)
    $publikasiResult = $publikasiModel->getByDosenId((int)$id);
    $publikasiGrouped = $publikasiResult['success'] ? $publikasiResult['data'] : [];

    require __DIR__ . '/../app/Views/client/detail_dosen.php';
});


/**
 * Produk Lab
 * URL: /produk-lab
 */
$router->get('produk-lab', function () {
    $produkModel = new ProdukModel();

    // Ambil semua data produk dari database
    $produkResult = $produkModel->getAllProduk();
    $listProduk = $produkResult['success'] ? $produkResult['data'] : [];

    require __DIR__ . '/../app/Views/client/produk_lab.php';
});

/**
 * Aktivitas Lab
 * URL: /aktivitas-laboratorium
 */
$router->get('aktivitas-laboratorium', function () {
    // Ambil data terbaru aktivitas dengan limit dari query parameter
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 6;
    if ($limit < 6) $limit = 6; // Minimum limit

    $aktivitasModel = new AktivitasModel();
    $listAktivitas = $aktivitasModel->getAll($limit);
    $aktivitasData = $listAktivitas['data'];

    require __DIR__ . '/../app/Views/client/aktivitas_lab.php';
});

/**
 * Detail Aktivitas Lab
 * URL: /aktivitas/{id}
 */
$router->get('aktivitas-laboratorium/(\d+)', function ($id) {
    $aktivitasModel = new AktivitasModel();
    $aktivitasResult = $aktivitasModel->getById((int)$id);

    // Redirect jika aktivitas tidak ditemukan
    if (!$aktivitasResult['success']) {
        header("Location: " . base_url('aktivitas-laboratorium'));
        exit;
    }

    $aktivitas = $aktivitasResult['data'];

    require __DIR__ . '/../app/Views/client/detail_aktivitas.php';
});

/**
 * Publikasi Dosen
 * URL: /publikasi-dosen
 * Menampilkan semua publikasi dari semua dosen dengan search, filter, dan pagination
 */
$router->get('publikasi-dosen', function () {
    $controller = new PublikasiAkademikController();
    $result = $controller->getPublikasiForClient();

    $listPublikasi = $result['data'];
    $pagination = $result['pagination'];
    $totalPublikasi = $result['total'];

    require __DIR__ . '/../app/Views/client/publikasi_dosen.php';
});

/**
 * Mitra Laboratorium
 * URL: /mitra-lab
 */
$router->get('mitra-laboratorium', function () {
    $mitraModel = new MitraModel();

    $internasional = $mitraModel->getMitraByKategori("internasional");
    $internasionalList = $internasional['data'];

    $pendidikan = $mitraModel->getMitraByKategori("institusi pendidikan");
    $pendidikanList = $pendidikan['data'];

    $institusiPemerintah = $mitraModel->getMitraByKategori("institusi pemerintah");
    $institusiPemerintahList = $institusiPemerintah['data'];

    $industri = $mitraModel->getMitraByKategori("industri");
    $industriList = $industri['data'];

    $komunitas = $mitraModel->getMitraByKategori("komunitas");
    $komunitasList = $komunitas['data'];

    require __DIR__ . '/../app/Views/client/mitra.php';
});

/**
 * Rekrutment
 * URL: /rekrutment
 * Menampilkan semua recruitment yang terbuka dan tertutup
 */
$router->get('rekrutment', function () {
    $controller = new RecruitmentController();

    // Ambil semua data recruitment (tanpa pagination untuk client)
    $recruitmentModel = new RecruitmentModel();
    $allRecruitment = $recruitmentModel->getAllRecruitmentWithPagination(1000, 0); // Ambil banyak data

    // Pisahkan berdasarkan status
    $recruitmentTerbuka = [];
    $recruitmentTertutup = [];

    if ($allRecruitment['success'] && !empty($allRecruitment['data'])) {
        foreach ($allRecruitment['data'] as $recruitment) {
            if ($recruitment['status'] === 'buka') {
                $recruitmentTerbuka[] = $recruitment;
            } else {
                $recruitmentTertutup[] = $recruitment;
            }
        }
    }

    require __DIR__ . '/../app/Views/client/rekrutment.php';
});

/**
 * Contact Us
 * URL: /contact-us
 */
$router->get('contact-us', function () {
    require __DIR__ . '/../app/Views/client/contact_us.php';
});

 * Rekrutment Form
 * URL: /rekrutment/form/{id}
 * Menampilkan form recruitment untuk mendaftar
 */
$router->get("rekrutment/form/(\\d+)", function ($rekrutmenId) {
    // Validasi apakah rekrutmen ada dan statusnya buka
    $recruitmentModel = new RecruitmentModel();
    $recruitmentResult = $recruitmentModel->getById($rekrutmenId);

    // Redirect jika rekrutmen tidak ditemukan atau sudah tutup
    if (!$recruitmentResult['success'] || $recruitmentResult['data']['status'] !== 'buka') {
        header("Location: " . base_url('rekrutment'));
        exit;
    }

    $recruitmentData = $recruitmentResult['data'];

    require __DIR__ . '/../app/Views/client/form_rekrutment.php';
});

/**
 * Rekrutment Submit
 * URL: POST /rekrutment/submit
 * Handle form pendaftaran mahasiswa
 */
$router->post("rekrutment/submit", function () {
    $controller = new RecruitmentController();
    $controller->submitPendaftaran();
});


/**
 * Rekrutment Sukses
 * URL: /rekrutment/sukses
 * Halaman sukses setelah pendaftaran
 */
$router->get("rekrutment/sukses", function () {
    require __DIR__ . '/../app/Views/client/sukses_pendaftaran.php';
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
 * URL: GET /admin/logout
 *
 * Note: Idealnya logout menggunakan POST untuk keamanan,
 * tapi untuk kemudahan navigasi tetap menggunakan GET.
 * Session akan dihapus menggunakan SessionHelper untuk proper cleanup.
 */
$router->get('admin/logout', function () {
    $controller = new AuthController();
    $controller->handleLogout();
});


// ============================================================================
// ADMIN DASHBOARD
// ============================================================================
// Protected: Hanya admin yang bisa akses

/**
 * Admin Dashboard
 * URL: /admin/dashboard
 *
 * Load statistik dashboard dari database dan tampilkan ke view
 */
$router->get('admin/dashboard', function () {
    // Load dashboard model
    $dashboardModel = new DashboardModel();

    // Ambil statistik dashboard dari view v_dashboard_count
    $statsResult = $dashboardModel->getDashboardStats();
    $stats = $statsResult['success'] ? $statsResult['data'] : [];

    // Ambil publikasi terbaru
    $publikasiResult = $dashboardModel->getRecentPublikasi(4);
    $recentPublikasi = $publikasiResult['success'] ? $publikasiResult['data'] : [];

    // Ambil statistik publikasi per tipe
    $publikasiByTipeResult = $dashboardModel->getPublikasiByTipe();
    $publikasiByTipe = $publikasiByTipeResult['success'] ? $publikasiByTipeResult['data'] : [];

    // Ambil aktivitas lab terbaru
    $aktivitasLabResult = $dashboardModel->getRecentAktivitasLab(3);
    $recentAktivitas = $aktivitasLabResult['success'] ? $aktivitasLabResult['data'] : [];

    // Ambil statistik rekrutmen
    $recruitmentStatsResult = $dashboardModel->getRecruitmentStats();
    $recruitmentStats = $recruitmentStatsResult['success'] ? $recruitmentStatsResult['data'] : [];

    // Ambil statistik asisten lab
    $asistenLabStatsResult = $dashboardModel->getAsistenLabStats();
    $asistenLabStats = $asistenLabStatsResult['success'] ? $asistenLabStatsResult['data'] : [];

    // Ambil rekrutmen aktif
    $rekrutmenAktifResult = $dashboardModel->getActiveRecruitment(5);
    $rekrutmenAktif = $rekrutmenAktifResult['success'] ? $rekrutmenAktifResult['data'] : [];

    // Ambil pendaftar terbaru
    $recentPendaftarResult = $dashboardModel->getRecentPendaftar(5);
    $recentPendaftar = $recentPendaftarResult['success'] ? $recentPendaftarResult['data'] : [];

    // Load view dengan data
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
 * Fasilitas - List/Index dengan Pagination & Search
 * URL: GET /admin/fasilitas?page={number}&per_page={number}&search={keyword}
 */
$router->get('admin/fasilitas', function () {
    $controller = new FasilitasController();

    // Ambil parameter dari query string
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Validasi input
    $page = max(1, $page);
    $perPage = max(1, min(100, $perPage));

    // Hitung offset
    $offset = ($page - 1) * $perPage;

    // Siapkan params untuk search & filter
    $params = [
        'search' => $search,
        'limit' => $perPage,
        'offset' => $offset
    ];

    // Ambil data dengan search & pagination dari model
    $fasilitasModel = new FasilitasModel();
    $result = $fasilitasModel->getAllWithSearchAndFilter($params);

    // Generate pagination
    $pagination = PaginationHelper::paginate($result['total'], $page, $perPage);

    $listFasilitas = $result['data'] ?? [];

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
$router->get('admin/produk', function () {
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
$router->get('admin/produk/detail/(\d+)', function ($id) {
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
$router->get('admin/produk/create', function () {
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
$router->post('admin/produk/create', function () {
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
$router->get('admin/produk/edit/(\d+)', function ($id) {
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
$router->post('admin/produk/update', function () {
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
$router->post('admin/produk/delete/(\d+)', function ($id) {
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
$router->get('admin/mitra', function () {
    $controller = new MitraController();
    // Ambil parameter dari query string
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Validasi input
    $page = max(1, $page);
    $perPage = max(1, min(100, $perPage));

    // Hitung offset
    $offset = ($page - 1) * $perPage;

    // Siapkan params untuk search & filter
    $params = [
        'search' => $search,
        'limit' => $perPage,
        'offset' => $offset
    ];

    // Ambil data dengan search & pagination dari model
    $mitraModel = new MitraModel();
    $result = $mitraModel->getAllWithSearchAndFilter($params);

    // Generate pagination
    $pagination = PaginationHelper::paginate($result['total'], $page, $perPage);

    $listMitra = $result['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/mitra/index.php';
}, [AuthMiddleware::class]);

/**
 * Mitra - Detail Page
 * URL: GET /mitra/detail/{id}
 */
$router->get('admin/mitra/detail/(\d+)', function ($id) {
    $controller = new MitraController();

    // Get data mitra by ID
    $mitraData = $controller->getMitraById((int)$id);

    if (!$mitraData['success']) {
        header("Location: " . base_url('admin/mitra'));
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
$router->get('admin/mitra/create', function () {
    require __DIR__ . '/../app/Views/admin/mitra/create.php';
}, [AuthMiddleware::class]);

/**
 * Mitra - Create (Handle Submit)
 * URL: POST /mitra/create
 */
$router->post('admin/mitra/create', function () {
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
$router->get('admin/mitra/edit/(\d+)', function ($id) {
    $controller = new MitraController();

    // Get data mitra by ID
    $mitraData = $controller->getMitraById((int)$id);

    if (!$mitraData['success']) {
        header("Location: " . base_url('admin/mitra'));
        exit;
    }

    $mitra = $mitraData['data'];

    require __DIR__ . '/../app/Views/admin/mitra/edit.php';
}, [AuthMiddleware::class]);

/**
 * Mitra - Update (Handle Submit)
 * URL: POST /mitra/update
 */
$router->post('admin/mitra/update', function () {
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
$router->post('admin/mitra/delete/(\d+)', function ($id) {
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
$router->get('admin/aktivitas-lab', function () {
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
$router->get('admin/aktivitas-lab/detail/(\\d+)', function ($id) {
    $controller = new AktivitasController();

    // Get data aktivitas by ID
    $aktivitasData = $controller->getAktivitasById((int)$id);

    if (!$aktivitasData['success']) {
        header("Location: " . base_url('admin/aktivitas-lab'));
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
 * URL: GET /admin/aktivitas-lab/create
 */
$router->get('admin/aktivitas-lab/create', function () {
    require __DIR__ . '/../app/Views/admin/aktivitas-lab/create.php';
}, [AuthMiddleware::class]);

/**
 * Aktivitas - Create (Handle Submit)
 * URL: POST /admin/aktivitas-lab/create
 */
$router->post('admin/aktivitas-lab/create', function () {
    $controller = new AktivitasController();
    $controller->createAktivitas();
}, [AuthMiddleware::class]);

// ----------------------------------------
// UPDATE Operations
// ----------------------------------------

/**
 * Aktivitas - Edit Page (Form)
 * URL: GET /admin/aktivitas-lab/edit/{id}
 */
$router->get('admin/aktivitas-lab/edit/(\\d+)', function ($id) {
    $controller = new AktivitasController();

    // Get data aktivitas by ID
    $aktivitasData = $controller->getAktivitasById((int)$id);

    if (!$aktivitasData['success']) {
        header("Location: " . base_url('admin/aktivitas-lab'));
        exit;
    }

    $aktivitas = $aktivitasData['data'];

    require __DIR__ . '/../app/Views/admin/aktivitas-lab/edit.php';
}, [AuthMiddleware::class]);

/**
 * Aktivitas - Update (Handle Submit)
 * URL: POST /admin/aktivitas-lab/update
 */
$router->post('admin/aktivitas-lab/update', function () {
    $controller = new AktivitasController();
    $controller->updateAktivitas();
}, [AuthMiddleware::class]);

// ----------------------------------------
// DELETE Operations
// ----------------------------------------

/**
 * Aktivitas - Delete
 * URL: POST /admin/aktivitas-lab/delete/{id}
 */
$router->post('admin/aktivitas-lab/delete/(\\d+)', function ($id) {
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
 * URL: GET /admin/recruitment?page={number}&per_page={number}
 */
$router->get('admin/recruitment', function () {
    $controller = new RecruitmentController();
    // Ambil parameter dari query string
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Validasi input
    $page = max(1, $page);
    $perPage = max(1, min(100, $perPage));

    // Hitung offset
    $offset = ($page - 1) * $perPage;

    // Siapkan params untuk search & filter
    $params = [
        'search' => $search,
        'limit' => $perPage,
        'offset' => $offset
    ];

    // Ambil data dengan search & pagination dari model
    $recruitmentModel = new RecruitmentModel();
    $result = $recruitmentModel->getAllWithSearchAndFilter($params);

    // Generate pagination
    $pagination = PaginationHelper::paginate($result['total'], $page, $perPage);

    $listRecruitment = $result['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/recruitment/index.php';
}, [AuthMiddleware::class]);

/**
 * Recruitment - Detail Page
 * URL: GET /admin/recruitment/detail/{id}
 */
$router->get('admin/recruitment/detail/(\\d+)', function ($id) {
    $controller = new RecruitmentController();

    // Get data recruitment by ID
    $recruitmentData = $controller->getRecruitmentById((int)$id);

    if (!$recruitmentData['success']) {
        header("Location: " . base_url('admin/recruitment'));
        exit;
    }

    $recruitment = $recruitmentData['data'];

    require __DIR__ . '/../app/Views/admin/recruitment/read.php';
}, [AuthMiddleware::class]);

// ----------------------------------------
// CREATE Operations
// ----------------------------------------

/**
 * Recruitment - Create Page (Form)
 * URL: GET /admin/recruitment/create
 */
$router->get('admin/recruitment/create', function () {
    require __DIR__ . '/../app/Views/admin/recruitment/create.php';
}, [AuthMiddleware::class]);

/**
 * Recruitment - Create (Handle Submit)
 * URL: POST /admin/recruitment/create
 */
$router->post('admin/recruitment/create', function () {
    $controller = new RecruitmentController();
    $controller->createRecruitment();
}, [AuthMiddleware::class]);

// ----------------------------------------
// UPDATE Operations
// ----------------------------------------

/**
 * Recruitment - Edit Page (Form)
 * URL: GET /admin/recruitment/edit/{id}
 */
$router->get('admin/recruitment/edit/(\\d+)', function ($id) {
    $controller = new RecruitmentController();

    // Get data recruitment by ID
    $recruitmentData = $controller->getRecruitmentById((int)$id);

    if (!$recruitmentData['success']) {
        header("Location: " . base_url('admin/recruitment'));
        exit;
    }

    $recruitment = $recruitmentData['data'];

    require __DIR__ . '/../app/Views/admin/recruitment/edit.php';
}, [AuthMiddleware::class]);

/**
 * Recruitment - Update (Handle Submit)
 * URL: POST /admin/recruitment/update
 */
$router->post('admin/recruitment/update', function () {
    $controller = new RecruitmentController();
    $controller->updateRecruitment();
}, [AuthMiddleware::class]);

// ----------------------------------------
// DELETE Operations
// ----------------------------------------

/**
 * Recruitment - Delete
 * URL: POST /admin/recruitment/delete/{id}
 */
$router->post('admin/recruitment/delete/(\\d+)', function ($id) {
    $controller = new RecruitmentController();
    $controller->deleteRecruitment($id);
}, [AuthMiddleware::class]);

// ============================================================================
// PUBLIKASI MANAGEMENT
// ============================================================================
// CRUD operations untuk data publikasi dosen

// ----------------------------------------
// READ Operations
// ----------------------------------------

/**
 * Publikasi - List/Index dengan Pagination & Search
 * URL: GET /publikasi?page={number}&per_page={number}&search={keyword}
 */
$router->get('admin/publikasi-akademik', function () {
    $controller = new PublikasiAkademikController();

    // Ambil parameter dari query string
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Validasi input
    $page = max(1, $page);
    $perPage = max(1, min(100, $perPage));

    // Hitung offset
    $offset = ($page - 1) * $perPage;

    // Siapkan params untuk search & filter
    $params = [
        'search' => $search,
        'limit' => $perPage,
        'offset' => $offset
    ];

    // Ambil data dengan search & pagination dari model
    $publikasiModel = new PublikasiAkademikModel();
    $result = $publikasiModel->getAllWithSearchAndFilter($params);

    // Generate pagination
    $pagination = PaginationHelper::paginate($result['total'], $page, $perPage);

    $listPublikasi = $result['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/publikasi/index.php';
}, [AuthMiddleware::class]);

/**
 * Publikasi - Detail Page
 * URL: GET /publikasi/detail/{id}
 */
$router->get('admin/publikasi-akademik/read/(\\d+)', function ($id) {
    $controller = new PublikasiAkademikController();

    // Get data publikasi by ID
    $publikasiData = $controller->getPublikasiById((int)$id);

    if (!$publikasiData['success']) {
        header("Location: " . base_url('admin/publikasi-akademik'));
        exit;
    }

    $publikasi = $publikasiData['data'];

    require __DIR__ . '/../app/Views/admin/publikasi/read.php';
}, [AuthMiddleware::class]);

// ----------------------------------------
// CREATE Operations
// ----------------------------------------

/**
 * Publikasi - Create Page (Form)
 * URL: GET /publikasi/create
 */
$router->get('admin/publikasi-akademik/create', function () {
    // // Get list dosen untuk dropdown
    $dosenController = new DosenController();
    $dosenResult = $dosenController->getAllDosenActive();
    $listDosen = $dosenResult['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/publikasi/create.php';
}, [AuthMiddleware::class]);

/**
 * Publikasi - Create (Handle Submit)
 * URL: POST /publikasi/create
 */
$router->post('admin/publikasi-akademik/create', function () {
    $controller = new PublikasiAkademikController();
    $controller->createPublikasi();
}, [AuthMiddleware::class]);

// ----------------------------------------
// UPDATE Operations
// ----------------------------------------

/**
 * Publikasi - Edit Page (Form)
 * URL: GET /publikasi/edit/{id}
 */
$router->get('admin/publikasi-akademik/edit/(\\d+)', function ($id) {
    $controller = new PublikasiAkademikController();

    // Get data publikasi by ID
    $publikasiData = $controller->getPublikasiById((int)$id);

    if (!$publikasiData['success']) {
        header("Location: " . base_url('admin/publikasi-akademik'));
        exit;
    }

    $publikasi = $publikasiData['data'];

    // Get list dosen untuk dropdown
    $dosenController = new DosenController();
    $dosenResult = $dosenController->getAllDosenActive();
    $listDosen = $dosenResult['data'] ?? [];

    require __DIR__ . '/../app/Views/admin/publikasi/edit.php';
}, [AuthMiddleware::class]);

/**
 * Publikasi - Update (Handle Submit)
 * URL: POST /publikasi/update
 */
$router->post('admin/publikasi-akademik/update', function () {
    $controller = new PublikasiAkademikController();
    $controller->updatePublikasi();
}, [AuthMiddleware::class]);

// ----------------------------------------
// DELETE Operations
// ----------------------------------------

/**
 * Publikasi - Delete
 * URL: POST /publikasi/delete/{id}
 */
$router->post('admin/publikasi-akademik/delete/(\\d+)', function ($id) {
    $controller = new PublikasiAkademikController();
    $controller->deletePublikasiAkademik((int)$id);
}, [AuthMiddleware::class]);



// ============================================================================
// KELOLA PENDAFTAR
// ============================================================================
// CRUD operations untuk data pendaftar asisten lab

// ----------------------------------------
// READ Operations
// ----------------------------------------

/**
 * Pendaftar - List/Index dengan Pagination & Search
 * URL: GET /admin/daftar-pendaftar
 */
$router->get("admin/daftar-pendaftar", function () {
    $controller = new PendaftarController();
    $result = $controller->getAllPendaftar();

    $listPendaftar = $result['data'];
    $pagination = $result['pagination'];
    $totalPendaftar = $result['total'];

    require __DIR__ . '/../app/Views/admin/pendaftar/index.php';
}, [AuthMiddleware::class]);

/**
 * Pendaftar - Detail Page
 * URL: GET /admin/daftar-pendaftar/detail/{id}
 */
$router->get("admin/daftar-pendaftar/detail/(\\d+)", function ($id) {
    $controller = new PendaftarController();
    $result = $controller->getPendaftarById($id);

    if (!$result['success']) {
        header("Location: " . base_url('admin/daftar-pendaftar'));
        exit;
    }

    $pendaftar = $result['data'];

    require __DIR__ . '/../app/Views/admin/pendaftar/read.php';
}, [AuthMiddleware::class]);

/**
 * Pendaftar - Update Status Seleksi
 * URL: POST /admin/daftar-pendaftar/update-status
 */
$router->post("admin/daftar-pendaftar/update-status", function () {
    $controller = new PendaftarController();
    $result = $controller->updateStatusSeleksi();

    // if (!$result['success']) {
    //     header("Location: " . base_url('admin/daftar-pendaftar'));
    //     exit;
    // }
    require __DIR__ . '/../app/Views/admin/pendaftar/read.php';
}, [AuthMiddleware::class]);

/**
 * Pendaftar - Delete
 * URL: POST /admin/daftar-pendaftar/delete/{id}
 */
$router->post("admin/daftar-pendaftar/delete/(\\d+)", function ($id) {
    $controller = new PendaftarController();
    $controller->deletePendaftar($id);
}, [AuthMiddleware::class]);


// ============================================================================
// ASISTEN LAB MANAGEMENT
// ============================================================================
// CRUD operations untuk data asisten lab (mahasiswa yang diterima)

// ----------------------------------------
// READ Operations
// ----------------------------------------

/**
 * Asisten Lab - List/Index dengan Pagination & Search
 * URL: GET /admin/asisten-lab
 */
$router->get("admin/asisten-lab", function () {
    $controller = new AsistenLabController();
    $result = $controller->getAllAsistenLab();

    $listAsistenLab = $result['data'];
    $pagination = $result['pagination'];
    $totalAsistenLab = $result['total'];

    require __DIR__ . '/../app/Views/admin/asisten-lab/index.php';
}, [AuthMiddleware::class]);

/**
 * Asisten Lab - Detail Page
 * URL: GET /admin/asisten-lab/detail/{id}
 */
$router->get("admin/asisten-lab/detail/(\\d+)", function ($id) {
    $controller = new AsistenLabController();
    $result = $controller->getAsistenLabById($id);

    if (!$result['success']) {
        header("Location: " . base_url('admin/asisten-lab'));
        exit;
    }

    $asisten = $result['data'];

    require __DIR__ . '/../app/Views/admin/asisten-lab/read.php';
}, [AuthMiddleware::class]);

// ----------------------------------------
// UPDATE Operations
// ----------------------------------------

/**
 * Asisten Lab - Edit Page (Form)
 * URL: GET /admin/asisten-lab/edit/{id}
 */
$router->get("admin/asisten-lab/edit/(\\d+)", function ($id) {
    $controller = new AsistenLabController();
    $result = $controller->getAsistenLabById($id);

    if (!$result['success']) {
        header("Location: " . base_url('admin/asisten-lab'));
        exit;
    }

    $asisten = $result['data'];

    require __DIR__ . '/../app/Views/admin/asisten-lab/edit.php';
}, [AuthMiddleware::class]);

/**
 * Asisten Lab - Update (Handle Submit)
 * URL: POST /admin/asisten-lab/update
 */
$router->post("admin/asisten-lab/update", function () {
    $controller = new AsistenLabController();
    $controller->updateAsistenLab();
}, [AuthMiddleware::class]);

// ----------------------------------------
// DELETE Operations
// ----------------------------------------

/**
 * Asisten Lab - Delete
 * URL: POST /admin/asisten-lab/delete/{id}
 */
$router->post("admin/asisten-lab/delete/(\\d+)", function ($id) {
    $controller = new AsistenLabController();
    $controller->deleteAsistenLab($id);
}, [AuthMiddleware::class]);


// ============================================================================
// END OF ROUTES
// ============================================================================

return $router;
