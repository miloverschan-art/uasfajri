<?php
/**
 * Class Database
 * ---------------------------------------------------------
 * Class ini bertugas untuk membuat koneksi ke database MySQL
 * menggunakan PDO (PHP Data Objects).
 * Menerapkan konsep OOP: Encapsulation (properti private)
 * dan Constructor.
 * ---------------------------------------------------------
 * Project : uas_251011700377_data_karyawan
 */
class Database
{
    // Properti koneksi database (private = encapsulation)
    private $host = "localhost";
    private $db_name = "db_karyawan";
    private $username = "root";
    private $password = "";
    private $charset = "utf8mb4";
    private $conn;

    /**
     * Method untuk membuat dan mengembalikan koneksi PDO
     * @return PDO
     */
    public function getConnection()
    {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=" . $this->charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            // Jika koneksi gagal, hentikan aplikasi dengan pesan error yang jelas
            die("Koneksi database gagal: " . $e->getMessage() .
                "<br>Pastikan MySQL di XAMPP sudah berjalan dan database 'db_karyawan' sudah diimport.");
        }

        return $this->conn;
    }
}
