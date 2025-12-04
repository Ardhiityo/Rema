<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use setasign\Fpdi\PdfReader\PdfReaderException;
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

            try {
                // Proses watermark (bagian yang bisa memicu FPDI error)
                (new ImageWatermarker())
                    ->input($sourcePath)
                    ->watermark($watermarkImagePath)
                    ->position('MiddleCenter', 0, 0)
                    ->resolution(300)
                    ->asOverlay()
                    ->pageRange(1, null)
                    ->save($tempOutput);
            } catch (\Exception $inner) {
                // Tangkap error dari FPDI
                logger($inner->getMessage(), ['Pdf Watermark Service' => 'Apply']);
                if (str_contains($inner->getMessage(), 'compression technique')) {
                    throw new \Exception(
                        "File PDF ini menggunakan kompresi yang tidak didukung FPDI. " .
                            "Silakan ubah ke PDF versi 1.4 di situs seperti " .
                            "<a href='https://www.pdf2go.com/convert-from-pdf' style='text-decoration:underline' target='_blank'>PDF2Go</a> " .
                            "atau <a href='https://docupub.com/pdfconvert/' style='text-decoration:underline' target='_blank'>DocuPub</a>."
                    );
                }

                // Kalau bukan error kompresi, lempar ulang
                throw $inner;
            }

            // Pindahkan file hasil
            $relativePath = 'repositories/' . $filename;
            $publicPath = storage_path('app/public/' . $relativePath);
            File::ensureDirectoryExists(dirname($publicPath));
            File::move($tempOutput, $publicPath);

            // Hapus file temp setelah dipindahkan
            File::delete($tempOutput);

            return $relativePath;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
