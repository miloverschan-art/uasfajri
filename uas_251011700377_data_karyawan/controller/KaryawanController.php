<?php
/**
 * Class KaryawanController
 * ---------------------------------------------------------
 * Mengatur seluruh logika CRUD data karyawan, validasi input,
 * upload foto, searching, pagination, dan penyediaan data laporan.
 * ---------------------------------------------------------
 */
require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../model/Karyawan.php";

class KaryawanController
{
    private $db;
    private $karyawanModel;
    private $uploadDir;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->karyawanModel = new Karyawan($this->db);
        $this->uploadDir = __DIR__ . "/../upload/";
    }

    /** Getter model (dipakai view untuk ambil data dropdown, dsb) */
    public function getModel()
    {
        return $this->karyawanModel;
    }

    /**
     * Mengambil data karyawan dengan pencarian & pagination
     */
    public function getKaryawanList($keyword, $departemen, $jabatan, $status, $page, $perPage = 5)
    {
        $offset = ($page - 1) * $perPage;
        $data = $this->karyawanModel->getAll($keyword, $departemen, $jabatan, $status, $perPage, $offset);
        $totalData = $this->karyawanModel->countAll($keyword, $departemen, $jabatan, $status);
        $totalPage = ceil($totalData / $perPage);

        return [
            "data" => $data,
            "total_data" => $totalData,
            "total_page" => $totalPage,
            "current_page" => $page
        ];
    }

    public function getKaryawanById($id)
    {
        return $this->karyawanModel->getById($id);
    }

    /**
     * Validasi umum data input form (tambah/edit)
     * Mengembalikan array berisi pesan error (kosong jika valid)
     */
    private function validate($data)
    {
        $errors = [];

        if (empty($data['nama'])) $errors[] = "Nama wajib diisi.";
        if (empty($data['jenis_kelamin'])) $errors[] = "Jenis kelamin wajib dipilih.";
        if (empty($data['tanggal_lahir'])) $errors[] = "Tanggal lahir wajib diisi.";
        if (empty($data['alamat'])) $errors[] = "Alamat wajib diisi.";
        if (empty($data['telepon'])) $errors[] = "Nomor HP wajib diisi.";
        if (empty($data['email'])) $errors[] = "Email wajib diisi.";
        if (empty($data['departemen'])) $errors[] = "Departemen wajib diisi.";
        if (empty($data['jabatan'])) $errors[] = "Jabatan wajib diisi.";
        if (empty($data['tanggal_masuk'])) $errors[] = "Tanggal masuk wajib diisi.";
        if ($data['gaji'] === "" || $data['gaji'] === null) $errors[] = "Gaji wajib diisi.";
        if (empty($data['status'])) $errors[] = "Status karyawan wajib dipilih.";

        // Validasi format email
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email tidak valid.";
        }

        // Validasi nomor HP hanya angka
        if (!empty($data['telepon']) && !ctype_digit($data['telepon'])) {
            $errors[] = "Nomor HP hanya boleh berisi angka.";
        }

        return $errors;
    }

    /**
     * Proses upload foto karyawan
     * Mengembalikan array ['status' => bool, 'filename' => string|null, 'message' => string]
     */
    private function uploadFoto($file)
    {
        // Jika tidak ada file yang diupload (opsional saat edit)
        if (!isset($file['name']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return ["status" => true, "filename" => null, "message" => ""];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ["status" => false, "filename" => null, "message" => "Terjadi kesalahan saat upload foto."];
        }

        // Validasi ekstensi file
        $allowedExt = ["jpg", "jpeg", "png"];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            return ["status" => false, "filename" => null, "message" => "Format foto harus JPG, JPEG, atau PNG."];
        }

        // Validasi ukuran maksimal 2MB
        $maxSize = 2 * 1024 * 1024;
        if ($file['size'] > $maxSize) {
            return ["status" => false, "filename" => null, "message" => "Ukuran foto maksimal 2 MB."];
        }

        // Buat folder upload jika belum ada
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0777, true);
        }

        // Nama file unik menggunakan uniqid()
        $newFileName = uniqid("foto_") . "." . $ext;
        $targetPath = $this->uploadDir . $newFileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ["status" => true, "filename" => $newFileName, "message" => "Upload berhasil."];
        } else {
            return ["status" => false, "filename" => null, "message" => "Gagal menyimpan file foto."];
        }
    }

    /**
     * Tambah data karyawan baru
     */
    public function tambah($post, $file)
    {
        $errors = $this->validate($post);
        if (!empty($errors)) {
            return ["status" => false, "message" => implode(" ", $errors)];
        }

        // Cek apakah ID sudah ada
        if ($this->karyawanModel->getById($post['id'])) {
            return ["status" => false, "message" => "ID karyawan sudah digunakan."];
        }

        $uploadResult = $this->uploadFoto($file);
        if (!$uploadResult['status']) {
            return ["status" => false, "message" => $uploadResult['message']];
        }

        $data = [
            "id" => $post['id'],
            "nama" => htmlspecialchars($post['nama']),
            "jenis_kelamin" => $post['jenis_kelamin'],
            "tanggal_lahir" => $post['tanggal_lahir'],
            "alamat" => htmlspecialchars($post['alamat']),
            "telepon" => $post['telepon'],
            "email" => $post['email'],
            "departemen" => htmlspecialchars($post['departemen']),
            "jabatan" => htmlspecialchars($post['jabatan']),
            "tanggal_masuk" => $post['tanggal_masuk'],
            "gaji" => $post['gaji'],
            "status" => $post['status'],
            "foto" => $uploadResult['filename']
        ];

        if ($this->karyawanModel->create($data)) {
            return ["status" => true, "message" => "Data karyawan berhasil ditambahkan."];
        } else {
            return ["status" => false, "message" => "Gagal menambahkan data karyawan."];
        }
    }

    /**
     * Update data karyawan
     */
    public function edit($id, $post, $file)
    {
        $errors = $this->validate($post);
        if (!empty($errors)) {
            return ["status" => false, "message" => implode(" ", $errors)];
        }

        $existing = $this->karyawanModel->getById($id);
        if (!$existing) {
            return ["status" => false, "message" => "Data karyawan tidak ditemukan."];
        }

        $uploadResult = $this->uploadFoto($file);
        if (!$uploadResult['status']) {
            return ["status" => false, "message" => $uploadResult['message']];
        }

        // Jika ada foto baru, hapus foto lama
        if ($uploadResult['filename'] && !empty($existing['foto'])) {
            $oldFile = $this->uploadDir . $existing['foto'];
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $data = [
            "nama" => htmlspecialchars($post['nama']),
            "jenis_kelamin" => $post['jenis_kelamin'],
            "tanggal_lahir" => $post['tanggal_lahir'],
            "alamat" => htmlspecialchars($post['alamat']),
            "telepon" => $post['telepon'],
            "email" => $post['email'],
            "departemen" => htmlspecialchars($post['departemen']),
            "jabatan" => htmlspecialchars($post['jabatan']),
            "tanggal_masuk" => $post['tanggal_masuk'],
            "gaji" => $post['gaji'],
            "status" => $post['status'],
            "foto" => $uploadResult['filename']
        ];

        if ($this->karyawanModel->update($id, $data)) {
            return ["status" => true, "message" => "Data karyawan berhasil diperbarui."];
        } else {
            return ["status" => false, "message" => "Gagal memperbarui data karyawan."];
        }
    }

    /**
     * Hapus data karyawan beserta foto
     */
    public function hapus($id)
    {
        $existing = $this->karyawanModel->getById($id);
        if (!$existing) {
            return ["status" => false, "message" => "Data karyawan tidak ditemukan."];
        }

        // Hapus file foto jika ada
        if (!empty($existing['foto'])) {
            $file = $this->uploadDir . $existing['foto'];
            if (file_exists($file)) {
                unlink($file);
            }
        }

        if ($this->karyawanModel->delete($id)) {
            return ["status" => true, "message" => "Data karyawan berhasil dihapus."];
        } else {
            return ["status" => false, "message" => "Gagal menghapus data karyawan."];
        }
    }
}
