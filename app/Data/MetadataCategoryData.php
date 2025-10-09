<?php

namespace App\Data;

use App\Models\Category;
use App\Models\MetaData;
use App\Models\Repository;
use Spatie\LaravelData\Data;

class MetadataCategoryData extends Data
{
    public function __construct(
        public string $category,
        public string $file_path,
        public string $metadata_slug,
        public string $category_slug
    ) {}

    public static function fromModel(Category $category, MetaData $meta_data)
    {
        return new self(
            $category->name,
            $category->pivot->file_path,
            $meta_data->slug,
            $category->slug
        );
    }
}
