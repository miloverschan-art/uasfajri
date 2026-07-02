<?php
/**
 * view/karyawan/index.php
 * Menampilkan daftar data karyawan dengan fitur searching, filter & pagination
 */
session_start();
define("BASE_URL", "../../");

require_once __DIR__ . "/../../controller/AuthController.php";
require_once __DIR__ . "/../../controller/KaryawanController.php";

AuthController::checkLogin();

$karyawanController = new KaryawanController();
$model = $karyawanController->getModel();

// Ambil parameter pencarian & filter dari GET
$keyword    = $_GET['keyword'] ?? '';
$departemen = $_GET['departemen'] ?? '';
$jabatan    = $_GET['jabatan'] ?? '';
$status     = $_GET['status'] ?? '';
$page       = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

$result = $karyawanController->getKaryawanList($keyword, $departemen, $jabatan, $status, $page, 5);
$dataKaryawan = $result['data'];
$totalPage    = $result['total_page'];
$currentPage  = $result['current_page'];

$departemenList = $model->getDepartemenList();
$jabatanList    = $model->getJabatanList();

// Notifikasi hasil dari proses tambah/edit/hapus (dikirim lewat query string)
$notif = $_GET['notif'] ?? '';
$notifType = $_GET['notif_type'] ?? '';

$pageTitle = "Data Karyawan";
$activeMenu = "karyawan";

require __DIR__ . "/../partials/header.php";
require __DIR__ . "/../partials/sidebar.php";
?>

<main class="main-content flex-grow-1 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h4 class="fw-bold mb-0"><i class="fa-solid fa-users"></i> Data Karyawan</h4>
        <a href="tambah.php" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> Tambah Data
        </a>
    </div>

    <!-- Form Pencarian & Filter -->
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="index.php" class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="keyword" class="form-control" placeholder="Cari nama karyawan..." value="<?= htmlspecialchars($keyword) ?>">
                </div>
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
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">-- Semua Status --</option>
                        <option value="Aktif" <?= $status === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                        <option value="Non-Aktif" <?= $status === 'Non-Aktif' ? 'selected' : '' ?>>Non-Aktif</option>
                        <option value="Cuti" <?= $status === 'Cuti' ? 'selected' : '' ?>>Cuti</option>
                    </select>
                </div>
                <div class="col-md-1 d-grid">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-search"></i></button>
                </div>
                <?php if ($keyword || $departemen || $jabatan || $status): ?>
                <div class="col-12">
                    <a href="index.php" class="small text-decoration-none"><i class="fa-solid fa-rotate-left"></i> Reset Filter</a>
                </div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Tabel Data -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Foto</th>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Jenis Kelamin</th>
                            <th>Departemen</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($dataKaryawan)): ?>
                        <tr><td colspan="8" class="text-center text-muted py-4">Data karyawan tidak ditemukan.</td></tr>
                        <?php else: ?>
                            <?php foreach ($dataKaryawan as $k): ?>
                            <tr>
                                <td>
                                    <?php if (!empty($k['foto']) && file_exists(__DIR__ . "/../../upload/" . $k['foto'])): ?>
                                    <img src="<?= BASE_URL ?>upload/<?= htmlspecialchars($k['foto']) ?>" class="rounded-circle" width="40" height="40" style="object-fit:cover;">
                                    <?php else: ?>
                                    <div class="avatar-placeholder"><i class="fa-solid fa-user"></i></div>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($k['id']) ?></td>
                                <td><?= htmlspecialchars($k['nama']) ?></td>
                                <td><?= htmlspecialchars($k['jenis_kelamin']) ?></td>
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
                                <td class="text-center">
                                    <a href="detail.php?id=<?= urlencode($k['id']) ?>" class="btn btn-sm btn-outline-info" title="Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="edit.php?id=<?= urlencode($k['id']) ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-hapus"
                                            data-id="<?= htmlspecialchars($k['id']) ?>"
                                            data-nama="<?= htmlspecialchars($k['nama']) ?>" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <?php if ($totalPage > 1): ?>
        <div class="card-footer bg-white">
            <nav>
                <ul class="pagination justify-content-center mb-0">
                    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
                    <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>&keyword=<?= urlencode($keyword) ?>&departemen=<?= urlencode($departemen) ?>&jabatan=<?= urlencode($jabatan) ?>&status=<?= urlencode($status) ?>">
                            <?= $i ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
        <?php endif; ?>
    </div>
</main>

<!-- Form tersembunyi untuk proses hapus -->
<form id="formHapus" action="hapus_proses.php" method="POST" class="d-none">
    <input type="hidden" name="id" id="hapusId">
</form>

<?php require __DIR__ . "/../partials/footer.php"; ?>

<script>
// Notifikasi SweetAlert2 dari hasil aksi tambah/edit/hapus
<?php if ($notif): ?>
Swal.fire({
    icon: '<?= $notifType === 'error' ? 'error' : 'success' ?>',
    title: '<?= $notifType === 'error' ? 'Gagal' : 'Berhasil' ?>',
    text: '<?= addslashes($notif) ?>',
    confirmButtonColor: '#0d6efd',
    timer: 2500,
    timerProgressBar: true
});
<?php endif; ?>

// Konfirmasi hapus data menggunakan SweetAlert2
document.querySelectorAll('.btn-hapus').forEach(function (btn) {
    btn.addEventListener('click', function () {
        const id = this.dataset.id;
        const nama = this.dataset.nama;

        Swal.fire({
            icon: 'warning',
            title: 'Hapus Data?',
            text: 'Anda yakin ingin menghapus data karyawan "' + nama + '"?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('hapusId').value = id;
                document.getElementById('formHapus').submit();
            }
        });
    });
});
</script>
