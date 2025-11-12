<!-- <?php


// Get user data from session
$userFullname = $_SESSION['user_fullname'] ?? 'User';
$userEmail = $_SESSION['user_email'] ?? '';

?> -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Applied Informatics Laboratory</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Sidebar & Layout CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Dashboard Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/dashboard.css') ?>">
</head>

<body>
    <!-- Dashboard Layout -->
    <div class="dashboard-layout">

        <!-- Sidebar -->
        <?php include __DIR__ . '/../layouts/sidebar.php'; ?>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Topbar -->
            <div class="topbar">
                <div class="topbar-left">
                    <button class="btn-mobile-menu" id="mobileMenuBtn">
                        <i data-feather="menu"></i>
                    </button>
                    <div>
                        <h6 class="mb-0 fw-bold">Dashboard</h6>
                        <small class="text-muted">Laboratorium Applied Informatics</small>
                    </div>
                </div>
                <div class="topbar-right">
                    <div class="user-info-topbar">
                        <span class="fw-medium"><?= htmlspecialchars($userFullname) ?></span>
                        <small class="text-muted d-block"><?= htmlspecialchars($userEmail) ?></small>
                    </div>
                </div>
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
    <script src="<?= asset_url('js/components/sidebar.js') ?>"></script>

</body>

</html>