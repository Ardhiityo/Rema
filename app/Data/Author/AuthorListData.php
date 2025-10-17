<?php

namespace App\Data\Author;

use App\Models\Author;
use Illuminate\Support\Str;
use Spatie\LaravelData\Data;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelData\Attributes\Computed;

class AuthorListData extends Data
{
    #[Computed]
    public string $short_name;

    public function __construct(
        public int $id,
        public int|null|string $nim,
        public string $name,
        public string|null $study_program,
        public string|null $avatar,
    ) {
        $this->short_name = Str::limit($name, 15, '...');
    }

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
