<?php

namespace App\Data;

use App\Models\Repository;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;

class RecentlyAddData extends Data
{
    public function __construct(
        public string $author,
        public string $type,
        public string $slug,
        public string $title
    ) {}

    public static function fromModel(Repository $repository)
    {
        return new self(
            Str::limit($repository->author->user->name, 15, '...'),
            $repository->category->name,
            $repository->slug,
            Str::limit($repository->title, 15, '...')
        );
    }
}
