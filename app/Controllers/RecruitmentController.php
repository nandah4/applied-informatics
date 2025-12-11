<?php

/**
 * File: Controllers/RecruitmentController.php
 * Deskripsi: Controller untuk menangani request terkait data recruitment
 *
 * PERUBAHAN:
 * - Removed lokasi field
 * - Added kategori, periode, banner_image fields
 * - Status sekarang bisa diubah manual menjadi 'tutup' meskipun tanggal masih panjang
 * - Auto-open tetap aktif saat tanggal diperpanjang dari expired ke valid
 * - Auto-close tetap aktif untuk recruitment yang sudah expired
 *
 * LOGIKA STATUS:
 * 1. Jika tanggal_tutup < hari ini -> status SELALU 'tutup' (tidak bisa dibuka)
 * 2. Jika tanggal_tutup >= hari ini:
 *    a. Jika diperpanjang dari expired -> auto 'buka'
 *    b. Jika tanggal masih valid -> gunakan pilihan user (bisa tutup manual)
 */

class RecruitmentController
{
    private $recruitmentModel;

    public function __construct()
    {
        $this->recruitmentModel = new RecruitmentModel();
    }

    /**
     * Get all recruitment for index page with search
     * Method: GET
     *
     * @return array
     */
    public function getAllRecruitment()
    {
        // Ambil parameter dari GET request
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

        // Validasi input
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));

        // Hitung offset
        $offset = ($page - 1) * $perPage;

        // Siapkan params untuk model
        $params = [
            'search' => $search,
            'limit' => $perPage,
            'offset' => $offset
        ];

        // Get data dari model
        $result = $this->recruitmentModel->getAllWithSearchAndFilter($params);

        // Generate pagination
        $pagination = PaginationHelper::paginate($result['total'], $page, $perPage);

        return [
            'data' => $result['data'],
            'pagination' => $pagination,
            'total' => $result['total']
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        $judul = $_POST['judul'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';
        $status = $_POST['status'] ?? 'tutup';
        $kategori = $_POST['kategori'] ?? 'asisten lab';
        $periode = $_POST['periode'] ?? '';
        $tanggal_buka = $_POST['tanggal_buka'] ?? '';
        $tanggal_tutup = $_POST['tanggal_tutup'] ?? '';

        $allowedStatus = ['buka', 'tutup'];
        if (!in_array($status, $allowedStatus)) {
            ResponseHelper::error('Status recruitment tidak valid');
            return;
        }

        $allowedKategori = ['asisten lab', 'magang'];
        if (!in_array($kategori, $allowedKategori)) {
            ResponseHelper::error('Kategori recruitment tidak valid');
            return;
        }

        $validationErrors = $this->validateRecruitmentInput($judul, $deskripsi, $kategori, $periode, $tanggal_buka, $tanggal_tutup);

        if (!empty($validationErrors)) {
            ResponseHelper::error($validationErrors[0]);
            return;
        }

        // Handle banner image upload
        $banner_image = null;
        if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = FileUploadHelper::upload($_FILES['banner_image'], 'image', 'recruitment', 2 * 1024 * 1024);
            if (!$uploadResult['success']) {
                ResponseHelper::error('Gagal upload banner: ' . $uploadResult['message']);
                return;
            }
            $banner_image = $uploadResult['filename'];
        }

        $recruitmentData = [
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'status' => $status,
            'kategori' => $kategori,
            'periode' => $periode,
            'tanggal_buka' => $tanggal_buka,
            'tanggal_tutup' => $tanggal_tutup,
            'banner_image' => $banner_image
        ];

        $result = $this->recruitmentModel->insert($recruitmentData);

        if (!$result['success']) {
            // Delete uploaded file if insert failed
            if ($banner_image) {
                FileUploadHelper::delete($banner_image, 'recruitment');
            }
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
     * @param string $kategori
     * @param string $periode
     * @param string $tanggal_buka
     * @param string $tanggal_tutup
     * @return array - Array of error messages (kosong jika valid)
     */
    private function validateRecruitmentInput($judul, $deskripsi, $kategori, $periode, $tanggal_buka, $tanggal_tutup)
    {
        $errors = [];

        if (empty($judul)) {
            $errors[] = "Judul recruitment wajib diisi";
        } else {
            $judulValidation = ValidationHelper::validateName($judul, 1, 255);
            if (!$judulValidation['valid']) {
                $errors[] = $judulValidation['message'];
            }
        }

        if (empty($deskripsi)) {
            $errors[] = "Deskripsi wajib diisi";
        } else {
            $deskripsiValidation = ValidationHelper::validateText($deskripsi, 5000, true);
            if (!$deskripsiValidation['valid']) {
                $errors[] = $deskripsiValidation['message'];
            }
        }

        if (empty($kategori)) {
            $errors[] = "Kategori wajib dipilih";
        }

        if (empty($periode)) {
            $errors[] = "Periode wajib diisi";
        }

        if (empty($tanggal_buka)) {
            $errors[] = "Tanggal buka wajib diisi";
        }

        if (empty($tanggal_tutup)) {
            $errors[] = "Tanggal tutup wajib diisi";
        }

        if (!empty($tanggal_buka) && !empty($tanggal_tutup)) {
            if ($tanggal_tutup < $tanggal_buka) {
                $errors[] = "Tanggal tutup tidak boleh lebih awal dari tanggal buka";
            }
        }

        return $errors;
    }

    /**
     * Handle request untuk update recruitment
     * Method: POST
     * Endpoint: /applied-informatics/admin/recruitment/update
     *
     * LOGIKA STATUS:
     * - User dapat memilih status 'buka' atau 'tutup' dari form
     * - Jika tanggal_tutup < hari ini -> status akan di-force menjadi 'tutup' oleh SP
     * - Jika tanggal_tutup >= hari ini:
     *   - Jika diperpanjang dari expired -> auto 'buka' oleh SP
     *   - Jika tanggal masih valid -> gunakan pilihan user dari form
     *
     * @return void - Mengembalikan JSON response
     */
    public function updateRecruitment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        $id = $_POST['id'] ?? null;
        $judul = $_POST['judul'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';
        $status = $_POST['status'] ?? 'tutup'; // Status dari form
        $kategori = $_POST['kategori'] ?? 'asisten lab';
        $periode = $_POST['periode'] ?? '';
        $tanggal_buka = $_POST['tanggal_buka'] ?? '';
        $tanggal_tutup = $_POST['tanggal_tutup'] ?? '';
        $old_banner_image = $_POST['old_banner_image'] ?? '';

        if (!$id || !is_numeric($id)) {
            ResponseHelper::error('ID recruitment tidak valid');
            return;
        }

        $allowedStatus = ['buka', 'tutup'];
        if (!in_array($status, $allowedStatus)) {
            ResponseHelper::error('Status recruitment tidak valid');
            return;
        }

        $allowedKategori = ['asisten lab', 'magang'];
        if (!in_array($kategori, $allowedKategori)) {
            ResponseHelper::error('Kategori recruitment tidak valid');
            return;
        }

        $validationErrors = $this->validateRecruitmentInput($judul, $deskripsi, $kategori, $periode, $tanggal_buka, $tanggal_tutup);

        if (!empty($validationErrors)) {
            ResponseHelper::error($validationErrors[0]);
            return;
        }

        $oldRecruitmentResult = $this->recruitmentModel->getById($id);
        if (!$oldRecruitmentResult['success']) {
            ResponseHelper::error('Recruitment tidak ditemukan');
            return;
        }

        // Handle banner image upload
        $banner_image = $old_banner_image; // Keep old image by default
        if (isset($_FILES['banner_image']) && $_FILES['banner_image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = FileUploadHelper::upload($_FILES['banner_image'], 'image', 'recruitment', 2 * 1024 * 1024);
            if (!$uploadResult['success']) {
                ResponseHelper::error('Gagal upload banner: ' . $uploadResult['message']);
                return;
            }
            $banner_image = $uploadResult['filename'];

            // Delete old image if new one uploaded successfully
            if (!empty($old_banner_image)) {
                FileUploadHelper::delete($old_banner_image, 'recruitment');
            }
        }

        // Status dari form akan digunakan, kecuali jika tanggal expired atau diperpanjang
        $recruitmentData = [
            'judul' => $judul,
            'deskripsi' => $deskripsi,
            'status' => $status, // Akan diproses oleh SP
            'kategori' => $kategori,
            'periode' => $periode,
            'tanggal_buka' => $tanggal_buka,
            'tanggal_tutup' => $tanggal_tutup,
            'banner_image' => $banner_image
        ];

        $result = $this->recruitmentModel->update($id, $recruitmentData);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        $csrfToken = $_POST['csrf_token'] ?? '';
        if (!CsrfHelper::validateToken($csrfToken)) {
            ResponseHelper::error('Invalid CSRF token. Silakan refresh halaman.');
            return;
        }

        if (!$id || !is_numeric($id)) {
            ResponseHelper::error('ID recruitment tidak valid');
            return;
        }

        // Get recruitment data first to delete banner image
        $recruitmentResult = $this->recruitmentModel->getById($id);
        if ($recruitmentResult['success'] && !empty($recruitmentResult['data']['banner_image'])) {
            FileUploadHelper::delete($recruitmentResult['data']['banner_image'], 'recruitment');
        }

        $result = $this->recruitmentModel->delete($id);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . base_url('rekrutment'));
            exit;
        }

        $rekrutmen_id = $_POST['rekrutmen_id'] ?? null;
        $nim = trim($_POST['nim'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $no_hp = trim($_POST['no_hp'] ?? '');
        $semester = $_POST['semester'] ?? null;
        $ipk = $_POST['ipk'] ?? null;
        $link_portfolio = trim($_POST['link_portfolio'] ?? '');
        $link_github = trim($_POST['link_github'] ?? '');

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

        if (!isset($_FILES['file_cv']) || $_FILES['file_cv']['error'] === UPLOAD_ERR_NO_FILE) {
            $errors[] = "Curriculum Vitae (CV) wajib diupload";
        }

        if (!empty($errors)) {
            $_SESSION['error_message'] = implode('<br>', $errors);
            header("Location: " . base_url('rekrutment/form/' . $rekrutmen_id));
            exit;
        }

        $uploadCV = FileUploadHelper::upload($_FILES['file_cv'], 'pdf', 'cv', 2 * 1024 * 1024);

        if (!$uploadCV['success']) {
            $_SESSION['error_message'] = "Gagal upload CV: " . $uploadCV['message'];
            header("Location: " . base_url('rekrutment/form/' . $rekrutmen_id));
            exit;
        }

        $file_cv = $uploadCV['filename'];

        $file_khs = null;
        if (isset($_FILES['file_khs']) && $_FILES['file_khs']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadKHS = FileUploadHelper::upload($_FILES['file_khs'], 'pdf', 'khs', 2 * 1024 * 1024);

            if ($uploadKHS['success']) {
                $file_khs = $uploadKHS['filename'];
            }
        }

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

        $result = $this->recruitmentModel->insertPendaftar($pendaftarData);

        if (!$result['success']) {
            FileUploadHelper::delete($file_cv, 'cv');
            if ($file_khs) {
                FileUploadHelper::delete($file_khs, 'khs');
            }

            $_SESSION['error_message'] = $result['message'];
            header("Location: " . base_url('rekrutment/form/' . $rekrutmen_id));
            exit;
        }

        $_SESSION['success_message'] = $result['message'];
        header("Location: " . base_url('rekrutment/sukses'));
        exit;
    }
}
