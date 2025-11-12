# 🧪 Applied Informatics Laboratory - CMS & Website Profile

> Website profil dan Content Management System (CMS) untuk Laboratorium Applied Informatics - Universitas Teknokrat Indonesia

[![PHP Version](https://img.shields.io/badge/PHP-8.0%2B-blue)](https://www.php.net/)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15%2B-blue)](https://www.postgresql.org/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3.8-purple)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/License-MIT-green)](LICENSE)

---

## 📋 Table of Contents

- [Overview](#-overview)
- [Technology Stack](#-technology-stack)
- [Struktur Project](#-struktur-project)
- [Setup & Installation](#-setup--installation)
- [Database Setup](#-database-setup)
- [Routing System](#-routing-system)
- [MVC Architecture](#-mvc-architecture)
- [Helpers & Utilities](#-helpers--utilities)
- [Asset Management](#-asset-management)
- [Authentication & Middleware](#-authentication--middleware)
- [File Upload System](#-file-upload-system)
- [Development Workflow](#-development-workflow)
- [Conventions & Best Practices](#-conventions--best-practices)
- [Troubleshooting](#-troubleshooting)
- [API Endpoints](#-api-endpoints)
- [Team](#-team)

---

## 📖 Overview

Website ini adalah sistem manajemen konten (CMS) untuk Laboratorium Applied Informatics yang memiliki fitur:

### ✨ Fitur Utama
- 🔐 **Authentication System** - Login & logout untuk admin
- 👥 **Manajemen Anggota** - CRUD Dosen & Mahasiswa
- 🏢 **Konten Laboratorium** - Kelola Fasilitas, Produk, Mitra Kerjasama
- 📊 **Manajemen Aktivitas** - Publikasi, Penelitian, Pengabdian, Kekayaan Intelektual
- 📁 **File Upload** - Upload & manage foto profil, gambar fasilitas, dll
- 🎨 **Responsive UI** - Bootstrap 5 dengan custom design
- ⚡ **AJAX Operations** - Seamless user experience tanpa reload

---

## 🛠️ Technology Stack

### Backend
- **PHP 8.0+** - Server-side programming language
- **PostgreSQL 15+** - Relational database
- **PDO** - Database abstraction layer
- **Composer** - Dependency manager

### Frontend
- **HTML5 / CSS3** - Markup & styling
- **Bootstrap 5.3.8** - UI Framework
- **JavaScript (ES6+)** - Client-side scripting
- **jQuery 3.x** - DOM manipulation & AJAX
- **Select2** - Advanced select boxes
- **Feather Icons** - Icon library

### Tools & Libraries
- **phpdotenv** - Environment configuration management
- **Custom MVC** - Lightweight PHP MVC framework
- **Clean URL Routing** - SEO-friendly URLs

---

## 📁 Struktur Project

```
applied-informatics/
│
├── 📂 app/                          # Application core
│   ├── 📂 Controllers/              # Business logic controllers
│   │   ├── AuthController.php       # Authentication logic
│   │   └── DosenController.php      # Dosen CRUD operations
│   │
│   ├── 📂 Models/                   # Database models
│   │   ├── AuthModel.php            # User authentication model
│   │   ├── DosenModel.php           # Dosen data model
│   │   ├── JabatanModel.php         # Jabatan data model
│   │   └── KeahlianModel.php        # Keahlian data model
│   │
│   ├── 📂 Views/                    # View templates (HTML/PHP)
│   │   ├── 📂 admin/                # Admin pages
│   │   │   └── 📂 dosen/            # Dosen management views
│   │   │       ├── index.php        # List dosen
│   │   │       ├── create.php       # Create dosen form
│   │   │       ├── edit.php         # Edit dosen form
│   │   │       └── read.php         # Detail dosen
│   │   ├── 📂 auth/                 # Authentication views
│   │   │   └── login.php            # Login page
│   │   ├── 📂 home/                 # Public pages
│   │   │   └── index.php            # Homepage
│   │   └── 📂 layouts/              # Reusable layouts
│   │       └── sidebar.php          # Admin sidebar
│   │
│   ├── 📂 Helpers/                  # Helper functions
│   │   ├── FileUploadHelper.php     # File upload utilities
│   │   ├── PaginationHelper.php     # Pagination logic
│   │   ├── ResponseHelper.php       # JSON response formatter
│   │   ├── ValidationHelper.php     # Input validation
│   │   └── validation.php           # Additional validators
│   │
│   ├── 📂 Middleware/               # Middleware components
│   │   └── AuthMiddleware.php       # Authentication checker
│   │
│   └── 📂 Core/                     # Core framework files
│       ├── Database.php             # Database connection
│       └── Router.php               # URL routing handler
│
├── 📂 config/                       # Configuration files
│   ├── app.php                      # App config & helper functions
│   ├── database.php                 # Database configuration
│   └── routes.php                   # Route definitions
│
├── 📂 public/                       # Publicly accessible files
│   ├── index.php                    # Entry point
│   ├── 📂 assets/                   # Static assets
│   │   ├── 📂 css/                  # Stylesheets
│   │   │   ├── 📂 base/             # Base styles (reset, variables, layout)
│   │   │   ├── 📂 components/       # Component styles (sidebar, navbar)
│   │   │   └── 📂 pages/            # Page-specific styles
│   │   ├── 📂 js/                   # JavaScript files
│   │   │   ├── 📂 components/       # Component scripts
│   │   │   ├── 📂 helpers/          # Helper functions
│   │   │   └── 📂 pages/            # Page-specific scripts
│   │   └── 📂 images/               # Static images
│   └── 📂 uploads/                  # User uploaded files
│       ├── 📂 dosen/                # Dosen profile photos
│       ├── 📂 fasilitas/            # Facility images
│       ├── 📂 produk/               # Product images
│       ├── 📂 mitra/                # Partner logos
│       └── 📂 default/              # Default placeholder images
│
├── 📂 database/                     # Database files
│   ├── 📂 procedures/               # PostgreSQL stored procedures
│   └── schema.sql                   # Database schema (jika ada)
│
├── 📂 vendor/                       # Composer dependencies (auto-generated)
│
├── .env                             # Environment variables (SENSITIVE!)
├── .gitignore                       # Git ignore rules
├── .htaccess                        # Apache rewrite rules (clean URLs)
├── composer.json                    # PHP dependencies
├── composer.lock                    # Locked dependency versions
├── README.md                        # This file
└── ROUTING.md                       # Routing documentation
```

---

## 🚀 Setup & Installation

### Prerequisites
- **MAMP** (atau XAMPP/WAMP) - Local server environment
- **PHP 8.0+** - Server-side language
- **PostgreSQL 15+** - Database server
- **Composer** - Dependency manager
- **Git** - Version control (optional)

### Step 1: Clone/Download Project
```bash
# Clone via Git
git clone <repository-url> /Applications/MAMP/htdocs/applied-informatics

# Atau download ZIP dan extract ke folder MAMP htdocs
```

### Step 2: Install Dependencies
```bash
cd /Applications/MAMP/htdocs/applied-informatics
composer install
```

### Step 3: Environment Configuration
```bash
# File .env sudah ada, pastikan konfigurasinya sesuai
nano .env
```

**Isi `.env`:**
```env
DB_HOST=localhost
DB_PORT=5432
DB_NAME=db_lab_ai
DB_USER=postgres
DB_PASS=your_password_here
```

### Step 4: Database Setup
Lihat section [Database Setup](#-database-setup) di bawah.

### Step 5: Set Permissions (MacOS/Linux)
```bash
# Set write permission untuk upload folder
chmod -R 775 public/uploads/
chmod -R 775 public/uploads/dosen/
chmod -R 775 public/uploads/fasilitas/
```

### Step 6: Configure MAMP
1. Buka **MAMP**
2. Set **Document Root**: `/Applications/MAMP/htdocs/applied-informatics/public`
3. Start servers (Apache & PostgreSQL)

### Step 7: Access Application
```
http://localhost:8888/applied-informatics
```

**Default Admin Credentials:** (sesuaikan dengan database Anda)
```
Email: admin@example.com
Password: admin123
```

---

## 💾 Database Setup

### 1. Create Database
```sql
-- Via terminal
createdb -U postgres db_lab_ai

-- Atau via psql
psql -U postgres
CREATE DATABASE db_lab_ai;
```

### 2. Connect to Database
```bash
psql -U postgres -d db_lab_ai
```

### 3. Import Schema
```sql
-- Import file SQL schema (jika ada)
\i /path/to/schema.sql

-- Atau copy-paste SQL dari file schema
```

### 4. Create Tables
**Contoh tabel yang diperlukan:**

```sql
-- Tabel Admin
CREATE TABLE tbl_admin (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Jabatan
CREATE TABLE tbl_jabatan (
    id SERIAL PRIMARY KEY,
    jabatan VARCHAR(255) NOT NULL
);

-- Tabel Keahlian
CREATE TABLE tbl_keahlian (
    id SERIAL PRIMARY KEY,
    keahlian VARCHAR(255) NOT NULL
);

-- Tabel Dosen
CREATE TABLE tbl_dosen (
    id SERIAL PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    nidn VARCHAR(20) UNIQUE,
    jabatan_id INT REFERENCES tbl_jabatan(id),
    foto_profil VARCHAR(255),
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Junction table: Dosen - Keahlian (Many-to-Many)
CREATE TABLE tbl_dosen_keahlian (
    id SERIAL PRIMARY KEY,
    dosen_id INT REFERENCES tbl_dosen(id) ON DELETE CASCADE,
    keahlian_id INT REFERENCES tbl_keahlian(id) ON DELETE CASCADE
);
```

### 5. Import Stored Procedures
```bash
# Navigate to procedures folder
cd database/procedures/

# Import each procedure
psql -U postgres -d db_lab_ai -f sp_insert_dosen_with_keahlian.sql
```

---

## 🌐 Routing System

Project ini menggunakan **Clean URL Routing** dengan `.htaccess`.

### URL Pattern
```
# Pattern
http://localhost:8888/applied-informatics/{module}/{action}/{param}

# Examples
http://localhost:8888/applied-informatics/dashboard
http://localhost:8888/applied-informatics/dosen
http://localhost:8888/applied-informatics/dosen/create
http://localhost:8888/applied-informatics/dosen/edit/5
http://localhost:8888/applied-informatics/dosen/delete/5
```

### Route Definition
**File:** `config/routes.php`

```php
use App\Core\Router;
use App\Middleware\AuthMiddleware;

$router = new Router();

// Public routes
$router->get('/', function () {
    require __DIR__ . '/../app/Views/home/index.php';
});

$router->get('login', function () {
    require __DIR__ . '/../app/Views/auth/login.php';
});

// Protected routes (dengan middleware)
$router->get('dashboard', function () {
    require __DIR__ . '/../app/Views/admin/dashboard.php';
}, [AuthMiddleware::class]);

// Dosen routes
$router->get('dosen', function () {
    $controller = new DosenController();
    $result = $controller->getAllDosen();

    $listDosen = $result['data'];
    $pagination = $result['pagination'];

    require __DIR__ . '/../app/Views/admin/dosen/index.php';
}, [AuthMiddleware::class]);

$router->post('dosen/create', function () {
    $controller = new DosenController();
    $controller->createDosen();
});
```

### Menambah Route Baru

#### 1. **Tambahkan route di `config/routes.php`**
```php
$router->get('nama-route', function () {
    // Logic here
    require __DIR__ . '/../app/Views/folder/file.php';
}, [AuthMiddleware::class]); // Optional middleware
```

#### 2. **Buat view file**
```bash
touch app/Views/admin/nama-module/nama-view.php
```

#### 3. **Access URL**
```
http://localhost:8888/applied-informatics/nama-route
```

---

## 🏗️ MVC Architecture

### Model-View-Controller Pattern

```
User Request
     ↓
Router (routes.php)
     ↓
Controller (DosenController.php)
     ↓
Model (DosenModel.php)
     ↓
Database (PostgreSQL)
     ↓
Model returns data
     ↓
Controller passes to View
     ↓
View renders HTML
     ↓
Response to User
```

### Example: Dosen CRUD

#### **Model** (`app/Models/DosenModel.php`)
```php
class DosenModel {
    public function getAllDosen() {
        $query = "SELECT * FROM tbl_dosen ORDER BY id DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDosenById($id) {
        $query = "SELECT * FROM tbl_dosen WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
```

#### **Controller** (`app/Controllers/DosenController.php`)
```php
class DosenController {
    private $dosenModel;

    public function __construct() {
        $this->dosenModel = new DosenModel();
    }

    public function getAllDosen() {
        $result = $this->dosenModel->getAllDosen();
        return ['data' => $result];
    }

    public function createDosen() {
        // Validation
        // Insert to database
        // Return response
    }
}
```

#### **View** (`app/Views/admin/dosen/index.php`)
```php
<!DOCTYPE html>
<html>
<head>
    <title>Data Dosen</title>
</head>
<body>
    <h1>Data Dosen</h1>
    <table>
        <?php foreach ($listDosen as $dosen): ?>
        <tr>
            <td><?= $dosen['full_name'] ?></td>
            <td><?= $dosen['email'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
```

---

## 🛠️ Helpers & Utilities

### 1. **ResponseHelper** - JSON Response

**File:** `app/Helpers/ResponseHelper.php`

```php
// Success response
ResponseHelper::success('Data berhasil disimpan', ['id' => 123]);

// Error response
ResponseHelper::error('Validasi gagal');

// Validation error
ResponseHelper::validationError(['email' => 'Email sudah terdaftar']);
```

### 2. **ValidationHelper** - Input Validation

**File:** `app/Helpers/ValidationHelper.php`

```php
// Validate email
$result = ValidationHelper::validateEmail($email);
if (!$result['valid']) {
    echo $result['message'];
}

// Validate name
$result = ValidationHelper::validateName($name, 3, 255);

// Validate NIDN
$result = ValidationHelper::validateNIDN($nidn, true);

// Validate file size
$result = ValidationHelper::validateFileSize($file, 2); // 2MB
```

### 3. **FileUploadHelper** - File Upload

**File:** `app/Helpers/FileUploadHelper.php`

```php
// Upload image
$result = FileUploadHelper::upload(
    $_FILES['foto'],        // File
    'image',                // Type: image/document/video
    'dosen',                // Folder: uploads/dosen/
    2 * 1024 * 1024        // Max size: 2MB
);

if ($result['success']) {
    $filename = $result['filename'];
}

// Delete file
FileUploadHelper::delete($filename, 'dosen');
```

### 4. **PaginationHelper** - Pagination

**File:** `app/Helpers/PaginationHelper.php`

```php
$pagination = PaginationHelper::paginate($totalRecords, $currentPage, $perPage);

// Returns:
// [
//     'current_page' => 1,
//     'per_page' => 10,
//     'total_records' => 100,
//     'total_pages' => 10,
//     'has_prev' => false,
//     'has_next' => true,
//     'page_numbers' => [...]
// ]
```

### 5. **Helper Functions** - Global Utilities

**File:** `config/app.php`

```php
// Base URL
echo base_url('dosen/create');
// Output: http://localhost:8888/applied-informatics/dosen/create

// Asset URL
echo asset_url('css/main.css');
// Output: http://localhost:8888/applied-informatics/public/assets/css/main.css

// Upload URL
echo upload_url('dosen/photo.jpg');
// Output: http://localhost:8888/applied-informatics/public/uploads/dosen/photo.jpg
```

---

## 🎨 Asset Management

### Folder Structure
```
public/assets/
├── css/
│   ├── base/
│   │   ├── main.css          # Global styles
│   │   ├── variables.css     # CSS variables
│   │   └── layout.css        # Layout utilities
│   ├── components/
│   │   └── sidebar.css       # Sidebar styles
│   └── pages/
│       └── dosen/
│           ├── index.css     # List page
│           └── form.css      # Create/Edit form
├── js/
│   ├── jquery.min.js
│   ├── components/
│   │   └── sidebar.js        # Sidebar interactions
│   ├── helpers/
│   │   ├── jQueryHelpers.js  # AJAX helpers
│   │   └── validationHelpers.js # Validation
│   └── pages/
│       └── dosen/
│           ├── index.js      # List page logic
│           └── form.js       # Form logic
└── images/
    └── lab-ai-logo.png
```

### Usage in Views
```php
<!-- CSS -->
<link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">
<link rel="stylesheet" href="<?= asset_url('css/pages/dosen/index.css') ?>">

<!-- JavaScript -->
<script src="<?= asset_url('js/jquery.min.js') ?>"></script>
<script src="<?= asset_url('js/pages/dosen/index.js') ?>"></script>

<!-- Images -->
<img src="<?= asset_url('images/lab-ai-logo.png') ?>" alt="Logo">
```

---

## 🔐 Authentication & Middleware

### Authentication Flow
```
1. User akses protected page (e.g., /dashboard)
   ↓
2. Router check middleware (AuthMiddleware::class)
   ↓
3. Middleware check $_SESSION['user_id']
   ↓
4a. If logged in → Allow access
4b. If not logged in → Redirect to /login
```

### AuthMiddleware

**File:** `app/Middleware/AuthMiddleware.php`

```php
class AuthMiddleware {
    public static function handle() {
        session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('login'));
            exit;
        }
    }
}
```

### Login Process

**Controller:** `app/Controllers/AuthController.php`
```php
public function handleLogin() {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate & authenticate
    $user = $this->authModel->getUserByEmail($email);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];

        ResponseHelper::success('Login berhasil');
    } else {
        ResponseHelper::error('Email atau password salah');
    }
}
```

### Logout
```php
session_unset();
session_destroy();
header('Location: ' . base_url('/'));
```

---

## 📁 File Upload System

### Supported File Types
- **Images:** JPG, JPEG, PNG (max 2MB)
- **Documents:** PDF, DOC, DOCX (max 5MB)
- **Videos:** MP4, AVI, MOV (max 10MB)

### Upload Flow
```
1. User select file
   ↓
2. Frontend validation (size, type)
   ↓
3. AJAX submit to controller
   ↓
4. FileUploadHelper::upload()
   ↓
5. Validate & move file to uploads/{folder}/
   ↓
6. Generate unique filename (timestamp + random)
   ↓
7. Save filename to database
   ↓
8. Return response
```

### Upload Directory Structure
```
public/uploads/
├── dosen/               # Dosen profile photos
│   ├── .gitkeep        # Keep folder in git
│   └── 1699999999_abc123.jpg
├── fasilitas/          # Facility images
├── produk/             # Product images
├── mitra/              # Partner logos
└── default/            # Default placeholders
    └── image.png
```

### Security Measures
✅ File type validation (whitelist)
✅ File size validation
✅ Unique filename generation
✅ File extension validation
✅ Directory traversal prevention
✅ Execute permission disabled on uploads folder

---

## 💻 Development Workflow

### 1. Create New Module (Example: Mahasiswa)

#### Step 1: Create Model
```bash
touch app/Models/MahasiswaModel.php
```

```php
<?php
class MahasiswaModel {
    private $db;
    private $table_name = 'tbl_mahasiswa';

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllMahasiswa() {
        // Implementation
    }
}
```

#### Step 2: Create Controller
```bash
touch app/Controllers/MahasiswaController.php
```

```php
<?php
class MahasiswaController {
    private $mahasiswaModel;

    public function __construct() {
        $this->mahasiswaModel = new MahasiswaModel();
    }

    public function index() {
        // Implementation
    }
}
```

#### Step 3: Create Views
```bash
mkdir -p app/Views/admin/mahasiswa
touch app/Views/admin/mahasiswa/index.php
touch app/Views/admin/mahasiswa/create.php
touch app/Views/admin/mahasiswa/edit.php
```

#### Step 4: Add Routes
Edit `config/routes.php`:
```php
$router->get('mahasiswa', function () {
    $controller = new MahasiswaController();
    $controller->index();
}, [AuthMiddleware::class]);

$router->post('mahasiswa/create', function () {
    $controller = new MahasiswaController();
    $controller->create();
});
```

#### Step 5: Create Assets
```bash
mkdir -p public/assets/css/pages/mahasiswa
mkdir -p public/assets/js/pages/mahasiswa
touch public/assets/css/pages/mahasiswa/index.css
touch public/assets/js/pages/mahasiswa/index.js
```

### 2. AJAX Request Pattern

**JavaScript (Frontend):**
```javascript
jQueryHelpers.makeAjaxRequest({
    url: '/applied-informatics/mahasiswa/create',
    method: 'POST',
    data: formData,
    onSuccess: (response) => {
        if (response.success) {
            jQueryHelpers.showAlert('Berhasil disimpan', 'success');
        }
    },
    onError: (error) => {
        jQueryHelpers.showAlert('Terjadi kesalahan', 'danger');
    }
});
```

**PHP (Backend):**
```php
public function create() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        ResponseHelper::error('Invalid request method');
        return;
    }

    // Get data
    $name = $_POST['name'] ?? '';

    // Validate
    $validation = ValidationHelper::validateName($name, 3, 255);
    if (!$validation['valid']) {
        ResponseHelper::error($validation['message']);
        return;
    }

    // Insert to database
    $result = $this->mahasiswaModel->insert(['name' => $name]);

    if ($result['success']) {
        ResponseHelper::success('Data berhasil disimpan');
    } else {
        ResponseHelper::error('Gagal menyimpan data');
    }
}
```

---

## 📏 Conventions & Best Practices

### Naming Conventions

#### Files & Folders
```
✅ GOOD:
- DosenController.php
- DosenModel.php
- index.php
- form.css

❌ BAD:
- dosencontroller.php
- dosen_model.php
- Index.php
```

#### Classes
```php
✅ GOOD: PascalCase
class DosenController {}
class FileUploadHelper {}

❌ BAD:
class dosen_controller {}
class fileUploadHelper {}
```

#### Methods & Variables
```php
✅ GOOD: camelCase
public function getAllDosen() {}
$userName = "John";

❌ BAD:
public function GetAllDosen() {}
$user_name = "John";
```

#### Database Tables
```sql
✅ GOOD: snake_case dengan prefix
tbl_dosen
tbl_jabatan
tbl_dosen_keahlian

❌ BAD:
Dosen
jabatan_tbl
dosenKeahlian
```

### Code Organization

#### Controller Methods
```php
// 1. Public methods (called from routes)
public function index() {}
public function create() {}

// 2. Private helper methods
private function validateInput() {}
private function processData() {}
```

#### SQL Queries
```php
// Use prepared statements
$query = "SELECT * FROM tbl_dosen WHERE id = :id";
$stmt = $this->db->prepare($query);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
```

### Security Best Practices

✅ **Always validate input**
```php
$email = $_POST['email'] ?? '';
$validation = ValidationHelper::validateEmail($email);
```

✅ **Use prepared statements**
```php
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
```

✅ **Escape output**
```php
<p><?= htmlspecialchars($dosen['full_name']) ?></p>
```

✅ **Check authentication**
```php
$router->get('dashboard', function () {
    // ...
}, [AuthMiddleware::class]);
```

✅ **Validate file uploads**
```php
FileUploadHelper::upload($file, 'image', 'dosen', 2 * 1024 * 1024);
```

---

## 🐛 Troubleshooting

### Common Issues

#### 1. **404 Not Found**
**Problem:** Clean URLs tidak bekerja

**Solution:**
```bash
# Check .htaccess exists
ls -la /Applications/MAMP/htdocs/applied-informatics/.htaccess

# Enable mod_rewrite di MAMP
# Edit: /Applications/MAMP/conf/apache/httpd.conf
# Uncomment: LoadModule rewrite_module modules/mod_rewrite.so
```

#### 2. **Database Connection Failed**
**Problem:** Tidak bisa connect ke PostgreSQL

**Solution:**
```bash
# Check PostgreSQL running
ps aux | grep postgres

# Check .env configuration
cat .env

# Test connection
psql -U postgres -d db_lab_ai -c "SELECT 1"
```

#### 3. **Upload Failed - Permission Denied**
**Problem:** File tidak bisa di-upload

**Solution:**
```bash
# Set write permission
chmod -R 775 /Applications/MAMP/htdocs/applied-informatics/public/uploads/

# Check owner
chown -R _www:_www /Applications/MAMP/htdocs/applied-informatics/public/uploads/
```

#### 4. **CSS/JS Not Loading**
**Problem:** Assets tidak load

**Solution:**
```php
// Check base_url() di config/app.php
define('BASE_URL', 'http://localhost:8888/applied-informatics');

// Pastikan path benar
echo asset_url('css/main.css');
```

#### 5. **Session Not Working**
**Problem:** Login tidak persist

**Solution:**
```php
// Pastikan session_start() dipanggil
session_start();

// Check session save path
echo session_save_path();

// Set custom path jika perlu
session_save_path('/tmp');
```

### Debug Mode

**Enable Error Reporting:**
```php
// Add to public/index.php (untuk development only)
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

**Check Logs:**
```bash
# Apache error log
tail -f /Applications/MAMP/logs/apache_error.log

# PHP error log
tail -f /Applications/MAMP/logs/php_error.log
```

---

## 🔌 API Endpoints

### Authentication

#### Login
```
POST /applied-informatics/login

Body:
{
    "email": "admin@example.com",
    "password": "password123"
}

Response:
{
    "success": true,
    "message": "Login berhasil"
}
```

#### Logout
```
GET /applied-informatics/logout

Response: Redirect to homepage
```

### Dosen Management

#### Get All Dosen (with Pagination)
```
GET /applied-informatics/dosen?page=1&per_page=10

Response:
{
    "data": [
        {
            "id": 1,
            "full_name": "Yuri Ariyanto, S.Kom., M.Kom.",
            "email": "yuri@teknokrat.ac.id",
            "nidn": "1234567890",
            "jabatan_name": "Kepala Laboratorium",
            "keahlian_list": "Machine Learning, AI",
            "foto_profil": "1699999999_abc123.jpg"
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 10,
        "total_records": 25,
        "total_pages": 3
    }
}
```

#### Create Dosen
```
POST /applied-informatics/dosen/create

Body (multipart/form-data):
- full_name: string
- email: string
- nidn: string
- jabatan_id: integer
- keahlian_ids: string (comma-separated)
- foto_profil: file
- deskripsi: text

Response:
{
    "success": true,
    "message": "Data dosen berhasil ditambahkan",
    "data": {
        "id": 1
    }
}
```

#### Update Dosen
```
POST /applied-informatics/dosen/update

Body: Same as Create + id field

Response:
{
    "success": true,
    "message": "Data dosen berhasil diupdate"
}
```

#### Delete Dosen
```
POST /applied-informatics/dosen/delete/{id}

Response:
{
    "success": true,
    "message": "Data dosen berhasil dihapus"
}
```

### Jabatan Management

#### Create Jabatan
```
POST /applied-informatics/dosen/create-jabatan

Body:
{
    "jabatan": "Dosen Tetap"
}

Response:
{
    "success": true,
    "message": "Jabatan berhasil ditambahkan",
    "data": {
        "id": 1,
        "jabatan": "Dosen Tetap"
    }
}
```

### Keahlian Management

#### Create Keahlian
```
POST /applied-informatics/dosen/create-keahlian

Body:
{
    "keahlian": "Machine Learning"
}

Response:
{
    "success": true,
    "message": "Keahlian berhasil ditambahkan",
    "data": {
        "id": 1,
        "keahlian": "Machine Learning"
    }
}
```

---

## 👥 Team

**Applied Informatics Laboratory Development Team**

- **Project Lead:** [Nama]
- **Backend Developer:** [Nama]
- **Frontend Developer:** [Nama]
- **Database Administrator:** [Nama]
- **UI/UX Designer:** [Nama]

**Contact:**
- Email: lab.ai@teknokrat.ac.id
- Website: [URL]

---

## 📝 Changelog

### Version 2.0.0 (2024-11-12)
- ✨ Implement pagination system
- 🔧 Add PaginationHelper
- 🐛 Fix Kepala Lab validation logic
- 📦 Add FileUploadHelper improvements
- 📝 Complete documentation

### Version 1.0.0 (2024-11-01)
- 🎉 Initial release
- ✨ MVC architecture implementation
- 🔐 Authentication system
- 👥 Dosen management CRUD
- 📁 File upload system
- 🎨 Bootstrap UI implementation

---

## 📄 License

This project is licensed under the MIT License.

```
MIT License

Copyright (c) 2024 Applied Informatics Laboratory - Universitas Teknokrat Indonesia

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction...
```

---

## 🔗 Related Documentation

- [ROUTING.md](ROUTING.md) - Detailed routing guide
- [API.md](API.md) - API documentation (if exists)
- [DEPLOYMENT.md](DEPLOYMENT.md) - Deployment guide (if exists)

---

## 🙏 Acknowledgments

- Bootstrap 5 - UI Framework
- jQuery - JavaScript library
- Feather Icons - Icon set
- PostgreSQL - Database
- PHP - Programming language

---

**Last Updated:** 2024-11-12
**Version:** 2.0.0
**Location:** `/Applications/MAMP/htdocs/applied-informatics/`

---

📧 **Need Help?** Contact: lab.ai@teknokrat.ac.id
