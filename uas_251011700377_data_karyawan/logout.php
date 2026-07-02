<?php
/**
 * logout.php
 * Menghancurkan session lalu mengarahkan kembali ke halaman login
 */
session_start();
require_once __DIR__ . "/controller/AuthController.php";

$authController = new AuthController();
$authController->logout();

header("Location: index.php");
exit;
