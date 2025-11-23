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
            <a href="#" class="navbar-brand">
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
                        <a class="nav-link active" href="#">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('tentang-kami') ?>">Tentang Kami</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Riset dan Produk
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= base_url('publikasi-dosen') ?>">Publikasi Dosen</a></li>
                            <li><a class="dropdown-item" href="<?= base_url('produk-lab') ?>">Produk Lab</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('aktivitas-laboratorium') ?>">Aktivitas Lab</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('mitra-lab') ?>">Mitra</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Rekrutment</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>