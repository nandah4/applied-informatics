<?php

/**
 * Get current path untuk set active menu
 * Parse URL path only (tanpa query string & base URL)
 */
$current_url = $_SERVER['REQUEST_URI'];
$current_path = parse_url($current_url, PHP_URL_PATH);

// Remove base URL jika ada (e.g., /applied-informatics)
$base_path = '/applied-informatics';
if (strpos($current_path, $base_path) === 0) {
    $current_path = substr($current_path, strlen($base_path));
}

/**
 * Helper function to check if menu is active
 *
 * @param string $path - Target path untuk check (e.g., '/', '/tentang-kami')
 * @param string $current - Current path dari URL
 * @return string - 'active' jika match, empty string jika tidak
 */
function isActive($path, $current)
{
    // Normalize path
    $path = '/' . trim($path, '/');
    $current = '/' . trim($current, '/');

    // Special case: Homepage - exact match only
    if ($path === '/') {
        return $current === '/' ? 'active' : '';
    }

    // For other pages: starts with match (support nested routes)
    // Case-insensitive comparison
    return stripos($current, $path) === 0 ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Laboratorium Applied Informatics Politeknik Negeri Malang">
    <title>Applied Informatics Laboratory - Politeknik Negeri Malang</title>

    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Base CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/base/main.css') ?>">
    <link rel="stylesheet" href="<?= asset_url('css/base/layout.css') ?>">

    <!-- Header Component CSS -->
    <link rel="stylesheet" href="<?= asset_url('css/components/header.css') ?>">
</head>

<body>
    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-4 px-lg-5">
            <a href="<?= base_url('') ?>" class="navbar-brand">
                <img src="<?= asset_url('images/lab-ai-logo.png') ?>" alt="Lab AI Logo" class="logo">
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarContent" aria-controls="navbarContent"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto gap-lg-4">
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('/', $current_path) ?>" href="<?= base_url('') ?>">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('/anggota-laboratorium', $current_path) ?>" href="<?= base_url('anggota-laboratorium') ?>">Anggota</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle <?= isActive('/publikasi-dosen', $current_path) || isActive('/produk-lab', $current_path) ? 'active' : '' ?>" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Riset dan Produk
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item <?= isActive('/publikasi-dosen', $current_path) ?>" href="<?= base_url('publikasi-dosen') ?>">Publikasi Dosen</a></li>
                            <li><a class="dropdown-item <?= isActive('/produk-lab', $current_path) ?>" href="<?= base_url('produk-lab') ?>">Produk Lab</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('/aktivitas-laboratorium', $current_path) ?>" href="<?= base_url('aktivitas-laboratorium') ?>">Aktivitas Lab</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link  <?= isActive('/mitra-laboratorium', $current_path) ?>" href="<?= base_url('mitra-laboratorium') ?>">Mitra</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('/rekrutment', $current_path) ?>" href="<?= base_url('rekrutment') ?>">Rekrutment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= isActive('/ar-showcase', $current_path) ?>" href="<?= base_url('ar-showcase') ?>">AR Showcase</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('contact-us') ?>">Contact Us</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>