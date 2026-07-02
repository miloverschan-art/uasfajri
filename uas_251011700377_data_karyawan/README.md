# Sistem Informasi Data Karyawan

**Project**: uas_251011700377_data_karyawan
**NIM**: 251011700377
**Mata Kuliah**: Pemrograman Web 2 (UAS)
**Kategori**: Data Karyawan

## Teknologi

- PHP Native (OOP, tanpa Framework, tanpa Composer)
- MySQL (PDO)
- Bootstrap 5 (CDN)
- Font Awesome (CDN)
- SweetAlert2 (CDN)
- JavaScript murni

## Cara Menjalankan di XAMPP

1. Copy folder `uas_251011700377_data_karyawan` ke dalam `C:\xampp\htdocs\`.
2. Jalankan **Apache** dan **MySQL** melalui XAMPP Control Panel.
3. Buka **phpMyAdmin** (`http://localhost/phpmyadmin`).
4. Buat/Import database dengan cara:
   - Klik **Import**
   - Pilih file `database/db_karyawan.sql`
   - Klik **Go** / **Import**
   - Database `db_karyawan` beserta tabel `users` dan `karyawan` (5 data awal) akan otomatis terbuat.
5. Buka browser, akses:
   ```
   http://localhost/uas_251011700377_data_karyawan/
   ```
6. Login menggunakan akun default:
   - **Username**: `admin`
   - **Password**: `password`

   Jika hash password default tidak cocok pada environment Anda, silakan buat akun baru melalui halaman **Register**.

## Struktur Folder

```
uas_251011700377_data_karyawan/
├── assets/
│   ├── css/style.css
│   ├── js/script.js
│   └── img/
├── config/
│   └── Database.php          -> Koneksi PDO ke MySQL
├── controller/
│   ├── AuthController.php    -> Login, Register, Logout
│   └── KaryawanController.php-> CRUD, Upload Foto, Validasi
├── model/
│   ├── User.php
│   └── Karyawan.php
├── view/
│   ├── login.php  (tampilan login berada di index.php root)
│   ├── register.php
│   ├── dashboard.php
│   ├── partials/              -> header, sidebar, footer (reusable)
│   ├── karyawan/
│   │   ├── index.php          -> List + Search + Filter + Pagination
│   │   ├── tambah.php
│   │   ├── edit.php
│   │   ├── detail.php
│   │   └── hapus_proses.php   -> Endpoint proses hapus
│   └── report/
│       ├── laporan.php
│       ├── export_pdf.php
│       └── export_excel.php
├── report/
│   └── fpdf/fpdf.php          -> Library PDF ringan (custom, gaya FPDF)
├── upload/                    -> Folder penyimpanan foto karyawan
├── database/
│   └── db_karyawan.sql
├── index.php                  -> Entry point (Login)
├── logout.php
└── README.md
```

## Fitur

- **Login & Register** dengan Session, `password_hash()` & `password_verify()`.
- **Dashboard**: Total Karyawan, Total Departemen, Total Jabatan, Tabel 5 Data Terbaru.
- **CRUD Data Karyawan**: Tambah, Edit, Hapus (dengan konfirmasi SweetAlert2), Detail.
- **Upload Foto**: validasi JPG/JPEG/PNG, maksimal 2MB, nama file unik (`uniqid()`).
- **Searching & Filter**: berdasarkan Nama, Departemen, Jabatan, Status + Pagination.
- **Laporan Karyawan**: filter Departemen/Jabatan/Status, tombol Cetak, Export PDF, Export Excel.
- **Export PDF**: menggunakan library PDF ringan buatan sendiri (`report/fpdf/fpdf.php`), tanpa Composer/DomPDF.
- **Export Excel**: PHP native, header `application/vnd.ms-excel`, tanpa PhpSpreadsheet.

## Catatan tentang Export PDF

Karena project ini tidak diperbolehkan menggunakan Composer, sementara library FPDF resmi
umumnya diunduh melalui repository/Composer, maka disediakan **library PDF ringan buatan sendiri**
di `report/fpdf/fpdf.php` dengan gaya pemakaian yang mirip FPDF (`AddPage()`, `SetFont()`, `Cell()`,
`Ln()`, `Output()`). Library ini membangun struktur file PDF secara manual menggunakan font
standar (Helvetica) sehingga tidak memerlukan file font tambahan dan tetap 100% PHP native,
cukup disalin ke dalam folder project sesuai ketentuan tugas.

## Akun Default

| Username | Password | Role  |
|----------|----------|-------|
| admin    | password | admin |

## Data Karyawan Awal

ID pertama menggunakan NIM: **251011700377** (Ahmad Fauzi - Departemen IT).
Total 5 data awal sudah tersedia pada file `database/db_karyawan.sql`.
