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

    /**
     * Handle pendaftaran mahasiswa (form submission)
     * Method: POST
     * Endpoint: /rekrutment/submit
     *
     * @return void - Redirect ke halaman sukses atau kembali ke form dengan error
     */
    public function submitPendaftaran()
    {
        // 1. Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . base_url('rekrutment'));
            exit;
        }

        // 2. Ambil data dari POST
        $rekrutmen_id = $_POST['rekrutmen_id'] ?? null;
        $nim = trim($_POST['nim'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $no_hp = trim($_POST['no_hp'] ?? '');
        $semester = $_POST['semester'] ?? null;
        $ipk = $_POST['ipk'] ?? null;
        $link_portfolio = trim($_POST['link_portfolio'] ?? '');
        $link_github = trim($_POST['link_github'] ?? '');

        // 3. Validasi input wajib
        $errors = [];

        if (empty($rekrutmen_id) || !is_numeric($rekrutmen_id)) {
            $errors[] = "ID Rekrutmen tidak valid";
        }

        if (empty($nim)) {
            $errors[] = "NIM wajib diisi";
        }

        if (empty($nama)) {
            $errors[] = "Nama lengkap wajib diisi";
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email tidak valid";
        }

        if (empty($semester) || !is_numeric($semester)) {
            $errors[] = "Semester wajib dipilih";
        }

        // Validasi file CV (wajib)
        if (!isset($_FILES['file_cv']) || $_FILES['file_cv']['error'] === UPLOAD_ERR_NO_FILE) {
            $errors[] = "Curriculum Vitae (CV) wajib diupload";
        }

        // Jika ada error validasi, redirect kembali dengan pesan error
        if (!empty($errors)) {
            $_SESSION['error_message'] = implode('<br>', $errors);
            header("Location: " . base_url('rekrutment/form/' . $rekrutmen_id));
            exit;
        }

        // 4. Upload file CV
        $uploadCV = FileUploadHelper::upload($_FILES['file_cv'], 'pdf', 'cv', 2 * 1024 * 1024);

        if (!$uploadCV['success']) {
            $_SESSION['error_message'] = "Gagal upload CV: " . $uploadCV['message'];
            header("Location: " . base_url('rekrutment/form/' . $rekrutmen_id));
            exit;
        }

        $file_cv = $uploadCV['filename'];

        // 5. Upload file KHS (optional)
        $file_khs = null;
        if (isset($_FILES['file_khs']) && $_FILES['file_khs']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadKHS = FileUploadHelper::upload($_FILES['file_khs'], 'pdf', 'khs', 2 * 1024 * 1024);

            if ($uploadKHS['success']) {
                $file_khs = $uploadKHS['filename'];
            }
            // Jika upload KHS gagal, abaikan karena optional
        }

        // 6. Siapkan data untuk insert
        $pendaftarData = [
            'rekrutmen_id' => $rekrutmen_id,
            'nim' => $nim,
            'nama' => $nama,
            'email' => $email,
            'no_hp' => $no_hp,
            'semester' => $semester,
            'ipk' => $ipk,
            'link_portfolio' => $link_portfolio,
            'link_github' => $link_github,
            'file_cv' => $file_cv,
            'file_khs' => $file_khs
        ];

        // 7. Insert ke database menggunakan stored procedure
        $result = $this->recruitmentModel->insertPendaftar($pendaftarData);

        if (!$result['success']) {
            // Jika gagal, hapus file yang sudah diupload
            FileUploadHelper::delete($file_cv, 'cv');
            if ($file_khs) {
                FileUploadHelper::delete($file_khs, 'khs');
            }

            $_SESSION['error_message'] = $result['message'];
            header("Location: " . base_url('rekrutment/form/' . $rekrutmen_id));
            exit;
        }

        // 8. Redirect ke halaman sukses
        $_SESSION['success_message'] = $result['message'];
        header("Location: " . base_url('rekrutment/sukses'));
        exit;
    }
}