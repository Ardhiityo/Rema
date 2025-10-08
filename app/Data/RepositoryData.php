<?php

namespace App\Data;

use App\Models\Repository;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Storage;

class RepositoryData extends Data
{
    public function __construct(
        public int $meta_data_id,
        public int $category_id,
        public string $category,
        public string $file_path
    ) {}

    public static function fromModel(Repository $repository)
    {
        return new self(
            $repository->meta_data_id,
            $repository->category_id,
            $repository->category->name,
            Storage::url($repository->file_path)
        );
    }
}
