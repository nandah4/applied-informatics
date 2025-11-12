# 📚 KERANGKA KERJA FITUR FASILITAS

> Dokumentasi lengkap untuk membangun fitur manajemen fasilitas laboratorium

---

## 📋 Daftar Isi

1. [Penjelasan Fitur](#penjelasan-fitur)
2. [Struktur Database](#struktur-database)
3. [Model - FasilitasModel.php](#model---fasilitasmodelphp)
4. [Controller - FasilitasController.php](#controller---fasilitascontrollerphp)
5. [Routes - routes.php](#routes---routesphp)
6. [Contoh Penggunaan](#contoh-penggunaan)

---

## 🎯 Penjelasan Fitur

Fitur **Fasilitas** adalah untuk mengelola data fasilitas laboratorium seperti:
- Komputer/Laptop
- Proyektor
- Printer
- Alat-alat lab
- Ruangan
- dll

### Operasi yang Dibutuhkan (CRUD)
- ✅ **C**reate - Tambah fasilitas baru
- ✅ **R**ead - Lihat daftar & detail fasilitas
- ✅ **U**pdate - Edit data fasilitas
- ✅ **D**elete - Hapus fasilitas

---

## 🗄️ Struktur Database

### Tabel: `tbl_fasilitas`

Lihat di direktori database/schema.sql

## 📦 Model - FasilitasModel.php

> **Lokasi:** `app/Models/FasilitasModel.php`
>
> **Fungsi:** Menangani semua operasi database untuk fasilitas

### Kerangka Kode:

```php
<?php

/**
 * FASILITAS MODEL
 *
 * Model ini menangani semua operasi database untuk tabel tbl_fasilitas
 * Menggunakan PDO untuk koneksi database PostgreSQL
 */

class FasilitasModel
{
    private $db;
    private $table_name = 'tbl_fasilitas';

    /**
     * CONSTRUCTOR
     *
     * Dipanggil saat object FasilitasModel dibuat
     * Fungsi: Inisialisasi koneksi database
     */
    public function __construct()
    {
        // TODO: Buat koneksi database
        // $database = new Database();
        // $this->db = $database->getConnection();
    }

    /**
     * GET ALL FASILITAS
     *
     * Fungsi: Mengambil semua data fasilitas dari database
     *
     * @return array Format: ['success' => true, 'data' => [...]]
     *
     * Contoh Return:
     * [
     *   'success' => true,
     *   'message' => 'Berhasil mengambil data',
     *   'data' => [
     *     ['id' => 1, 'nama_fasilitas' => 'Komputer', ...],
     *     ['id' => 2, 'nama_fasilitas' => 'Proyektor', ...]
     *   ]
     * ]
     */
    public function getAllFasilitas()
    {
        // TODO: Implementasi query untuk ambil semua data
        // 1. Buat query SELECT * FROM tbl_fasilitas
        // 2. Prepare dan execute
        // 3. Fetch semua data
        // 4. Return dalam format array
    }

    /**
     * GET ALL FASILITAS (DENGAN PAGINATION)
     *
     * Fungsi: Mengambil data fasilitas dengan batasan per halaman
     *
     * @param int $limit  - Jumlah data per halaman (default: 10)
     * @param int $offset - Data mulai dari ke berapa (default: 0)
     *
     * @return array Format: ['success' => true, 'data' => [...], 'total' => 100]
     *
     * Cara Kerja Pagination:
     * - Halaman 1: limit=10, offset=0  (data 1-10)
     * - Halaman 2: limit=10, offset=10 (data 11-20)
     * - Halaman 3: limit=10, offset=20 (data 21-30)
     */
    public function getAllFasilitasPaginated($limit = 10, $offset = 0)
    {
        // TODO: Implementasi query dengan LIMIT dan OFFSET
        // 1. Hitung total data (COUNT)
        // 2. Query data dengan LIMIT dan OFFSET
        // 3. Return data + total
    }

    /**
     * GET FASILITAS BY ID
     *
     * Fungsi: Mengambil detail 1 fasilitas berdasarkan ID
     *
     * @param int $id - ID fasilitas yang dicari
     *
     * @return array Format: ['success' => true, 'data' => [...]]
     *
     * Contoh Return:
     * [
     *   'success' => true,
     *   'data' => ['id' => 1, 'nama_fasilitas' => 'Komputer', ...]
     * ]
     */
    public function getFasilitasById($id)
    {
        // TODO: Implementasi query SELECT dengan WHERE id = ?
        // 1. Query SELECT * FROM tbl_fasilitas WHERE id = :id
        // 2. Bind parameter :id
        // 3. Execute dan fetch
        // 4. Return data
    }

    /**
     * INSERT FASILITAS
     *
     * Fungsi: Menambahkan fasilitas baru ke database
     *
     * @param array $data - Data fasilitas yang akan disimpan
     *                      Format: [
     *                          'nama_fasilitas' => 'Komputer Lab 1',
     *                          'kategori' => 'Hardware',
     *                          'jumlah' => 10,
     *                          'kondisi' => 'Baik',
     *                          'deskripsi' => 'Komputer untuk praktikum',
     *                          'foto' => 'komputer.jpg'
     *                      ]
     *
     * @return array Format: ['success' => true, 'data' => ['id' => 5]]
     */
    public function insertFasilitas($data)
    {
        // TODO: Implementasi query INSERT
        // 1. Buat query INSERT INTO tbl_fasilitas (kolom1, kolom2) VALUES (:val1, :val2)
        // 2. Bind semua parameter
        // 3. Execute
        // 4. Ambil ID yang baru dibuat (lastInsertId)
        // 5. Return ID baru
    }

    /**
     * UPDATE FASILITAS
     *
     * Fungsi: Mengupdate data fasilitas yang sudah ada
     *
     * @param int   $id   - ID fasilitas yang akan diupdate
     * @param array $data - Data baru (format sama seperti insert)
     *
     * @return array Format: ['success' => true, 'message' => 'Berhasil update']
     */
    public function updateFasilitas($id, $data)
    {
        // TODO: Implementasi query UPDATE
        // 1. Buat query UPDATE tbl_fasilitas SET kolom1=:val1 WHERE id=:id
        // 2. Bind semua parameter
        // 3. Execute
        // 4. Return status berhasil/gagal
    }

    /**
     * DELETE FASILITAS
     *
     * Fungsi: Menghapus fasilitas dari database
     *
     * @param int $id - ID fasilitas yang akan dihapus
     *
     * @return array Format: ['success' => true, 'data' => ['foto' => 'nama_foto.jpg']]
     *
     * Catatan: Return nama foto untuk dihapus dari folder uploads
     */
    public function deleteFasilitas($id)
    {
        // TODO: Implementasi query DELETE
        // 1. Ambil data fasilitas dulu (untuk dapat nama foto)
        // 2. Query DELETE FROM tbl_fasilitas WHERE id=:id
        // 3. Execute
        // 4. Return status + nama foto
    }
}
```

---

## 🎮 Controller - FasilitasController.php

> **Lokasi:** `app/Controllers/FasilitasController.php`
>
> **Fungsi:** Menangani request dari user, validasi, dan memanggil Model

### Kerangka Kode:

```php
<?php

/**
 * FASILITAS CONTROLLER
 *
 * Controller ini menangani semua request untuk fitur fasilitas
 * Tugasnya: Validasi input, panggil Model, return response
 */

class FasilitasController
{
    private $fasilitasModel;

    /**
     * CONSTRUCTOR
     *
     * Inisialisasi FasilitasModel saat controller dibuat
     */
    public function __construct()
    {
        // TODO: Inisialisasi model
        // $this->fasilitasModel = new FasilitasModel();
    }

    /**
     * GET ALL FASILITAS (Untuk Halaman List)
     *
     * Fungsi: Mengambil data fasilitas dengan pagination
     * Method: GET
     *
     * @return array Data fasilitas + pagination info
     *
     * Cara Kerja:
     * 1. Ambil parameter page & per_page dari URL ($_GET)
     * 2. Validasi parameter (harus angka, min 1)
     * 3. Hitung offset untuk pagination
     * 4. Panggil Model->getAllFasilitasPaginated()
     * 5. Gunakan PaginationHelper untuk generate info halaman
     * 6. Return data + pagination
     */
    public function getAllFasilitas()
    {
        // TODO: Implementasi
        // 1. $page = $_GET['page'] ?? 1;
        // 2. $perPage = $_GET['per_page'] ?? 10;
        // 3. Panggil model
        // 4. Generate pagination
        // 5. Return data
    }

    /**
     * GET FASILITAS BY ID (Untuk Halaman Detail)
     *
     * Fungsi: Mengambil detail 1 fasilitas
     * Method: GET
     *
     * @param int $id - ID fasilitas
     * @return array Detail fasilitas
     */
    public function getFasilitasById($id)
    {
        // TODO: Implementasi
        // 1. Validasi ID (harus angka, > 0)
        // 2. Panggil model->getFasilitasById($id)
        // 3. Return data
    }

    /**
     * CREATE FASILITAS (Handle Form Submit)
     *
     * Fungsi: Menambahkan fasilitas baru
     * Method: POST
     * Endpoint: /fasilitas/create
     *
     * Request Body:
     * - nama_fasilitas (required)
     * - kategori (required)
     * - jumlah (required, number)
     * - kondisi (required)
     * - deskripsi (optional)
     * - foto (optional, file upload)
     *
     * Response: JSON
     */
    public function createFasilitas()
    {
        // TODO: Implementasi
        // 1. Cek request method harus POST
        // 2. Ambil data dari $_POST
        // 3. Validasi semua input (gunakan ValidationHelper)
        // 4. Handle upload foto (gunakan FileUploadHelper)
        // 5. Siapkan data untuk insert
        // 6. Panggil model->insertFasilitas($data)
        // 7. Return JSON response (gunakan ResponseHelper)
    }

    /**
     * UPDATE FASILITAS (Handle Edit Form)
     *
     * Fungsi: Mengupdate data fasilitas
     * Method: POST
     * Endpoint: /fasilitas/update
     *
     * Request Body: Sama seperti create + id
     */
    public function updateFasilitas()
    {
        // TODO: Implementasi (mirip dengan create)
        // 1. Validasi request method
        // 2. Ambil data dari $_POST (termasuk id)
        // 3. Validasi input
        // 4. Handle upload foto (jika ada foto baru)
        // 5. Hapus foto lama jika ada foto baru
        // 6. Panggil model->updateFasilitas($id, $data)
        // 7. Return response
    }

    /**
     * DELETE FASILITAS
     *
     * Fungsi: Menghapus fasilitas
     * Method: POST
     * Endpoint: /fasilitas/delete/{id}
     *
     * @param int $id - ID fasilitas yang akan dihapus
     */
    public function deleteFasilitasById($id)
    {
        // TODO: Implementasi
        // 1. Validasi request method
        // 2. Validasi ID
        // 3. Panggil model->deleteFasilitas($id)
        // 4. Hapus file foto jika ada
        // 5. Return response
    }

    /**
     * VALIDATE INPUT (Helper Private Method)
     *
     * Fungsi: Validasi input form fasilitas
     * Method: Private (hanya digunakan di controller ini)
     *
     * @param array $data - Data yang akan divalidasi
     * @return array - Array error messages (kosong jika valid)
     */
    private function validateFasilitasInput($data)
    {
        // TODO: Implementasi validasi
        // 1. Validasi nama_fasilitas (required, min 3 char)
        // 2. Validasi kategori (required)
        // 3. Validasi jumlah (required, harus angka)
        // 4. Validasi kondisi (required)
        // 5. Return array errors
    }
}
```

---

## 🛣️ Routes - routes.php

> **Lokasi:** `config/routes.php`
>
> **Fungsi:** Mendefinisikan URL dan menghubungkan ke Controller

### Kerangka Kode:

```php
<?php

// ========================================
// FASILITAS ROUTES
// ========================================

/**
 * GET /fasilitas
 * Halaman: List semua fasilitas (dengan pagination)
 * View: app/Views/admin/fasilitas/index.php
 */
$router->get('fasilitas', function () {
    // TODO: Implementasi
    // 1. Buat instance controller
    // 2. Panggil method getAllFasilitas()
    // 3. Ambil data dari controller
    // 4. Pass data ke view
    // 5. Require view file

    // Contoh:
    // $controller = new FasilitasController();
    // $result = $controller->getAllFasilitas();
    // $listFasilitas = $result['data'];
    // $pagination = $result['pagination'];
    // require __DIR__ . '/../app/Views/admin/fasilitas/index.php';
}, [AuthMiddleware::class]);

/**
 * GET /fasilitas/create
 * Halaman: Form tambah fasilitas baru
 * View: app/Views/admin/fasilitas/create.php
 */
$router->get('fasilitas/create', function () {
    // TODO: Tampilkan form create
    // require __DIR__ . '/../app/Views/admin/fasilitas/create.php';
}, [AuthMiddleware::class]);

/**
 * POST /fasilitas/create
 * API: Handle submit form tambah fasilitas
 * Response: JSON
 */
$router->post('fasilitas/create', function () {
    // TODO: Implementasi
    // 1. Buat instance controller
    // 2. Panggil method createFasilitas()

    // Contoh:
    // $controller = new FasilitasController();
    // $controller->createFasilitas();
});

/**
 * GET /fasilitas/edit/{id}
 * Halaman: Form edit fasilitas
 * View: app/Views/admin/fasilitas/edit.php
 */
$router->get('fasilitas/edit/:id', function ($id) {
    // TODO: Implementasi
    // 1. Buat instance controller
    // 2. Ambil data fasilitas by ID
    // 3. Pass data ke view
    // 4. Require view file
}, [AuthMiddleware::class]);

/**
 * POST /fasilitas/update
 * API: Handle submit form edit fasilitas
 * Response: JSON
 */
$router->post('fasilitas/update', function () {
    // TODO: Implementasi
    // $controller = new FasilitasController();
    // $controller->updateFasilitas();
});

/**
 * GET /fasilitas/detail/{id}
 * Halaman: Detail fasilitas
 * View: app/Views/admin/fasilitas/read.php
 */
$router->get('fasilitas/detail/:id', function ($id) {
    // TODO: Implementasi
    // 1. Ambil data fasilitas
    // 2. Tampilkan view detail
}, [AuthMiddleware::class]);

/**
 * POST /fasilitas/delete/{id}
 * API: Hapus fasilitas
 * Response: JSON
 */
$router->post('fasilitas/delete/:id', function ($id) {
    // TODO: Implementasi
    // $controller = new FasilitasController();
    // $controller->deleteFasilitasById($id);
});
```

---

## 💡 Contoh Penggunaan

### 1. Alur Create Fasilitas

```
User mengisi form → Submit
         ↓
routes.php: POST /fasilitas/create
         ↓
FasilitasController->createFasilitas()
         ↓
Validasi input (ValidationHelper)
         ↓
Upload foto (FileUploadHelper)
         ↓
FasilitasModel->insertFasilitas($data)
         ↓
Database: INSERT INTO tbl_fasilitas
         ↓
Return JSON response
         ↓
Frontend: Tampilkan alert sukses
```

### 2. Alur Read/List Fasilitas

```
User akses /fasilitas?page=1&per_page=10
         ↓
routes.php: GET /fasilitas
         ↓
FasilitasController->getAllFasilitas()
         ↓
Ambil parameter page & per_page
         ↓
FasilitasModel->getAllFasilitasPaginated(10, 0)
         ↓
Database: SELECT dengan LIMIT 10 OFFSET 0
         ↓
PaginationHelper->paginate()
         ↓
Pass data ke View
         ↓
Render HTML tabel + pagination
```

### 3. Alur Update Fasilitas

```
User edit data → Submit
         ↓
POST /fasilitas/update
         ↓
FasilitasController->updateFasilitas()
         ↓
Validasi input
         ↓
Handle foto (jika ada foto baru)
         ↓
FasilitasModel->updateFasilitas($id, $data)
         ↓
Database: UPDATE tbl_fasilitas WHERE id=X
         ↓
Hapus foto lama (jika ada foto baru)
         ↓
Return JSON response
```

### 4. Alur Delete Fasilitas

```
User klik tombol hapus → Konfirmasi
         ↓
POST /fasilitas/delete/5
         ↓
FasilitasController->deleteFasilitasById(5)
         ↓
FasilitasModel->deleteFasilitas(5)
         ↓
Database: DELETE FROM tbl_fasilitas WHERE id=5
         ↓
FileUploadHelper::delete(foto, 'fasilitas')
         ↓
Hapus file foto dari folder uploads
         ↓
Return JSON response
```

---

## 📝 Checklist Implementasi

Gunakan checklist ini untuk memastikan semua sudah dibuat:

### Database
- [ ] Buat tabel `tbl_fasilitas` di PostgreSQL
- [ ] Test insert manual data dummy

### Model (FasilitasModel.php)
- [ ] Constructor
- [ ] getAllFasilitas()
- [ ] getAllFasilitasPaginated()
- [ ] getFasilitasById()
- [ ] insertFasilitas()
- [ ] updateFasilitas()
- [ ] deleteFasilitas()

### Controller (FasilitasController.php)
- [ ] Constructor
- [ ] getAllFasilitas()
- [ ] getFasilitasById()
- [ ] createFasilitas()
- [ ] updateFasilitas()
- [ ] deleteFasilitasById()
- [ ] validateFasilitasInput() (private)

### Routes (routes.php)
- [ ] GET /fasilitas (list)
- [ ] GET /fasilitas/create (form create)
- [ ] POST /fasilitas/create (submit create)
- [ ] GET /fasilitas/edit/:id (form edit)
- [ ] POST /fasilitas/update (submit edit)
- [ ] GET /fasilitas/detail/:id (detail)
- [ ] POST /fasilitas/delete/:id (delete)

### Views
- [ ] index.php (list fasilitas)
- [ ] create.php (form tambah)
- [ ] edit.php (form edit)
- [ ] read.php (detail fasilitas)

### Assets
- [ ] CSS: fasilitas/index.css
- [ ] CSS: fasilitas/form.css
- [ ] JS: fasilitas/index.js
- [ ] JS: fasilitas/form.js

### Testing
- [ ] Test create fasilitas
- [ ] Test read/list fasilitas
- [ ] Test update fasilitas
- [ ] Test delete fasilitas
- [ ] Test upload foto
- [ ] Test pagination

---

## 🔧 Helper yang Digunakan

Pastikan sudah familiar dengan helper berikut:

1. **ValidationHelper** - Validasi input
   ```php
   ValidationHelper::validateName($nama, 3, 255);
   ValidationHelper::validateNumber($jumlah, 1, 999);
   ```

2. **FileUploadHelper** - Upload file
   ```php
   FileUploadHelper::upload($_FILES['foto'], 'image', 'fasilitas', 2*1024*1024);
   FileUploadHelper::delete($filename, 'fasilitas');
   ```

3. **ResponseHelper** - JSON response
   ```php
   ResponseHelper::success('Berhasil', ['id' => 5]);
   ResponseHelper::error('Gagal validasi');
   ```

4. **PaginationHelper** - Pagination
   ```php
   PaginationHelper::paginate($totalRecords, $currentPage, $perPage);
   ```

---

## 🎓 Tips untuk Teman Anda

1. **Mulai dari Model** - Buat method Model dulu, test langsung
2. **Lalu Controller** - Buat controller yang memanggil Model
3. **Kemudian Routes** - Hubungkan URL ke Controller
4. **Terakhir Views** - Buat tampilan HTML
5. **Gunakan Postman** - Test API endpoint sebelum buat frontend
6. **Debug dengan `error_log()`** - Log semua error ke file
7. **Ikuti pattern Dosen** - Lihat DosenController sebagai referensi

---

## 📞 Butuh Bantuan?

Jika ada yang tidak jelas:
1. Lihat implementasi `DosenController.php` dan `DosenModel.php`
2. Baca dokumentasi di `README.md`
3. Check error log di `/Applications/MAMP/logs/php_error.log`

---

**Dibuat:** 2024-11-12
**Untuk:** Pembelajaran fitur CRUD Fasilitas
**Status:** Template/Skeleton - Siap diisi implementasi
