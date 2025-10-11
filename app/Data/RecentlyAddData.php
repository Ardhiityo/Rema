<?php

namespace App\Data;

use App\Models\MetaData;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Storage;

class RecentlyAddData extends Data
{
    public function __construct(
        public string $author,
        public string $avatar,
        public string $categories,
        public string $slug,
        public string $title
    ) {}

    public static function fromModel(MetaData $meta_data)
    {
        return new self(
            Str::limit($meta_data->author->user->name, 15, '...'),
            Storage::url($meta_data->author->user->avatar),
            $meta_data->categories->pluck('name')->implode(','),
            $meta_data->slug,
            Str::limit($meta_data->title, 15, '...')
        );
    }
}
