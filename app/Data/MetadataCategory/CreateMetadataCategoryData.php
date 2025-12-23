<?php

declare(strict_types=1);

namespace App\Data\MetadataCategory;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class CreateMetadataCategoryData extends Data
{
    public function __construct(
        public int|string $meta_data_id,
        public int|string $category_id,
        public UploadedFile $file_path
    ) {}
}
