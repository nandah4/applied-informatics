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
 * - createJabatan(), deleteJabatan(), getAllJabatan(): Manage jabatan
 * - createKeahlian(), deleteKeahlian(), getAllKeahlian(): Manage keahlian
 */

class DosenController
{
    private $jabatanModel;
    private $keahlianModel;
    private $dosenModel;

    /**
     * Constructor: Inisialisasi models
     */
    public function __construct()
    {
        $this->jabatanModel = new JabatanModel();
        $this->keahlianModel = new KeahlianModel();
        $this->dosenModel = new DosenModel();
    }

    // ========================================
    // DOSEN CRUD OPERATIONS
    // ========================================

    /**
     * Handle request untuk create dosen baru
     * Method: POST
     * Endpoint: /applied-informatics/dosen/create
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

        // 2. Ambil data dari POST
        $full_name = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $nidn = $_POST['nidn'] ?? '';
        $jabatan_id = $_POST['jabatan_id'] ?? '';
        $keahlian_ids = $_POST['keahlian_ids'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';

        // 3. Validasi input menggunakan ValidationHelper
        $validationErrors = $this->validateDosenInput($full_name, $email, $nidn, $jabatan_id, $keahlian_ids, $deskripsi);

        if (!empty($validationErrors)) {
            ResponseHelper::error($validationErrors[0]); // Return error pertama
            return;
        }

        // 3b. Validasi khusus: Cek apakah jabatan "Ketua Lab" sudah ada
        $jabatanName = $this->dosenModel->getJabatanNameById((int)$jabatan_id);
        if ($jabatanName && stripos($jabatanName, 'kepala') !== false && stripos($jabatanName, 'laboratorium') !== false) {
            // Jabatan ini adalah "Ketua Lab" atau variasinya
            $checkExists = $this->dosenModel->isJabatanExists((int)$jabatan_id);

            if ($checkExists['exists']) {
                ResponseHelper::error('Ketua Lab sudah ada (' . $checkExists['dosen_name'] . '). Hanya boleh ada 1 Ketua Lab.');
                return;
            }
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
            'keahlian_ids' => $keahlianArray, // Array untuk stored procedure
            'foto_profil' => $fotoFileName,
            'deskripsi' => $deskripsi
        ];

        // 6. Insert dosen ke database menggunakan stored procedure
        // Stored procedure akan handle insert dosen DAN keahlian sekaligus
        $result = $this->dosenModel->insertDosen($dosenData);

        if (!$result['success']) {
            // Jika gagal dan ada foto yang sudah diupload, hapus fotonya
            if ($fotoFileName) {
                FileUploadHelper::delete($fotoFileName, 'dosen');
            }

            ResponseHelper::error($result['message']);
            return;
        }

        // 7. Return success response
        $dosen_id = $result['data']['id'];
        ResponseHelper::success('Data dosen berhasil ditambahkan', [
            'id' => $dosen_id
        ]);
    }

    /**
     * Get semua data dosen dengan pagination
     * Method: GET
     *
     * @return array - Data dosen dengan pagination info
     */
    public function getAllDosen()
    {
        // Ambil parameter dari GET request
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

        // Validasi input
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage)); // Max 100 per page

        // Generate pagination data
        // Kita perlu get total dulu untuk pagination
        $allDataResult = $this->dosenModel->getAllDosen();
        $totalRecords = count($allDataResult['data']);

        $pagination = PaginationHelper::paginate($totalRecords, $page, $perPage);

        // Get data dengan pagination
        $result = $this->dosenModel->getAllDosenPaginated($pagination['per_page'], $pagination['offset']);

        return [
            'data' => $result['data'],
            'pagination' => $pagination
        ];
    }


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
    // JABATAN OPERATIONS
    // ========================================

    /**
     * Handle create jabatan dari AJAX request
     * Method: POST
     *
     * @return void - Mengembalikan JSON response
     */
    public function createJabatan()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        $jabatan = $_POST['jabatan'] ?? '';

        // Validasi menggunakan ValidationHelper
        $validation = ValidationHelper::validateName($jabatan, 2, 255);
        if (!$validation['valid']) {
            ResponseHelper::error($validation['message']);
            return;
        }

        // Panggil model untuk create jabatan
        $result = $this->jabatanModel->createJabatan($jabatan);

        // Kirim response
        if ($result['success']) {
            ResponseHelper::success($result['message'], $result['data']);
        } else {
            ResponseHelper::error($result['message']);
        }
    }

    /**
     * Get semua data jabatan
     *
     * @return array - Data jabatan
     */
    public function getAllJabatan()
    {
        $result = $this->jabatanModel->getAllJabatan();
        return $result;
    }

    /**
     * Handle delete jabatan from AJAX request
     * Method: POST
     *
     * @return void - Mengembalikan JSON response
     */
    public function deleteJabatan()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        $id = $_POST['id'] ?? '';

        // Validasi ID
        $validation = ValidationHelper::validateId($id, 'ID jabatan');
        if (!$validation['valid']) {
            ResponseHelper::error($validation['message']);
            return;
        }

        // Call model to delete jabatan
        $result = $this->jabatanModel->deleteJabatan((int)$id);

        // Send response
        if ($result['success']) {
            ResponseHelper::success($result['message']);
        } else {
            ResponseHelper::error($result['message']);
        }
    }

    // ========================================
    // KEAHLIAN OPERATIONS
    // ========================================

    /**
     * Get semua data keahlian
     *
     * @return array - Data keahlian
     */
    public function getKeahlianByDosenID($id)
    {
        $result = $this->keahlianModel->getKeahlianByDosenID($id);
        return $result;
    }

    /**
     * Get semua data keahlian by Dosen ID
     *
     * @return array - Data keahlian
     */
    public function getAllKeahlian()
    {
        $result = $this->keahlianModel->getAllKeahlian();
        return $result;
    }

    /**
     * Handle create keahlian dari AJAX request
     * Method: POST
     *
     * @return void - Mengembalikan JSON response
     */
    public function createKeahlian()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        $keahlian = $_POST['keahlian'] ?? '';

        // Validasi menggunakan ValidationHelper
        $validation = ValidationHelper::validateName($keahlian, 1, 255);
        if (!$validation['valid']) {
            ResponseHelper::error($validation['message']);
            return;
        }

        $result = $this->keahlianModel->createKeahlian($keahlian);

        if ($result['success']) {
            ResponseHelper::success($result['message'], $result['data']);
        } else {
            ResponseHelper::error($result['message']);
        }
    }

    /**
     * Handle delete keahlian from AJAX request
     * Method: POST
     *
     * @return void - Mengembalikan JSON response
     */
    public function deleteKeahlian()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        $id = $_POST['id'] ?? '';

        // Validasi ID
        $validation = ValidationHelper::validateId($id, 'ID keahlian');
        if (!$validation['valid']) {
            ResponseHelper::error($validation['message']);
            return;
        }

        // Call model to delete keahlian
        $result = $this->keahlianModel->deleteKeahlian((int)$id);

        // Send response
        if ($result['success']) {
            ResponseHelper::success($result['message']);
        } else {
            ResponseHelper::error($result['message']);
        }
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
     * Endpoint: /applied-informatics/dosen/update
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

        // 2. Ambil data dari POST
        $id = $_POST['id'] ?? '';
        $full_name = $_POST['full_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $nidn = $_POST['nidn'] ?? '';
        $jabatan_id = $_POST['jabatan_id'] ?? '';
        $keahlian_ids = $_POST['keahlian_ids'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';

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

        // 3b. Validasi khusus: Cek apakah jabatan "Kepala Lab" sudah ada (exclude dosen ini)
        $jabatanName = $this->dosenModel->getJabatanNameById((int)$jabatan_id);

        if ($jabatanName && stripos($jabatanName, 'kepala') !== false && stripos($jabatanName, 'laboratorium') !== false) {
            // Cek apakah ada dosen lain dengan jabatan ini (exclude current dosen)
            $checkExists = $this->dosenModel->isJabatanExists((int)$jabatan_id, (int)$id);

            if ($checkExists['exists']) {
                ResponseHelper::error('Kepala Lab sudah ada (' . $checkExists['dosen_name'] . '). Hanya boleh ada 1 Kepala Lab. ' . $id);
                return;
            }
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
            'foto_profil' => $fotoFileName,
            'deskripsi' => $deskripsi
        ];

        // 6. Update data dosen
        $result = $this->dosenModel->updateDosen((int)$id, $dosenData);

        if (!$result['success']) {
            // Jika gagal dan ada foto baru yang sudah diupload, hapus fotonya
            if ($fotoFileName && $fotoFileName !== $oldFotoFileName) {
                FileUploadHelper::delete($fotoFileName, 'dosen');
            }

            ResponseHelper::error($result['message']);
            return;
        }

        // 7. Update keahlian dosen
        if (!empty($keahlian_ids)) {
            $keahlianArray = array_map('intval', explode(',', $keahlian_ids));
            $keahlianResult = $this->dosenModel->updateDosenKeahlian((int)$id, $keahlianArray);

            if (!$keahlianResult['success']) {
                error_log('Gagal update keahlian untuk dosen ID ' . $id . ': ' . $keahlianResult['message']);
            }
        }

        // 8. Return success response
        ResponseHelper::success('Data dosen berhasil diupdate');
    }

    /**
     * Handle request untuk delete dosen
     * Method: POST
     * Endpoint: /applied-informatics/dosen/delete/{id}
     *
     * @return void - Mengembalikan JSON response
     */
    public function deleteDosenByID($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // Panggil method deleteDosen (bukan deleteDosenByID)
        $result = $this->dosenModel->deleteDosen($id);

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
}
