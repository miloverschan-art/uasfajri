<?php
/**
 * Class User (Model)
 * ---------------------------------------------------------
 * Menangani seluruh operasi data terkait user/akun:
 * - Cek username saat login
 * - Simpan user baru saat register
 * - Cek apakah username sudah dipakai
 * ---------------------------------------------------------
 */
class User
{
    private $conn;      // Properti koneksi database (encapsulation)
    private $table = "users";

    // Constructor menerima koneksi PDO dari luar (dependency injection sederhana)
    public function __construct($db)
    {
        $this->conn = $db;
    }

    /**
     * Mengambil data user berdasarkan username
     * Digunakan untuk proses login
     */
    public function getByUsername($username)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Mengecek apakah username sudah terdaftar (untuk validasi register)
     */
    public function isUsernameExists($username)
    {
        $query = "SELECT id FROM " . $this->table . " WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    /**
     * Menyimpan user baru ke database (register)
     * Password sudah dalam bentuk hash saat dipanggil
     */
    public function register($username, $hashedPassword, $role = "admin")
    {
        $query = "INSERT INTO " . $this->table . " (username, password, role, created_at)
                  VALUES (:username, :password, :role, NOW())";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":role", $role);
        return $stmt->execute();
    }
}
