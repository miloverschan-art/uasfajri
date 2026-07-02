<?php
/**
 * view/report/laporan.php
 * Halaman laporan seluruh data karyawan dengan filter
 * dan tombol Cetak, Export PDF, Export Excel
 */
session_start();
define("BASE_URL", "../../");

require_once __DIR__ . "/../../controller/AuthController.php";
require_once __DIR__ . "/../../controller/KaryawanController.php";

AuthController::checkLogin();

$karyawanController = new KaryawanController();
$model = $karyawanController->getModel();

$departemen = $_GET['departemen'] ?? '';
$jabatan    = $_GET['jabatan'] ?? '';
$status     = $_GET['status'] ?? '';

$dataLaporan = $model->getForReport($departemen, $jabatan, $status);
$departemenList = $model->getDepartemenList();
$jabatanList    = $model->getJabatanList();

// Query string untuk dikirim ke export_pdf.php / export_excel.php
$queryString = http_build_query([
    "departemen" => $departemen,
    "jabatan" => $jabatan,
    "status" => $status
]);

$pageTitle = "Laporan Karyawan";
$activeMenu = "laporan";

require __DIR__ . "/../partials/header.php";
require __DIR__ . "/../partials/sidebar.php";
?>

<main class="main-content flex-grow-1 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h4 class="fw-bold mb-0"><i class="fa-solid fa-file-lines"></i> Laporan Karyawan</h4>
        <div class="d-flex gap-2 flex-wrap">
            <button onclick="window.print()" class="btn btn-secondary btn-sm">
                <i class="fa-solid fa-print"></i> Cetak
            </button>
            <a href="export_pdf.php?<?= $queryString ?>" target="_blank" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-file-pdf"></i> Export PDF
            </a>
            <a href="export_excel.php?<?= $queryString ?>" class="btn btn-success btn-sm">
                <i class="fa-solid fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-3 no-print">
        <div class="card-body">
            <form method="GET" action="laporan.php" class="row g-2">
                <div class="col-md-3">
                    <select name="departemen" class="form-select">
                        <option value="">-- Semua Departemen --</option>
                        <?php foreach ($departemenList as $dep): ?>
                        <option value="<?= htmlspecialchars($dep) ?>" <?= $departemen === $dep ? 'selected' : '' ?>><?= htmlspecialchars($dep) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="jabatan" class="form-select">
                        <option value="">-- Semua Jabatan --</option>
                        <?php foreach ($jabatanList as $jab): ?>
                        <option value="<?= htmlspecialchars($jab) ?>" <?= $jabatan === $jab ? 'selected' : '' ?>><?= htmlspecialchars($jab) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="Aktif" <?= $status === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="Non-Aktif" <?= $status === 'Non-Aktif' ? 'selected' : '' ?>>Non-Aktif</option>
                        <option value="Cuti" <?= $status === 'Cuti' ? 'selected' : '' ?>>Cuti</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Kop Laporan (khusus tampil saat cetak) -->
    <div class="text-center mb-3 print-only d-none">
        <h5 class="fw-bold mb-0">LAPORAN DATA KARYAWAN</h5>
        <small>Tanggal Cetak: <?= date("d-m-Y") ?></small>
    </div>

    <!-- Tabel Laporan -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold">
            <i class="fa-solid fa-table"></i> Total Data: <?= count($dataLaporan) ?>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Departemen</th>
                            <th>Jabatan</th>
                            <th>Tanggal Masuk</th>
                            <th>Gaji</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dataLaporan)): ?>
                        <tr><td colspan="9" class="text-center text-muted py-4">Tidak ada data.</td></tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($dataLaporan as $k): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($k['id']) ?></td>
                                <td><?= htmlspecialchars($k['nama']) ?></td>
                                <td><?= htmlspecialchars($k['jenis_kelamin']) ?></td>
                                <td><?= htmlspecialchars($k['departemen']) ?></td>
                                <td><?= htmlspecialchars($k['jabatan']) ?></td>
                                <td><?= date("d-m-Y", strtotime($k['tanggal_masuk'])) ?></td>
                                <td>Rp <?= number_format($k['gaji'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($k['status']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php require __DIR__ . "/../partials/footer.php"; ?>
