<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AvatarGenerator
{
    public static function generate()
    {
        $public_path = "assets/compiled/jpg/" . rand(min: 1, max: 8) . ".jpg";

        $sourcePath = public_path($public_path);
        $fileContents = File::get($sourcePath);

        // Buat nama file unik dengan ekstensi asli
        $filename = 'avatars/' . uniqid() . '.jpg';

        // Simpan ke storage/app/public
        Storage::disk('public')->put($filename, $fileContents);

        return $filename;
    }
}
