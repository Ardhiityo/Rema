<?php

namespace App\Data;

use App\Models\Author;
use Spatie\LaravelData\Data;

class AuthorData extends Data
{
    public function __construct(
        public int $id,
        public string $nim,
        public string $name,
        public int $study_program_id,
        public string $study_program_name
    ) {}

    public static function fromModel(Author $author)
    {
        return new self(
            $author->id,
            $author->nim,
            $author->name,
            $author->studyProgram->id,
            $author->studyProgram->name,
        );
    }
}
