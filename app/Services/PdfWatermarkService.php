<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Gutti3k\PdfWatermarker\Watermarkers\ImageWatermarker;


class PdfWatermarkService
{
    public static function apply(string $sourcePath, string $filename): string
    {
        try {
            $tempDir = storage_path('app/temp');

            File::ensureDirectoryExists($tempDir);

            $tempOutput = $tempDir . '/output_' . $filename;

            try {
                // Proses watermark (bagian yang bisa memicu FPDI error)
                (new ImageWatermarker())
                    ->input($sourcePath)
                    ->watermark(public_path('assets/watermark/unival.png'))
                    ->position('MiddleCenter', 0, 0)
                    ->resolution(300)
                    ->asOverlay()
                    ->pageRange(1, null)
                    ->save($tempOutput);
            } catch (Exception $exception) {
                // Tangkap error dari FPDI
                logger($exception->getMessage(), ['Pdf Watermark Service' => 'Apply']);

                if (str_contains($exception->getMessage(), 'compression technique')) {
                    throw new Exception(
                        "File PDF ini menggunakan kompresi yang tidak didukung sistem. " .
                            "Silakan ubah ke PDF versi 1.4 di situs seperti " .
                            "<a href='https://www.pdf2go.com/convert-from-pdf' style='text-decoration:underline' target='_blank'>PDF2Go</a> " .
                            "atau <a href='https://docupub.com/pdfconvert/' style='text-decoration:underline' target='_blank'>DocuPub</a>."
                    );
                }

                // Kalau bukan error kompresi, lempar ulang
                throw new Exception($exception->getMessage());
            }

            // Pindahkan file hasil
            $relativePath = 'repositories/' . $filename;

            $publicPath = storage_path('app/public/' . $relativePath);

            File::ensureDirectoryExists(dirname($publicPath));

            Storage::disk('public')->put($relativePath, File::get($tempOutput));

            File::delete($tempOutput);

            return $relativePath;
        } catch (Exception $exception) {
            Log::info(json_encode([
                'user' => [
                    'id' => Auth::user()->id,
                    'name' => Auth::user()->name,
                ],
                'details' => [
                    'source' => [
                        'class' => 'PdfWatermarkService',
                        'method' => 'apply',
                    ],
                    'data' => [
                        'sourcePath' => $sourcePath,
                        'relativePath' => 'repositories/' . $filename
                    ]
                ],
                'message' => $exception->getMessage()
            ], JSON_PRETTY_PRINT));

            throw new Exception($exception->getMessage());
        }
    }
}
