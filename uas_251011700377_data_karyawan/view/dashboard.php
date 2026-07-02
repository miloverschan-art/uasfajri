<?php
/**
 * view/dashboard.php
 * Halaman utama setelah login - menampilkan ringkasan statistik
 */
session_start();
define("BASE_URL", "../");

require_once __DIR__ . "/../controller/AuthController.php";
require_once __DIR__ . "/../controller/KaryawanController.php";

// Cek login, redirect otomatis jika belum login
AuthController::checkLogin();

$karyawanController = new KaryawanController();
$model = $karyawanController->getModel();

$totalKaryawan   = $model->getTotalKaryawan();
$totalDepartemen = $model->getTotalDepartemen();
$totalJabatan    = $model->getTotalJabatan();
$latestKaryawan  = $model->getLatest(5);

$pageTitle = "Dashboard";
$activeMenu = "dashboard";

require __DIR__ . "/partials/header.php";
require __DIR__ . "/partials/sidebar.php";
?>

<main class="main-content flex-grow-1 p-4">
    <h4 class="fw-bold mb-1"><i class="fa-solid fa-gauge"></i> Dashboard</h4>
    <p class="text-muted mb-4">Selamat datang, <?= htmlspecialchars($_SESSION['username']) ?>! Berikut ringkasan data karyawan.</p>

    <!-- Card Statistik -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-primary text-white me-3">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Karyawan</div>
                        <div class="fs-4 fw-bold"><?= $totalKaryawan ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-info text-white me-3">
                        <i class="fa-solid fa-sitemap"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Departemen</div>
                        <div class="fs-4 fw-bold"><?= $totalDepartemen ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-secondary text-white me-3">
                        <i class="fa-solid fa-id-badge"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Total Jabatan</div>
                        <div class="fs-4 fw-bold"><?= $totalJabatan ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Karyawan Terbaru -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold">
            <i class="fa-solid fa-clock-rotate-left"></i> 5 Data Karyawan Terbaru
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Departemen</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($latestKaryawan)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-3">Belum ada data karyawan.</td></tr>
                        <?php else: ?>
                            <?php foreach ($latestKaryawan as $k): ?>
                            <tr>
                                <td><?= htmlspecialchars($k['id']) ?></td>
                                <td><?= htmlspecialchars($k['nama']) ?></td>
                                <td><?= htmlspecialchars($k['departemen']) ?></td>
                                <td><?= htmlspecialchars($k['jabatan']) ?></td>
                                <td>
                                    <?php
                                        $badge = "secondary";
                                        if ($k['status'] === 'Aktif') $badge = "success";
                                        elseif ($k['status'] === 'Cuti') $badge = "warning";
                                        elseif ($k['status'] === 'Non-Aktif') $badge = "danger";
                                    ?>
                                    <span class="badge bg-<?= $badge ?>"><?= htmlspecialchars($k['status']) ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require __DIR__ . "/partials/footer.php"; ?>