<?php
/**
 * view/report/export_pdf.php
 * Export laporan data karyawan ke format PDF
 * Menggunakan library ringan report/fpdf/fpdf.php (gaya FPDF, tanpa Composer/DomPDF)
 */
session_start();
require_once __DIR__ . "/../../controller/AuthController.php";
require_once __DIR__ . "/../../controller/KaryawanController.php";
require_once __DIR__ . "/../../report/fpdf/fpdf.php";

AuthController::checkLogin();

$karyawanController = new KaryawanController();
$model = $karyawanController->getModel();

$departemen = $_GET['departemen'] ?? '';
$jabatan    = $_GET['jabatan'] ?? '';
$status     = $_GET['status'] ?? '';

$dataLaporan = $model->getForReport($departemen, $jabatan, $status);

// Inisialisasi PDF
$pdf = new SimpleFPDF();
$pdf->AddPage();

// Logo sederhana (kotak biru dengan inisial)
$pdf->DrawLogoBox(10, 10, 15);

// Judul laporan
$pdf->SetXY(30, 10);
$pdf->SetFont("Helvetica", "B", 14);
$pdf->SetTextColor(20, 20, 20);
$pdf->Cell(150, 8, "Laporan Data Karyawan", 0, 1, "L");

$pdf->SetXY(30, 18);
$pdf->SetFont("Helvetica", "", 10);
$pdf->SetTextColor(90, 90, 90);
$pdf->Cell(150, 6, "Tanggal Cetak: " . date("d-m-Y H:i"), 0, 1, "L");

// Garis pemisah
$pdf->SetDrawColor(180, 180, 180);
$pdf->Line(10, 30, 200, 30);

// Header tabel
$pdf->SetXY(10, 36);
$pdf->SetFont("Helvetica", "B", 9);
$pdf->SetTextColor(255, 255, 255);
$pdf->SetFillColor(13, 110, 253);

$colWidths = [10, 25, 35, 25, 30, 30, 25]; // No, ID, Nama, Departemen, Jabatan, Status, Gaji
$headers = ["No", "ID", "Nama", "Departemen", "Jabatan", "Status", "Gaji"];

foreach ($headers as $i => $h) {
    $pdf->Cell($colWidths[$i], 8, $h, 1, 0, "C", true);
}
$pdf->Ln(8);

// Isi tabel
$pdf->SetFont("Helvetica", "", 8);
$pdf->SetTextColor(30, 30, 30);
$pdf->SetFillColor(245, 245, 245);

$no = 1;
$fill = false;
foreach ($dataLaporan as $k) {
    // Cek apakah perlu halaman baru
    if ($pdf->GetY() > 275) {
        $pdf->AddPage();
        $pdf->SetXY(10, 10);
        $pdf->SetFont("Helvetica", "B", 9);
        $pdf->SetFillColor(13, 110, 253);
        $pdf->SetTextColor(255, 255, 255);
        foreach ($headers as $i => $h) {
            $pdf->Cell($colWidths[$i], 8, $h, 1, 0, "C", true);
        }
        $pdf->Ln(8);
        $pdf->SetFont("Helvetica", "", 8);
        $pdf->SetTextColor(30, 30, 30);
    }

    $pdf->SetX(10);
    $pdf->Cell($colWidths[0], 7, $no++, 1, 0, "C", $fill);
    $pdf->Cell($colWidths[1], 7, $k['id'], 1, 0, "C", $fill);
    $pdf->Cell($colWidths[2], 7, $k['nama'], 1, 0, "L", $fill);
    $pdf->Cell($colWidths[3], 7, $k['departemen'], 1, 0, "L", $fill);
    $pdf->Cell($colWidths[4], 7, $k['jabatan'], 1, 0, "L", $fill);
    $pdf->Cell($colWidths[5], 7, $k['status'], 1, 0, "C", $fill);
    $pdf->Cell($colWidths[6], 7, number_format($k['gaji'], 0, ',', '.'), 1, 0, "R", $fill);
    $pdf->Ln(7);

    $fill = !$fill;
}

// Output PDF ke browser
$pdf->Output("I", "laporan_karyawan_" . date("Ymd_His") . ".pdf");
