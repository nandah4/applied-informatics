<?php

/**
 * ============================================================================
 * PRODUK CONTROLLER 
 * ============================================================================
 *
 * File: Controllers/ProdukController.php
 * Deskripsi: Controller untuk menangani request terkait data produk
 * 
 * Fungsi utama:
 * - createProduk(): Handle request create produk baru
 * - getAllProduk(): Get semua data produk dengan pagination
 * - getProdukById(): Get detail produk berdasarkan ID
 * - updateProduk(): Handle request update produk
 * - deleteProdukById(): Handle request delete produk
 * - getAllDosen(): Get semua dosen untuk dropdown
 */

class ProdukController
{
    private $produkModel;

    /**
     * CONSTRUCTOR
     * Inisialisasi ProdukModel
     */
    public function __construct()
    {
        $this->produkModel = new ProdukModel();
    }

    /**
     * GET ALL PRODUK (Untuk View List)
     *
     * Fungsi: Mengambil data dengan pagination
     * Method: GET
     * @return array - ['data' => array, 'pagination' => array]
     */
    public function getAllProduk()
    {
        // Ambil parameter page & per_page dari $_GET
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 10;

        // Validasi parameter
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage)); // Max 100 per page

        // Ambil total records DARI MODEL dengan $countOnly = true
        $paginationData = $this->produkModel->getAllProdukPaginated($perPage, 0, true);
        $totalRecords = $paginationData['total'];

        // Gunakan PaginationHelper
        $pagination = PaginationHelper::paginate($totalRecords, $page, $perPage);

        // Panggil model->getAllProdukPaginated()
        $result = $this->produkModel->getAllProdukPaginated($pagination['per_page'], $pagination['offset']);

        return [
            'data' => $result['data'],
            'pagination' => $pagination
        ];
    }

    /**
     * GET PRODUK BY ID
     *
     * Fungsi: Mengambil detail 1 produk
     * @param int $id - ID produk
     * @return array - Data Produk
     */
    public function getProdukById($id)
    {
        // Validasi ID
        $validation = ValidationHelper::validateId($id, 'ID Produk');
        if (!$validation['valid']) {
            return [
                'success' => false,
                'message' => $validation['message'],
                'data' => null
            ];
        }

        // Panggil model
        $result = $this->produkModel->getProdukById((int)$id);

        // Return data
        return $result;
    }

    /**
     * GET ALL DOSEN (Untuk Dropdown)
     *
     * Fungsi: Mengambil semua data dosen untuk dropdown
     * @return array - ['success' => bool, 'data' => array]
     */
    public function getAllDosen()
    {
        return $this->produkModel->getAllDosen();
    }

    /**
     * CREATE PRODUK (Handle Form Submit)
     *
     * Fungsi: Menambah produk baru
     * Method: POST
     * Endpoint: /applied-informatics/produk/create
     * Response: JSON
     */
    public function createProduk()
    {
        // Cek request method POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // Ambil data dari $_POST
        $nama_produk = $_POST['nama_produk'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';
        $link_produk = $_POST['link_produk'] ?? '';
        $author_type = $_POST['author_type'] ?? 'dosen'; // Default: dosen
        $author_dosen_id = $_POST['author_dosen_id'] ?? null;
        $author_mahasiswa_nama = $_POST['author_mahasiswa_nama'] ?? null;

        // Validasi input
        $validationErrors = $this->validateProdukInput(
            $nama_produk, 
            $deskripsi, 
            $link_produk,
            $author_type,
            $author_dosen_id,
            $author_mahasiswa_nama
        );

        if (!empty($validationErrors)) {
            ResponseHelper::error($validationErrors[0]); // Tampilkan error pertama
            return;
        }

        // Handle upload foto (WAJIB di mode create)
        $fotoFileName = null;
        if (isset($_FILES['foto_produk']) && $_FILES['foto_produk']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = FileUploadHelper::upload(
                $_FILES['foto_produk'],
                'image',
                'produk',
                2 * 1024 * 1024 // Max size: 2MB
            );

            if (!$uploadResult['success']) {
                ResponseHelper::error($uploadResult['message']);
                return;
            }

            $fotoFileName = $uploadResult['filename'];
        } else {
            // Foto wajib di mode create (sesuai validasi form.js)
            ResponseHelper::error('Foto produk wajib diisi.');
            return;
        }

        // Siapkan data author berdasarkan author_type
        $finalAuthorDosenId = null;
        $finalAuthorMahasiswaNama = null;

        if ($author_type === 'dosen') {
            $finalAuthorDosenId = $author_dosen_id;
        } elseif ($author_type === 'mahasiswa') {
            $finalAuthorMahasiswaNama = $author_mahasiswa_nama;
        } elseif ($author_type === 'kolaborasi') {
            $finalAuthorDosenId = $author_dosen_id;
            $finalAuthorMahasiswaNama = $author_mahasiswa_nama;
        }

        // Siapkan data untuk insert
        $produk_data = [
            'nama_produk' => $nama_produk,
            'deskripsi' => $deskripsi,
            'foto_produk' => $fotoFileName,
            'link_produk' => $link_produk,
            'author_dosen_id' => $finalAuthorDosenId,
            'author_mahasiswa_nama' => $finalAuthorMahasiswaNama
        ];

        // Panggil model->insert()
        $result = $this->produkModel->insert($produk_data);

        // Jika gagal insert, hapus foto yang sudah terlanjur di-upload
        if (!$result['success']) {
            if ($fotoFileName) {
                FileUploadHelper::delete($fotoFileName, 'produk');
            }
            ResponseHelper::error($result['message']);
            return;
        }

        // Return success response
        ResponseHelper::success('Data produk berhasil ditambahkan', $result['data']);
    }

    /**
     * UPDATE PRODUK (Handle Edit Form)
     * 
     * Endpoint: /applied-informatics/produk/update
     * Fungsi: Update data produk
     * Method: POST
     * Response: JSON
     */
    public function updateProduk()
    {
        // Cek request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // Ambil data dari $_POST
        $id = $_POST['id'] ?? '';
        $nama_produk = $_POST['nama_produk'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';
        $link_produk = $_POST['link_produk'] ?? '';
        $author_type = $_POST['author_type'] ?? 'dosen';
        $author_dosen_id = $_POST['author_dosen_id'] ?? null;
        $author_mahasiswa_nama = $_POST['author_mahasiswa_nama'] ?? null;

        // Validasi ID
        $idValidation = ValidationHelper::validateId($id, 'ID Produk');
        if (!$idValidation['valid']) {
            ResponseHelper::error($idValidation['message']);
            return;
        }

        // Validasi input
        $validationErrors = $this->validateProdukInput(
            $nama_produk, 
            $deskripsi, 
            $link_produk,
            $author_type,
            $author_dosen_id,
            $author_mahasiswa_nama
        );

        if (!empty($validationErrors)) {
            ResponseHelper::error($validationErrors[0]);
            return;
        }

        // Ambil data lama untuk mendapatkan nama file foto lama
        $oldDataResult = $this->produkModel->getProdukById((int)$id);
        if (!$oldDataResult['success']) {
            ResponseHelper::error('Data produk lama tidak ditemukan.');
            return;
        }

        $oldFotoFileName = $oldDataResult['data']['foto_produk'] ?? null;
        $fotoFileName = $oldFotoFileName; // Default: pakai foto lama

        // Handle upload foto baru (opsional di mode edit)
        if (isset($_FILES['foto_produk']) && $_FILES['foto_produk']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = FileUploadHelper::upload(
                $_FILES['foto_produk'],
                'image',
                'produk',
                2 * 1024 * 1024
            );

            if (!$uploadResult['success']) {
                ResponseHelper::error($uploadResult['message']);
                return;
            }

            $fotoFileName = $uploadResult['filename'];

            // Hapus foto lama SETELAH upload berhasil
            if ($oldFotoFileName && $oldFotoFileName !== $fotoFileName) {
                FileUploadHelper::delete($oldFotoFileName, 'produk');
            }
        }

        // Siapkan data author berdasarkan author_type
        $finalAuthorDosenId = null;
        $finalAuthorMahasiswaNama = null;

        if ($author_type === 'dosen') {
            $finalAuthorDosenId = $author_dosen_id;
        } elseif ($author_type === 'mahasiswa') {
            $finalAuthorMahasiswaNama = $author_mahasiswa_nama;
        } elseif ($author_type === 'kolaborasi') {
            $finalAuthorDosenId = $author_dosen_id;
            $finalAuthorMahasiswaNama = $author_mahasiswa_nama;
        }

        // Siapkan data untuk update
        $produk_data = [
            'nama_produk' => $nama_produk,
            'deskripsi' => $deskripsi,
            'foto_produk' => $fotoFileName,
            'link_produk' => $link_produk,
            'author_dosen_id' => $finalAuthorDosenId,
            'author_mahasiswa_nama' => $finalAuthorMahasiswaNama
        ];

        // Panggil model->updateProduk()
        $result = $this->produkModel->updateProduk((int)$id, $produk_data);

        // Jika gagal update dan ada foto baru, hapus foto baru tsb
        if (!$result['success']) {
            if ($fotoFileName && $fotoFileName !== $oldFotoFileName) {
                FileUploadHelper::delete($fotoFileName, 'produk');
            }
            ResponseHelper::error($result['message']);
            return;
        }

        // Return success response
        ResponseHelper::success('Data produk berhasil diupdate');
    }

    /**
     * DELETE PRODUK
     *
     * Fungsi: Hapus produk
     * Method: POST
     * Endpoint: /applied-informatics/produk/delete/{id}
     * Response: JSON
     */
    public function deleteProdukById($id)
    {
        // Validasi request method
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            ResponseHelper::error('Invalid request method');
            return;
        }

        // Panggil model->deleteProduk()
        $result = $this->produkModel->deleteProduk((int)$id);

        if (!$result['success']) {
            ResponseHelper::error($result['message']);
            return;
        }

        // Hapus file foto jika ada
        if (!empty($result['data']['foto_produk'])) {
            FileUploadHelper::delete($result['data']['foto_produk'], 'produk');
        }

        // Return success response
        ResponseHelper::success($result['message']);
    }

    /**
     * VALIDATE INPUT (Private Helper)
     *
     * Fungsi: Validasi data form produk
     * @param string $nama_produk - Nama produk
     * @param string $deskripsi - Deskripsi produk
     * @param string $link_produk - Link produk
     * @param string $author_type - Tipe author (dosen/mahasiswa/kolaborasi)
     * @param mixed $author_dosen_id - ID dosen
     * @param mixed $author_mahasiswa_nama - Nama mahasiswa
     * @return array - Array error messages
     */
    private function validateProdukInput(
        $nama_produk, 
        $deskripsi, 
        $link_produk,
        $author_type,
        $author_dosen_id,
        $author_mahasiswa_nama
    ) {
        $errors = [];

        // 1. Validasi nama produk (wajib, min 3 char, max 255 char)
        $namaValidation = ValidationHelper::validateName($nama_produk, 3, 255);
        if (!$namaValidation['valid']) {
            $errors[] = $namaValidation['message'];
        }

        // 2. Validasi deskripsi (opsional, max 5000 char)
        $deskripsiValidation = ValidationHelper::validateText($deskripsi, 5000, false);
        if (!$deskripsiValidation['valid']) {
            $errors[] = $deskripsiValidation['message'];
        }

        // 3. Validasi link produk (opsional, jika diisi harus valid URL)
        if (!empty($link_produk)) {
            $linkValidation = ValidationHelper::validateUrl($link_produk, false);
            if (!$linkValidation['valid']) {
                $errors[] = $linkValidation['message'];
            }
        }

        // 4. Validasi author berdasarkan author_type
        if ($author_type === 'dosen') {
            // Jika tipe dosen, author_dosen_id wajib diisi
            if (empty($author_dosen_id)) {
                $errors[] = 'Dosen harus dipilih';
            }
        } elseif ($author_type === 'mahasiswa') {
            // Jika tipe mahasiswa, author_mahasiswa_nama wajib diisi
            if (empty($author_mahasiswa_nama)) {
                $errors[] = 'Nama mahasiswa harus diisi';
            } else {
                // Validasi nama mahasiswa (min 3 char, max 255 char)
                $mahasiswaValidation = ValidationHelper::validateName($author_mahasiswa_nama, 3, 255);
                if (!$mahasiswaValidation['valid']) {
                    $errors[] = 'Nama mahasiswa: ' . $mahasiswaValidation['message'];
                }
            }
        } elseif ($author_type === 'kolaborasi') {
            // Jika tipe kolaborasi, keduanya wajib diisi
            if (empty($author_dosen_id)) {
                $errors[] = 'Dosen harus dipilih untuk kolaborasi';
            }
            if (empty($author_mahasiswa_nama)) {
                $errors[] = 'Nama mahasiswa harus diisi untuk kolaborasi';
            } else {
                $mahasiswaValidation = ValidationHelper::validateName($author_mahasiswa_nama, 3, 255);
                if (!$mahasiswaValidation['valid']) {
                    $errors[] = 'Nama mahasiswa: ' . $mahasiswaValidation['message'];
                }
            }
        } else {
            $errors[] = 'Tipe author tidak valid';
        }

        return $errors;
    }
}