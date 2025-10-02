<?php

namespace App\Data;

use App\Models\Author;
use Spatie\LaravelData\Data;

class AuthorData extends Data
{
    public function __construct(
        public int $author_id,
        public int $user_id,
        public string $nim,
        public string $name,
        public int $study_program_id,
        public string $study_program_name
    ) {}

    public static function fromModel(Author $author)
    {
        return new self(
            $author->id,
            $author->user->id,
            $author->nim,
            $author->user->name,
            $author->studyProgram->id,
            $author->studyProgram->name,
        );
    }
}
