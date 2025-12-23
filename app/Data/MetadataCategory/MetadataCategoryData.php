<?php

declare(strict_types=1);

namespace App\Data\MetadataCategory;

use App\Models\MetaDataCategory;
use Spatie\LaravelData\Data;

class MetadataCategoryData extends Data
{
    public function __construct(
        public int $meta_data_id,
        public int $category_id,
        public string $file_path
    ) {}

    public static function fromModel(MetaDataCategory $repository): self
    {
        return new self(
            $repository->meta_data_id,
            $repository->category_id,
            $repository->file_path
        );
    }
}
