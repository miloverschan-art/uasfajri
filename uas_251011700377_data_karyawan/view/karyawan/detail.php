<?php
/**
 * view/karyawan/detail.php
 * Menampilkan detail lengkap satu data karyawan
 */
session_start();
define("BASE_URL", "../../");

require_once __DIR__ . "/../../controller/AuthController.php";
require_once __DIR__ . "/../../controller/KaryawanController.php";

AuthController::checkLogin();

$karyawanController = new KaryawanController();

$id = $_GET['id'] ?? '';
$karyawan = $karyawanController->getKaryawanById($id);

if (!$karyawan) {
    header("Location: index.php?notif=" . urlencode("Data karyawan tidak ditemukan.") . "&notif_type=error");
    exit;
}

$pageTitle = "Detail Karyawan";
$activeMenu = "karyawan";

require __DIR__ . "/../partials/header.php";
require __DIR__ . "/../partials/sidebar.php";
?>

<main class="main-content flex-grow-1 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0"><i class="fa-solid fa-address-card"></i> Detail Karyawan</h4>
        <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center mb-3">
                    <?php if (!empty($karyawan['foto']) && file_exists(__DIR__ . "/../../upload/" . $karyawan['foto'])): ?>
                    <img src="<?= BASE_URL ?>upload/<?= htmlspecialchars($karyawan['foto']) ?>" class="rounded img-fluid detail-photo">
                    <?php else: ?>
                    <div class="avatar-placeholder-lg mx-auto"><i class="fa-solid fa-user"></i></div>
                    <?php endif; ?>
                    <h5 class="mt-3 mb-0"><?= htmlspecialchars($karyawan['nama']) ?></h5>
                    <?php
                        $badge = "secondary";
                        if ($karyawan['status'] === 'Aktif') $badge = "success";
                        elseif ($karyawan['status'] === 'Cuti') $badge = "warning";
                        elseif ($karyawan['status'] === 'Non-Aktif') $badge = "danger";
                    ?>
                    <span class="badge bg-<?= $badge ?> mt-1"><?= htmlspecialchars($karyawan['status']) ?></span>
                </div>
                <div class="col-md-9">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="200">ID Karyawan</th>
                            <td>: <?= htmlspecialchars($karyawan['id']) ?></td>
                        </tr>
                        <tr>
                            <th>Jenis Kelamin</th>
                            <td>: <?= htmlspecialchars($karyawan['jenis_kelamin']) ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Lahir</th>
                            <td>: <?= date("d-m-Y", strtotime($karyawan['tanggal_lahir'])) ?></td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>: <?= nl2br(htmlspecialchars($karyawan['alamat'])) ?></td>
                        </tr>
                        <tr>
                            <th>Nomor HP</th>
                            <td>: <?= htmlspecialchars($karyawan['telepon']) ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>: <?= htmlspecialchars($karyawan['email']) ?></td>
                        </tr>
                        <tr>
                            <th>Departemen</th>
                            <td>: <?= htmlspecialchars($karyawan['departemen']) ?></td>
                        </tr>
                        <tr>
                            <th>Jabatan</th>
                            <td>: <?= htmlspecialchars($karyawan['jabatan']) ?></td>
                        </tr>
                        <tr>
                            <th>Tanggal Masuk</th>
                            <td>: <?= date("d-m-Y", strtotime($karyawan['tanggal_masuk'])) ?></td>
                        </tr>
                        <tr>
                            <th>Gaji</th>
                            <td>: Rp <?= number_format($karyawan['gaji'], 0, ',', '.') ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>
            <div class="d-flex gap-2">
                <a href="edit.php?id=<?= urlencode($karyawan['id']) ?>" class="btn btn-primary">
                    <i class="fa-solid fa-pen"></i> Edit Data
                </a>
                <a href="index.php" class="btn btn-outline-secondary">Kembali ke Daftar</a>
            </div>
        </div>
    </div>
</main>

<?php require __DIR__ . "/../partials/footer.php"; ?>
