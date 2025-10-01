<?php

namespace App\Data;

use App\Models\Repository;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Attributes\Computed;

class RepositoryData extends Data
{
    #[Computed]
    public string $publised_at_to_ymd;
    #[Computed]
    public string $publised_at_to_dfy;
    #[Computed]
    public string $published_at_year;
    #[Computed]
    public string $short_title;

    public function __construct(
        public int $id,
        public string $title,
        public string $nim,
        public string $abstract,
        public string $file_path,
        public string $type,
        public int $author_id,
        public string $author_name,
        public string $published_at,
        public string $year,
        public string $slug,
        public string $study_program
    ) {
        $date = Carbon::parse($this->published_at);
        $this->publised_at_to_dfy = $date->format('d F Y');
        $this->publised_at_to_ymd = $date->format('Y-m-d');
        $this->published_at_year = $date->year;
        $this->short_title = Str::limit($title, 30, '...');
    }

    public static function fromModel(Repository $repository)
    {
        return new self(
            $repository->id,
            $repository->title,
            $repository->author->nim,
            $repository->abstract,
            $repository->file_path,
            $repository->type == 'thesis' ? 'Skripsi' : ($repository->type === 'final_Project' ? 'Tugas Akhir' : 'Manual Book'),
            $repository->author->id,
            $repository->author->name,
            $repository->published_at,
            $repository->year,
            $repository->slug,
            $repository->author->studyProgram->name
        );
    }
}
