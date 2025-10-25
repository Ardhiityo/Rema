<?php

namespace App\Data\Author;

use App\Models\Author;
use Spatie\LaravelData\Data;

class AuthorData extends Data
{
    public function __construct(
        public int $id,
        public int $user_id,
        public string|null $name,
        public int|null $nim,
        public int|null $study_program_id,
        public string $status
    ) {}

    public static function fromModel(Author $author)
    {
        return new self(
            $author->id,
            $author->user_id,
            $author->user?->name,
            $author->nim,
            $author->study_program_id,
            $author->status
        );
    }
}
