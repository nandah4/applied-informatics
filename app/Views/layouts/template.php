<?php
/**
 * Template Halaman dengan Sidebar
 * Copy file ini untuk membuat halaman baru dengan sidebar
 */

// Load configuration if not already loaded
if (!function_exists('base_url')) {
    require_once __DIR__ . '/../../../config/app.php';
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ' . base_url('index.php?url=login'));
    exit;
}

// Get user data from session
$userFullname = $_SESSION['user_fullname'] ?? 'User';
$userEmail = $_SESSION['user_email'] ?? '';

// Page specific variables
$pageTitle = 'Dasboard'; // Ubah sesuai kebutuhan
$pageDescription = 'Deskripsi singkat halaman'; // Ubah sesuai kebutuhan

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Page Specific CSS (Optional) -->
    <!-- <link rel="stylesheet" href="<?= asset_url('css/pages/your-page.css') ?>"> -->
</head>

<body>
    <!-- Dashboard Layout -->
    <div class="dashboard-layout">

        <!-- Sidebar -->
        <?php include __DIR__ . '/sidebar.php'; ?>

        <!-- Main Content Area -->
        <main class="main-content">

            <!-- Topbar -->
            <div class="topbar">
                <div class="topbar-left">
                    <button class="btn-mobile-menu" id="mobileMenuBtn">
                        <i data-feather="menu"></i>
                    </button>
                    <div>
                        <h6 class="mb-0 fw-bold"><?= $pageTitle ?></h6>
                        <small class="text-muted"><?= $pageDescription ?></small>
                    </div>
                </div>
                <div class="topbar-right">
                    <div class="user-info-topbar">
                        <span class="fw-medium"><?= htmlspecialchars($userFullname) ?></span>
                        <small class="text-muted d-block"><?= htmlspecialchars($userEmail) ?></small>
                    </div>
                </div>
            </div>

            <!-- Content Wrapper -->
            <div class="content-wrapper">

                <!-- Breadcrumb (Optional) -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= base_url('index.php?url=dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?= $pageTitle ?></li>
                    </ol>
                </nav>

                <!-- Page Header -->
                <div class="page-header">
                    <h1><?= $pageTitle ?></h1>
                    <p><?= $pageDescription ?></p>
                </div>

                <!-- Main Content Start -->
                <!-- =============================================== -->
                <!-- Konten halaman Anda di sini -->

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">Card Title</h5>
                    </div>
                    <div class="card-body">
                        <p>Konten card Anda di sini...</p>
                    </div>
                </div>

                <!-- =============================================== -->
                <!-- Main Content End -->

            </div>

        </main>

    </div>

    <!-- jQuery -->
    <script src="<?= asset_url('js/jquery.min.js') ?>"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <!-- Sidebar JS (jQuery Version) -->
    <script src="<?= asset_url('js/components/sidebar-simple.js') ?>"></script>

    <!-- Page Specific JS (Optional) -->
    <!-- <script src="<?= asset_url('js/pages/your-page.js') ?>"></script> -->

    <!-- Your page specific JavaScript here -->
    <script>
        $(document).ready(function() {
            // Your jQuery code here
        });
    </script>

</body>

</html>
