<?php
/**
 * view/karyawan/tambah.php
 * Form tambah data karyawan baru
 */
session_start();
define("BASE_URL", "../../");

require_once __DIR__ . "/../../controller/AuthController.php";
require_once __DIR__ . "/../../controller/KaryawanController.php";

AuthController::checkLogin();

$karyawanController = new KaryawanController();
$message = "";
$messageType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $karyawanController->tambah($_POST, $_FILES['foto'] ?? []);

    if ($result['status']) {
        header("Location: index.php?notif=" . urlencode($result['message']) . "&notif_type=success");
        exit;
    } else {
        $message = $result['message'];
        $messageType = "error";
    }
}

$pageTitle = "Tambah Data Karyawan";
$activeMenu = "karyawan";

require __DIR__ . "/../partials/header.php";
require __DIR__ . "/../partials/sidebar.php";
?>

<main class="main-content flex-grow-1 p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0"><i class="fa-solid fa-user-plus"></i> Tambah Data Karyawan</h4>
        <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="POST" action="tambah.php" enctype="multipart/form-data" id="formTambah">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">ID Karyawan <span class="text-danger">*</span></label>
                        <input type="text" name="id" class="form-control" placeholder="Contoh: 251011700382" required
                               value="<?= isset($_POST['id']) ? htmlspecialchars($_POST['id']) : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama karyawan" required
                               value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_lahir" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nomor HP <span class="text-danger">*</span></label>
                        <input type="text" name="telepon" class="form-control" placeholder="08xxxxxxxxxx" pattern="[0-9]+" title="Hanya boleh angka" required
                               value="<?= isset($_POST['telepon']) ? htmlspecialchars($_POST['telepon']) : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control" placeholder="nama@email.com" required
                               value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Alamat <span class="text-danger">*</span></label>
                        <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap" required><?= isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : '' ?></textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Foto</label>
                        <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png">
                        <small class="text-muted">Format JPG/JPEG/PNG, maksimal 2 MB.</small>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Departemen <span class="text-danger">*</span></label>
                        <input type="text" name="departemen" class="form-control" placeholder="Contoh: IT" required
                               value="<?= isset($_POST['departemen']) ? htmlspecialchars($_POST['departemen']) : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                        <input type="text" name="jabatan" class="form-control" placeholder="Contoh: Staff" required
                               value="<?= isset($_POST['jabatan']) ? htmlspecialchars($_POST['jabatan']) : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_masuk" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Gaji (Rp) <span class="text-danger">*</span></label>
                        <input type="number" name="gaji" class="form-control" placeholder="0" min="0" step="1000" required
                               value="<?= isset($_POST['gaji']) ? htmlspecialchars($_POST['gaji']) : '' ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status Karyawan <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="">-- Pilih --</option>
                            <option value="Aktif">Aktif</option>
                            <option value="Non-Aktif">Non-Aktif</option>
                            <option value="Cuti">Cuti</option>
                        </select>
                    </div>
                </div>

                <hr>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Data</button>
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
    title: 'Gagal Menyimpan',
    text: '<?= addslashes($message) ?>',
    confirmButtonColor: '#0d6efd'
});
<?php endif; ?>

// Konfirmasi sebelum menyimpan data
document.getElementById('formTambah').addEventListener('submit', function (e) {
    e.preventDefault();
    const form = this;
    Swal.fire({
        icon: 'question',
        title: 'Simpan Data Karyawan?',
        text: 'Pastikan data yang Anda masukkan sudah benar.',
        showCancelButton: true,
        confirmButtonText: 'Ya, Simpan',
        cancelButtonText: 'Periksa Lagi',
        confirmButtonColor: '#0d6efd'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
</script>
