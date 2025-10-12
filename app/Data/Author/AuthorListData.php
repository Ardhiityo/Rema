<?php

namespace App\Data\Author;

use App\Models\Author;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Storage;

class AuthorListData extends Data
{
    public function __construct(
        public int $id,
        public int $nim,
        public string $name,
        public string $study_program,
        public string|null $avatar,
    ) {}

    public static function fromModel(Author $author): self
    {
        return new self(
            $author->id,
            $author->nim,
            $author->user->name,
            $author->studyProgram->name,
            $author->user->avatar ? Storage::url($author->user->avatar) : null
        );
    }
}
