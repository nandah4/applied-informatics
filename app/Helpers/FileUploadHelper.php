<?php

/**
 * File: Helpers/FileUploadHelper.php
 * Deskripsi: Helper untuk menangani upload file (foto, dokumen, dll)
 *
 * Fungsi:
 * - Upload file dengan validasi tipe dan ukuran
 * - Generate nama file unik
 * - Hapus file lama
 * - Mendukung berbagai tipe file (image, pdf, doc, dll)
 */

class FileUploadHelper
{
    /**
     * Upload file dengan validasi
     *
     * @param array $file - File dari $_FILES (contoh: $_FILES['foto_profil'])
     * @param string $uploadType - Tipe upload: 'image', 'document', 'pdf'
     * @param string $folder - Folder tujuan upload (contoh: 'dosen', 'fasilitas')
     * @param int $maxSize - Ukuran maksimal file dalam bytes (default: 2MB)
     * @return array - ['success' => bool, 'filename' => string, 'message' => string]
     */
    public static function upload($file, $uploadType = 'image', $folder = 'uploads', $maxSize = 2097152)
    {
        // Validasi: Cek apakah file ada
        if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return [
                'success' => false,
                'filename' => null,
                'message' => 'File tidak ditemukan'
            ];
        }

        // Validasi: Cek error upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return [
                'success' => false,
                'filename' => null,
                'message' => 'Error saat upload file: ' . self::getUploadErrorMessage($file['error'])
            ];
        }

        // Validasi: Cek ukuran file
        if ($file['size'] > $maxSize) {
            $maxSizeMB = $maxSize / 1024 / 1024;
            return [
                'success' => false,
                'filename' => null,
                'message' => "Ukuran file maksimal {$maxSizeMB}MB"
            ];
        }

        // Validasi: Cek tipe file
        $allowedTypes = self::getAllowedTypes($uploadType);
        $fileType = strtolower($file['type']);
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($fileType, $allowedTypes['mimes']) && !in_array($fileExtension, $allowedTypes['extensions'])) {
            return [
                'success' => false,
                'filename' => null,
                'message' => 'Tipe file tidak diperbolehkan. Hanya: ' . implode(', ', $allowedTypes['extensions'])
            ];
        }

        // Generate nama file unik
        $filename = self::generateUniqueFilename($file['name'], $folder);

        // Tentukan folder upload
        $uploadDir = __DIR__ . '/../../public/uploads/' . $folder . '/';

        // Buat folder jika belum ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $uploadPath = $uploadDir . $filename;

        // Upload file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return [
                'success' => true,
                'filename' => $filename,
                'message' => 'File berhasil diupload'
            ];
        }

        return [
            'success' => false,
            'filename' => null,
            'message' => 'Gagal memindahkan file ke folder tujuan'
        ];
    }

    /**
     * Hapus file dari folder uploads
     *
     * @param string $filename - Nama file yang akan dihapus
     * @param string $folder - Folder tempat file berada
     * @return bool - true jika berhasil dihapus
     */
    public static function delete($filename, $folder)
    {
        if (empty($filename)) {
            return false;
        }

        $filePath = __DIR__ . '/../../public/uploads/' . $folder . '/' . $filename;

        if (file_exists($filePath)) {
            return unlink($filePath);
        }

        return false;
    }

    /**
     * Generate nama file unik untuk menghindari konflik
     *
     * @param string $originalName - Nama file asli
     * @param string $prefix - Prefix untuk nama file (contoh: 'dosen', 'fasilitas')
     * @return string - Nama file unik
     */
    private static function generateUniqueFilename($originalName, $prefix = '')
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $timestamp = time();
        $uniqueId = uniqid();

        return "{$prefix}_{$timestamp}_{$uniqueId}.{$extension}";
    }

    /**
     * Mendapatkan allowed types berdasarkan tipe upload
     *
     * @param string $uploadType - 'image', 'document', 'pdf', 'all'
     * @return array - ['mimes' => [], 'extensions' => []]
     */
    private static function getAllowedTypes($uploadType)
    {
        $types = [
            'image' => [
                'mimes' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'],
                'extensions' => ['jpg', 'jpeg', 'png', 'gif']
            ],
            'document' => [
                'mimes' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
                'extensions' => ['pdf', 'doc', 'docx']
            ],
            'pdf' => [
                'mimes' => ['application/pdf'],
                'extensions' => ['pdf']
            ],
            'all' => [
                'mimes' => ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'application/pdf', 'application/msword'],
                'extensions' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']
            ]
        ];

        return $types[$uploadType] ?? $types['image'];
    }

    /**
     * Mendapatkan pesan error berdasarkan kode error upload
     *
     * @param int $errorCode - Kode error dari $_FILES['file']['error']
     * @return string - Pesan error
     */
    private static function getUploadErrorMessage($errorCode)
    {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File melebihi upload_max_filesize di php.ini',
            UPLOAD_ERR_FORM_SIZE => 'File melebihi MAX_FILE_SIZE yang ditentukan di form',
            UPLOAD_ERR_PARTIAL => 'File hanya terupload sebagian',
            UPLOAD_ERR_NO_FILE => 'Tidak ada file yang diupload',
            UPLOAD_ERR_NO_TMP_DIR => 'Folder temporary tidak ditemukan',
            UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
            UPLOAD_ERR_EXTENSION => 'Upload dihentikan oleh extension PHP'
        ];

        return $errors[$errorCode] ?? 'Error tidak diketahui';
    }
}
