<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use setasign\Fpdi\Fpdi;

class PdfWatermarkService
{
    public static function apply(string $sourcePath, string $filename, string $watermarkText): string
    {
        try {
            // Pastikan hanya nama file, bukan full path
            $filename = basename($filename);

            if (!str_ends_with(strtolower($filename), '.pdf')) {
                $filename .= '.pdf';
            }

            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($sourcePath);

            for ($i = 1; $i <= $pageCount; $i++) {
                $tplIdx = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tplIdx);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tplIdx);

                $pdf->SetFont('Helvetica', '', 45);
                $pdf->SetTextColor(192, 192, 192);
                $pdf->SetXY(0, $size['height'] / 2);
                $pdf->Cell($size['width'], 10, $watermarkText, 0, 0, 'C');
            }

            $tempDir = storage_path('app/temp');
            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0775, true, true);
            }

            $tempOutput = $tempDir . '/output_' . $filename;

            $pdf->Output($tempOutput, 'F');

            $relativePath = 'repositories/' . $filename;
            $publicPath = storage_path('app/public/' . $relativePath);
            File::ensureDirectoryExists(dirname($publicPath));

            File::move($tempOutput, $publicPath);

            return $relativePath;
        } catch (\Exception $e) {
            Log::error('Gagal membuat watermark PDF: ' . $e->getMessage());
            throw new \Exception('Gagal membuat watermark PDF');
        }
    }
}
