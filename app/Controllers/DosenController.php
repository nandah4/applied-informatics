<?php

/**
 * File: Controllers/DosenController.php
 * Deskripsi: Controller untuk menangani request terkait data dosen
 *
 * Fungsi utama:
 * - createDosen(): Handle request create dosen baru
 * - getAllDosen(): Get semua data dosen
 * - updateDosen(): Handle request update dosen
 * - deleteDosen(): Handle request delete dosen
 * - createProfilPublikasi(), updateProfilPublikasi(), deleteProfilPublikasi()
 */

class DosenController
{
    private $dosenModel;
    private $profilPublikasiModel;

    /**
     * Constructor: Inisialisasi models
     */
    public function __construct()
    {
        $this->dosenModel = new DosenModel();
        $this->profilPublikasiModel = new ProfilPublikasiModel();
    }

    // ========================================
    // DOSEN CRUD OPERATIONS
    // ========================================

    /**
     * Handle request untuk create dosen baru
     * Method: POST
     * Endpoint: /applied-informatics/admin/dosen/create
     *
     * @return void - Mengembalikan JSON response
     */
    public function createDosen()
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }
        // Validate CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        // 2. Ambil data dari POST
        $full_name = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $nidn = $_POST['nidn'] ?? '';
        $jabatan_id = $_POST['jabatan_id'] ?? '';
        $keahlian_ids = $_POST['keahlian_ids'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';
        $status_aktif = $_POST['status_aktif'] ?? '1';

        // 3. Validasi input menggunakan ValidationHelper
        $validationErrors = $this->validateDosenInput($full_name, $email, $nidn, $jabatan_id, $keahlian_ids, $deskripsi);

        if (!empty($validationErrors)) {
            ResponseHelper::error($validationErrors[0]); // Return error pertama
            return;
        }

        // 4. Handle upload foto profil
        $fotoFileName = null;
        if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = FileUploadHelper::upload(
                $_FILES['foto_profil'],
                'image',           // Tipe upload: image
                'dosen',           // Folder: uploads/dosen/
                2 * 1024 * 1024    // Max size: 2MB
            );

            if (!$uploadResult['success']) {
                ResponseHelper::error($uploadResult['message']);
                return;
            }

            $fotoFileName = $uploadResult['filename'];
        }

        // 5. Siapkan data untuk insert (termasuk keahlian_ids)
        // Convert "1,3,5" menjadi [1, 3, 5] untuk stored procedure
        $keahlianArray = [];
        if (!empty($keahlian_ids)) {
            $keahlianArray = array_map('intval', explode(',', $keahlian_ids));
        }

        $dosenData = [
            'full_name' => $full_name,
            'email' => $email,
            'nidn' => $nidn,
            'jabatan_id' => (int)$jabatan_id,
            'keahlian_ids' => $keahlianArray,
            'foto_profil' => $fotoFileName,
            'deskripsi' => $deskripsi,
            'status_aktif' => ($status_aktif === '1' || $status_aktif === 'true' || $status_aktif === true) ? 1 : 0
        ];

        // 6. Insert dosen ke database menggunakan stored procedure
        $result = $this->dosenModel->insert($dosenData);

        if (!$result['success']) {
            // Jika gagal dan ada foto yang sudah diupload, hapus fotonya
            if ($fotoFileName) {
                FileUploadHelper::delete($fotoFileName, 'dosen');
            }

            ResponseHelper::error($result['message']);
            return;
        }

        // 7. Return success response
        ResponseHelper::success('Data dosen berhasil ditambahkan', []);
    }

    /**
     * Get semua data dosen dengan pagination
     * Method: GET
     *
     * @return array - Data dosen dengan pagination info
     */

    public function getAllDosenActive()
    {
        return $this->dosenModel->getAllDosenActive();
    }

    public function getAllDosen()
    {
        // Ambil parameter dari GET request
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Validasi input
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));

        // Hitung offset untuk query
        $offset = ($page - 1) * $perPage;

        $params = [
            'search' => $search,
            'limit' => $perPage,
            'offset' => $offset
        ];

        // Get data dengan pagination
        $result = $this->dosenModel->getAllDosenPaginated($params);

        // Generate pagination dari total yang dikembalikan model
        $pagination = PaginationHelper::paginate($result['total'], $page, $perPage);

        return [
            'data' => $result['data'],
            'pagination' => $pagination
        ];
    }

    // Prev Code
    // public function getAllDosen()
    // {
    //     // Ambil parameter dari GET request
    //     $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    //     $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

    //     // Validasi input
    //     $page = max(1, $page);
    //     $perPage = max(1, min(100, $perPage));

    //     // Hitung offset untuk query
    //     $offset = ($page - 1) * $perPage;

    //     // Get data dengan pagination
    //     $result = $this->dosenModel->getAllDosenPaginated($perPage, $offset);

    //     // Generate pagination dari total yang dikembalikan model
    //     $pagination = PaginationHelper::paginate($result['total'], $page, $perPage);

    //     return [
    //         'data' => $result['data'],
    //         'pagination' => $pagination
    //     ];
    // }


    /**
     * Validasi input untuk create/update dosen
     *
     * @param string $full_name
     * @param string $email
     * @param string $nidn
     * @param mixed $jabatan_id
     * @param mixed $keahlian_ids
     * @param string $deskripsi
     * @return array - Array of error messages (kosong jika valid)
     */
    private function validateDosenInput($full_name, $email, $nidn, $jabatan_id, $keahlian_ids, $deskripsi)
    {
        $errors = [];

        // Validasi nama lengkap
        $nameValidation = ValidationHelper::validateName($full_name, 3, 255);
        if (!$nameValidation['valid']) {
            $errors[] = $nameValidation['message'];
        }

        // Validasi email
        $emailValidation = ValidationHelper::validateEmail($email);
        if (!$emailValidation['valid']) {
            $errors[] = $emailValidation['message'];
        }

        // Validasi NIDN
        $nidnValidation = ValidationHelper::validateNIDN($nidn, true);
        if (!$nidnValidation['valid']) {
            $errors[] = $nidnValidation['message'];
        }

        // Validasi jabatan ID
        $jabatanValidation = ValidationHelper::validateId($jabatan_id, 'Jabatan');
        if (!$jabatanValidation['valid']) {
            $errors[] = $jabatanValidation['message'];
        }

        // Validasi keahlian IDs
        $keahlianValidation = ValidationHelper::validateIds($keahlian_ids, 'Keahlian', true);
        if (!$keahlianValidation['valid']) {
            $errors[] = $keahlianValidation['message'];
        }

        // Validasi deskripsi (optional)
        $deskripsiValidation = ValidationHelper::validateText($deskripsi, 1000, false);
        if (!$deskripsiValidation['valid']) {
            $errors[] = $deskripsiValidation['message'];
        }

        return $errors;
    }

    // ========================================
    // EDIT DOSEN
    // ========================================

    /**
     * Get detail dosen by ID
     * Method: GET
     *
     * @param int $id - ID dosen
     * @return array - Data dosen
     */
    public function getDosenById($id)
    {
        $result = $this->dosenModel->getDosenById($id);
        return $result;
    }

    /**
     * Handle request untuk update dosen
     * Method: POST
     * Endpoint: /admin/dosen/update
     *
     * @return void - Mengembalikan JSON response
     */
    public function updateDosen()
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 2. Validasi CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        // 3. Ambil data dari POST
        $id = $_POST['id'] ?? '';
        $full_name = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $nidn = $_POST['nidn'] ?? '';
        $jabatan_id = $_POST['jabatan_id'] ?? '';
        $keahlian_ids = $_POST['keahlian_ids'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';
        $status_aktif = $_POST['status_aktif'] ?? '1';

        // 2a. Validasi ID
        $idValidation = ValidationHelper::validateId($id, 'ID Dosen');
        if (!$idValidation['valid']) {
            ResponseHelper::error($idValidation['message']);
            return;
        }

        // 3. Validasi input
        $validationErrors = $this->validateDosenInput($full_name, $email, $nidn, $jabatan_id, $keahlian_ids, $deskripsi);
        if (!empty($validationErrors)) {
            ResponseHelper::error($validationErrors[0]);
            return;
        }

        // 4. Handle upload foto profil (jika ada file baru)
        $oldDosenData = $this->dosenModel->getDosenById((int)$id);
        $oldFotoFileName = $oldDosenData['data']['foto_profil'] ?? null;
        $fotoFileName = $oldFotoFileName; // Default: pakai foto lama

        if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = FileUploadHelper::upload(
                $_FILES['foto_profil'],
                'image',
                'dosen',
                2 * 1024 * 1024
            );

            if (!$uploadResult['success']) {
                ResponseHelper::error($uploadResult['message']);
                return;
            }

            $fotoFileName = $uploadResult['filename'];

            // Hapus foto lama jika ada
            if ($oldFotoFileName) {
                FileUploadHelper::delete($oldFotoFileName, 'dosen');
            }
        }

        // 5. Siapkan data untuk update
        $dosenData = [
            'full_name' => $full_name,
            'email' => $email,
            'nidn' => $nidn,
            'jabatan_id' => (int)$jabatan_id,
            'keahlian_ids' => $keahlian_ids,
            'foto_profil' => $fotoFileName,
            'deskripsi' => $deskripsi,
            'status_aktif' => ($status_aktif === '1' || $status_aktif === 'true' || $status_aktif === true) ? 1 : 0
        ];

        // 6. Update data dosen
        $result = $this->dosenModel->update((int)$id, $dosenData);

        if (!$result['success']) {
            // Jika gagal dan ada foto baru yang sudah diupload, hapus fotonya
            if ($fotoFileName && $fotoFileName !== $oldFotoFileName) {
                FileUploadHelper::delete($fotoFileName, 'dosen');
            }

            ResponseHelper::error($result['message']);
            return;
        }

        // 8. Return success response
        ResponseHelper::success('Data dosen berhasil diupdate');
    }

    /**
     * Handle request untuk delete dosen
     * Method: POST
     * Endpoint: /admin/dosen/delete/{id}
     *
     * @return void - Mengembalikan JSON response
     */
    public function deleteDosenByID($id)
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 2. Validasi CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        // 3. Validasi ID
        $idValidation = ValidationHelper::validateId($id, 'ID Dosen');
        if (!$idValidation['valid']) {
            ResponseHelper::error($idValidation['message']);
            return;
        }

        // 4. Proses delete dosen
        $result = $this->dosenModel->delete((int)$id);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        // Hapus file foto jika ada
        if (!empty($result['data']['foto_profil'])) {
            FileUploadHelper::delete($result['data']['foto_profil'], 'dosen');
        }

        ResponseHelper::success($result['message']);
    }

    // ========================================
    // PROFIL PUBLIKASI DOSEN
    // ========================================

    /**
     * Handle request untuk create profil publikasi
     * Method: POST
     * Endpoint: /admin/dosen/{id}/profil-publikasi/create
     *
     * @param int $dosen_id - ID dosen
     * @return void - Mengembalikan JSON response
     */
    public function createProfilPublikasi($dosen_id)
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 2. Validasi CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        // 3. Validasi dosen_id
        $idValidation = ValidationHelper::validateId($dosen_id, 'ID Dosen');
        if (!$idValidation['valid']) {
            ResponseHelper::error($idValidation['message']);
            return;
        }

        // 4. Ambil data dari POST
        $tipe = $_POST['tipe'] ?? '';
        $url_profil = $_POST['url_profil'] ?? '';

        // 5. Validasi input
        if (empty($tipe)) {
            ResponseHelper::error('Tipe profil harus dipilih');
            return;
        }

        $validTipes = ['SINTA', 'SCOPUS', 'GOOGLE_SCHOLAR', 'ORCID', 'RESEARCHGATE'];
        if (!in_array($tipe, $validTipes)) {
            ResponseHelper::error('Tipe profil tidak valid');
            return;
        }

        if (empty($url_profil)) {
            ResponseHelper::error('URL profil harus diisi');
            return;
        }

        if (!filter_var($url_profil, FILTER_VALIDATE_URL)) {
            ResponseHelper::error('Format URL tidak valid');
            return;
        }

        // 6. Insert ke database
        $result = $this->profilPublikasiModel->insert([
            'dosen_id' => (int)$dosen_id,
            'tipe' => $tipe,
            'url_profil' => $url_profil
        ]);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        ResponseHelper::success($result['message']);
    }

    /**
     * Handle request untuk update profil publikasi
     * Method: POST
     * Endpoint: /admin/dosen/profil-publikasi/update
     *
     * @return void - Mengembalikan JSON response
     */
    public function updateProfilPublikasi()
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 2. Validasi CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        // 3. Ambil data dari POST
        $id = $_POST['id'] ?? '';
        $url_profil = $_POST['url_profil'] ?? '';

        // 4. Validasi input
        $idValidation = ValidationHelper::validateId($id, 'ID Profil');
        if (!$idValidation['valid']) {
            ResponseHelper::error($idValidation['message']);
            return;
        }

        if (empty($url_profil)) {
            ResponseHelper::error('URL profil harus diisi');
            return;
        }

        if (!filter_var($url_profil, FILTER_VALIDATE_URL)) {
            ResponseHelper::error('Format URL tidak valid');
            return;
        }

        // 5. Update ke database
        $result = $this->profilPublikasiModel->update((int)$id, $url_profil);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        ResponseHelper::success($result['message']);
    }

    /**
     * Handle request untuk delete profil publikasi
     * Method: POST
     * Endpoint: /admin/dosen/profil-publikasi/delete/{id}
     *
     * @param int $id - ID profil publikasi
     * @return void - Mengembalikan JSON response
     */
    public function deleteProfilPublikasi($id)
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // 2. Validasi CSRF token
        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        // 3. Validasi ID
        $idValidation = ValidationHelper::validateId($id, 'ID Profil');
        if (!$idValidation['valid']) {
            ResponseHelper::error($idValidation['message']);
            return;
        }

        // 4. Delete dari database
        $result = $this->profilPublikasiModel->delete((int)$id);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        ResponseHelper::success($result['message']);
    }

    /**
     * Get semua profil publikasi milik dosen
     * Method: GET
     * Endpoint: /admin/dosen/{id}/profil-publikasi
     *
     * @param int $dosen_id - ID dosen
     * @return array - Data profil publikasi
     */
    public function getProfilPublikasi($dosen_id)
    {
        return $this->profilPublikasiModel->getByDosenId((int)$dosen_id);
    }
}
