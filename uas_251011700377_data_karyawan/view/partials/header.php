<?php
/**
 * Partial: Header / Navbar
 * Ditampilkan di semua halaman setelah login
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($pageTitle) ? $pageTitle . " - " : "" ?>Sistem Informasi Data Karyawan</title>

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <script>
        // Variabel global path dasar project, dipakai oleh script.js
        const BASE_URL = "<?= BASE_URL ?>";
    </script>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark app-navbar sticky-top">
    <div class="container-fluid">
        <button class="btn btn-link text-white d-lg-none" id="sidebarToggle">
            <i class="fa-solid fa-bars"></i>
        </button>
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>view/dashboard.php">
            <i class="fa-solid fa-building"></i> SI Data Karyawan
        </a>
        <div class="ms-auto d-flex align-items-center">
            <span class="text-white me-3 d-none d-md-inline">
                <i class="fa-solid fa-user-circle"></i> <?= htmlspecialchars($_SESSION['username'] ?? '') ?>
            </span>
            <a href="#" class="btn btn-outline-light btn-sm" id="btnLogout">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="d-flex">
