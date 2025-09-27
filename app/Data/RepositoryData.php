<?php

namespace App\Data;

use App\Models\Repository;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;

class RepositoryData extends Data
{
    public function __construct(
        public int $id,
        public string $title,
        public string $abstract,
        public string $file_path,
        public string $type,
        public int $author_id,
        public string $author_name,
        public string $published_at,
        public string $year,
        public string $slug,
    ) {}

    public static function fromModel(Repository $repository)
    {
        return new self(
            $repository->id,
            $repository->title,
            $repository->abstract,
            $repository->file_path,
            $repository->type,
            $repository->author->id,
            $repository->author->name,
            $repository->published_at->format('d F Y'),
            $repository->year,
            $repository->slug
        );
    }
}
