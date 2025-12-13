# 🧪 Applied Informatics Laboratory - CMS & Website Profile

> Website profil dan Content Management System (CMS) untuk Laboratorium Applied Informatics - Jurusan Teknologi Informasi, Politeknik Negeri Malang

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

#### 🔐 Authentication & Security
- **Login System** - Secure authentication untuk admin
- **Session Timeout Management** - Idle timeout, absolute timeout, dan session regeneration
- **CSRF Protection** - Token-based CSRF validation
- **Role-Based Access Control** - Admin dan guest roles

#### 👥 Manajemen Data Master
- **Dosen Management** - CRUD lengkap dengan foto profil, jabatan, keahlian, dan status aktif
- **Asisten Lab** - Kelola data mahasiswa asisten laboratorium
- **Jabatan & Keahlian** - Master data untuk referensi

#### 🎓 Recruitment System
- **Buka/Tutup Rekrutmen** - Kelola periode rekrutmen asisten lab
- **Formulir Pendaftaran** - Form pendaftaran online untuk calon asisten
- **Manajemen Pendaftar** - Review, terima, atau tolak pendaftar
- **Server-Side Search & Pagination** - Pencarian dan pagination efisien

#### 🏢 Konten Laboratorium
- **Fasilitas** - Kelola fasilitas laboratorium dengan foto
- **Produk** - Showcase produk/project mahasiswa
- **Mitra Kerjasama** - Kelola partnership dengan industri/institusi
- **Aktivitas Lab** - Dokumentasi kegiatan laboratorium

#### 📊 Publikasi & Riset
- **Publikasi Akademik** - Riset, Kekayaan Intelektual, PPM
- **Profil Publikasi Dosen** - Link ke SINTA, SCOPUS, Google Scholar, ORCID, ResearchGate

#### 🎨 User Experience
- **Responsive UI** - Bootstrap 5 dengan custom design
- **AJAX Operations** - Seamless UX tanpa reload halaman
- **File Upload System** - Upload foto dengan validasi
- **Modern Dashboard** - Statistik dan overview data

#### 🌐 Client/Public Pages
- **Homepage** - Landing page dengan sections:
  - Hero Section dengan CTA button
  - Visi Misi V2 dengan Accordion design
  - Statistik Laboratorium
  - Fasilitas V2 dengan grid dan modal
  - Publikasi V2 dengan horizontal scroll
  - Aktivitas Lab dengan card grid dan date badge
  - CTA Section dengan glass bubble animations
- **Publikasi Dosen** - Repositori penelitian dengan:
  - Search by judul/nama dosen
  - Filter by tipe publikasi (Riset, Kekayaan Intelektual, PPM)
  - Filter by tahun publikasi
  - Server-side pagination
- **Aktivitas Laboratorium** - Daftar kegiatan lab dengan detail view
- **Mitra Laboratorium** - Showcase partnership
- **Produk Lab** - Galeri produk/project
- **Contact Us** - Informasi kontak dengan embedded Google Maps
- **Rekrutment** - Form pendaftaran asisten lab

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
- **phpdotenv (^5.6)** - Environment configuration management
- **PHPMailer (^7.0)** - Email sending library
- **Custom MVC** - Lightweight PHP MVC framework
- **Clean URL Routing** - SEO-friendly URLs
- **Stored Procedures** - PostgreSQL stored procedures untuk operasi kompleks

---

## 📁 Struktur Project

```
applied-informatics/
│
├── 📂 app/                          # Application core
│   ├── 📂 Controllers/              # Business logic controllers
│   │   ├── AuthController.php       # Authentication & logout
│   │   ├── DosenController.php      # Dosen CRUD operations
│   │   ├── RecruitmentController.php # Recruitment management
│   │   ├── PendaftarController.php  # Applicant management
│   │   ├── AsistenLabController.php # Lab assistant management
│   │   ├── AktivitasController.php  # Lab activities
│   │   ├── FasilitasController.php  # Facilities management
│   │   ├── MitraController.php      # Partnership management
│   │   ├── ProdukController.php     # Product management
│   │   ├── PublikasiAkademikController.php # Academic publications
│   │   ├── JabatanController.php    # Position/title management
│   │   └── KeahlianController.php   # Expertise management
│   │
│   ├── 📂 Models/                   # Database models
│   │   ├── AuthModel.php            # User authentication
│   │   ├── DosenModel.php           # Dosen data operations
│   │   ├── RecruitmentModel.php     # Recruitment data
│   │   ├── PendaftarModel.php       # Applicant data
│   │   ├── AsistenLabModel.php      # Lab assistant data
│   │   ├── DashboardModel.php       # Dashboard statistics
│   │   ├── AktivitasModel.php       # Lab activities
│   │   ├── FasilitasModel.php       # Facilities
│   │   ├── MitraModel.php           # Partners
│   │   ├── ProdukModel.php          # Products
│   │   ├── PublikasiAkademikModel.php # Publications
│   │   ├── ProfilPublikasiModel.php # Publication profiles
│   │   ├── JabatanModel.php         # Positions/titles
│   │   └── KeahlianModel.php        # Expertise areas
│   │
│   ├── 📂 Views/                    # View templates (HTML/PHP)
│   │   ├── 📂 admin/                # Admin pages
│   │   │   ├── 📂 dosen/            # Dosen management views
│   │   │   ├── 📂 recruitment/      # Recruitment views
│   │   │   ├── 📂 pendaftar/        # Applicant views
│   │   │   ├── 📂 asisten-lab/      # Lab assistant views
│   │   │   ├── 📂 aktivitas-lab/    # Activities views
│   │   │   ├── 📂 fasilitas/        # Facilities views
│   │   │   ├── 📂 mitra/            # Partners views
│   │   │   ├── 📂 produk/           # Products views
│   │   │   └── 📂 publikasi/        # Publications views
│   │   ├── 📂 auth/                 # Authentication views
│   │   │   └── login.php            # Login page with timeout messages
│   │   ├── 📂 client/               # Public client pages
│   │   │   ├── index.php            # Homepage (hero, visi-misi, statistik, fasilitas, publikasi, aktivitas, CTA)
│   │   │   ├── publikasi_dosen.php  # Publikasi dengan search & filter
│   │   │   ├── aktivitas_lab.php    # Daftar aktivitas laboratorium
│   │   │   ├── detail_aktivitas.php # Detail aktivitas
│   │   │   ├── anggota_lab.php      # Daftar anggota laboratorium
│   │   │   ├── detail_dosen.php     # Profil detail dosen
│   │   │   ├── mitra.php            # Mitra kerjasama
│   │   │   ├── produk_lab.php       # Produk laboratorium
│   │   │   ├── contact_us.php       # Halaman kontak
│   │   │   ├── rekrutment.php       # Daftar rekrutment
│   │   │   ├── form_rekrutment.php  # Form pendaftaran
│   │   │   └── sukses_pendaftaran.php # Halaman sukses daftar
│   │   └── 📂 layouts/              # Reusable layouts
│   │       └── sidebar.php          # Admin sidebar navigation
│   │
│   ├── 📂 Helpers/                  # Helper functions
│   │   ├── SessionHelper.php        # Session timeout management (NEW)
│   │   ├── CsrfHelper.php           # CSRF token management
│   │   ├── FileUploadHelper.php     # File upload utilities
│   │   ├── PaginationHelper.php     # Pagination logic
│   │   ├── ResponseHelper.php       # JSON response formatter
│   │   ├── ValidationHelper.php     # Input validation
│   │   ├── EmailHelper.php          # Email sending
│   │   └── date_helper.php          # Date utilities
│   │
│   ├── 📂 Middleware/               # Middleware components
│   │   └── AuthMiddleware.php       # Auth + session timeout checker
│   │
│   └── 📂 Core/                     # Core framework files
│       ├── Database.php             # Database connection
│       ├── BaseModel.php            # Base model class
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
│   │   │   ├── 📂 components/       # Component styles (sidebar, navbar, typography)
│   │   │   └── 📂 pages/            # Page-specific styles
│   │   │       ├── 📂 home/         # Homepage styles (home.css)
│   │   │       ├── 📂 publikasi/    # Publikasi dosen styles
│   │   │       ├── 📂 aktivitas-lab/# Aktivitas styles
│   │   │       ├── 📂 contact-us/   # Contact page styles
│   │   │       └── ...              # Other page styles
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

### 4. Database Schema Overview

Database menggunakan **naming convention** dengan prefix:
- **sys_** - System tables (authentication, users)
- **ref_** - Reference/lookup tables
- **mst_** - Master data tables
- **trx_** - Transaction/activity tables
- **map_** - Many-to-many mapping tables

**Tabel-tabel utama:**

#### System Tables (sys_)
```sql
-- sys_users: User authentication
CREATE TABLE sys_users (
    id BIGSERIAL PRIMARY KEY,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role user_role_enum NOT NULL DEFAULT 'guest', -- 'guest' or 'admin'
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);
```

#### Reference Tables (ref_)
```sql
-- ref_jabatan: Positions/titles
CREATE TABLE ref_jabatan (
    id BIGSERIAL PRIMARY KEY,
    nama_jabatan VARCHAR(255) UNIQUE NOT NULL
);

-- ref_keahlian: Expertise areas
CREATE TABLE ref_keahlian (
    id BIGSERIAL PRIMARY KEY,
    nama_keahlian VARCHAR(255) UNIQUE NOT NULL
);
```

#### Master Tables (mst_)
```sql
-- mst_dosen: Lecturers/professors
CREATE TABLE mst_dosen (
    id BIGSERIAL PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    nidn VARCHAR(50) UNIQUE,
    foto_profil VARCHAR(255),
    deskripsi TEXT,
    status_aktif BOOLEAN DEFAULT TRUE,
    jabatan_id BIGINT REFERENCES ref_jabatan(id),
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);

-- mst_mahasiswa: Lab assistants
CREATE TABLE mst_mahasiswa (
    id BIGSERIAL PRIMARY KEY,
    nim VARCHAR(20) UNIQUE NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    nama VARCHAR(150) NOT NULL,
    no_hp VARCHAR(20),
    jabatan_lab VARCHAR(100) DEFAULT 'Asisten Lab',
    semester INT NOT NULL,
    link_github VARCHAR(255),
    status_aktif BOOLEAN DEFAULT TRUE,
    tanggal_gabung DATE DEFAULT CURRENT_DATE,
    asal_pendaftar_id BIGINT REFERENCES trx_pendaftar(id),
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);
```

#### Transaction Tables (trx_)
```sql
-- trx_rekrutmen: Recruitment periods
CREATE TABLE trx_rekrutmen (
    id BIGSERIAL PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT NOT NULL,
    status rekrutmen_status_enum NOT NULL DEFAULT 'tutup', -- 'buka' or 'tutup'
    tanggal_buka DATE,
    tanggal_tutup DATE,
    lokasi VARCHAR(255),
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);

-- trx_pendaftar: Applicants
CREATE TABLE trx_pendaftar (
    id BIGSERIAL PRIMARY KEY,
    rekrutmen_id BIGINT NOT NULL REFERENCES trx_rekrutmen(id),
    nim VARCHAR(20) NOT NULL,
    nama VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    no_hp VARCHAR(20),
    semester INT NOT NULL,
    ipk DECIMAL(3,2),
    link_portfolio VARCHAR(255),
    link_github VARCHAR(255),
    file_cv VARCHAR(255) NOT NULL,
    file_khs VARCHAR(255),
    status_seleksi seleksi_status_enum NOT NULL DEFAULT 'Pending', -- 'Pending', 'Diterima', 'Ditolak'
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at TIMESTAMP NOT NULL DEFAULT NOW()
);
```

#### Mapping Tables (map_)
```sql
-- map_dosen_keahlian: Dosen-Keahlian many-to-many
CREATE TABLE map_dosen_keahlian (
    dosen_id BIGINT REFERENCES mst_dosen(id) ON DELETE CASCADE,
    keahlian_id BIGINT REFERENCES ref_keahlian(id) ON DELETE CASCADE,
    PRIMARY KEY (dosen_id, keahlian_id)
);
```

**Import Schema Lengkap:**
```bash
psql -U postgres -d db_lab_ai -f database/schema.sql
```

### 5. Import Stored Procedures & Views

**Stored Procedures:**
```bash
# Navigate to procedures folder
cd database/procedures/

# Import procedures
psql -U postgres -d db_lab_ai -f sp_dosen.sql
psql -U postgres -d db_lab_ai -f sp_recruitment.sql
psql -U postgres -d db_lab_ai -f sp_aktivitas_lab.sql
psql -U postgres -d db_lab_ai -f sp_fasilitas.sql
psql -U postgres -d db_lab_ai -f sp_mitra.sql
psql -U postgres -d db_lab_ai -f sp_produk.sql
psql -U postgres -d db_lab_ai -f sp_publikasi_akademik.sql
```

**Database Views:**
```bash
# Import views for data presentation
cd database/views/

psql -U postgres -d db_lab_ai -f vw_show_dosen.sql
psql -U postgres -d db_lab_ai -f vw_show_recruitment.sql
psql -U postgres -d db_lab_ai -f vw_show_publikasi_akademik.sql
psql -U postgres -d db_lab_ai -f vw_show_produk.sql
psql -U postgres -d db_lab_ai -f vw_dashboard.sql
```

**Key Stored Procedures:**
- `sp_insert_dosen()` - Insert dosen dengan keahlian (many-to-many)
- `sp_update_dosen()` - Update dosen dengan keahlian
- `sp_insert_recruitment()` - Insert recruitment period
- `sp_update_recruitment()` - Update recruitment period

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

### 5. **SessionHelper** - Session Timeout Management

**File:** `app/Helpers/SessionHelper.php`

```php
// Initialize session timestamps (saat login)
SessionHelper::initSessionTimestamps();

// Update last activity
SessionHelper::updateLastActivity();

// Check if session expired
$check = SessionHelper::isSessionExpired();
if ($check['expired']) {
    echo $check['reason']; // 'idle' or 'absolute'
}

// Regenerate session ID periodically
SessionHelper::regenerateSessionIfNeeded();

// Destroy session completely
SessionHelper::destroySession();
```

**Session Timeout Configuration:**
File: `config/app.php`
```php
define('SESSION_IDLE_TIMEOUT', 30 * 60);      // 30 minutes
define('SESSION_ABSOLUTE_TIMEOUT', 8 * 60 * 60); // 8 hours
define('SESSION_REGENERATION_INTERVAL', 15 * 60); // 15 minutes
```

**Cara Kerja:**
- **Idle Timeout**: Session expired setelah 30 menit tidak ada aktivitas
- **Absolute Timeout**: Session expired setelah 8 jam sejak login (maksimal)
- **Session Regeneration**: Session ID diregen setiap 15 menit (mencegah session fixation)

### 6. **CsrfHelper** - CSRF Protection

**File:** `app/Helpers/CsrfHelper.php`

```php
// Generate CSRF token (di form)
$token = CsrfHelper::generateToken();

// Validate CSRF token (di controller)
if (!CsrfHelper::validateToken($csrfToken)) {
    ResponseHelper::error('Invalid CSRF token');
}

// Regenerate token (setelah operasi sukses)
CsrfHelper::regenerateToken();
```

### 7. **EmailHelper** - Email Sending

**File:** `app/Helpers/EmailHelper.php`

```php
// Send email via PHPMailer
$result = EmailHelper::send(
    'user@example.com',      // To
    'Welcome',               // Subject
    '<h1>Welcome!</h1>'     // HTML Body
);

if ($result['success']) {
    echo 'Email sent!';
}
```

### 8. **Helper Functions** - Global Utilities

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
│   │   ├── variables.css     # CSS variables (colors, fonts, spacing)
│   │   └── layout.css        # Layout utilities
│   ├── components/
│   │   ├── sidebar.css       # Sidebar styles
│   │   └── typography.css    # Typography styles
│   └── pages/
│       ├── home/
│       │   └── home.css      # Homepage styles dengan sections:
│       │                     # - Global & Utilities
│       │                     # - Hero Section
│       │                     # - Visi Misi V2 (Accordion)
│       │                     # - Statistik Section
│       │                     # - Fasilitas V2 (Grid & Modal)
│       │                     # - Publikasi V2 (Horizontal Scroll)
│       │                     # - Aktivitas Section (Card Grid)
│       │                     # - CTA Section (Glass Bubbles)
│       │                     # - Responsive Design (5 breakpoints)
│       ├── publikasi/
│       │   └── publikasi_dosen.css  # Publikasi dosen page
│       ├── aktivitas-lab/
│       ├── contact-us/
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
│       ├── publikasi_dosen/
│       │   └── publikasi_dosen.js # Search, filter tahun, filter tipe
│       └── dosen/
│           ├── index.js      # List page logic
│           └── form.js       # Form logic
└── images/
    ├── beranda/              # Homepage images
    │   ├── pattern-hero-section-bg.png
    │   └── assets-home.png
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

### Authentication Flow (Updated with Session Timeout)
```
1. User akses protected page (e.g., /dashboard)
   ↓
2. Router check middleware (AuthMiddleware::class)
   ↓
3. Middleware checks:
   - Apakah user logged in? ($_SESSION['user_id'])
   - Apakah role = admin? ($_SESSION['role'])
   - Apakah session expired? (idle/absolute timeout)
   ↓
4a. If valid → Update last activity & regenerate session (if needed) → Allow access
4b. If not logged in → Redirect to /login
4c. If session expired → Destroy session → Redirect to /login with timeout message
4d. If not admin → Show 403 error
```

### AuthMiddleware (with Session Timeout)

**File:** `app/Middleware/AuthMiddleware.php`

```php
use App\Helpers\SessionHelper;

class AuthMiddleware {
    public function handle() {
        // 1. Check if user logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . base_url('/'));
            exit;
        }

        // 2. Check admin role
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            echo '<h1>403 - Akses Ditolak</h1>';
            exit;
        }

        // 3. Check session timeout
        $timeoutCheck = SessionHelper::isSessionExpired();
        if ($timeoutCheck['expired']) {
            SessionHelper::destroySession();
            session_start();
            $_SESSION['timeout_reason'] = $timeoutCheck['reason'];
            $_SESSION['timeout_message'] = $this->getTimeoutMessage($timeoutCheck);
            header('Location: ' . base_url('/'));
            exit;
        }

        // 4. Update last activity
        SessionHelper::updateLastActivity();

        // 5. Regenerate session ID periodically (anti session fixation)
        SessionHelper::regenerateSessionIfNeeded();

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
            "email": "yuri@polinema.ac.id",
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
- status_aktif: integer (1=aktif, 0=tidak aktif)
- foto_profil: file
- deskripsi: text
- csrf_token: string

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

### Recruitment Management

#### Get All Recruitment
```
GET /applied-informatics/admin/recruitment?page=1&per_page=10

Response:
{
    "success": true,
    "data": [...],
    "pagination": {...}
}
```

#### Create Recruitment
```
POST /applied-informatics/admin/recruitment/create

Body:
- judul: string
- deskripsi: text
- status: enum ('buka', 'tutup')
- tanggal_buka: date
- tanggal_tutup: date
- lokasi: string
- csrf_token: string

Response:
{
    "success": true,
    "message": "Rekrutmen berhasil dibuat"
}
```

#### Update Recruitment Status
```
POST /applied-informatics/admin/recruitment/update-status/{id}

Body:
- status: enum ('buka', 'tutup')

Response:
{
    "success": true,
    "message": "Status rekrutmen berhasil diubah"
}
```

### Pendaftar Management (Applicants)

#### Get All Pendaftar with Server-Side Search & Pagination
```
GET /applied-informatics/admin/pendaftar/get-all?page=1&per_page=10&search=john

Response:
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nim": "12345678",
            "nama": "John Doe",
            "email": "john@example.com",
            "semester": 5,
            "ipk": "3.75",
            "status_seleksi": "Pending",
            "created_at": "2024-11-29 10:00:00"
        }
    ],
    "pagination": {
        "total": 50,
        "per_page": 10,
        "current_page": 1,
        "last_page": 5
    }
}
```

#### Accept Applicant
```
POST /applied-informatics/admin/pendaftar/terima/{id}

Response:
{
    "success": true,
    "message": "Pendaftar berhasil diterima dan dipindahkan ke asisten lab"
}
```

#### Reject Applicant
```
POST /applied-informatics/admin/pendaftar/tolak/{id}

Response:
{
    "success": true,
    "message": "Pendaftar berhasil ditolak"
}
```

#### Delete Applicant
```
POST /applied-informatics/admin/pendaftar/delete/{id}

Response:
{
    "success": true,
    "message": "Data pendaftar berhasil dihapus"
}
```

### Asisten Lab Management (Lab Assistants)

#### Get All Asisten Lab with Server-Side Search & Pagination
```
GET /applied-informatics/admin/asisten-lab/get-all?page=1&per_page=10&search=jane

Response:
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nim": "12345678",
            "nama": "Jane Doe",
            "email": "jane@example.com",
            "semester": 6,
            "jabatan_lab": "Asisten Lab",
            "status_aktif": true,
            "tanggal_gabung": "2024-01-15"
        }
    ],
    "total": 25
}
```

#### Update Asisten Lab
```
POST /applied-informatics/admin/asisten-lab/update/{id}

Body:
- nim: string
- nama: string
- email: string
- no_hp: string
- semester: integer
- jabatan_lab: string
- link_github: string
- status_aktif: boolean
- csrf_token: string

Response:
{
    "success": true,
    "message": "Data asisten lab berhasil diupdate"
}
```

#### Delete Asisten Lab
```
POST /applied-informatics/admin/asisten-lab/delete/{id}

Response:
{
    "success": true,
    "message": "Data asisten lab berhasil dihapus"
}
```

### Client/Public Endpoints

#### Submit Recruitment Application (Public)
```
POST /applied-informatics/recruitment/submit

Body (multipart/form-data):
- rekrutmen_id: integer
- nim: string
- nama: string
- email: string
- no_hp: string
- semester: integer
- ipk: decimal
- link_portfolio: string (optional)
- link_github: string (optional)
- file_cv: file (PDF, max 2MB)
- file_khs: file (PDF, max 2MB, optional)
- csrf_token: string

Response:
{
    "success": true,
    "message": "Pendaftaran berhasil dikirim"
}
```

---

## 👥 Team

**Applied Informatics Laboratory Development Team**

- **Project Lead and Backend Developer:** Ananda Priya Yustira
- **Backend Developer:** Fadhil Taufiqurrahman
- **Frontend Developer:** Muhammad Fattahul Alim
- **System Analyst:** Louis Judia B Sinaga
- **UI/UX Designer:** Rizal

**Contact:**
- Email: lab.ai@polinema.ac.id
- Website: [URL]

---

## 📝 Changelog

### Version 3.0.0 (2024-11-29)
**Major Features & Security Updates**

#### 🔐 Security Enhancements
- ✨ **Session Timeout Management** - Idle timeout (30 min), absolute timeout (8 hours), session regeneration (15 min)
- 🛡️ **SessionHelper** - Comprehensive session management with timeout tracking
- 🔒 **Enhanced AuthMiddleware** - Added timeout checks and session regeneration
- ⚡ **CSRF Token Regeneration** - Auto-regenerate after successful operations

#### 🎓 Recruitment System (NEW)
- ✨ **Recruitment Management** - Create and manage recruitment periods
- 📝 **Public Application Form** - Modern, responsive recruitment form
- 👥 **Pendaftar Module** - Manage applicants with accept/reject workflow
- 🔍 **Server-Side Search & Pagination** - Efficient search across applicants
- 📊 **Asisten Lab Module** - Manage accepted lab assistants
- 🔄 **Automated Transfer** - Auto-create lab assistant when applicant accepted

#### 👨‍🏫 Dosen Management Updates
- ✨ **Status Aktif Field** - Track active/inactive status for lecturers
- 🔧 **Updated Stored Procedures** - sp_insert_dosen and sp_update_dosen with status_aktif
- 🎨 **Status Badge UI** - Visual indicators for active/inactive status

#### 📊 Content Management Enhancements
- ✨ **Aktivitas Lab** - Lab activities management
- 🏢 **Fasilitas** - Facilities management
- 🤝 **Mitra** - Partnership management
- 📦 **Produk** - Product showcase
- 📚 **Publikasi Akademik** - Academic publications (Riset, KI, PPM)

#### 🗄️ Database Improvements
- 🔄 **Schema Refactoring** - Organized with naming conventions (sys_, ref_, mst_, trx_, map_)
- 📋 **New Tables** - trx_rekrutmen, trx_pendaftar, mst_mahasiswa
- 🔗 **Database Views** - vw_show_dosen, vw_show_recruitment, vw_dashboard
- ⚡ **Stored Procedures** - Complex operations handled by PostgreSQL procedures

#### 🎨 UI/UX Improvements
- 📱 **Responsive Forms** - Modern recruitment form design
- 🎯 **Required Field Indicators** - Visual "*" markers for required fields
- 📊 **Enhanced Dashboard** - Updated statistics from all modules
- 🎨 **Custom CSS** - Page-specific styling for better UX

#### 🛠️ Technical Updates
- 📧 **EmailHelper** - PHPMailer integration for notifications
- 🔧 **ValidationHelper** - Enhanced input validation
- 📦 **BaseModel** - Base class for all models
- 🗂️ **Organized Assets** - Structured CSS/JS by pages

#### 🐛 Bug Fixes
- 🔧 **Parameter Order Fix** - Fixed DosenModel parameter order for stored procedures
- 🐛 **CSRF Token Validation** - Improved token handling
- 🔒 **Session Security** - Fixed session fixation vulnerability

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

## 🙏 Acknowledgments

- Bootstrap 5 - UI Framework
- jQuery - JavaScript library
- Feather Icons - Icon set
- PostgreSQL - Database
- PHP - Programming language

---

**Last Updated:** 2024-11-29
**Version:** 3.0.0
**Location:** `/Applications/MAMP/htdocs/applied-informatics/`

**Major Updates in v3.0.0:**
- 🔐 Session Timeout Management
- 🎓 Recruitment System (complete workflow)
- 👥 Pendaftar & Asisten Lab modules
- ✨ Status aktif field for dosen
- 📊 Enhanced security features


