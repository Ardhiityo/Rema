<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;

class PdfWatermarkService
{
    public static function apply(string $sourcePath, string $destinationPath, string $watermarkText)
    {
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($sourcePath);

        for ($i = 1; $i <= $pageCount; $i++) {
            $tplIdx = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tplIdx);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tplIdx);

            // Tambahkan watermark
            $pdf->SetFont('Arial', '', 45); // Ukuran besar
            $pdf->SetTextColor(178, 102, 255);

            // Posisi tengah halaman
            $pdf->SetXY(0, $size['height'] / 2);

            // Cell selebar halaman, teks di tengah
            $pdf->Cell($size['width'], 10, $watermarkText, 0, 0, 'C');
        }

        $pdf->Output('F', $destinationPath);
    }
}
