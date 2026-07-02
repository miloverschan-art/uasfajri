<?php
/**
 * Class AuthController
 * ---------------------------------------------------------
 * Mengatur logika autentikasi: login, register, dan logout.
 * Menggunakan Session untuk menyimpan status login user.
 * ---------------------------------------------------------
 */
require_once __DIR__ . "/../config/Database.php";
require_once __DIR__ . "/../model/User.php";

class AuthController
{
    private $db;
    private $userModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->userModel = new User($this->db);
    }

    /**
     * Proses login user
     * Mengembalikan array ['status' => bool, 'message' => string]
     */
    public function login($username, $password)
    {
        $username = trim($username);

        if (empty($username) || empty($password)) {
            return ["status" => false, "message" => "Username dan password wajib diisi."];
        }

        $user = $this->userModel->getByUsername($username);

        if (!$user) {
            return ["status" => false, "message" => "Username tidak ditemukan."];
        }

        // Verifikasi password menggunakan password_verify()
        if (!password_verify($password, $user['password'])) {
            return ["status" => false, "message" => "Password salah."];
        }

        // Set session
        $_SESSION['user_id']  = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role']     = $user['role'];
        $_SESSION['logged_in'] = true;

        return ["status" => true, "message" => "Login berhasil."];
    }

    /**
     * Proses register user baru
     */
    public function register($username, $password, $confirmPassword)
    {
        $username = trim($username);

        // Validasi field kosong
        if (empty($username) || empty($password) || empty($confirmPassword)) {
            return ["status" => false, "message" => "Semua field wajib diisi."];
        }

        // Validasi panjang password minimal 8 karakter
        if (strlen($password) < 8) {
            return ["status" => false, "message" => "Password minimal 8 karakter."];
        }

        // Validasi konfirmasi password
        if ($password !== $confirmPassword) {
            return ["status" => false, "message" => "Konfirmasi password tidak sesuai."];
        }

        // Validasi username tidak boleh sama / sudah dipakai
        if ($this->userModel->isUsernameExists($username)) {
            return ["status" => false, "message" => "Username sudah digunakan, silakan pilih username lain."];
        }

        // Hash password menggunakan password_hash()
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $success = $this->userModel->register($username, $hashedPassword);

        if ($success) {
            return ["status" => true, "message" => "Registrasi berhasil, silakan login."];
        } else {
            return ["status" => false, "message" => "Registrasi gagal, silakan coba lagi."];
        }
    }

    /**
     * Proses logout: hancurkan session
     */
    public function logout()
    {
        $_SESSION = [];
        session_unset();
        session_destroy();
    }

    /**
     * Cek apakah user sudah login, jika belum redirect ke halaman login
     * Dipanggil di setiap halaman yang memerlukan autentikasi
     */
    public static function checkLogin()
    {
        if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header("Location: " . self::baseUrl() . "index.php");
            exit;
        }
    }

    /**
     * Helper untuk mendapatkan base URL project (sederhana)
     */
    public static function baseUrl()
    {
        return "";
    }
}
