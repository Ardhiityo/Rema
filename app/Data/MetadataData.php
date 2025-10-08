<?php

namespace App\Data;

use App\Models\Metadata;
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

    public static function fromModel(Metadata $repository)
    {
        return new self(
            $repository->id,
            $repository->title,
            $repository->author?->nim ?? '-',
            $repository->abstract,
            $repository->author->id,
            $repository->author->user->name,
            $repository->author->user->avatar ? Storage::url($repository->author->user->avatar) : null,
            $repository->year,
            $repository->slug,
            $repository->author?->studyProgram?->name ?? '-',
            $repository->status,
            $repository->visibility,
            $repository->created_at->format('d F Y')
        );
    }
}
