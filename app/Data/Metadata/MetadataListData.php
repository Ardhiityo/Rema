<?php

declare(strict_types=1);

namespace App\Data\Metadata;

use App\Models\Metadata;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Attributes\Computed;

class MetadataListData extends Data
{
    #[Computed()]
    public string $short_title;

    #[Computed()]
    public string $short_name;

    public function __construct(
        public string $title,
        public string $avatar,
        public string $name,
        public string $nim,
        public string $slug,
        public string $status
    ) {
        $this->short_title = Str::limit($title, 35, '...');
        $this->short_name = Str::limit($name, 15, '...');
    }

    public static function fromModel(Metadata $meta_data): self
    {
        return new self(
            $meta_data->title,
            $meta_data?->author?->user?->avatar ? Storage::url($meta_data->author->user->avatar) : asset('assets/compiled/jpg/anonym.jpg'),
            $meta_data->author_name,
            $meta_data->author_nim,
            $meta_data->slug,
            $meta_data->status
        );
    }

    public function toModel(): Metadata
    {
        $meta_data = new Metadata();

        $meta_data->fill([
            'status' => $this->status,
            'author_id' => Auth::user()?->author?->id ?? null,
        ]);

        return $meta_data;
    }
}
