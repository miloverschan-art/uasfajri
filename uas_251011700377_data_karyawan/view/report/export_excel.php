<?php
/**
 * view/report/export_excel.php
 * Export laporan data karyawan ke format Excel (.xls)
 * Menggunakan PHP native murni: tabel HTML dibungkus header Excel
 * (tanpa Composer / PhpSpreadsheet)
 */
session_start();
require_once __DIR__ . "/../../controller/AuthController.php";
require_once __DIR__ . "/../../controller/KaryawanController.php";

AuthController::checkLogin();

$karyawanController = new KaryawanController();
$model = $karyawanController->getModel();

$departemen = $_GET['departemen'] ?? '';
$jabatan    = $_GET['jabatan'] ?? '';
$status     = $_GET['status'] ?? '';

$dataLaporan = $model->getForReport($departemen, $jabatan, $status);

// Header agar file terbuka otomatis di Microsoft Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=laporan_karyawan.xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border="1">
    <tr>
        <th colspan="9" style="font-size:16px; text-align:center; background:#0d6efd; color:#ffffff;">
            LAPORAN DATA KARYAWAN
        </th>
    </tr>
    <tr>
        <th colspan="9" style="text-align:center;">Tanggal Cetak: <?= date("d-m-Y H:i") ?></th>
    </tr>
    <tr></tr>
    <tr style="background:#f2f2f2; font-weight:bold;">
        <th>No</th>
        <th>ID Karyawan</th>
        <th>Nama</th>
        <th>Jenis Kelamin</th>
        <th>Departemen</th>
        <th>Jabatan</th>
        <th>Tanggal Masuk</th>
        <th>Gaji</th>
        <th>Status</th>
    </tr>
    <?php $no = 1; foreach ($dataLaporan as $k): ?>
    <tr>
        <td><?= $no++ ?></td>
        <td><?= htmlspecialchars($k['id']) ?></td>
        <td><?= htmlspecialchars($k['nama']) ?></td>
        <td><?= htmlspecialchars($k['jenis_kelamin']) ?></td>
        <td><?= htmlspecialchars($k['departemen']) ?></td>
        <td><?= htmlspecialchars($k['jabatan']) ?></td>
        <td><?= date("d-m-Y", strtotime($k['tanggal_masuk'])) ?></td>
        <td><?= (float)$k['gaji'] ?></td>
        <td><?= htmlspecialchars($k['status']) ?></td>
    </tr>
    <?php endforeach; ?>
</table>
