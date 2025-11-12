<?php


function handleFileUpload($file)
{
    // Validasi tipe file
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!in_array($file['type'], $allowedTypes)) {
        return false;
    }

    // Validasi ukuran (max 2MB)
    if ($file['size'] > 2 * 1024 * 1024) {
        return false;
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'dosen_' . time() . '_' . uniqid() . '.' . $extension;

    // Upload path
    $uploadDir = __DIR__ . '/../../public/uploads/dosen/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $uploadPath = $uploadDir . $filename;

    // Move file
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        return $filename;
    }

    return false;
}
