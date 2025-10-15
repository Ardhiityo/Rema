<?php

namespace App\Data\MetadataCategory;

use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Data;

class CreateMetadataCategoryData extends Data
{
    public function __construct(
        public int $meta_data_id,
        public int $category_id,
        public UploadedFile $file_path
    ) {}
}
