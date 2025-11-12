<?php

use Dotenv\Dotenv;

/**
 * File: index.php
 * Entry Point Aplikasi
 * 
 * Alur:
 * 1. Load autoloader (Composer)
 * 2. Load environment variables (.env)
 * 3. Load config, helpers, dan classes
 * 4. Load routes dan jalankan router
 */

// Inisialisai Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Helpers/ResponseHelper.php';
require_once __DIR__ . '/../app/Helpers/validation.php';


require_once __DIR__ . '/../app/Core/Router.php';

// Load Helpers
require_once __DIR__ . '/../app/Helpers/FileUploadHelper.php';
require_once __DIR__ . '/../app/Helpers/ValidationHelper.php';
require_once __DIR__ . '/../app/Helpers/PaginationHelper.php';

// Load Middleware
require_once __DIR__ . '/../app/Middleware/AuthMiddleware.php';

// Load Models
require_once __DIR__ . '/../app/Models/AuthModel.php';
require_once __DIR__ . '/../app/Models/JabatanModel.php';
require_once __DIR__ . '/../app/Models/KeahlianModel.php';
require_once __DIR__ . '/../app/Models/DosenModel.php';

// Load Controllers
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/DosenController.php';

// Load semua route dari config/routes.php
$router = require_once __DIR__ . '/../config/routes.php';

// Jalankan router (process request)
$router->run();
