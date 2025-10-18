<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Gutti3k\PdfWatermarker\Watermarkers\ImageWatermarker;


class PdfWatermarkService
{
    public static function apply(string $sourcePath, string $filename): string
    {
        try {
            $filename = basename($filename);
            if (!str_ends_with(strtolower($filename), '.pdf')) {
                $filename .= '.pdf';
            }

            $tempDir = storage_path('app/temp');
            File::ensureDirectoryExists($tempDir);
            $tempOutput = $tempDir . '/output_' . $filename;

            $watermarkImagePath = public_path('assets/watermark/unival.png');

            if (!File::exists($watermarkImagePath)) {
                throw new \Exception('File watermark tidak ditemukan: ' . $watermarkImagePath);
            }

            (new ImageWatermarker())
                ->input($sourcePath)
                ->watermark($watermarkImagePath)
                ->position('MiddleCenter', 0, 0)
                ->resolution(300)
                ->asOverlay()
                ->pageRange(1, null)
                ->save($tempOutput); // ✅ ini yang benar

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
