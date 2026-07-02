<?php
/**
 * view/register.php
 * Halaman pendaftaran akun baru
 */
session_start();
define("BASE_URL", "../");

require_once __DIR__ . "/../controller/AuthController.php";

// Jika sudah login, tidak perlu register lagi
if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: dashboard.php");
    exit;
}

$authController = new AuthController();
$message = "";
$messageType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    $result = $authController->register($username, $password, $confirm);
    $message = $result['message'];
    $messageType = $result['status'] ? "success" : "error";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Sistem Informasi Data Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-body">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <div class="card auth-card shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <i class="fa-solid fa-user-plus fa-2x text-primary"></i>
                            <h4 class="fw-bold mt-2 mb-0">Daftar Akun</h4>
                            <small class="text-muted">Buat akun baru untuk mengakses sistem</small>
                        </div>

                        <form method="POST" action="register.php" id="formRegister">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" minlength="8" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                                    <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password" minlength="8" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa-solid fa-user-plus"></i> Daftar
                            </button>
                        </form>

                        <p class="text-center mt-3 mb-0">
                            Sudah punya akun? <a href="../index.php">Login di sini</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        <?php if (!empty($message)): ?>
        Swal.fire({
            icon: '<?= $messageType === "error" ? "error" : "success" ?>',
            title: '<?= $messageType === "error" ? "Registrasi Gagal" : "Registrasi Berhasil" ?>',
            text: '<?= addslashes($message) ?>',
            confirmButtonColor: '#0d6efd'
        })<?php if ($messageType === 'success'): ?>.then(() => { window.location.href = '../index.php'; })<?php endif; ?>;
        <?php endif; ?>
    </script>
</body>
</html>
