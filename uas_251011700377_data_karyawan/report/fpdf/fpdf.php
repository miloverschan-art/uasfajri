<?php
/**
 * Class SimpleFPDF
 * ---------------------------------------------------------
 * Library pembuat file PDF sederhana, murni PHP native,
 * TANPA Composer dan TANPA DomPDF.
 * Ditulis dengan gaya penggunaan yang mirip FPDF
 * (AddPage, SetFont, Cell, Ln, Output) agar mudah dipahami
 * dan cukup disalin ke dalam folder project.
 *
 * Class ini membangun struktur file PDF (PDF 1.4) secara manual
 * menggunakan font standar 14 (Helvetica) yang built-in di
 * setiap PDF reader, sehingga tidak memerlukan file font tambahan.
 * ---------------------------------------------------------
 */
class SimpleFPDF
{
    private $pages = [];
    private $currentPageContent = "";
    private $pageOpen = false;

    private $x = 10;
    private $y = 10;
    private $pageWidth = 210;   // A4 mm
    private $pageHeight = 297;  // A4 mm

    private $fontFamily = "Helvetica";
    private $fontStyle = "";
    private $fontSize = 12;

    private $fillColor = [255, 255, 255];
    private $textColor = [0, 0, 0];
    private $drawColor = [0, 0, 0];

    const MM_TO_PT = 2.83464567; // 1 mm = 2.83464567 pt

    public function __construct()
    {
        // Konstruktor kosong, siap membuat halaman baru
    }

    /** Menambah halaman baru */
    public function AddPage()
    {
        if ($this->pageOpen) {
            $this->pages[] = $this->currentPageContent;
        }
        $this->currentPageContent = "";
        $this->pageOpen = true;
        $this->x = 10;
        $this->y = 10;
    }

    /** Mengatur font aktif */
    public function SetFont($family = "Helvetica", $style = "", $size = 12)
    {
        $this->fontFamily = $family;
        $this->fontStyle = $style;
        $this->fontSize = $size;
    }

    /** Mengatur warna teks (RGB 0-255) */
    public function SetTextColor($r, $g, $b)
    {
        $this->textColor = [$r, $g, $b];
    }

    /** Mengatur warna isi (fill) untuk cell */
    public function SetFillColor($r, $g, $b)
    {
        $this->fillColor = [$r, $g, $b];
    }

    /** Mengatur warna garis */
    public function SetDrawColor($r, $g, $b)
    {
        $this->drawColor = [$r, $g, $b];
    }

    /** Set posisi X, Y secara manual */
    public function SetXY($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function GetX() { return $this->x; }
    public function GetY() { return $this->y; }
    public function SetX($x) { $this->x = $x; }
    public function SetY($y) { $this->y = $y; }

    /** Pindah baris baru */
    public function Ln($h = 6)
    {
        $this->y += $h;
        $this->x = 10;
    }

    /**
     * Menulis satu cell/kotak berisi teks (mirip FPDF Cell())
     * @param float $w lebar (mm), 0 = sisa lebar halaman
     * @param float $h tinggi (mm)
     * @param string $txt isi teks
     * @param int $border 0/1 garis kotak
     * @param int $ln 0=tetap,1=pindah baris bawah,2=samping
     * @param string $align L/C/R
     * @param bool $fill isi warna latar
     */
    public function Cell($w, $h, $txt = "", $border = 0, $ln = 0, $align = "L", $fill = false)
    {
        if ($w == 0) {
            $w = $this->pageWidth - $this->x - 10;
        }

        $ptX = $this->x * self::MM_TO_PT;
        $ptY = ($this->pageHeight - $this->y - $h) * self::MM_TO_PT;
        $ptW = $w * self::MM_TO_PT;
        $ptH = $h * self::MM_TO_PT;

        $ops = "";

        // Gambar kotak fill
        if ($fill) {
            $ops .= sprintf("%.3f %.3f %.3f rg\n", $this->fillColor[0] / 255, $this->fillColor[1] / 255, $this->fillColor[2] / 255);
            $ops .= sprintf("%.2f %.2f %.2f %.2f re f\n", $ptX, $ptY, $ptW, $ptH);
        }

        // Gambar border
        if ($border) {
            $ops .= sprintf("%.3f %.3f %.3f RG\n", $this->drawColor[0] / 255, $this->drawColor[1] / 255, $this->drawColor[2] / 255);
            $ops .= "0.3 w\n";
            $ops .= sprintf("%.2f %.2f %.2f %.2f re S\n", $ptX, $ptY, $ptW, $ptH);
        }

        // Tulis teks
        if ($txt !== "") {
            $fontCode = $this->getFontCode();
            $textWidthApprox = strlen($txt) * $this->fontSize * 0.5;
            $tx = $ptX + 2;
            if ($align === "C") {
                $tx = $ptX + ($ptW - $textWidthApprox) / 2;
            } elseif ($align === "R") {
                $tx = $ptX + $ptW - $textWidthApprox - 2;
            }
            $ty = $ptY + ($ptH - $this->fontSize) / 2 + 2;

            $escaped = $this->escapeText($txt);

            $ops .= sprintf(
                "BT /%s %.1f Tf %.3f %.3f %.3f rg %.2f %.2f Td (%s) Tj ET\n",
                $fontCode,
                $this->fontSize,
                $this->textColor[0] / 255,
                $this->textColor[1] / 255,
                $this->textColor[2] / 255,
                $tx,
                $ty,
                $escaped
            );
        }

        $this->currentPageContent .= $ops;

        if ($ln == 1) {
            $this->y += $h;
            $this->x = 10;
        } elseif ($ln == 2) {
            $this->y += $h;
        } else {
            $this->x += $w;
        }
    }

    /** Menulis teks bebas tanpa border (mirip Write) */
    public function Text($x, $y, $txt)
    {
        $ptX = $x * self::MM_TO_PT;
        $ptY = ($this->pageHeight - $y) * self::MM_TO_PT;
        $fontCode = $this->getFontCode();
        $escaped = $this->escapeText($txt);

        $this->currentPageContent .= sprintf(
            "BT /%s %.1f Tf %.3f %.3f %.3f rg %.2f %.2f Td (%s) Tj ET\n",
            $fontCode,
            $this->fontSize,
            $this->textColor[0] / 255,
            $this->textColor[1] / 255,
            $this->textColor[2] / 255,
            $ptX,
            $ptY,
            $escaped
        );
    }

    /** Menggambar garis lurus */
    public function Line($x1, $y1, $x2, $y2)
    {
        $ptX1 = $x1 * self::MM_TO_PT;
        $ptY1 = ($this->pageHeight - $y1) * self::MM_TO_PT;
        $ptX2 = $x2 * self::MM_TO_PT;
        $ptY2 = ($this->pageHeight - $y2) * self::MM_TO_PT;

        $this->currentPageContent .= sprintf(
            "%.3f %.3f %.3f RG 0.4 w %.2f %.2f m %.2f %.2f l S\n",
            $this->drawColor[0] / 255, $this->drawColor[1] / 255, $this->drawColor[2] / 255,
            $ptX1, $ptY1, $ptX2, $ptY2
        );
    }

    /** Menggambar kotak/lingkaran sederhana sebagai pengganti logo */
    public function DrawLogoBox($x, $y, $size = 15)
    {
        $ptX = $x * self::MM_TO_PT;
        $ptY = ($this->pageHeight - $y - $size) * self::MM_TO_PT;
        $ptS = $size * self::MM_TO_PT;

        $this->currentPageContent .= sprintf(
            "0.078 0.365 0.831 rg %.2f %.2f %.2f %.2f re f\n",
            $ptX, $ptY, $ptS, $ptS
        );

        // Tulis inisial di tengah kotak logo
        $this->currentPageContent .= sprintf(
            "BT /F2 %.1f Tf 1 1 1 rg %.2f %.2f Td (SDK) Tj ET\n",
            $size * 0.35,
            $ptX + ($ptS * 0.15),
            $ptY + ($ptS * 0.4)
        );
    }

    private function getFontCode()
    {
        // F1 = Helvetica normal, F2 = Helvetica Bold
        if (strpos(strtoupper($this->fontStyle), "B") !== false) {
            return "F2";
        }
        return "F1";
    }

    private function escapeText($txt)
    {
        $txt = str_replace(["\\", "(", ")"], ["\\\\", "\\(", "\\)"], $txt);
        // Konversi karakter non-ASCII sederhana agar tidak merusak struktur PDF
        $txt = iconv("UTF-8", "ASCII//TRANSLIT//IGNORE", $txt);
        return $txt;
    }

    /**
     * Mengeluarkan file PDF ke browser (Output) atau file
     * @param string $mode "I" = inline tampil di browser, "D" = download
     * @param string $filename nama file
     */
    public function Output($mode = "I", $filename = "document.pdf")
    {
        // Simpan halaman terakhir
        if ($this->pageOpen) {
            $this->pages[] = $this->currentPageContent;
            $this->pageOpen = false;
        }

        $pdf = $this->buildPdf();

        if ($mode === "D") {
            header("Content-Type: application/pdf");
            header("Content-Disposition: attachment; filename=\"" . $filename . "\"");
        } else {
            header("Content-Type: application/pdf");
            header("Content-Disposition: inline; filename=\"" . $filename . "\"");
        }
        header("Content-Length: " . strlen($pdf));
        echo $pdf;
    }

    /** Membangun struktur biner file PDF dari halaman-halaman yang sudah dibuat */
    private function buildPdf()
    {
        $objects = [];
        $pageWidthPt = round($this->pageWidth * self::MM_TO_PT, 2);
        $pageHeightPt = round($this->pageHeight * self::MM_TO_PT, 2);

        // 1. Catalog
        $objects[] = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";

        // 2. Pages (parent)
        $kids = [];
        $pageCount = count($this->pages);
        $pageObjStart = 4; // object id awal untuk halaman konten (setelah font objects)

        // Kita susun object id: 1 catalog, 2 pages, 3 font1, 3b font2 -> gunakan id tetap
        // 3 = Font Helvetica, 4 = Font Helvetica-Bold
        // 5..(5+n*2-1) = pasangan (page, content) per halaman

        $pageIds = [];
        $objId = 5;
        foreach ($this->pages as $i => $content) {
            $pageIds[] = $objId;
            $objId += 2; // page object + content object
        }

        $kidsRefs = implode(" ", array_map(function ($id) { return $id . " 0 R"; }, $pageIds));
        $objects[1] = "2 0 obj\n<< /Type /Pages /Kids [" . $kidsRefs . "] /Count " . $pageCount . " >>\nendobj\n";

        // 3. Font Helvetica
        $objects[2] = "3 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Name /F1 /Encoding /WinAnsiEncoding >>\nendobj\n";
        // 4. Font Helvetica-Bold
        $objects[3] = "4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Name /F2 /Encoding /WinAnsiEncoding >>\nendobj\n";

        $idx = 4;
        foreach ($this->pages as $i => $content) {
            $pageId = $pageIds[$i];
            $contentId = $pageId + 1;

            $objects[$idx++] = sprintf(
                "%d 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 %.2f %.2f] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents %d 0 R >>\nendobj\n",
                $pageId, $pageWidthPt, $pageHeightPt, $contentId
            );

            $stream = $content;
            $objects[$idx++] = sprintf(
                "%d 0 obj\n<< /Length %d >>\nstream\n%s\nendstream\nendobj\n",
                $contentId, strlen($stream), $stream
            );
        }

        // Susun file PDF final dengan xref table
        $pdf = "%PDF-1.4\n";
        $offsets = [];
        $body = "";
        $currentOffset = strlen($pdf);

        foreach ($objects as $obj) {
            $offsets[] = $currentOffset;
            $body .= $obj;
            $currentOffset += strlen($obj);
        }

        $pdf .= $body;

        $xrefStart = strlen($pdf);
        $totalObjects = count($objects) + 1;

        $pdf .= "xref\n0 " . $totalObjects . "\n";
        $pdf .= "0000000000 65535 f \n";
        foreach ($offsets as $off) {
            $pdf .= sprintf("%010d 00000 n \n", $off);
        }

        $pdf .= "trailer\n<< /Size " . $totalObjects . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n" . $xrefStart . "\n%%EOF";

        return $pdf;
    }
}
