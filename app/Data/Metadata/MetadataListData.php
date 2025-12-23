<?php

declare(strict_types=1);

namespace App\Data\Metadata;

use App\Models\MetaData;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Attributes\Computed;

class MetadataListData extends Data
{
    #[Computed()]
    public string $short_title;

    #[Computed()]
    public string $short_name;

    #[Computed()]
    public string $visibility_ucfirst;

    public function __construct(
        public string $title,
        public string $avatar,
        public string $name,
        public string $visibility,
        public string $slug,
        public int $views,
        public string $status
    ) {
        $this->short_title = Str::limit($title, 35, '...');
        $this->short_name = Str::limit($name, 15, '...');
        $this->visibility_ucfirst = ucfirst($visibility);
    }

    public static function fromModel(MetaData $meta_data): self
    {
        return new self(
            $meta_data->title,
            $meta_data->author->user->avatar ? Storage::url($meta_data->author->user->avatar) : false,
            $meta_data->author->user->name,
            $meta_data->visibility,
            $meta_data->slug,
            $meta_data->activities->count(),
            $meta_data->status
        );
    }
}
