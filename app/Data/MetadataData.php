<?php

namespace App\Data;

use App\Models\Metadata;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Attributes\Computed;

class MetadataData extends Data
{
    #[Computed]
    public string $short_title;
    #[Computed]
    public string $badge_status;
    #[Computed]
    public string $ucfirst_status;
    #[Computed]
    public string $ucfirst_visibility;

    public function __construct(
        public int $id,
        public string $title,
        public string|null $nim,
        public string $abstract,
        public int $author_id,
        public string $author_name,
        public string|null $author_avatar,
        public Collection $metadata,
        public string $year,
        public string $slug,
        public string|null $study_program,
        public string $status,
        public string $visibility,
        public string $created_at
    ) {
        $this->short_title = Str::limit($title, 30, '...');
        $this->badge_status = $status === 'approve' ? 'badge bg-success' : 'badge bg-danger';
        $this->ucfirst_status = ucfirst($status);
        $this->ucfirst_visibility = ucfirst($visibility);
    }

    public static function fromModel(Metadata $meta_data)
    {
        return new self(
            $meta_data->id,
            $meta_data->title,
            $meta_data->author?->nim ?? '-',
            $meta_data->abstract,
            $meta_data->author->id,
            $meta_data->author->user->name,
            $meta_data->author->user->avatar ? Storage::url($meta_data->author->user->avatar) : null,
            $meta_data->categories,
            $meta_data->year,
            $meta_data->slug,
            $meta_data->author?->studyProgram?->name ?? '-',
            $meta_data->status,
            $meta_data->visibility,
            $meta_data->created_at->format('d F Y')
        );
    }
}
