<?php

declare(strict_types=1);

namespace App\Data;

use App\Models\MetaData;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Attributes\Computed;

class RecentlyAddData extends Data
{
    #[Computed]
    public string $short_name;

    #[Computed]
    public string $short_title;

    public function __construct(
        public string $name,
        public string|null $avatar,
        public string $slug,
        public string $title
    ) {

        $this->short_title =  Str::limit($title, 45, '...');
        $this->short_name = Str::limit($name, 30, '...');
    }

    public static function fromModel(MetaData $meta_data)
    {
        return new self(
            $meta_data->author_name,
            $meta_data?->author?->user?->avatar ? Storage::url($meta_data->author->user->avatar) : asset('assets/compiled/jpg/anonym.jpg'),
            $meta_data->slug,
            $meta_data->title
        );
    }
}
