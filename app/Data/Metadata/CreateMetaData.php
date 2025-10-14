<?php

namespace App\Data\Metadata;

use Spatie\LaravelData\Data;

class CreateMetaData extends Data
{
    public function __construct(
        public string $title,
        public string $abstract,
        public int $author_id,
        public string $visibility,
        public int $year,
        public string $slug,
        public string $status
    ) {}
}
