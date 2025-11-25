<?php

/**
 * File: Controllers/MitraController.php
 * Deskripsi: Controller untuk menangani request terkait data mitra
 *
 * Fungsi utama:
 * - createMitra(): Handle request create mitra baru
 */

class MitraController
{
    private $mitraModel;

    public function __construct()
    {
        $this->mitraModel = new MitraModel();
    }

    // ========================================
    // MITRA CRUD OPERATIONS
    // ========================================

    /**
     * Get all mitra for index page
     * Method: GET
     *
     * @return array
     */
    public function getAllMitra()
    {
        // Ambil parameter dari GET request
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

        // Validasi input
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));

        // Hitung offset untuk query
        $offset = ($page - 1) * $perPage;

        // Ambil data dengan pagination
        $result = $this->mitraModel->getAllMitraWithPagination($perPage, $offset);

        // Generate pagination dari total yang dikembalikan model
        $pagination = PaginationHelper::paginate($result['total'], $page, $perPage);

        return [
            'data' => $result['data'],
            'pagination' => $pagination
        ];
    }

    /**
     * Get mitra by ID
     * Method: GET
     *
     * @param int $id
     * @return array
     */
    public function getMitraById($id)
    {
        return $this->mitraModel->getById($id);
    }

    /**
     * Handle request untuk create mitra baru
     * Method: POST
     * Endpoint: /applied-informatics/admin/mitra/create
     *
     * @return void - Mengembalikan JSON response
     */
    public function createMitra()
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 1A. Validasi csrf token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        // 2. Ambil data dari POST
        $nama = $_POST['nama'] ?? '';
        $status = $_POST['status'] ?? 'aktif';
        $kategori_mitra = $_POST['kategori_mitra'] ?? 'industri';
        $tanggal_mulai = $_POST['tanggal_mulai'];
        $tanggal_akhir = $_POST['tanggal_akhir'] ?? null;
        $deskripsi = $_POST['deskripsi'] ?? '';

        // 2A. Validasi status
        $allowedStatus = ['aktif', 'non-aktif'];
        if (!in_array($status, $allowedStatus)) {
            ResponseHelper::error('Status mitra tidak valid');
            return;
        }

        // 3. Validasi input
        $validationErrors = $this->validateMitraInput($nama, $kategori_mitra, $tanggal_mulai, $tanggal_akhir);

        if (!empty($validationErrors)) {
            ResponseHelper::error($validationErrors[0]); // Return error pertama
            return;
        }

        // 4. Handle upload logo mitra
        $logoFileName = null;
        if (isset($_FILES['logo_mitra']) && $_FILES['logo_mitra']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = FileUploadHelper::upload(
                $_FILES['logo_mitra'],
                'image',
                'mitra',
                2 * 1024 * 1024
            );

            if (!$uploadResult['success']) {
                ResponseHelper::error($uploadResult['message']);
                return;
            }

            $logoFileName = $uploadResult['filename'];
        }

        // 5. Siapkan data untuk insert
        $mitraData = [
            'nama' => $nama,
            'status' => $status,
            'kategori_mitra' => $kategori_mitra,
            'logo_mitra' => $logoFileName,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_akhir' => $tanggal_akhir,
            'deskripsi' => $deskripsi
        ];

        // 6. Insert ke database
        $result = $this->mitraModel->insert($mitraData);

        if (!$result['success']) {
            // Jika gagal dan ada logo yang sudah diupload, hapus logonya
            if ($logoFileName) {
                FileUploadHelper::delete($logoFileName, 'mitra');
            }

            ResponseHelper::error($result['message']);
            return;
        }

        ResponseHelper::success('Data mitra berhasil ditambahkan');
    }

    /**
     * Validasi input untuk create/update mitra
     *
     * @param string $nama
     * @param string $kategori_mitra
     * @param string $tanggal_mulai
     * @param string $tanggal_akhir
     * @return array - Array of error messages (kosong jika valid)
     */
    private function validateMitraInput($nama, $kategori_mitra, $tanggal_mulai, $tanggal_akhir)
    {
        $errors = [];
        $allowedKategori = ['industri', 'internasional', 'institusi pemerintah', 'institusi pendidikan', 'komunitas'];

        // Validasi nama
        if (empty($nama)) {
            $errors[] = "Nama mitra wajib diisi";
        } else {
            $namaValidation = ValidationHelper::validateName($nama, 1, 255);
            if (!$namaValidation['valid']) {
                $errors[] = $namaValidation['message'];
            }
        }

        // Validasi kategori mitra
        if (empty($kategori_mitra)) {
            $errors[] = "Kategori mitra wajib diisi";
        } elseif (!in_array($kategori_mitra, $allowedKategori)) {
            $errors[] = "Kategori mitra tidak valid";
        }

        // Validasi tanggal mulai
        if (empty($tanggal_mulai)) {
            $errors[] = "Tanggal mulai kerjasama wajib diisi";
        }

        // Validasi tanggal akhir (opsional, tapi jika diisi harus lebih besar dari tanggal mulai)
        if (!empty($tanggal_akhir) && !empty($tanggal_mulai)) {
            if ($tanggal_akhir < $tanggal_mulai) {
                $errors[] = "Tanggal akhir tidak boleh lebih awal dari tanggal mulai";
            }
        }

        return $errors;
    }

    /**
     * Handle request untuk update mitra
     * Method: POST
     * Endpoint: /applied-informatics/mitra/update
     *
     * @return void - Mengembalikan JSON response
     */
    public function updateMitra()
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 1A. Validasi csrf token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        // 2. Ambil data dari POST
        $id = $_POST['id'] ?? null;
        $nama = $_POST['nama'] ?? '';
        $status = $_POST['status'] ?? 'aktif';
        $kategori_mitra = $_POST['kategori_mitra'] ?? 'industri';
        $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
        $tanggal_akhir = $_POST['tanggal_akhir'] ?? null;
        $deskripsi = $_POST['deskripsi'] ?? '';

        // 3. Validasi ID (harus numeric)
        if (!$id || !is_numeric($id)) {
            ResponseHelper::error('ID mitra tidak valid');
            return;
        }

        // 3A. Validasi status
        $allowedStatus = ['aktif', 'non-aktif'];
        if (!in_array($status, $allowedStatus)) {
            ResponseHelper::error('Status mitra tidak valid');
            return;
        }

        // 4. Validasi input
        $validationErrors = $this->validateMitraInput($nama, $kategori_mitra, $tanggal_mulai, $tanggal_akhir);

        if (!empty($validationErrors)) {
            ResponseHelper::error($validationErrors[0]);
            return;
        }

        // 5. Get data mitra lama untuk logo
        $oldMitraResult = $this->mitraModel->getById($id);
        if (!$oldMitraResult['success']) {
            ResponseHelper::error('Mitra tidak ditemukan');
            return;
        }

        $oldMitra = $oldMitraResult['data'];
        $logoFileName = $oldMitra['logo_mitra'];

        // 6. Handle upload logo baru (jika ada)
        if (isset($_FILES['logo_mitra']) && $_FILES['logo_mitra']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = FileUploadHelper::upload(
                $_FILES['logo_mitra'],
                'image',
                'mitra',
                2 * 1024 * 1024
            );

            if (!$uploadResult['success']) {
                ResponseHelper::error($uploadResult['message']);
                return;
            }

            // Hapus logo lama jika ada
            if ($oldMitra['logo_mitra']) {
                FileUploadHelper::delete($oldMitra['logo_mitra'], 'mitra');
            }

            $logoFileName = $uploadResult['filename'];
        }

        // 7. Siapkan data untuk update
        $mitraData = [
            'nama' => $nama,
            'status' => $status,
            'kategori_mitra' => $kategori_mitra,
            'logo_mitra' => $logoFileName,
            'tanggal_mulai' => $tanggal_mulai,
            'tanggal_akhir' => $tanggal_akhir,
            'deskripsi' => $deskripsi
        ];

        // 8. Update ke database
        $result = $this->mitraModel->update($id, $mitraData);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        // 9. Return success response
        ResponseHelper::success('Data mitra berhasil diupdate', ['id' => $id]);
    }

    /**
     * Handle request untuk delete mitra
     * Method: POST
     * Endpoint: /applied-informatics/mitra/delete/{id}
     *
     * @param int $id
     * @return void - Mengembalikan JSON response
     */
    public function deleteMitra($id)
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 2. Validasi ID
        if (!$id || !is_numeric($id)) {
            ResponseHelper::error('ID mitra tidak valid');
            return;
        }

        // 3. Delete dari database
        $result = $this->mitraModel->delete($id);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        // 4. Hapus file logo jika ada
        if (isset($result['data']['logo_mitra']) && $result['data']['logo_mitra']) {
            FileUploadHelper::delete($result['data']['logo_mitra'], 'mitra');
        }

        // 5. Return success response
        ResponseHelper::success('Data mitra berhasil dihapus');
    }
}
