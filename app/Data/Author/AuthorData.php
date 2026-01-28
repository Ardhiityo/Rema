<?php

declare(strict_types=1);

namespace App\Data\Author;

use App\Models\Author;
use Spatie\LaravelData\Data;

class AuthorData extends Data
{
    public function __construct(
        public int|string $id,
        public int|string $user_id,
        public string|null $name,
        public int|null|string $nim,
        public int|null|string $study_program_id
    ) {}

    public static function fromModel(Author $author)
    {
        return new self(
            $author->id,
            $author->user_id,
            $author->user?->name,
            $author->nim,
            $author->study_program_id
        );
    }
}
