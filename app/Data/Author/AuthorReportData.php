<?php

declare(strict_types=1);

namespace App\Data\Author;

use App\Models\Author;
use Spatie\LaravelData\Data;

class AuthorReportData extends Data
{
    public function __construct(
        public string $name,
        public string|int $nim,
        public string $study_program,
        public string|null $meta_data,
        public string|null $categories,
        public string|null $status
    ) {}

    public static function fromModel(Author $author): self
    {
        return new self(
            $author->user->name,
            $author?->nim ?? '-',
            $author?->studyProgram?->name ?? '-',
            $author?->metadata->sortByDesc('id')->first()?->title ?? '-',
            $author?->metadata->sortByDesc('id')->first()?->categories?->pluck('name')->implode(', ') ?? '-',
            ucfirst($author?->metadata->sortByDesc('id')->first()?->status ?? '-')
        );
    }
}
