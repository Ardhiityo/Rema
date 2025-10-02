<?php

namespace App\Data;

use App\Models\Repository;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Computed;

class RepositoryData extends Data
{
    #[Computed]
    public string $short_title;
    public string $badge_status;

    public function __construct(
        public int $id,
        public string $title,
        public string|null $nim,
        public string $abstract,
        public string $file_path,
        public string $category_id,
        public string $category_name,
        public int $author_id,
        public string $author_name,
        public string $year,
        public string $slug,
        public string|null $study_program,
        public string $status,
        public string $created_at
    ) {
        $this->short_title = Str::limit($title, 30, '...');
        $this->badge_status = $status === 'Approve' ? 'badge bg-success' : 'badge bg-danger';
    }

    public static function fromModel(Repository $repository)
    {
        return new self(
            $repository->id,
            $repository->title,
            $repository->author?->nim ?? '-',
            $repository->abstract,
            $repository->file_path,
            $repository->category_id,
            $repository->category->name,
            $repository->author->id,
            $repository->author->user->name,
            $repository->year,
            $repository->slug,
            $repository->author?->studyProgram?->name ?? '-',
            ucfirst($repository->status),
            $repository->created_at->format('d F Y')
        );
    }
}
