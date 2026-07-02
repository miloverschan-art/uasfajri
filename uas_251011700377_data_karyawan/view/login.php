<?php
/**
 * view/login.php
 * Halaman Login utama (di-include oleh index.php di root)
 * Variabel $message dan $messageType dikirim dari index.php (opsional)
 */
$message = $message ?? "";
$messageType = $messageType ?? "";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Data Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="auth-body">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5 col-lg-4">
                <div class="card auth-card shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <i class="fa-solid fa-building fa-2x text-primary"></i>
                            <h4 class="fw-bold mt-2 mb-0">SI Data Karyawan</h4>
                            <small class="text-muted">Silakan login untuk melanjutkan</small>
                        </div>

                        <form method="POST" action="index.php" id="formLogin">
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
                                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa-solid fa-right-to-bracket"></i> Login
                            </button>
                        </form>

                        <p class="text-center mt-3 mb-0">
                            Belum punya akun? <a href="view/register.php">Register di sini</a>
                        </p>
                    </div>
                </div>
                <p class="text-center text-muted small mt-3">
                    &copy; <?= date("Y") ?> UAS Pemrograman Web 2 - NIM 251011700377
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        <?php if (!empty($message)): ?>
        Swal.fire({
            icon: '<?= $messageType === "error" ? "error" : "success" ?>',
            title: '<?= $messageType === "error" ? "Login Gagal" : "Berhasil" ?>',
            text: '<?= addslashes($message) ?>',
            confirmButtonColor: '#0d6efd'
        });
        <?php endif; ?>
    </script>
</body>
</html>
