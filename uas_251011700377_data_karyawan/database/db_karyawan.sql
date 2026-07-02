-- =========================================================
-- Database: db_karyawan
-- Project  : uas_251011700377_data_karyawan
-- NIM      : 251011700377
-- Deskripsi: Sistem Informasi Data Karyawan (UAS Pemrograman Web 2)
-- =========================================================

CREATE DATABASE IF NOT EXISTS db_karyawan CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE db_karyawan;

-- =========================================================
-- Tabel users (untuk login & register)
-- =========================================================
CREATE TABLE IF NOT EXISTS users (
    id INT(11) NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Akun default login:
-- Username : admin
-- Password : password
-- (password sudah di-hash menggunakan password_hash() PHP - algoritma BCRYPT)
INSERT INTO users (username, password, role, created_at) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW());
-- Jika hash di atas tidak cocok pada environment Anda, silakan buat akun baru melalui halaman Register.

-- =========================================================
-- Tabel karyawan
-- =========================================================
CREATE TABLE IF NOT EXISTS karyawan (
    id VARCHAR(20) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('Laki-laki','Perempuan') NOT NULL,
    tanggal_lahir DATE NOT NULL,
    alamat TEXT NOT NULL,
    telepon VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL,
    departemen VARCHAR(50) NOT NULL,
    jabatan VARCHAR(50) NOT NULL,
    tanggal_masuk DATE NOT NULL,
    gaji DECIMAL(15,2) NOT NULL DEFAULT 0,
    status ENUM('Aktif','Non-Aktif','Cuti') NOT NULL DEFAULT 'Aktif',
    foto VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =========================================================
-- Data awal karyawan (minimal 5 data)
-- Data pertama menggunakan ID sesuai NIM: 251011700377
-- =========================================================
INSERT INTO karyawan
(id, nama, jenis_kelamin, tanggal_lahir, alamat, telepon, email, departemen, jabatan, tanggal_masuk, gaji, status, foto, created_at)
VALUES
('251011700377', 'fajri ahmad', 'Laki-laki', '1998-05-14', 'Jl. Merdeka No. 10, Tangerang Selatan', '081234567377', 'fajri.ahmad@perusahaan.com', 'IT', 'Programmer', '2021-01-10', 6500000.00, 'Aktif', NULL, NOW()),
('251011700378', 'Siti Nurhaliza', 'Perempuan', '1996-08-22', 'Jl. Kenanga No. 5, Jakarta Selatan', '081234567378', 'siti.nurhaliza@perusahaan.com', 'Human Resource', 'HR Staff', '2020-03-15', 5500000.00, 'Aktif', NULL, NOW()),
('251011700379', 'Budi Santoso', 'Laki-laki', '1993-11-02', 'Jl. Sudirman No. 21, Jakarta Pusat', '081234567379', 'budi.santoso@perusahaan.com', 'Finance', 'Staff Keuangan', '2019-07-01', 6000000.00, 'Aktif', NULL, NOW()),
('251011700380', 'Dewi Lestari', 'Perempuan', '1999-02-18', 'Jl. Diponegoro No. 8, Depok', '081234567380', 'dewi.lestari@perusahaan.com', 'Marketing', 'Marketing Executive', '2022-05-20', 5800000.00, 'Cuti', NULL, NOW()),
('251011700381', 'Rizky Ramadhan', 'Laki-laki', '1995-12-30', 'Jl. Ahmad Yani No. 3, Bekasi', '081234567381', 'rizky.ramadhan@perusahaan.com', 'IT', 'Network Engineer', '2018-09-12', 7000000.00, 'Non-Aktif', NULL, NOW());
