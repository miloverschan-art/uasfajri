<?php
/**
 * index.php
 * Entry point aplikasi (root).
 * Memproses login lalu menampilkan view/login.php sebagai tampilan.
 * Jika user sudah login maka langsung diarahkan ke dashboard.
 */
session_start();
define("BASE_URL", "./");

require_once __DIR__ . "/controller/AuthController.php";

// Jika sudah login, redirect ke dashboard
if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: view/dashboard.php");
    exit;
}

$authController = new AuthController();
$message = "";
$messageType = "";

// Proses form login (form pada view/login.php mengirim POST ke index.php ini)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = $authController->login($username, $password);

    if ($result['status']) {
        header("Location: view/dashboard.php");
        exit;
    } else {
        $message = $result['message'];
        $messageType = "error";
    }
}

// Tampilkan halaman login
require __DIR__ . "/view/login.php";
