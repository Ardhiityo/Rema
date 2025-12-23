<?php

declare(strict_types=1);

namespace App\Data\MetadataCategory;

use Spatie\LaravelData\Data;
use Illuminate\Http\UploadedFile;

class UpdateMetadataCategoryData extends Data
{
    public function __construct(
        public int|string $meta_data_id,
        public int|string $category_id,
        public UploadedFile|null $file_path
    ) {}
}
