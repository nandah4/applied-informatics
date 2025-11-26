<?php

/**
 * File: Controllers/RecruitmentController.php
 * Deskripsi: Controller untuk menangani request terkait data recruitment
 *
 * Fungsi utama:
 * - createRecruitment(): Handle request create recruitment baru
 * - updateRecruitment(): Handle request update recruitment
 * - deleteRecruitment(): Handle request delete recruitment
 * - getAllRecruitment(): Get all recruitment dengan pagination
 * - getRecruitmentById(): Get detail recruitment by ID
 *
 * AUTO-STATUS FEATURE:
 * - Status akan otomatis menjadi "tutup" jika tanggal_tutup < hari ini
 * - Status akan otomatis menjadi "buka" jika tanggal_tutup >= hari ini (termasuk saat diperpanjang)
 * - User tidak perlu manual mengubah status saat memperpanjang periode recruitment
 */

class RecruitmentController
{
    private $recruitmentModel;

    public function __construct()
    {
        $this->recruitmentModel = new RecruitmentModel();
    }

    // ========================================
    // RECRUITMENT CRUD OPERATIONS
    // ========================================

    /**
     * Get all recruitment for index page
     * Method: GET
     *
     * @return array
     */
    public function getAllRecruitment()
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
        $result = $this->recruitmentModel->getAllRecruitmentWithPagination($perPage, $offset);

        // Generate pagination dari total yang dikembalikan model
        $pagination = PaginationHelper::paginate($result['total'], $page, $perPage);

        return [
            'data' => $result['data'],
            'pagination' => $pagination
        ];
    }

    /**
     * Get recruitment by ID
     * Method: GET
     *
     * @param int $id
     * @return array
     */
    public function getRecruitmentById($id)
    {
        return $this->recruitmentModel->getById($id);
    }

    /**
     * Handle request untuk create recruitment baru
     * Method: POST
     * Endpoint: /applied-informatics/admin/recruitment/create
     *
     * @return void - Mengembalikan JSON response
     */
    public function createRecruitment()
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
        $judul = $_POST['judul'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';
        $status = $_POST['status'] ?? 'tutup';
        $tanggal_buka = $_POST['tanggal_buka'] ?? '';
        $tanggal_tutup = $_POST['tanggal_tutup'] ?? '';
        $lokasi = $_POST['lokasi'] ?? '';

        // 2A. Validasi status
        $allowedStatus = ['buka', 'tutup'];
        if (!in_array($status, $allowedStatus)) {
            ResponseHelper::error('Status recruitment tidak valid');
            return;
        }

        // 3. Validasi input
        $validationErrors = $this->validateRecruitmentInput($judul, $deskripsi, $tanggal_buka, $tanggal_tutup, $lokasi);

        if (!empty($validationErrors)) {
            ResponseHelper::error($validationErrors[0]); // Return error pertama
            return;
        }

        // 4. Siapkan data untuk insert
        $recruitmentData = [
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'status' => $status,
            'tanggal_buka' => $tanggal_buka,
            'tanggal_tutup' => $tanggal_tutup,
            'lokasi' => $lokasi
        ];

        // 5. Insert ke database
        $result = $this->recruitmentModel->insert($recruitmentData);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        ResponseHelper::success('Data recruitment berhasil ditambahkan');
    }

    /**
     * Validasi input untuk create/update recruitment
     *
     * @param string $judul
     * @param string $deskripsi
     * @param string $tanggal_buka
     * @param string $tanggal_tutup
     * @param string $lokasi
     * @return array - Array of error messages (kosong jika valid)
     */
    private function validateRecruitmentInput($judul, $deskripsi, $tanggal_buka, $tanggal_tutup, $lokasi)
    {
        $errors = [];

        // Validasi judul
        if (empty($judul)) {
            $errors[] = "Judul recruitment wajib diisi";
        } else {
            $judulValidation = ValidationHelper::validateName($judul, 1, 255);
            if (!$judulValidation['valid']) {
                $errors[] = $judulValidation['message'];
            }
        }

        // Validasi deskripsi
        if (empty($deskripsi)) {
            $errors[] = "Deskripsi wajib diisi";
        } else {
            $deskripsiValidation = ValidationHelper::validateText($deskripsi, 5000, true);
            if (!$deskripsiValidation['valid']) {
                $errors[] = $deskripsiValidation['message'];
            }
        }

        // Validasi tanggal buka
        if (empty($tanggal_buka)) {
            $errors[] = "Tanggal buka wajib diisi";
        }

        // Validasi tanggal tutup
        if (empty($tanggal_tutup)) {
            $errors[] = "Tanggal tutup wajib diisi";
        }

        // Validasi tanggal tutup tidak lebih awal dari tanggal buka
        if (!empty($tanggal_buka) && !empty($tanggal_tutup)) {
            if ($tanggal_tutup < $tanggal_buka) {
                $errors[] = "Tanggal tutup tidak boleh lebih awal dari tanggal buka";
            }
        }

        // Validasi lokasi
        if (empty($lokasi)) {
            $errors[] = "Lokasi wajib diisi";
        } else {
            $lokasiValidation = ValidationHelper::validateName($lokasi, 1, 255);
            if (!$lokasiValidation['valid']) {
                $errors[] = $lokasiValidation['message'];
            }
        }

        return $errors;
    }

    /**
     * Handle request untuk update recruitment
     * Method: POST
     * Endpoint: /applied-informatics/admin/recruitment/update
     *
     * NOTE: Status akan AUTO-UPDATE berdasarkan tanggal:
     * - Jika tanggal_tutup < hari ini -> status = "tutup"
     * - Jika tanggal_tutup >= hari ini -> status = "buka" (auto-reopen saat diperpanjang)
     *
     * @return void - Mengembalikan JSON response
     */
    public function updateRecruitment()
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
        $judul = $_POST['judul'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';
        $status = $_POST['status'] ?? 'tutup'; // Status dari form (akan di-override oleh stored procedure)
        $tanggal_buka = $_POST['tanggal_buka'] ?? '';
        $tanggal_tutup = $_POST['tanggal_tutup'] ?? '';
        $lokasi = $_POST['lokasi'] ?? '';

        // 3. Validasi ID (harus numeric)
        if (!$id || !is_numeric($id)) {
            ResponseHelper::error('ID recruitment tidak valid');
            return;
        }

        // 3A. Validasi status
        $allowedStatus = ['buka', 'tutup'];
        if (!in_array($status, $allowedStatus)) {
            ResponseHelper::error('Status recruitment tidak valid');
            return;
        }

        // 4. Validasi input
        $validationErrors = $this->validateRecruitmentInput($judul, $deskripsi, $tanggal_buka, $tanggal_tutup, $lokasi);

        if (!empty($validationErrors)) {
            ResponseHelper::error($validationErrors[0]);
            return;
        }

        // 5. Get data recruitment lama untuk verifikasi
        $oldRecruitmentResult = $this->recruitmentModel->getById($id);
        if (!$oldRecruitmentResult['success']) {
            ResponseHelper::error('Recruitment tidak ditemukan');
            return;
        }

        // 6. Siapkan data untuk update
        // NOTE: Status akan di-override oleh stored procedure berdasarkan tanggal
        $recruitmentData = [
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'status' => $status, // Ini akan di-override oleh SP
            'tanggal_buka' => $tanggal_buka,
            'tanggal_tutup' => $tanggal_tutup,
            'lokasi' => $lokasi
        ];

        // 7. Update ke database
        $result = $this->recruitmentModel->update($id, $recruitmentData);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        // 8. Return success response
        ResponseHelper::success('Data recruitment berhasil diupdate', ['id' => $id]);
    }

    /**
     * Handle request untuk delete recruitment
     * Method: POST
     * Endpoint: /applied-informatics/admin/recruitment/delete/{id}
     *
     * @param int $id
     * @return void - Mengembalikan JSON response
     */
    public function deleteRecruitment($id)
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

        // 2. Validasi ID
        if (!$id || !is_numeric($id)) {
            ResponseHelper::error('ID recruitment tidak valid');
            return;
        }

        // 3. Delete dari database menggunakan stored procedure
        $result = $this->recruitmentModel->delete($id);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        // 4. Return success response
        ResponseHelper::success('Data recruitment berhasil dihapus');
    }
}