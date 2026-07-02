<?php
/**
 * view/karyawan/edit.php
 * Form edit data karyawan berdasarkan ID
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

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $karyawanController->edit($id, $_POST, $_FILES['foto'] ?? []);

    if ($result['status']) {
        header("Location: index.php?notif=" . urlencode($result['message']) . "&notif_type=success");
        exit;
    } else {
        $message = $result['message'];
        // Refresh data agar form tetap menampilkan input lama jika gagal
        $karyawan = array_merge($karyawan, $_POST);
    }
}

$pageTitle = "Edit Data Karyawan";
$activeMenu = "karyawan";

require __DIR__ . "/../partials/header.php";
require __DIR__ . "/../partials/sidebar.php";
?>

<main class="main-content flex-grow-1 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0"><i class="fa-solid fa-pen"></i> Edit Data Karyawan</h4>
        <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="edit.php?id=<?= urlencode($id) ?>" enctype="multipart/form-data" id="formEdit">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">ID Karyawan</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($karyawan['id']) ?>" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" required
                               value="<?= htmlspecialchars($karyawan['nama']) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="Laki-laki" <?= $karyawan['jenis_kelamin'] === 'Laki-laki' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="Perempuan" <?= $karyawan['jenis_kelamin'] === 'Perempuan' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_lahir" class="form-control" required
                               value="<?= htmlspecialchars($karyawan['tanggal_lahir']) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nomor HP <span class="text-danger">*</span></label>
                        <input type="text" name="telepon" class="form-control" pattern="[0-9]+" title="Hanya boleh angka" required
                               value="<?= htmlspecialchars($karyawan['telepon']) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" required
                               value="<?= htmlspecialchars($karyawan['email']) ?>">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea name="alamat" class="form-control" rows="2" required><?= htmlspecialchars($karyawan['alamat']) ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Foto</label>
                        <?php if (!empty($karyawan['foto']) && file_exists(__DIR__ . "/../../upload/" . $karyawan['foto'])): ?>
                        <div class="mb-2">
                            <img src="<?= BASE_URL ?>upload/<?= htmlspecialchars($karyawan['foto']) ?>" width="60" height="60" class="rounded" style="object-fit:cover;">
                        </div>
                        <?php endif; ?>
                        <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Departemen <span class="text-danger">*</span></label>
                        <input type="text" name="departemen" class="form-control" required
                               value="<?= htmlspecialchars($karyawan['departemen']) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" name="jabatan" class="form-control" required
                               value="<?= htmlspecialchars($karyawan['jabatan']) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_masuk" class="form-control" required
                               value="<?= htmlspecialchars($karyawan['tanggal_masuk']) ?>">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Gaji (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="gaji" class="form-control" min="0" step="1000" required
                               value="<?= htmlspecialchars($karyawan['gaji']) ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status Karyawan <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="Aktif" <?= $karyawan['status'] === 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                            <option value="Non-Aktif" <?= $karyawan['status'] === 'Non-Aktif' ? 'selected' : '' ?>>Non-Aktif</option>
                            <option value="Cuti" <?= $karyawan['status'] === 'Cuti' ? 'selected' : '' ?>>Cuti</option>
                        </select>
                    </div>
                </div>

                <hr>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Update Data</button>
                    <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require __DIR__ . "/../partials/footer.php"; ?>

<script>
<?php if (!empty($message)): ?>
Swal.fire({
    icon: 'error',
    title: 'Gagal Memperbarui',
    text: '<?= addslashes($message) ?>',
    confirmButtonColor: '#0d6efd'
});
<?php endif; ?>

document.getElementById('formEdit').addEventListener('submit', function (e) {
    e.preventDefault();
    const form = this;
    Swal.fire({
        icon: 'question',
        title: 'Update Data Karyawan?',
        text: 'Perubahan akan disimpan ke database.',
        showCancelButton: true,
        confirmButtonText: 'Ya, Update',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#0d6efd'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
</script>
