# Panduan Routing - PBL Web Lab AI

## 📌 Sistem Routing
Project ini menggunakan **Query String Routing** yang sederhana dan mudah dipahami.

---

## 🌐 Daftar URL

### Development (MAMP)
Base URL: `http://localhost:8888/pbl_web_lab_ai/web/`

| Halaman | URL |
|---------|-----|
| **Home** | `http://localhost:8888/pbl_web_lab_ai/web/` <br> `http://localhost:8888/pbl_web_lab_ai/web/index.php` <br> `http://localhost:8888/pbl_web_lab_ai/web/index.php?url=home` |
| **Login** | `http://localhost:8888/pbl_web_lab_ai/web/index.php?url=login` |
| **Register** | `http://localhost:8888/pbl_web_lab_ai/web/index.php?url=register` |
| **Dashboard** | `http://localhost:8888/pbl_web_lab_ai/web/index.php?url=dashboard` |

---

## 📝 Cara Menambah Route Baru

1. Buka file `index.php`
2. Tambahkan case baru di dalam switch statement:

```php
case 'nama-route-baru':
    // Opsional: cek authentication
    // if (!isset($_SESSION['user_id'])) {
    //     header('Location: index.php?url=login');
    //     exit;
    // }

    require_once 'app/views/folder/file.php';
    break;
```

3. Akses dengan URL: `index.php?url=nama-route-baru`

---

## 🔧 Contoh Implementasi

### Tambah Route "About"

**File: index.php**
```php
case 'about':
    require_once 'app/views/public/about.php';
    break;
```

**URL:** `http://localhost:8888/pbl_web_lab_ai/web/index.php?url=about`

---

### Tambah Route dengan Parameter

**File: index.php**
```php
case 'profile':
    // Get ID dari query string
    $userId = $_GET['id'] ?? null;

    if (!$userId) {
        header('Location: index.php?url=home');
        exit;
    }

    require_once 'app/views/profile/index.php';
    break;
```

**URL:** `http://localhost:8888/pbl_web_lab_ai/web/index.php?url=profile&id=123`

---

## 🚫 File yang Sudah Tidak Digunakan

- ❌ `.htaccess` → sudah direname jadi `.htaccess.disabled`
- ✅ Tidak perlu URL rewriting lagi
- ✅ Tidak perlu konfigurasi Apache mod_rewrite

---

## 💡 Tips untuk Tim

1. **Bookmark URL favorit** di browser untuk development
2. **Copy-paste URL** langsung ke browser
3. **Tidak perlu setup .htaccess** di local environment
4. **Mudah di-debug** - error langsung ke route yang sesuai

---

## 🔐 Authentication Flow

```
User belum login → akses dashboard
  ↓
index.php?url=dashboard (cek session)
  ↓
redirect ke index.php?url=login
  ↓
setelah login → set $_SESSION['user_id']
  ↓
redirect ke index.php?url=dashboard
```

---

## 📂 Struktur File Routing

```
web/
├── index.php              ← Entry point & routing logic
├── ROUTING.md            ← Dokumentasi ini
├── app/
│   └── views/
│       ├── home/
│       │   └── index.php      (route: home)
│       ├── auth/
│       │   ├── login.php      (route: login)
│       │   └── register.php   (route: register)
│       └── dashboard/
│           └── index.php      (route: dashboard)
```

---

## ❓ FAQ

**Q: Kenapa tidak pakai .htaccess untuk URL yang lebih bersih?**
A: Untuk skala project kecil, query string lebih mudah dipahami tim dan tidak perlu konfigurasi tambahan.

**Q: Bagaimana cara pindah ke production?**
A: Ganti `BASE_URL` di `config/app.php` sesuai domain production.

**Q: Bisa tambah middleware authentication?**
A: Ya, uncomment kode authentication di setiap case yang memerlukan login.

---

📅 Last updated: 2025-11-06
👥 Team: Group 1 - PBL Web Lab AI
