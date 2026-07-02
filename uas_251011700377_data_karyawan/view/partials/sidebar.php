<?php
/**
 * Partial: Sidebar
 * Menu navigasi utama aplikasi
 * Variabel $activeMenu dipakai untuk menandai menu yang aktif
 */
$activeMenu = $activeMenu ?? "";
?>
<div class="sidebar bg-white border-end" id="sidebar">
    <div class="list-group list-group-flush py-2">
        <a href="<?= BASE_URL ?>view/dashboard.php"
           class="list-group-item list-group-item-action <?= $activeMenu === 'dashboard' ? 'active' : '' ?>">
            <i class="fa-solid fa-gauge me-2"></i> Dashboard
        </a>
        <a href="<?= BASE_URL ?>view/karyawan/index.php"
           class="list-group-item list-group-item-action <?= $activeMenu === 'karyawan' ? 'active' : '' ?>">
            <i class="fa-solid fa-users me-2"></i> Data Karyawan
        </a>
        <a href="<?= BASE_URL ?>view/report/laporan.php"
           class="list-group-item list-group-item-action <?= $activeMenu === 'laporan' ? 'active' : '' ?>">
            <i class="fa-solid fa-file-lines me-2"></i> Laporan Karyawan
        </a>
        <a href="<?= BASE_URL ?>logout.php" id="btnLogoutSidebar"
           class="list-group-item list-group-item-action text-danger">
            <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
        </a>
    </div>
</div>
