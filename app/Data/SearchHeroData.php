<?php

namespace App\Data;

use Carbon\Carbon;
use App\Models\Repository;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Attributes\Computed;

class SearchHeroData extends Data
{
    #[Computed]
    public string $published_at_to_dfy;
    #[Computed]
    public string $published_at_to_diff_for_humans;
    #[Computed]
    public string $short_author_name;
    #[Computed]
    public string $short_title;

    public function __construct(
        public string $author,
        public string $nim,
        public string $study_program,
        public string $title,
        public string $category,
        public string $slug,
        public string $created_at
    ) {
        $this->short_author_name = Str::limit($author, 20, '...');
        $this->short_title = Str::limit($title, 90, '...');;
    }

    public static function fromModel(Repository $repository)
    {
        return new self(
            $repository->author->user->name,
            $repository->author->nim,
            $repository->author->studyProgram->name,
            $repository->title,
            $repository->category->name,
            $repository->slug,
            $repository->created_at->format('d F Y')
        );
    }
}
