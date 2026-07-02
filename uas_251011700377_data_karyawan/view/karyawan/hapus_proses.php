<?php
/**
 * view/karyawan/hapus_proses.php
 * Endpoint untuk memproses penghapusan data karyawan
 * Menerima POST dari form hapus di index.php
 */
session_start();
require_once __DIR__ . "/../../controller/AuthController.php";
require_once __DIR__ . "/../../controller/KaryawanController.php";

AuthController::checkLogin();

$karyawanController = new KaryawanController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
    $result = $karyawanController->hapus($_POST['id']);
    $notif = $result['message'];
    $notifType = $result['status'] ? 'success' : 'error';
} else {
    $notif = "Permintaan tidak valid.";
    $notifType = 'error';
}

header("Location: index.php?notif=" . urlencode($notif) . "&notif_type=" . $notifType);
exit;
