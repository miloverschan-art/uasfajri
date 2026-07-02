<?php
/**
 * Class Karyawan (Model)
 * ---------------------------------------------------------
 * Menangani seluruh operasi data karyawan:
 * - CRUD (Create, Read, Update, Delete)
 * - Searching + Pagination
 * - Statistik untuk Dashboard
 * - Data untuk Laporan (dengan filter)
 * ---------------------------------------------------------
 */
class Karyawan
{
    private $conn;                 // Koneksi database (encapsulation)
    private $table = "karyawan";   // Nama tabel

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Mengambil seluruh data karyawan dengan fitur pencarian & pagination
     * @param string $keyword kata kunci pencarian (nama)
     * @param string $departemen filter departemen
     * @param string $jabatan filter jabatan
     * @param string $status filter status
     * @param int $limit jumlah data per halaman
     * @param int $offset offset data
     */
    public function getAll($keyword = "", $departemen = "", $jabatan = "", $status = "", $limit = null, $offset = 0)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $query .= " AND nama LIKE :keyword";
            $params[":keyword"] = "%" . $keyword . "%";
        }
        if (!empty($departemen)) {
            $query .= " AND departemen = :departemen";
            $params[":departemen"] = $departemen;
        }
        if (!empty($jabatan)) {
            $query .= " AND jabatan = :jabatan";
            $params[":jabatan"] = $jabatan;
        }
        if (!empty($status)) {
            $query .= " AND status = :status";
            $params[":status"] = $status;
        }

        $query .= " ORDER BY created_at DESC";

        if ($limit !== null) {
            $query .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->conn->prepare($query);

        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        if ($limit !== null) {
            $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(":offset", (int)$offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Menghitung total data hasil pencarian (untuk pagination)
     */
    public function countAll($keyword = "", $departemen = "", $jabatan = "", $status = "")
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $query .= " AND nama LIKE :keyword";
            $params[":keyword"] = "%" . $keyword . "%";
        }
        if (!empty($departemen)) {
            $query .= " AND departemen = :departemen";
            $params[":departemen"] = $departemen;
        }
        if (!empty($jabatan)) {
            $query .= " AND jabatan = :jabatan";
            $params[":jabatan"] = $jabatan;
        }
        if (!empty($status)) {
            $query .= " AND status = :status";
            $params[":status"] = $status;
        }

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $val) {
            $stmt->bindValue($key, $val);
        }
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }

    /**
     * Mengambil satu data karyawan berdasarkan ID
     */
    public function getById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Menambah data karyawan baru
     */
    public function create($data)
    {
        $query = "INSERT INTO " . $this->table . "
                  (id, nama, jenis_kelamin, tanggal_lahir, alamat, telepon, email, departemen, jabatan, tanggal_masuk, gaji, status, foto, created_at)
                  VALUES
                  (:id, :nama, :jenis_kelamin, :tanggal_lahir, :alamat, :telepon, :email, :departemen, :jabatan, :tanggal_masuk, :gaji, :status, :foto, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $data['id']);
        $stmt->bindParam(":nama", $data['nama']);
        $stmt->bindParam(":jenis_kelamin", $data['jenis_kelamin']);
        $stmt->bindParam(":tanggal_lahir", $data['tanggal_lahir']);
        $stmt->bindParam(":alamat", $data['alamat']);
        $stmt->bindParam(":telepon", $data['telepon']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":departemen", $data['departemen']);
        $stmt->bindParam(":jabatan", $data['jabatan']);
        $stmt->bindParam(":tanggal_masuk", $data['tanggal_masuk']);
        $stmt->bindParam(":gaji", $data['gaji']);
        $stmt->bindParam(":status", $data['status']);
        $stmt->bindParam(":foto", $data['foto']);
        return $stmt->execute();
    }

    /**
     * Mengupdate data karyawan berdasarkan ID
     */
    public function update($id, $data)
    {
        $query = "UPDATE " . $this->table . " SET
                    nama = :nama,
                    jenis_kelamin = :jenis_kelamin,
                    tanggal_lahir = :tanggal_lahir,
                    alamat = :alamat,
                    telepon = :telepon,
                    email = :email,
                    departemen = :departemen,
                    jabatan = :jabatan,
                    tanggal_masuk = :tanggal_masuk,
                    gaji = :gaji,
                    status = :status";

        // Foto hanya diupdate jika ada file baru
        if (!empty($data['foto'])) {
            $query .= ", foto = :foto";
        }

        $query .= " WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":nama", $data['nama']);
        $stmt->bindParam(":jenis_kelamin", $data['jenis_kelamin']);
        $stmt->bindParam(":tanggal_lahir", $data['tanggal_lahir']);
        $stmt->bindParam(":alamat", $data['alamat']);
        $stmt->bindParam(":telepon", $data['telepon']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":departemen", $data['departemen']);
        $stmt->bindParam(":jabatan", $data['jabatan']);
        $stmt->bindParam(":tanggal_masuk", $data['tanggal_masuk']);
        $stmt->bindParam(":gaji", $data['gaji']);
        $stmt->bindParam(":status", $data['status']);

        if (!empty($data['foto'])) {
            $stmt->bindParam(":foto", $data['foto']);
        }

        return $stmt->execute();
    }

    /**
     * Menghapus data karyawan berdasarkan ID
     */
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    /**
     * Menghitung total seluruh karyawan (untuk dashboard)
     */
    public function getTotalKaryawan()
    {
        $query = "SELECT COUNT(*) as total FROM " . $this->table;
        $stmt = $this->conn->query($query);
        return $stmt->fetch()['total'];
    }

    /**
     * Menghitung total departemen unik (untuk dashboard)
     */
    public function getTotalDepartemen()
    {
        $query = "SELECT COUNT(DISTINCT departemen) as total FROM " . $this->table;
        $stmt = $this->conn->query($query);
        return $stmt->fetch()['total'];
    }

    /**
     * Menghitung total jabatan unik (untuk dashboard)
     */
    public function getTotalJabatan()
    {
        $query = "SELECT COUNT(DISTINCT jabatan) as total FROM " . $this->table;
        $stmt = $this->conn->query($query);
        return $stmt->fetch()['total'];
    }

    /**
     * Mengambil 5 data karyawan terbaru (untuk dashboard)
     */
    public function getLatest($limit = 5)
    {
        $query = "SELECT * FROM " . $this->table . " ORDER BY created_at DESC LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":limit", (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Mengambil daftar departemen unik (untuk dropdown filter)
     */
    public function getDepartemenList()
    {
        $query = "SELECT DISTINCT departemen FROM " . $this->table . " ORDER BY departemen ASC";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Mengambil daftar jabatan unik (untuk dropdown filter)
     */
    public function getJabatanList()
    {
        $query = "SELECT DISTINCT jabatan FROM " . $this->table . " ORDER BY jabatan ASC";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Mengambil data untuk laporan dengan filter (tanpa pagination)
     */
    public function getForReport($departemen = "", $jabatan = "", $status = "")
    {
        return $this->getAll("", $departemen, $jabatan, $status, null, 0);
    }
}
