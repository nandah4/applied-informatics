<?php
// Load configuration if not already loaded (for direct access)
if (!function_exists('base_url')) {
    require_once __DIR__ . '/../../../config/app.php';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Admin - Applied Informatics Laboratory</title>

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">

    <!-- Login Page CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/pages/login.css') ?>">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>

<body class="login-body">

    <!-- Parent Layout @grid -->
    <div class="parent-login">

        <div class="child-left-login">
            <div class="parent-img-left-login">
                <!-- Overlay -->
                <div class="child-overlay-img-left">
                    <h5>Laboratorium Applied Informatics</h5>
                    <p>Kelola publikasi proyek, riset, fasilitas, dan aktivitas lab lainnya, semuanya terpusat di satu dashboard Applied Informatics.</p>
                </div>
                <img src="<?= asset_url('images/login/login.jpg') ?>" alt="LAB AI PHOTO">
            </div>
        </div>

        <div class="card-login">
            <form id="loginForm" method="post">
                <!-- Header content login card -->
                <div class="header-login-card">
                    <div class="container-logo-login">
                        <img src="<?= asset_url('images/lab-ai-logo.png') ?>" alt="Logo Laboratorium Applied Informatics" />
                    </div>
                    <h4>Login ke Dashboard.</h4>
                </div>

                <!-- Main content login card -->
                <div class="main-login-card">
                    <!-- gmail -->
                    <div class="mb-3">
                        <label for="inputEmail" class="mb-2 label-form">Email <span class="text-danger">*</span></label>
                        <div class="input-group ig-email">
                            <input type="text" class="form-control" placeholder="Masukkan Gmail Anda ..." id="email">
                            <!-- Error Message -->
                            <div id="emailError" class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- password -->
                    <div class="mb-5 col">
                        <label for="inputPassword" class="mb-2 label-form">Password <span class="text-danger">*</span></label>
                        <div class="input-group ig-password">
                            <input type="password" class="form-control" placeholder="Masukkan Password Anda ..." id="password">
                            <span id="btn-visible-pw" class="input-group-text"><i data-feather="eye"></i></span>

                            <!-- Error Message -->
                            <div id="passwordError" class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- button -->
                    <button id="btn-submit" type="button" class="btn btn-login" data-bs-toggle="modal" data-bs-target="#exampleModal">Masuk</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alert Placholder -->
    <div id="liveAlertPlaceholder"></div>

    <!-- Scripts -->
    <script src="<?= asset_url('js/jquery.min.js') ?>"></script>
    <script src="<?= asset_url('js/helpers/validation.js') ?>"></script>
    <script src="<?= asset_url('js/pages/login.js') ?>"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <script>
        feather.replace();
    </script>
</body>

</html>