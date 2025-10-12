<?php

namespace App\Data\Author;

use App\Models\Author;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Storage;

class AuthorListData extends Data
{
    public function __construct(
        public int $id,
        public int|null $nim,
        public string $name,
        public string|null $study_program,
        public string|null $avatar,
    ) {}

    public static function fromModel(Author $author): self
    {
        return new self(
            $author->id,
            $author->nim ?? null,
            $author->user->name,
            $author->studyProgram->name ?? null,
            $author->user->avatar ? Storage::url($author->user->avatar) : null
        );
    }
}
