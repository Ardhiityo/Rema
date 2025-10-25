<?php

namespace App\Data\Metadata;

use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Data;

class CreateMetadataData extends Data
{
    #[Computed()]
    public string $title_formatted;

    public function __construct(
        public string $title,
        public int $author_id,
        public string $visibility,
        public int|string $year,
        public string $slug,
        public string $status
    ) {
        $this->title_formatted = ucwords(strtolower($title));
    }
}
