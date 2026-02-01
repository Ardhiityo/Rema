<?php

declare(strict_types=1);

namespace App\Data\Metadata;

use Spatie\LaravelData\Data;

class MetadataAuthorReportData extends Data
{
    public function __construct(
        public string $author_name,
        public string|int $author_nim,
        public string $study_program,
        public string|null $title,
        public string|null $categories,
        public string|null $status
    ) {}

    public static function fromModel(\App\Models\Metadata $metadata): self
    {
        return new self(
            $metadata->author_name,
            $metadata->author_nim,
            $metadata->studyProgram->name,
            $metadata->title,
            $metadata->categories?->pluck('name')->implode(', ') ?? '-',
            ucfirst($metadata->status)
        );
    }
}
